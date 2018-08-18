<?php

require_once ROOT_PATH."src/libraries/Classes/Controller.class.php";

class HomeController extends Controller
{
	public function __construct($container)
	{
		parent::__construct($container);
	}
	
	public function index($params)
	{
		$user = "user param";
		$data = [
			'title' => 'Home',
		];
		$this->container->get_View("gallery.php", $data);
	}
}