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

	public function save($params, $response = false, $user = false)
	{
		// $params : base64encode(str) | frames(JSON string) | form_key(str)
		if (!$response)
			$this->ajax($this, $params, 'save');
		else
		{
			if ($user && $user->get_pictures_count() > 20)
			{
				$response['message'] = '20 photos maximum per user.';
				
				echo json_encode($response); 
				return 1;
			}

			$img      = $this->image_helper->merge_image($params);
			$img_path = $this->image_helper->save_image($img, $user->get_id());
			
			$picture_id = $this->container->get_PictureCollection()->new([
				'user_id' => $user->get_id(),
				'path'    => $img_path]);
			
			$response['src']        = $img_path;
			$response['picture_id'] = "pic".$picture_id;
			
			echo json_encode($response);
		}
	}

	public function delete($params, $response = false, $user = false)
	{
		// $params : picture_id(int) | form_key(str)
		if (!$response)
			$this->ajax($this, $params, 'delete');
		else
		{
			$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
			
			if ($picture->get_user_id() !== $user->get_id())
			{
				header("HTTP/1.1 403 Forbidden");
				exit;
			}
			
			$picture->delete();
			
			$this->image_helper->delete_image($picture->get_path());
			
			$response['picture_id'] = $params['picture_id'];
			
			echo json_encode($response);
		}
	}

	public function like($params, $response = false, $user = false)
	{
		// $params : picture_id(int) | form_key(str)
		if (!$response)
			$this->ajax($this, $params, 'like');
		else
		{
			$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
			
			$like_id = $this->container->get_LikeCollection()->new([
				'user_id'    => $user->get_id(),
				'picture_id' => $params['picture_id']]);
			
			$response['like_id'] = $like_id;
			
			echo json_encode($response);
		}
	}

	public function dislike($params, $response = false, $user = false)
	{
		// $params -> like_id(int) | form_key(str)
		if (!$response)
			$this->ajax($this, $params, 'dislike');
		else
		{
			$like       = $this->container->get_LikeCollection()->find('id', $params['like_id']);
			$picture_id = $like->get_picture_id();
			
			$like->delete();
			
			$response['picture_id'] = 'pic'.$picture_id;
			
			echo json_encode($response);
		}
	}

	public function comment($params, $response = false, $user = false)
	{
		// $params : picture_id(int) | comment(str) | form_key(str)
		if (!$response)
			$this->ajax($this, $params, 'comment');
		else
		{
			$comment_id = $this->container->get_CommentCollection()->new([
				'user_id'    => $user->get_id(),
				'picture_id' => $params['picture_id'],
				'comment'    => $params['comment']]);
			
			$picture    = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
			$owner  = $picture->get_owner();

			$mailer = $this->container->get_Mailer();
			$mailer->comment_received($owner, $picture);

			$response['comment']       = $params['comment'];
			$response['owner_profile'] = $user->get_gravatar_hash();
			$response['owner_login']   = $user->get_login();
			
			echo json_encode($response);
		}
	}
}