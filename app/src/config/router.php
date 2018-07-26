<?php

require_once($PATH."src/MVC/controllers/HomeController.class.php");
require_once($PATH."src/MVC/controllers/WorkController.class.php");

function router()
{
	$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
	$controller = ucfirst(explode('/', $uri)[1])."Controller";
	$action = explode('/', $uri)[2];
	if ($_SERVER['REQUEST_METHOD'] === 'GET')
		$params = $_GET;
	else
		$params = $_POST;
	if (class_exists($controller) && method_exists($controller, $action))
	{	
		$instance = new $controller();
		$instance->$action($params);
	}
	else
	{	
		$instance = new HomeController();
		$instance->index($params);
	}
}

router();

?>