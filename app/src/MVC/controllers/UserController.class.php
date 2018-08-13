<?php

require_once ROOT_PATH."src/libraries/Classes/Controller.class.php";
require_once ROOT_PATH."src/libraries/Classes/View.class.php";

require_once ROOT_PATH."src/MVC/models/UserCollection.class.php";
require_once ROOT_PATH."src/MVC/models/UserModel.class.php";

require_once ROOT_PATH."src/libraries/Services/Mailer.class.php";

require_once ROOT_PATH."src/libraries/Helpers/FormKey.class.php";
require_once ROOT_PATH."src/libraries/Helpers/Security.class.php";

class UserController extends Controller
{
	public function signup($params)
	{	
		$csrf = new FormKey();
		new View("signup.php", ['title' => 'Sign Up', 'csrf' => $csrf->outputKey()]);
	}

	public function signin()
	{
		$csrf = new FormKey();
		new View("signin.php", ['title' => 'Sign In', 'csrf' => $csrf->outputKey()]);
	}

	public function edit($params)
	{
		new View("edit.php", ['title' => 'Edit']);
	}

	public function signout()
	{

	}

	public function create($params)
	{
		$security = new Security();

		$security->check_csrf('/user/signup');
		unset($params['form_key']);

		$security->validate_inputs_format($params, '/user/signup');

		// find if login and mail are already in database (must be uniques)
		$meta_user = new UserCollection();
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

		$mailer = new Mailer();
		$mailer->send_confirmation($new_user);

		$_SESSION['message'] = 'Almost done ! Now please check your mailbox to confirm your email !';
		header("Location: /user/signin");
	}

	public function confirm($params)
	{
		$meta_user = new UserCollection();
		$user = $meta_user->find('login', $params['login']);
		
		if (!$user)
		{
			sleep(1);
			$_SESSION['message'] = 'This account doesn\'t exist. You need to sign up first !';
			header("Location: /user/signup");
		}

		if ($user->get_confirmation() === 1)
		{	
			sleep(1);
			$_SESSION['message'] = 'This account is already activated. Please log in';
			header("Location: /user/signin");
		}

		if ($user->get_confirmkey() === $params['confirmkey'])
		{
			$user->set_confirmation(true);
			#$user->update();
		}
		else
		{
			sleep(1);
			$mailer = new Mailer();
			$mailer->send_confirmation($user);
			$_SESSION['message'] = 'Security Error. A new confirmation mail has been sent to this account';
			header("Location: /user/signup");	
		}

		$_SESSION['message'] = 'Your mail is confirmed. Please sign in !';
		header("Location: /user/signin");
	}

	public function connect($params)
	{
		$security = new Security();

		$security->check_csrf('/user/signin');
		unset($params['form_key']);

		$security->validate_inputs_format($params, '/user/signin');
	}

	public function update($params)
	{
		// check CSRF

		print_r($params);
	}
}