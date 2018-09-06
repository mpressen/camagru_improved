<?php

require_once ROOT_PATH."src/libraries/MVC_Classes/Controller.class.php";

class UserController extends Controller
{
	public function __construct($container)
	{
		parent::__construct($container);
	}
	
# SIGN UP (form -> send mail -> insert in db)
	public function signup()
	{	
		// $params : none
		$this->auth->being_auth(false);

		$this->container->get_View("signup.php", ['title' => 'Sign Up', 'csrf' => $this->form_key->outputKey()]);
	}

	public function create($params)
	{
		// $params : login(str) | mail(str) | pwd(str) | form_key(str)
		$this->auth->being_auth(false);
		
		$this->security->check_csrf('/user/signup');
		unset($params['form_key']);
		$this->security->validate_inputs_format($params, '/user/signup');

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
		// $params : login(str) | form_key(str)
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
			$_SESSION['message'] = 'This account is already activated.';
			header("Location: /user/signin");
			exit;
		}

		if ($user->get_confirmkey() !== $params['form_key'])
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

		$_SESSION['message'] = 'Your mail is confirmed.';
		header("Location: /user/signin");
	}

# SIGN IN (form -> redirect)
	public function signin()
	{
		// $params : none
		$this->auth->being_auth(false);

		$this->container->get_View("signin.php", ['title' => 'Sign In', 'csrf' => $this->form_key->outputKey()]);
	}

	public function connect($params)
	{	
		// $params : mail(str) | pwd(str) | form_key(str)
		$this->auth->being_auth(false);
		
		$this->security->check_csrf('/user/signin');
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
			$this->auth->connect($user);
			$_SESSION['message'] = "Welcome ".$user->get_login()." !";
			
		}
		header("Location: /");
	}

# SIGN OUT (redirect)
	public function signout()
	{
		// $params : none
		$user = $this->auth->being_auth(true);

		$this->auth->disconnect();

		$_SESSION['message'] = 'Bye '.$user->get_login().' !!!';
		header("Location: /");
	}


# RESET (form -> send mail -> control link -> form -> update in db)
	public function reset()
	{
		// $params : none
		$this->auth->being_auth(false);

		$this->container->get_View("reset.php", ['title' => 'Reset', 'csrf' => $this->form_key->outputKey()]);
	}

	public function reset_1($params)
	{
		// $params : mail(str) | form_key(str)
		$this->auth->being_auth(false);
		
		$this->security->check_csrf('/user/reset');
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
		// $params : login(str) | form_key(str)
		$this->auth->being_auth(false);

		$user = $this->users->find('login', $params['login']);
		if (!$user)
		{
			sleep(1);
			$_SESSION['message'] = 'This account doesn\'t exist. You need to sign up first !';
			header("Location: /user/signup");
			exit;
		}
		if ($user->get_confirmkey() !== $params['form_key'])
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
		// $params : none
		$user = $this->auth->being_auth(true);

		$this->container->get_View("reset_password.php", ['title' => 'Reset Password', 'csrf' => $this->form_key->outputKey(), 'user' => $user]);
	}

	public function reset_3($params)
	{
		// $params : pwd(str) | form_key(str)
		$user = $this->auth->being_auth(true);
		
		$this->security->check_csrf('/user/reset_password');
		$this->security->validate_inputs_format($params, '/user/reset_password');
		
		$user->set_pwd($params['pwd']);
		$user->update('pwd');

		$_SESSION['message'] = 'Your password has been changed.';
		header("Location: /");
	}

# UPDATE PROFILE INFO
	public function profile($params)
	{	
		// $params : none
		$user = $this->auth->being_auth(true);

		$this->container->get_View("update.php", ['title' => 'My profile', 'csrf' => $this->form_key->outputKey(), 'user' => $user]);
	}
	
	public function update($params)
	{
		// $params : login(str) or pwd(str) | form_key(str)
		$this->ajax($params, 'update');
	}

}