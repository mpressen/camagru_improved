<?php

require_once ROOT_PATH."src/libraries/Classes/Controller.class.php";
require_once ROOT_PATH."src/libraries/Classes/View.class.php";

require_once ROOT_PATH."src/MVC/models/UserModel.class.php";

require_once ROOT_PATH."src/libraries/Services/Mailer.class.php";

require_once ROOT_PATH."src/libraries/Helpers/FormKey.class.php";

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
		// check CSRF
		$csrf = new FormKey();
		if (!($csrf->validate()))
		{	
			//echo $csrf->get_form_key();
			$_SESSION['message'] = "CSRF attack spotted.";
			header("Location: /user/signup");
			exit;
		}
		unset($params['form_key']);

		// validate form inputs 
		foreach ($params as $key => $param) {
			if (empty($param))
			{
				sleep(1);
				$_SESSION['message'] = "Incorrect ".ucfirst($key)." field.";
				header("Location: /user/signup");
				exit;
			}
		}

		// find if login and mail are already in database (must be uniques)
		$user = new User();
		foreach(['login', 'mail'] as $field)
		{
			if ($user->find($field, $params[$field]))
			{
				$_SESSION['message'] = '"'.$params[$field].'" is already used.';
				header("Location: /user/signup");
				exit;
			}
		}

		// create new user on db
		$new_user = $user->new($params);

		// send the confirmation mail
		// $mailer = new Mailer();
		// $mailer->send_confirmation($new_user->get_mail);

		$_SESSION['message'] = 'Almost done ! Now please check your mailbox to confirm your email !';
		header("Location: /user/signin");
	}

	public function connect($params)
	{
		// check CSRF
		
		print_r($params);
	}

	public function update($params)
	{
		// check CSRF

		print_r($params);
	}
}