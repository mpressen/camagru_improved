<?php

require_once ROOT_PATH."src/libraries/Classes/Controller.class.php";

class PictureController extends Controller
{
	public function __construct($container)
	{
		parent::__construct($container);
	}
	
	public function workshop($params)
	{
		$this->container->get_auth()->being_auth(true);
		
		$user = "user param";
		$data = [
			'title' => 'Workshop',
		];
		$this->container->get_View("workshop.php", $data);
	}
}