<?php

class Route
{
	public $routes = array();
	public function add($route, $action)
	{
		if ($route != "/")
		{
			$route = rtrim($route, '/');
		}
		if (substr($route, 0, 1) != '/')
		{
			$route = '/'.$route;
		}
		// Check to see if they have something that matches the 
		// format {parameter}
		
		$find = "/\{(.*?)}/";
		$replace = "";
		$route = preg_replace($find, $replace, $route);
		$this->routes[$route] = $action;
		return true;
	}
	public function getRoute($uri)
	{
		return $this->routes[$uri];
	}
	public function searchByControllerAction($controllerAction)
	{
		return array_search($controllerAction, $this->routes);
	}
	public function routeExists($route)
	{
		if (!empty($this->routes[$route]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}

$route = new Route;
