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
		$user_id = $this->container->get_auth()->being_auth(true);
		$user = $this->container->get_UserCollection()->find('id', $user_id);
		
		$data = [
			'title' => 'Workshop',
			'user' => $user
		];
		$this->container->get_View("workshop.php", $data);
	}

	public function save($params)
	{
		// $params -> base64encode(str) and frames(array of objects)
		$user_id = $this->container->get_auth()->being_auth(true);
		$img = $this->image_helper->merge_image($params);
		$img_path = $this->image_helper->save_image($img, $user_id);
		$picture = $this->container->get_PictureCollection()->new(['user_id' => $user_id, 'path' => $img_path]);
		echo $img_path;
	}

	public function delete($params)
	{
		// $params -> picture_id
		$user_id = $this->container->get_auth()->being_auth(true);
		echo $params['picture_id'];
		
	}

}