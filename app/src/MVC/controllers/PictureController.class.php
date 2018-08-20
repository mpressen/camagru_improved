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
		$user_id = $this->container->get_auth()->being_auth(true);
		$user = $this->container->get_UserCollection()->find('id', $user_id);
		
		$data = [
			'title' => 'Workshop',
			'user' => $user
		];
		$this->container->get_View("workshop.php", $data);
	}
}