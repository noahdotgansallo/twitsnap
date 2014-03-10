<?php

class Redirect
{
	public static function action($controllerAction)
	{
		global $route;
		$redirectURI = array_search($controllerAction, $route->routes);
		$redirectURI = WEBROOT.$redirectURI;
		header("Location: $redirectURI");
	}
	public static function to($url)
	{
		$uri = WEBROOT.$url;
		header("Location: $uri");
	}
	public static function back()
	{
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}
}