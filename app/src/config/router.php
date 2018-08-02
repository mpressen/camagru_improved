<?php

require_once($PATH."src/MVC/controllers/HomeController.class.php");
require_once($PATH."src/MVC/controllers/UserController.class.php");

function validate($params)
{
	$args = array(
		'login' => array(
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options'   => array("regexp" => "/^(?=.*\w).{3,}$/")),
		'mail'  => FILTER_VALIDATE_EMAIL,
		'pwd'   => array(
			'filter'    => FILTER_VALIDATE_REGEXP,
			'options'   => array("regexp" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/")));
	return filter_input_array($params, $args);
}

function router()
{
	$uri = explode('?', $_SERVER['REQUEST_URI'])[0];
	$controller = ucfirst(explode('/', $uri)[1])."Controller";
	$action = explode('/', $uri)[2];
	if ($_SERVER['REQUEST_METHOD'] === 'GET')
	{
		$params = validate(INPUT_GET);
		print_r($params);
	}
	else
	{
		$params = validate(INPUT_POST);
		print_r($params);
	}
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