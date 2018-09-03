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

	public function modal($params)
	{
		// $params -> picture_id
		$user = $this->container->get_auth()->being_auth('osef');
		$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
		$count = $picture->get_likes();
		if ($user)
		{
			$auth_like = $picture->is_liked_by_auth_user($user->get_id());
			if ($auth_like)
				$auth_like = $auth_like->get_id();
			$user = true;
		}
		else
			$auth_like = false;

		echo json_encode(['count' => $count, 'auth' => $user, 'auth_like' => $auth_like]);
	}
}