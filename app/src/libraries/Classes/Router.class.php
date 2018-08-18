<?php

require_once ROOT_PATH."src/MVC/controllers/HomeController.class.php";
require_once ROOT_PATH."src/MVC/controllers/UserController.class.php";

class Router {
	public function __construct($container)
	{	
		$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
		
		$controller = ucfirst(explode('/', $uri)[1])."Controller";
		$action = explode('/', $uri)[2];
		
		if (class_exists($controller) && method_exists($controller, $action))
		{	
			$instance = new $controller($container);
			$params = $container->get_SECURITY()->validate($_SERVER['REQUEST_METHOD'] === 'GET' ? INPUT_GET : INPUT_POST);
			$instance->$action($params);
		}
		else
		{	
			$instance = new HomeController($container);
			$instance->index($params);
		}
	}

}