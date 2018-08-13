<?php

require_once ROOT_PATH."src/MVC/controllers/HomeController.class.php";
require_once ROOT_PATH."src/MVC/controllers/UserController.class.php";
require_once ROOT_PATH."src/libraries/Helpers/Security.class.php";

class Router {
	public function __construct()
	{	
		$security = new Security();
		$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
		
		$controller = ucfirst(explode('/', $uri)[1])."Controller";
		$action = explode('/', $uri)[2];
		$params = $security->validate($_SERVER['REQUEST_METHOD'] === 'GET' ? INPUT_GET : INPUT_POST);
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

}