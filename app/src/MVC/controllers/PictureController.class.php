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
		$img_path = $this->image_helper->save_image($img, $user_id);
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
			$_SESSION['message'] = 'You can only delete your own picture.';
			header("Location: /user/signup");
			exit;
		}
		$picture->delete();
		$this->image_helper->delete_image($picture->get_path());
		echo $params['picture_id'];
	}

}