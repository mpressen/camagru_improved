<?php

require_once ROOT_PATH."src/libraries/MVC_Classes/Controller.class.php";

class PictureController extends Controller
{
	public function __construct($container)
	{
		parent::__construct($container);
	}
	
	public function workshop()
	{	
		// $params : none
		$user = $this->auth->being_auth(true);
		
		$data = [
			'title' => 'Workshop',
			'user'  => $user,
			'csrf'  => $this->form_key->outputKey()
		];
		$this->container->get_View("workshop.php", $data);
	}

	public function save($params)
	{
		// $params : base64encode(str) | frames(JSON string) | form_key(str)
		$this->ajax($params, 'save');
	}

	public function delete($params)
	{
		// $params : picture_id(int) | form_key(str)
		$this->ajax($params, 'delete');
	}

	public function like($params)
	{
		// $params : picture_id(int) | form_key(str)
		$this->ajax($params, 'like');
	}

	public function dislike($params)
	{
		// $params -> like_id(int) | form_key(str)
		$this->ajax($params, 'dislike');
	}

	public function comment($params)
	{
		// $params : picture_id(int) | comment(str) | form_key(str)
		$this->ajax($params, 'comment');
	}
}