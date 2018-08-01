<?php

require_once($PATH."src/libraries/Classes/Controller.class.php");
require_once($PATH."src/libraries/Classes/View.class.php");

class UserController extends Controller
{
	public function signup()
	{
		new View("signup.php", ['title' => 'Sign Up']);
	}

	public function signin()
	{
		new View("signin.php", ['title' => 'Sign In']);
	}

	public function signout()
	{

	}

	public function create($params)
	{
		print_r($params);
	}
}