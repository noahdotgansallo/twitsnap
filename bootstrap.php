<?php

// Define the Document Root

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)).'/framework');

include "app/global/functions.php";
include "app/library/Controllers/MainController.php";
include "app/library/Models/DB.php";

// include The Library dependencies 
include "app/library/Hash.php";
include "app/library/Route.php";
include "app/library/Input.php";
include "app/library/Form.php";
include "app/library/Session.php";
include "app/library/Auth.php";
include "app/library/Redirect.php";
include "app/library/File.php";
include "app/filters.php";
include "app/library/Validator.php";

// define the url() function, so url's can be made in views

function url($page)
{
	//Define the URL function here/
	echo WEBROOT.'/'.$page;
}