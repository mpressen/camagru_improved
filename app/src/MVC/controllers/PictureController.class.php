<?php

require_once ROOT_PATH."src/libraries/Classes/Controller.class.php";

class PictureController extends Controller
{
	private $image_helper;

	public function __construct($container)
	{
		parent::__construct($container);
		$this->image_helper = $container->get_ImageHelper();
	}
	
	public function workshop($params)
	{
		$user = $this->container->get_auth()->being_auth(true);
		
		$data = [
			'title' => 'Workshop',
			'user' => $user
		];
		$this->container->get_View("workshop.php", $data);
	}

	public function save($params)
	{
		// $params -> base64encode(str) and frames(array of objects)
		$user = $this->container->get_auth()->being_auth(true);
		$img = $this->image_helper->merge_image($params);
		$img_path = $this->image_helper->save_image($img, $user->get_id());
		$picture_id = $this->container->get_PictureCollection()->new(['user_id' => $user->get_id(), 'path' => $img_path]);
		echo json_encode(['src' => $img_path, 'picture_id' => "pic".$picture_id]);
	}

	public function delete($params)
	{
		// $params -> picture_id
		$user = $this->container->get_auth()->being_auth(true);
		$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
		if ($picture->get_user_id() !== $user->get_id())
		{
			header("HTTP/1.1 403 Forbidden");
			exit;
		}
		$picture->delete();
		$this->image_helper->delete_image($picture->get_path());
		echo $params['picture_id'];
	}

	public function like($params)
	{
		// $params -> picture_id
		$user = $this->container->get_auth()->being_auth(true);
		$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
		$like_id = $this->container->get_LikeCollection()->new(['user_id' => $user->get_id(), 'picture_id' => $params['picture_id']]);
		echo $like_id;
	}

	public function dislike($params)
	{
		// $params -> like_id
		$user = $this->container->get_auth()->being_auth(true);
		$like = $this->container->get_LikeCollection()->find('id', $params['like_id']);
		$picture_id = $like->get_picture_id();
		$like->delete();
		echo 'pic'.$picture_id;
		
	}
}