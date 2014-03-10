<?php

$request_type = $_SERVER['REQUEST_METHOD'];


class Route
{
	public $routes = array();
	public $get_routes = array();
	public $post_routes = array();

	public static function get($getroute, $action)
	{
		// Add it to the regular routes too so the action function works legit
		global $route;
		$route->routes[$getroute] = $action;
		if ($getroute != '/')
		{
			$getroute = rtrim($getroute, '/');
		}
		if (substr($getroute,0,1) != '/')
		{
			$getroute = '/'.$getroute;
		}

		$find = "/\{(.*?)}/";
		$replace = "";
		$getroute = preg_replace($find, $replace, $getroute);
		$route->get_routes[$getroute] = $action;
		return true;
	}
	public static function post($postroute, $action)
	{
		// Add it to the regular routes too so the action function works legit
		global $route;
		$route->routes[$postroute] = $action;

		if ($postroute != '/')
		{
			$postroute = rtrim($postroute, '/');
		}
		if (substr($postroute,0,1) != '/')
		{
			$postroute = '/'.$postroute;
		}

		$find = "/\{(.*?)}/";
		$replace = "";
		$postroute = preg_replace($find, $replace, $postroute);
		$route->post_routes[$postroute] = $action;
	}
	public function getRoute($uri)
	{
		global $request_type;
		if ($request_type == 'GET')
		{
			return $this->get_routes[$uri];
		}
		elseif ($request_type == 'POST')
		{
			return $this->post_routes[$uri];
		}
	}
	public function searchByControllerAction($controllerAction)
	{
		/*
		global $request_type;
		if ($request_type == 'GET')
		{
			return array_search($controllerAction, $this->get_routes);
		}
		elseif ($request_type == 'POST')
		{
			return array_search($controllerAction, $this->post_routes);
		}
		*/
		return array_search($controllerAction, $this->routes);
	}
	public function routeExists($route)
	{
		global $request_type;
		if ($request_type == 'GET')
		{
			if (!empty($this->get_routes[$route]))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		elseif($request_type == 'POST')
		{
			if (!empty($this->post_routes[$route]))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}

$route = new Route;