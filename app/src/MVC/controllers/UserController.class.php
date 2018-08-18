<?php

require_once ROOT_PATH."src/libraries/Classes/Controller.class.php";

class UserController extends Controller
{
	public function __construct($container)
	{
		parent::__construct($container);
	}
	
	public function signup($params)
	{	
		$csrf = $this->get_container()->get_FormKey();
		$this->get_container()->get_View("signup.php", ['title' => 'Sign Up', 'csrf' => $csrf->outputKey()]);
	}

	public function signin()
	{
		$csrf = $this->get_container()->get_FormKey();
		$this->get_container()->get_View("signin.php", ['title' => 'Sign In', 'csrf' => $csrf->outputKey()]);
	}

	public function edit($params)
	{
		$this->get_container()->get_View("edit.php", ['title' => 'Edit']);
	}

	public function signout()
	{

	}

	public function create($params)
	{
		$this->get_container()->get_SECURITY()->check_csrf('/user/signup');
		unset($params['form_key']);

		$this->get_container()->get_SECURITY()->validate_inputs_format($params, '/user/signup');

		// find if login and mail are already in database (must be uniques)
		$meta_user = $this->get_container()->get_UserCollection();
		foreach(['login', 'mail'] as $field)
		{
			if ($meta_user->find($field, $params[$field]))
			{
				$_SESSION['message'] = '"'.$params[$field].'" is already used.';
				header("Location: /user/signup");
				exit;
			}
		}

		$new_user = $meta_user->new($params);

		$mailer = $this->get_container()->get_Mailer();
		$mailer->send_confirmation($new_user);

		$_SESSION['message'] = 'Almost done ! Now please check your mailbox to confirm your email !';
		header("Location: /user/signin");
	}

	public function confirm($params)
	{
		$meta_user = $this->get_container()->get_UserCollection();
		$user = $meta_user->find('login', $params['login']);
		
		if (!$user)
		{
			sleep(1);
			$_SESSION['message'] = 'This account doesn\'t exist. You need to sign up first !';
			header("Location: /user/signup");
		}

		if ($user->get_confirmation() == 1)
		{	
			sleep(1);
			$_SESSION['message'] = 'This account is already activated. Please log in';
			header("Location: /user/signin");
			exit;
		}

		if ($user->get_confirmkey() === $params['confirmkey'])
		{
			$user->set_confirmation(true);
			$user->update('confirmation');
		}
		else
		{
			sleep(1);
			$_SESSION['message'] = 'Confirmation failed';
			header("Location: /user/signin");	
			exit;
		}

		$_SESSION['message'] = 'Your mail is confirmed. Please sign in !';
		header("Location: /user/signin");
	}

	public function connect($params)
	{
		$this->get_container()->get_SECURITY()->check_csrf('/user/signin');
		unset($params['form_key']);
		
		$this->get_container()->get_SECURITY()->validate_inputs_format($params, '/user/signin');
	}

	public function update($params)
	{
		// check CSRF

		print_r($params);
	}
}