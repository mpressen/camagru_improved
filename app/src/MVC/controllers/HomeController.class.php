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
		$user_id = $this->container->get_auth()->being_auth('osef');
		$user = $this->container->get_UserCollection()->find('id', $user_id);

		$data = [
			'title' => 'Home',
			'user' => $user
		];
		$this->container->get_View("gallery.php", $data);
	}
}