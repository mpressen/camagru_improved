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
		$owner = $this->container->get_UserCollection()->find('id', $picture->get_user_id());
		$count = $picture->get_likes();
		$comments = $picture->get_comments();
		$display_comments = [];
		foreach($comments as $comment)
		{
			$owner = $this->container->get_UserCollection()->find('id', $comment->get_user_id());
			array_push($display_comments, [
				'text' => $comment->get_comment(),
				'owner_profile' => $owner->get_gravatar_hash(),
				'owner_login' =>$owner->get_login(),
				'timestamp' => date_format(date_create($comment->get_timestamp()), 'd/m/Y H:i:s')
			]);
		}
		if ($user)
		{
			$auth_like = $picture->is_liked_by_auth_user($user->get_id());
			if ($auth_like)
				$auth_like = $auth_like->get_id();
		}
		else
			$auth_like = false;

		echo json_encode(['count' => $count, 'auth' => $user, 'auth_like' => $auth_like, 'owner_profile' => $owner->get_gravatar_hash(), 'owner_login' => $owner->get_login(), 'comments' => $display_comments]);
	}
}