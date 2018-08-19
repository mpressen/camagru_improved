<?php

require_once ROOT_PATH."src/libraries/Classes/Controller.class.php";

class UserController extends Controller
{
	public function __construct($container)
	{
		parent::__construct($container);
	}
	

	# SIGN UP (form -> send mail -> insert in db)
	public function signup($params)
	{	
		$this->container->get_auth()->being_auth(false);

		$csrf = $this->container->get_FormKey();

		$this->container->get_View("signup.php", ['title' => 'Sign Up', 'csrf' => $csrf->outputKey()]);
	}
	public function create($params)
	{
		$this->container->get_auth()->being_auth(false);
		
		$this->container->get_security()->check_csrf('/user/signup');
		unset($params['form_key']);

		$this->container->get_security()->validate_inputs_format($params, '/user/signup');

		// find if login and mail are already in database (must be uniques)
		$meta_user = $this->container->get_UserCollection();
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

		$mailer = $this->container->get_Mailer();
		$mailer->send_confirmation($new_user);

		$_SESSION['message'] = 'Almost done ! Now please check your mailbox to confirm your email !';
		header("Location: /user/signin");
	}
	public function confirm($params)
	{
		$this->container->get_auth()->being_auth(false);

		$meta_user = $this->container->get_UserCollection();
		$user = $meta_user->find('login', $params['login']);
		
		if (!$user)
		{
			sleep(1);
			$_SESSION['message'] = 'This account doesn\'t exist. You need to sign up first !';
			header("Location: /user/signup");
			exit;
		}

		if ($user->get_confirmation() == 1)
		{	
			sleep(1);
			$_SESSION['message'] = 'This account is already activated. Please log in';
			header("Location: /user/signin");
			exit;
		}

		if ($user->get_confirmkey() !== $params['confirmkey'])
		{
			sleep(1);
			$_SESSION['message'] = 'Confirmation failed';
			header("Location: /user/signin");	
			exit;
		}
		
		$user->set_confirmation(true);
		$user->update('confirmation');
		$_SESSION['message'] = 'Your mail is confirmed. Please sign in !';
		header("Location: /user/signin");
	}


	# SIGN IN (form -> redirect)
	public function signin()
	{
		$this->container->get_auth()->being_auth(false);

		$csrf = $this->container->get_FormKey();

		$this->container->get_View("signin.php", ['title' => 'Sign In', 'csrf' => $csrf->outputKey()]);
	}
	public function connect($params)
	{
		$this->container->get_auth()->being_auth(false);
		
		$this->container->get_security()->check_csrf('/user/signin');
		unset($params['form_key']);
		
		$this->container->get_security()->validate_inputs_format($params, '/user/signin');

		$meta_user = $this->container->get_UserCollection();
		$user = $meta_user->find('mail', $params['mail']);
		if (!$user)
		{
			$_SESSION['message'] = '"'.$params['mail'].'" is not registered. Please create your account first.';
			header("Location: /user/signup");
			exit;	
		}
		if (!($user->get_pwd() === $this->container->get_security()->my_hash($params['pwd'])))
		{
			$_SESSION['message'] = 'Wrong password';
			header("Location: /user/signin");
			exit;
		}
		if ($user->get_confirmation() == 0)
			$_SESSION['message'] = "You haven't confirm your account yet. Check your mailbox";
		else
		{
			$_SESSION['message'] = "Welcome ".$user->get_login()." !";
			$this->container->get_auth()->connect($user->get_id());
		}
		header("Location: /");
	}


	# SIGN OUT (redirect)
	public function signout()
	{
		$user_id = $this->container->get_auth()->being_auth(true);

		$user = $this->container->get_UserCollection()->find('id', $user_id);

		$_SESSION['message'] = 'Bye '.$user->get_login().' !!!';

		$this->container->get_auth()->disconnect();
		header("Location: /");
	}


	# RESET (form -> send mail -> control link -> form -> update in db)
	public function reset()
	{
		$this->container->get_auth()->being_auth(false);

		$csrf = $this->container->get_FormKey();

		$this->container->get_View("reset.php", ['title' => 'Reset', 'csrf' => $csrf->outputKey()]);
	}
	public function reset_1($params)
	{
		$this->container->get_auth()->being_auth(false);
		
		$this->container->get_security()->check_csrf('/user/reset');
		unset($params['form_key']);
		
		$this->container->get_security()->validate_inputs_format($params, '/user/reset');

		$meta_user = $this->container->get_UserCollection();
		$user = $meta_user->find('mail', $params['mail']);
		if (!$user)
		{
			$_SESSION['message'] = '"'.$params['mail'].'" is not registered. Please create your account first.';
			header("Location: /user/signup");
			exit;	
		}
		$mailer = $this->container->get_Mailer();
		$mailer->reset_password($user);

		$_SESSION['message'] = 'Please check your mailbox to reset your password !';
		header("Location: /user/signin");
	}
	public function reset_2($params)
	{
		$this->container->get_auth()->being_auth(false);

		$meta_user = $this->container->get_UserCollection();
		$user = $meta_user->find('login', $params['login']);
		
		if (!$user)
		{
			sleep(1);
			$_SESSION['message'] = 'This account doesn\'t exist. You need to sign up first !';
			header("Location: /user/signup");
			exit;
		}

		if ($user->get_confirmkey() !== $params['confirmkey'])
		{
			sleep(1);
			$_SESSION['message'] = 'Authentication failed. Please restart the process.';
			header("Location: /user/reset");	
			exit;
		}

		$this->container->get_auth()->connect($user->get_id());

		header("Location: /user/reset_password");
	}
	public function reset_password()
	{
		$this->container->get_auth()->being_auth(true);

		$csrf = $this->container->get_FormKey();

		$this->container->get_View("reset_password.php", ['title' => 'Reset Password', 'csrf' => $csrf->outputKey()]);
	}
	public function reset_3($params)
	{
		$user_id = $this->container->get_auth()->being_auth(true);
		
		$this->container->get_security()->check_csrf('/user/reset_password');
		unset($params['form_key']);

		$this->container->get_security()->validate_inputs_format($params, '/user/reset_password');

		$user = $this->container->get_UserCollection()->find('id', $user_id);
		
		
		$user->set_pwd($params['pwd']);
		$user->update('pwd');
		$_SESSION['message'] = 'Your password has been changed.';
		header("Location: /");
	}

	public function update($params)
	{	
		$this->container->get_auth()->being_auth(true);

		$csrf = $this->container->get_FormKey();

		$this->container->get_View("signup.php", ['title' => 'My profile', 'csrf' => $csrf->outputKey()]);
	}
}