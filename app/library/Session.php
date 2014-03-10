<?php

class Session
{
	public static function put($key, $val)
	{
		$_SESSION[$key] = $val;
	}
	public static function get($key)
	{
		return $_SESSION[$key];
	}

	public static function destroy()
	{
		session_destroy();
	}
	public static function delete($var)
	{
		unset($_SESSION[$var]);
	}
}