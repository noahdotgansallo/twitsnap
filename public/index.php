<?php

function exception_handler($exception)
{
	ob_start();
	include "../app/views/error.php";
	$file_contents = ob_get_contents();
	ob_end_clean();
	$file_contents = str_replace('}}', ';?>', $file_contents);
	$file_contents = str_replace('{{', '<?=', $file_contents);
	file_put_contents("../cache/tmp/error.php", $file_contents);
	include "../cache/tmp/error.php";
	unlink("../cache/tmp/error.php");
}	


// set the function above to the default exception handler

set_exception_handler('exception_handler');

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

session_start();
// Require the bootstrapper

include "../bootstrap.php";

include "../app/routes.php";

include "../app/config/config.php";

// Include all the models

foreach (glob("../app/models/*.php") as $file)
{
	include $file;
}
function action($controllerAction)
{
	global $route;
	return WEBROOT.$route->searchByControllerAction($controllerAction);
}


// Define the asset() function
function asset($file)
{
	echo WEBROOT.'/public/'.$file;
}

// Let's add a / to the beginning of the $_GET['url']

// Also, check to see if the user just entered the root directory /

if (!isset($_GET['url']))
{
	$_GET['url'] = '';
}

$_GET['url'] = '/'.$_GET['url'];

// Get rid of the / at the end of the URL to add flexibility...  

if ($_GET['url'] != '/')
{
	$_GET['url'] = rtrim($_GET['url'], '/');
}

if (!$route->routeExists($_GET['url']))
{
	// Let's check to see if the route parameter
	// exists.
	$currentRoute = explode('/', $_GET['url']);
	$size = count($currentRoute);

	// Ignore the first part, it will be empty,
	// The second part will be the model we are looking 
	// for, then the third part will be the parameter
	$i = 1;
	$baseURI = '/';
	if ($i == 1)
	{
		$baseURI .= $currentRoute[1].'/';
	}
	else
	{
		while ($i < $size-1)
		{
			$baseURI .= $currentRoute[$i].'/';
			$i++;
		}
	}

	if ($route->routeExists($baseURI))
	{
		$parameter = $currentRoute[$size-1];
		$controllerAction = $route->getRoute($baseURI);
		$controllerAction = explode("@", $controllerAction);
		$controller = $controllerAction[0];
		$action = $controllerAction[1];
		include("../app/controllers/$controller.php");
		$controller = new $controller;
		$controller->$action($parameter);
	}
	else
	{
		// route does not exist
		//trigger_error('Route '.$_GET['url'].' does not exist', E_USER_ERROR);
		throw new Exception("Route ".$_GET['url']." does not exist");
	}
}

else
{ // The route exists, we can play nicely

// Check to see if the route is a closure
if (substr($route->getRoute($_GET['url']), 0, 7) == 'Closure')
{
	var_dump("closure");
}

else
{

	// Returns the controller and the action separated by @

	$controllerAction = explode('@', $route->getRoute($_GET['url']));

	//Explode the controller action to get the Controller and the Action it is referencing

	$controller = $controllerAction[0];

	$action = $controllerAction[1];

	// Include the appropriate controller
	//print(ROOT . DS . 'app' . DS . 'controllers' . DS . $controller . '.php') or die('could not include');
	include("../app/controllers/$controller.php");

		$controller = new $controller;
		$controller->$action();
	}

	// After this, we want to delete the view, so 
	// if there is an error, they will not be brought	
	// to this screen
	if (file_exists("../cache/tmp/temp.php"))
	{
		//unlink("../cache/tmp/temp.php");
	}	
}

