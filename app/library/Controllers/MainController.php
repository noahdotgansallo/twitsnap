<?php

/* DO NOT EDIT THIS FILE */

class MainController 
{

	protected $view;
	protected $variables;
	public function __construct()
	{
		$this->variables = array();
	}
	public function renderView($view, $variables=array())
	{
		foreach ($variables as $var_name=>$value)
		{	
			$$var_name = $value;
		}
		if (file_exists("../app/views/$view.php"))
		{
			ob_start();
			include "../app/views/$view.php";
			$file_contents = ob_get_contents();
			ob_end_clean();

			// Parse the file for blade-esque terms
			$file_contents = str_replace('}}', ';?>', $file_contents);
			$file_contents = str_replace('{{', '<?=', $file_contents);

			$startTemplate = strpos($file_contents, "@extends('")+10;
			$endtemplate = strpos($file_contents, "')", $startTemplate)-2;
			$template = substr($file_contents, $startTemplate, ($endtemplate-$startTemplate)+2);

			$templateString = "@extends('".$template."')";
			$file_contents = str_replace($templateString, "", $file_contents);
			$templateFile = "../app/views/$template.php";
			$templateContents = file_get_contents($templateFile);
			// Search the template file for yield('content')
			$file_contents = str_replace("@yield('content')", $file_contents, $templateContents);
			$file_contents = str_replace('}}', ';?>', $file_contents);
			$file_contents = str_replace('{{', '<?=', $file_contents);
			$file_contents = str_replace('[{', '<?php', $file_contents);
			$file_contents = str_replace('}]', '?>', $file_contents);

			## foreach
			//while (strpos($file_contents, "@foreach(") != False)
			//{
				$startFE = strpos($file_contents, "@foreach(")+9;
				$endFE = strpos($file_contents, ")", $startFE);

				$clause = substr($file_contents, $startFE, ($endFE-$startFE));

				$clauselen = strlen($clause);
				$foreach_callback = function($message)
				{
					$clause = $message[1];
					$find = $message[0];
					//echo $clause.' '.$find;
					//$file_contents = str_replace($find, 'test', $file_contents);
					return '<?php foreach('.$clause.') { ?>';
				};
				$if_callback = function($message)
				{
					$find = $message[0];
					$clause = $message[1];

					return '<?php if('.$clause.') { ?>';
				};

				$elseif_callback = function($message)
				{
					$find = $message[0];
					$clause = $message[1];
					return '<?php } elseif('.$clause.'){?>';
				};

				$file_contents = str_replace('@endif', '<?php } ?>', $file_contents);

				$file_contents = str_replace('@else', '<?php } else { ?>', $file_contents);

				$file_contents = preg_replace_callback('/@if\((.*)\)/', $if_callback, $file_contents);

				$file_contents = preg_replace_callback('/@elif\((.*)\)/', $elseif_callback, $file_contents);
				//$file_contents = preg_replace_callback('/^@foreach\((.*)\)$/', function($matches){ var_dump($matches);}, $file_contents);
				$file_contents = preg_replace_callback('/@foreach\((.*)\)/', $foreach_callback, $file_contents);//('/@foreach\((.*)\)/', '<?php echo foreach(', $file_contents);
				//$file_contents = str_replace('@foreach(', '<?php foreach(', $file_contents);//('/'.$startFE.'/', '<?php foreach('.$clause.'){', $file_contents, 1);
				$file_contents = str_replace('@endforeach', '<?php } ?>', $file_contents);
			//}

			file_put_contents("../cache/tmp/temp.php", $file_contents);
			include "../cache/tmp/temp.php";
		}
		else {
			// Enter your view not found page here...
			echo 'View not found';
			die();
		}
	}
	public function redirect($controllerAction)
	{

		global $route;
		include "../index.php";
		// Find the Route associated with the Controller
		$url = WEBROOT.$route->searchByControllerAction($controllerAction);

		header("location: $url");
		/*
		$controllerAction = explode('@', $controllerAction);
		$controller = $controllerAction[0];
		$action = $controllerAction[1];
		if (class_exists($controller))
		{
			$controller = new $controller;
			$controller->$action();
		}
		else {
			include "../app/controllers/$controller.php";
			$controller = new $controller;
			$controller->$action();
		}
		*/

	}
}