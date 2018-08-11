<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";

class User extends Model
{
	private $user_id;
	private $login;
	private $pwd;
	private $mail;
	private $confirmkey;
	private $confirmation;

	public function __construct($params)
	{
		//parent::__construct();
		foreach ($params as $key => $param) {
			$this->$key = $param;
		}
		echo 'userLogin : '.$this->login;
	}
}