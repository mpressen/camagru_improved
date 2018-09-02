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
		$data = [
			'title' => 'Home',
			'user' => $this->container->get_auth()->being_auth('osef'),
			'pictures' => $this->container->get_PictureCollection()->all_pictures()
		];
		$this->container->get_View("gallery.php", $data);
	}
}