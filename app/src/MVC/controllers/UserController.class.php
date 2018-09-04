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
		$this->auth->being_auth(false);

		$this->container->get_View("signup.php", ['title' => 'Sign Up', 'csrf' => $this->form_key->outputKey()]);
	}
	public function create($params)
	{
		$this->auth->being_auth(false);
		
		$this->security->check_csrf('/user/signup');
		unset($params['form_key']);

		$this->security->validate_inputs_format($params, '/user/signup');

		// find if login and mail are already in database (must be uniques)
		foreach(['login', 'mail'] as $field)
		{
			if ($this->users->find($field, $params[$field]))
			{
				$_SESSION['message'] = '"'.$params[$field].'" is already used.';
				header("Location: /user/signup");
				exit;
			}
		}

		$new_user = $this->users->new($params);

		$mailer = $this->container->get_Mailer();
		$mailer->send_confirmation($new_user);

		$_SESSION['message'] = 'Almost done ! Now please check your mailbox to confirm your email !';
		header("Location: /user/signin");
	}
	public function confirm($params)
	{
		$this->auth->being_auth(false);

		
		$user = $this->users->find('login', $params['login']);
		
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
		
		$user->set_confirmation(1);
		$user->update('confirmation');

		$user->set_confirmkey();
		$user->update('confirmkey');
		$_SESSION['message'] = 'Your mail is confirmed. Please sign in !';
		header("Location: /user/signin");
	}


	# SIGN IN (form -> redirect)
	public function signin()
	{
		$this->auth->being_auth(false);

		$this->container->get_View("signin.php", ['title' => 'Sign In', 'csrf' => $this->form_key->outputKey()]);
	}
	public function connect($params)
	{
		$this->auth->being_auth(false);
		
		$this->security->check_csrf('/user/signin');
		unset($params['form_key']);
		
		$this->security->validate_inputs_format($params, '/user/signin');

		
		$user = $this->users->find('mail', $params['mail']);
		if (!$user)
		{
			$_SESSION['message'] = '"'.$params['mail'].'" is not registered. Please create your account first.';
			header("Location: /user/signup");
			exit;	
		}
		if (!($user->get_pwd() === $this->security->my_hash($params['pwd'])))
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
			$this->auth->connect($user);
		}
		header("Location: /");
	}


	# SIGN OUT (redirect)
	public function signout()
	{
		$user = $this->auth->being_auth(true);

		$_SESSION['message'] = 'Bye '.$user->get_login().' !!!';

		$this->auth->disconnect();
		header("Location: /");
	}


	# RESET (form -> send mail -> control link -> form -> update in db)
	public function reset()
	{
		$this->auth->being_auth(false);

		$csrf = $this->form_key;

		$this->container->get_View("reset.php", ['title' => 'Reset', 'csrf' => $csrf->outputKey()]);
	}
	public function reset_1($params)
	{
		$this->auth->being_auth(false);
		
		$this->security->check_csrf('/user/reset');
		unset($params['form_key']);
		
		$this->security->validate_inputs_format($params, '/user/reset');

		$user = $this->users->find('mail', $params['mail']);
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
		$this->auth->being_auth(false);

		$user = $this->users->find('login', $params['login']);
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

		$user->set_confirmkey();
		$user->update('confirmkey');
		
		$this->auth->connect($user);

		header("Location: /user/reset_password");
	}
	public function reset_password()
	{
		$user = $this->auth->being_auth(true);

		$csrf = $this->form_key;

		$this->container->get_View("reset_password.php", ['title' => 'Reset Password', 'csrf' => $csrf->outputKey(), 'user' => $user]);
	}
	public function reset_3($params)
	{
		$user = $this->auth->being_auth(true);
		
		$this->security->check_csrf('/user/reset_password');
		unset($params['form_key']);

		$this->security->validate_inputs_format($params, '/user/reset_password');
		
		$user->set_pwd($params['pwd']);
		$user->update('pwd');
		$_SESSION['message'] = 'Your password has been changed.';
		header("Location: /");
	}

	# UPDATE PROFILE INFO
	public function profile($params)
	{	
		$user = $this->auth->being_auth(true);

		$csrf = $this->form_key;

		$this->container->get_View("update.php", ['title' => 'My profile', 'csrf' => $csrf->outputKey(), 'user' => $user]);
	}
	public function update($params)
	{
		$user = $this->auth->being_auth(true, true);
		if ($user === NULL)
		{
			echo 'no-log';
			exit;
		}
		
		$this->security->check_csrf('/user/profile');
		unset($params['form_key']);

		$this->security->validate_inputs_format($params, '/user/profile');

		
			
		if (isset($params['login']))
		{	
			# stop if this login already exist
			if ($this->users->find('login', $params['login']))
			{
				$_SESSION['message'] = '"'.$params['login'].'" is already used.';
				exit;
			}
			
			$user->set_login($params['login']);
			$user->update('login');
			
			$_SESSION['message'] = 'Your login has been updated !';
		}
		else if (isset($params['mail']))
		{
			# stop if this mail already exist
			if ($this->users->find('mail', $params['mail']))
			{
				$_SESSION['message'] = '"'.$params['mail'].'" is already used.';
				exit;
			}
			
			$user->set_mail($params['mail']);
			$user->update('mail');
			
			$user->set_confirmation(0);
			$user->update('confirmation');

			$mailer = $this->container->get_Mailer();
			$mailer->send_confirmation($user);

			$_SESSION['message'] = 'Mail updated ! Now please check your mailbox to confirm your new email !';
		}
		else
			$_SESSION['message'] = 'Nope !';
	}

}