<?php

class Hash
{
	public static function make($value)
	{
		for ($count = 0; $count <= 25; $count++)
		{
			$value = md5(md5('Hey').md5('PioneerPHP'.$value));
		}
		return $value;
	}
}