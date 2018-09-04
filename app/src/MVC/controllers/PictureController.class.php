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
		$user = $this->auth->being_auth(true);
		
		$data = [
			'title' => 'Workshop',
			'user' => $user,
			'csrf' => $this->form_key->outputKey()
		];
		$this->container->get_View("workshop.php", $data);
	}

	public function save($params)
	{
		// $params -> base64encode(str) and frames(array of objects)
		$user = $this->auth->being_auth(true, true);
		$no_csrf = $this->security->check_csrf('osef', true);
		$no_validation = $this->security->validate_inputs_format($params, 'osef', true);
		$form_key = $this->form_key->get_key();
		if ($this->security->ajax_secure_and_display($user, $no_validation, $no_csrf, $form_key))
			exit;
		
		$img = $this->image_helper->merge_image($params);
		$img_path = $this->image_helper->save_image($img, $user->get_id());
		$picture_id = $this->container->get_PictureCollection()->new(['user_id' => $user->get_id(), 'path' => $img_path]);
		echo json_encode(['src' => $img_path, 'picture_id' => "pic".$picture_id, 'csrf' => $form_key]);
	}

	public function delete($params)
	{
		// $params -> picture_id
		$user = $this->auth->being_auth(true, true);
		$no_csrf = $this->security->check_csrf('osef', true);
		$no_validation = $this->security->validate_inputs_format($params, 'osef', true);
		$form_key = $this->form_key->get_key();
		if ($this->security->ajax_secure_and_display($user, $no_validation, $no_csrf, $form_key))
			exit;

		$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
		if ($picture->get_user_id() !== $user->get_id())
		{
			header("HTTP/1.1 403 Forbidden");
			exit;
		}
		$picture->delete();
		$this->image_helper->delete_image($picture->get_path());
		echo json_encode(['picture_id' => $params['picture_id'], 'csrf' => $form_key]);
	}

	public function like($params)
	{
		// $params -> picture_id
		$user = $this->auth->being_auth(true, true);
		$no_csrf = $this->security->check_csrf('osef', true);
		$no_validation = $this->security->validate_inputs_format($params, 'osef', true);
		$form_key = $this->form_key->get_key();
		if ($this->security->ajax_secure_and_display($user, $no_validation, $no_csrf, $form_key))
			exit;

		$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
		$like_id = $this->container->get_LikeCollection()->new(['user_id' => $user->get_id(), 'picture_id' => $params['picture_id']]);
		echo json_encode(['like_id' => $like_id, 'csrf' => $this->form_key->get_key()]);
	}

	public function dislike($params)
	{
		// $params -> like_id
		$user = $this->auth->being_auth(true, true);
		$no_csrf = $this->security->check_csrf('osef', true);
		$no_validation = $this->security->validate_inputs_format($params, 'osef', true);
		$form_key = $this->form_key->get_key();
		if ($this->security->ajax_secure_and_display($user, $no_validation, $no_csrf, $form_key))
			exit;

		$like = $this->container->get_LikeCollection()->find('id', $params['like_id']);
		$picture_id = $like->get_picture_id();
		$like->delete();
		echo json_encode(['picture_id' => 'pic'.$picture_id, 'csrf' => $this->form_key->get_key()]);
		
	}

	public function comment($params)
	{
		// $params -> picture_id and comment
		$user = $this->auth->being_auth(true, true);
		$no_csrf = $this->security->check_csrf('osef', true);
		$no_validation = $this->security->validate_inputs_format($params, 'osef', true);
		$form_key = $this->form_key->get_key();
		if ($this->security->ajax_secure_and_display($user, $no_validation, $no_csrf, $form_key))
			exit;

		$picture = $this->container->get_PictureCollection()->find('id', $params['picture_id']);
		$comment_id = $this->container->get_CommentCollection()->new(['user_id' => $user->get_id(), 'picture_id' => $params['picture_id'], 'comment' => $params['comment']]);

		$owner = $picture->get_owner();
		$mailer = $this->container->get_Mailer();
		$mailer->comment_received($owner, $picture);

		echo json_encode(['comment' => $params['comment'], 'owner_profile' => $user->get_gravatar_hash(), 'owner_login' => $user->get_login(), 'csrf' => $form_key]);
	}
}