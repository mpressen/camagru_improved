<?php

abstract class Controller
{
	protected $container;
	protected $auth;
	protected $security;
	protected $form_key;
	protected $users;
	protected $image_helper;

	public function __construct($container)
	{
		$this->container = $container;
		$this->auth = $container->get_auth();
		$this->security = $container->get_security();
		$this->form_key = $container->get_form_key();
		$this->users = $container->get_users();
		$this->image_helper = $container->get_ImageHelper();
	}

	protected function ajax($params, $route)
	{
		//ajax validation
		$user     = $this->auth->being_auth(true, true);
		$csrf     = $this->security->check_csrf('osef', true);
		$response = ['key' => $this->form_key->get_key()];

		if ($this->security->ajax_secure_and_display($params, $user, $csrf, $response))
			exit;
		

//save
		if ($route === 'save')
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
		}
//delete
		else if ($route === 'delete')
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
		}
//like
		else if ($route === 'like')
		{
			$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
			
			$like_id = $this->container->get_LikeCollection()->new([
				'user_id'    => $user->get_id(),
				'picture_id' => $params['picture_id']]);
			
			$response['like_id'] = $like_id;
		}
//dislike
		else if ($route === 'dislike')
		{
			$like       = $this->container->get_LikeCollection()->find('id', $params['like_id']);
			$picture_id = $like->get_picture_id();
			
			$like->delete();
			
			$response['picture_id'] = 'pic'.$picture_id;
		}
//comment
		else if ($route === 'comment')
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
		}
//update
		else if ($route === 'update')
		{
			if (isset($params['login']))
			{	
				if ($this->users->find('login', $params['login']))
				{
					$response['message'] = '"'.$params['login'].'" is already used.';
					echo json_encode($response);
					exit;
				}

				$user->set_login($params['login']);
				$user->update('login');
				
				$response['message'] = 'Your login has been updated !';
				$response['field'] = $params['login'];
			}
			else if (isset($params['mail']))
			{
				if ($this->users->find('mail', $params['mail']))
				{
					$response['message'] = '"'.$params['mail'].'" is already used.';
					echo json_encode($response);
					exit;
				}
				
				$user->set_mail($params['mail']);
				$user->update('mail');
				$user->set_confirmation(0);
				$user->update('confirmation');
				
				$mailer = $this->container->get_Mailer();
				$mailer->send_confirmation($user);
				
				$response['message'] = 'Mail updated ! Now please check your mailbox to confirm your new email !';
			}
		}

		echo json_encode($response);
	}
}