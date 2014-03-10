<?php

// PDO based Database Class
include "../app/config/database.php";
$host = $dbCreds['host'];
$userN = $dbCreds['user'];
$pass = $dbCreds['password'];
$database = $dbCreds['database'];
try 
{
	$conn = new PDO('mysql:host='.$host.';dbname='.$database, $userN, $pass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) 
{
	throw new Exception($e->getMessage());
}

class DB
{
	public $connect;
	protected $table;
	protected $object;
	protected $type;
	public function __construct($object = NULL)
	{
		global $conn;
		if (get_called_class() != "DB")
		{
			$table = $this->table;
		}
		// Get all the columns
		// set each instance variable as the col name
		if ($object != NULL)
		{
			$this->type = "exists";
			// This is an existing database entry
			// Get all the col names/values and
			// set them
			foreach($object as $col=>$val)
			{
				$this->$col = $val;
			}

		}
		else
		{
			$this->type = "new";
			// This is a new database entry
			// Get the columns and set them as instance variables
			// with a null value
			$q = $conn->prepare('DESCRIBE '.$table);
			$q->execute();
			$columns = $q->fetchAll(PDO::FETCH_COLUMN);
			foreach ($columns as $column)
			{
				$this->$column = NULL;
			}
			$table = $this->table;
		}

	}
	public static function all($table = NULL)
	{
		$class = get_called_class();
		if ($class == "DB")
		{
			$class = $table;
		}
		$$class = new $class;
		$table = $$class->table;
		global $conn;

		$stmt = $conn->prepare('SELECT * FROM '.$table.' WHERE 1');
		$stmt->execute();
		$returnArray = [];
		
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($result as $key=>$obj)
		{
			$result[$key] = new $class($obj);
		}
		return $result;
	}
	public static function where($column, $operator, $value)
	{
		$class = get_called_class();
		$$class = new $class;
		$table = $$class->table;
		global $conn;

		$stmt = $conn->prepare('SELECT * FROM `'.$table.'` WHERE `'.$column.'`'.$operator.$value);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($result as $key=>$obj)
		{
			$result[$key] = new $class($obj);
		}
		return $result;
	}
	public static function raw($query)
	{
		global $conn;
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);

		return $result;
	}
	public static function find($integer)
	{
		global $conn;
		$class = get_called_class();
		$$class = new $class;
		$table = $$class->table;
		$stmt = $conn->prepare('SELECT * FROM '.$table.' WHERE `id`='.$integer);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_OBJ);
		$newobject = new $class($result);
		return $newobject;
	}

	public function getObject()
	{
		var_dump($this->object);
	}

	public function save()
	{
		global $conn;
		if ($this->type == "exists")
		{
			$startQuery = "UPDATE {$this->table} SET ";
			// We have to update the current table
			// Get all the columns in the database
			// Then match those columns with the keys
			// in the current user object
			$q = $conn->prepare('DESCRIBE '.$this->table);
			$q->execute();
			$columns = $q->fetchAll(PDO::FETCH_COLUMN);
			$count = 1;
			foreach ($columns as $col)
			{
				if ($count == 1)
				{
					$startQuery .= " $col='{$this->$col}'";
				}
				else
				{
					$startQuery .= ", $col='{$this->$col}'";
				}
				$count++;
			}
			$startQuery .= " WHERE id={$this->id}";
			$stmt = $conn->prepare($startQuery);
			$stmt->execute();
		}
		else
		{
			// Type does not already exist
			// Get all the column names, then
			// match the columns with the keys
			// in the current table
			$q = $conn->prepare('DESCRIBE '.$this->table);
			$q->execute();
			$columns = $q->fetchAll(PDO::FETCH_COLUMN);
			$count = 1;
			$startQuery = "INSERT INTO {$this->table} (";
			foreach ($columns as $col)
			{
				if ($count == 1)
				{
					$startQuery .= $col;
				}
				else
				{
					$startQuery .= ",".$col;
				}
				$count++;
			}
			$startQuery .= ") VALUES(";
			$count = 1;
			foreach ($columns as $col)
			{
				if ($count == 1)
				{
					$startQuery .= "'".$this->$col."'";
				}
				else
				{
					$startQuery .= ",'".$this->$col."'";
				}
				$count++;
			}
			$startQuery .= ")";
			$stmt = $conn->prepare($startQuery);
			$stmt->execute();

			// Now let's get that last Id and assign 
			// it to a property of the current object
			$this->id = $conn->lastInsertId('id');
		}
	}
	public function getType()
	{
		return $this->type;
	}
	public function hasOne($modelName)
	{
		global $conn;
		$model = new $modelName;
		$current_id = $this->id;
		$current_name = strtolower(get_called_class()).'_id';
		$foreign_table = $model->table;
		$query = "SELECT * FROM $foreign_table WHERE $current_name=$current_id";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$object =  $stmt->fetch(PDO::FETCH_OBJ);

		// lets return an instance of that class
		$newobject = new $modelName($object);
	}
	public function belongsTo($modelName)
	{
		global $conn;
		$model = new $modelName;
		$modelName = strtolower($modelName).'_id';
		$current_foreign_key = $this->$modelName;
		$foreign_table = $model->table;
		$query = "SELECT * FROM $foreign_table WHERE id=$current_foreign_key";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$object = $stmt->fetch(PDO::FETCH_OBJ);
		$newobject = new $modelName($object);
		return $newobject;
	}
	public function hasMany($modelName)
	{
		global $conn;
		$model = new $modelName;
		$current_id = $this->id;
		$current_name = strtolower(get_called_class()).'_id';
		$foreign_table = $model->table;
		$query = "SELECT * FROM $foreign_table WHERE $current_name=$current_id";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$returns =  $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($returns as $key=>$obj)
		{
			$returns[$key] = new $modelName($obj);
		}

		return $returns;

	}
	public function belongsToMany($modelName)
	{
		global $conn;
		$model = new $modelName;
		$modelName = strtolower($modelName).'_id';
		$current_foreign_key = $this->$modelName;
		$foreign_table = $model->table;
		$query = "SELECT * FROM $foreign_table WHERE id=$current_foreign_key";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$returns =  $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($returns as $key=>$obj)
		{
			$returns[$key] = new $modelName($obj);
		}
	}
	public function manyToMany($modelName)
	{
		global $conn;
		$model = new $modelName;
		$foreign_table = $model->table;
		$current_table = $this->table;

		// get the name of the pivot table
		$foreign_name = strtolower($modelName);
		$current_name = strtolower(get_called_class());
		$model_names = array($foreign_name, $current_name);
		asort($model_names);
		$pivot_table_name = implode('_', $model_names);	

		$current_index = $current_name.'_id';
		$foreign_index = $foreign_name.'_id';

		$current_id = $this->id;	

		$query = "SELECT $foreign_index FROM $pivot_table_name WHERE $current_index=$current_id";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$foreign_ids = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$for_in = "(";
		$count = 1;
		foreach ($foreign_ids as $obj)
		{
			if ($count != 1)
			{
				$for_in .= ',';
			}
			$for_in .= $obj->$foreign_index;
			$count++;
		}
		$for_in .= ")";
		$query = "SELECT * FROM $foreign_table WHERE id IN $for_in";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$returns = $stmt->fetchAll(PDO::FETCH_OBJ);

		foreach ($returns as $key=>$obj)
		{
			$returns[$key] = new $modelName($obj); 
		}

		return $returns;
	}
	public function attach($object)
	{
		global $conn;
		$currentClass = strtolower(get_called_class());
		$foreignClass = strtolower(get_class($object));
		$classes = array($currentClass, $foreignClass);
		asort($classes);
		$pivot_table_name = implode('_', $classes);

		$current_key = strtolower($currentClass).'_id';
		$foreign_key = strtolower($foreignClass).'_id';

		$current_id = $this->id;
		$foreign_id = $object->id;

		$query = "INSERT INTO $pivot_table_name ($current_key, $foreign_key) VALUES($current_id, $foreign_id)";
		$stmt = $conn->prepare($query);
		$stmt->execute();
	}
	public function inGroup($groupName)
	{
		$id = $this->id;
		$groups = $this->hasMany('Group');
	}
}