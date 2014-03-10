<?php

class Input 
{
	public static function get($name)
	{
		return $_REQUEST[$name];
	}
	public static function all()
	{
		return $_POST;
	}
	public static function file($name)
	{
		if (!empty($_FILES[$name])){
			$file = new File;
			$file->name = $_FILES[$name]['name'];
			$file->type = $_FILES[$name]['type'];
			$file->tmp_name = $_FILES[$name]['tmp_name'];
			$file->error = $_FILES[$name]['error'];
			$file->size = $_FILES[$name]['size'];
			return $file;
		}
		else
		{
			return false;
		}
	}
}