<?php

class Schema
{
	protected $query;
	protected $table;
	protected $hasPrimaryKey;
	protected $primaryKey;
	protected $type;
	protected $prefix;

	public function __construct()
	{


		// Set the primary key to false
		$this->hasPrimaryKey = FALSE;
	}
	public function create($name)
	{
		$this->type = "create";
		$this->prefix = '';
		$this->table = $name;
		$this->query = "CREATE TABLE `{$this->table}` (";
	}
	public function edit($name)
	{
		$this->prefix = "ADD";
		$this->type = "edit";
		$this->table = $name;
		$this->query = "ALTER TABLE `{$this->table}` ";
	}
	public function integer($name, $null = FALSE)
	{
		if ($null == FALSE)
		{
			$null = "NOT NULL";
		}
		else
		{
			$null = "NULL";
		}
		if ($this->type == "create")
		{
			$this->query .= "`$name` INT(11) $null,";
		}
		elseif ($this->type == "edit")
		{	
			$this->query .= "ADD `$name` INT(11) $null,";
		}
	}
	public function id($name='id')
	{
		if ($this->type == 'create')
		{
			$this->query .= "`$name` INT NOT NULL AUTO_INCREMENT,";
		}
		elseif ($this->type == 'edit')
		{
			$this->query .= "ADD `$name` INT NOT NULL AUTO_INCREMENT,";
		}
		$this->hasPrimaryKey = TRUE;
		$this->primaryKey = "$name";
	}
	public function date($name)
	{
		if ($this->type == 'create')
		{
			$this->query .= "`$name` DATE,";
		}
		elseif ($this->type == 'edit')
		{
			$this->query .= "ADD `$name` DATE,";
		}
	}
	public function dateTime($name)
	{
		if ($this->type == 'create')
		{
			$this->query .= "`$name` DATETIME,";
		}
		elseif ($this->type == 'edit')
		{
			$this->query .= "ADD `$name` DATETIME,";
		}
	}
	public function string($name, $length=50)
	{
		if ($this->type == 'create')
		{
			$this->query .= "`$name` VARCHAR($length),";
		}
		elseif ($this->type == 'edit')
		{
			$this->query .= "ADD `$name` VARCHAR($length),";
		}
	}
	public function timestamp($name)
	{
		$this->query .= "{$this->prefix} `$name` TIMESTAMP,";
	}
	public function time($name)
	{
		$this->query .= "{$this->prefix} `$name`TIME,";
	}
	public function text($name)
	{
		$this->query .= "{$this->prefix} `$name` TEXT,";
	}
	public function boolean($name)
	{
		$this->query .= "{$this->prefix} `$name` BOOLEAN,";
	}
	public function float($name)
	{
		$this->query .= "{$this->prefix} `$name` FLOAT,";
	}
	public function dropColumn($name)
	{
		$this->query .= "DROP COLUMN `$name`,";
	}
	public function save()
	{
		if ($this->hasPrimaryKey)
		{
			if ($this->type == 'edit')
			{
				$this->query .= "ADD ";
			}
			$this->query .= "PRIMARY KEY({$this->primaryKey})";
			if ($this->type == 'create')
			{
				$this->query .= ')';
			}
			$this->query .= ';';
		}
		else
		{
			$this->query = rtrim($this->query, ',');
			if ($this->type == 'create')
			{
				$this->query .= ');';
			}
			else
			{
				$this->query .= ';';
			}
		}
		// Now let's actually run their query
		global $conn;

		$stmt = $conn->prepare($this->query);
		$stmt->execute();

	}

}