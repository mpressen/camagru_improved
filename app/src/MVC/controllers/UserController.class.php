<?php

require_once($PATH."src/libraries/Classes/Controller.class.php");
require_once($PATH."src/libraries/Classes/View.class.php");

class UserController extends Controller
{
	public function signup($params)
	{
		new View("signup.php", ['title' => 'Sign Up']);
	}

	public function signin()
	{
		new View("signin.php", ['title' => 'Sign In']);
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
		foreach ($params as $key => $param) {
			if (empty($param))
			{
				sleep(1);
				$_SESSION['error'] = "Incorrect ".ucfirst($key)." field.";
				header("Location: /user/signup");
				exit;
			}
		}
		echo "validation passed";
	}

	public function connect($params)
	{
		print_r($params);
	}

	public function update($params)
	{
		print_r($params);
	}
}