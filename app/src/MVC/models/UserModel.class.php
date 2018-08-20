<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";

class User extends Model
{
	private $id;
	private $login;
	private $pwd;
	private $mail;
	private $confirmkey;
	private $confirmation;

	public function __construct($params, $pdo, $security)
	{
		parent::__construct($pdo, $security);
		foreach ($params as $key => $param) {
			$this->$key = $param;
		}
	}

	public function get_gravatar_hash()
	{
		return md5(strtolower(trim($this->mail)));
	}



	# GETTERS
	public function get_id()
	{
		return $this->id;
	}

	public function get_login()
	{
		return $this->login;
	}

	public function get_pwd()
	{
		return $this->pwd;
	}

	public function get_mail()
	{
		return $this->mail;
	}

	public function get_confirmkey()
	{
		return $this->confirmkey;
	}

	public function get_confirmation()
	{
		return $this->confirmation;
	}



	# SETTERS
	public function set_login($value)
	{
		$this->login = $value;
	}

	public function set_mail($value)
	{
		$this->mail = $value;
	}

	public function set_confirmation($value)
	{
		$this->confirmation = $value;
	}

	public function set_pwd($value)
	{
		$this->pwd = $this->security->my_hash($value);
	}
}