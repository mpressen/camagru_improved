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
		if ($params['picture_id'])
		{
			$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
			if (!$picture)
			{
				$_SESSION['message'] = 'This picture doesn\'t exist.';
				header("Location: /");
				exit;
			}
		}

		$data = [
			'title' => 'Home',
			'user' => $this->auth->being_auth('osef'),
			'pictures' => $this->container->get_PictureCollection()->all_pictures(20),
			'csrf' => $this->form_key->outputKey(),
			'picture' => $params['picture_id']
		];
		$this->container->get_View("gallery.php", $data);
	}

	public function modal($params)
	{
		// $params -> picture_id
		$user = $this->auth->being_auth('osef');
		$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
		$owner = $this->users->find('id', $picture->get_user_id());
		$count = $picture->get_likes();
		$comments_with_user_infos = $picture->get_comments_with_user_infos();
		$display_comments = [];
		foreach($comments_with_user_infos as $comment)
		{
			array_push($display_comments, [
				'text' => $comment['comment'],
				'owner_profile' => md5(strtolower(trim($comment['mail']))),
				'owner_login' =>$comment['login'],
				'timestamp' => date_format(date_create($comment['timestamp'])->setTimezone(new DateTimeZone('Europe/Paris')), 'd/m/Y H:i:s')
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

		echo json_encode(['count' => $count, 'auth' => $user, 'auth_like' => $auth_like, 'owner_profile' => $owner->get_gravatar_hash(), 'owner_login' => $owner->get_login(), 'comments' => $display_comments, 'image_path' => $picture->get_path()]);
	}
	public function infinite($params)
	{
		// $params -> picture_id
		$pictures = $this->container->get_PictureCollection()->all_pictures_before($params['picture_id'], $params['load_count']);

		$display_pictures = [];
		foreach($pictures as $picture)
		{
			array_push($display_pictures, [
				'id' => $picture->get_id(),
				'path' => $picture->get_path()
			]);
		}
		echo json_encode(['pictures' => $display_pictures]);
	}
}