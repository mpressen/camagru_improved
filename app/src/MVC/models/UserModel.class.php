<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";
require_once ROOT_PATH."src/libraries/Helpers/Security.class.php";

class User extends Model
{
	private $user_id;
	private $login;
	private $pwd;
	private $mail;
	private $confirmkey;
	private $confirmation;

	private $security;

	public function __construct()
	{
		parent::__construct();
		$this->security = new Security();
	}

	public function new($params)
	{
		// creer une confirmkey
		$params['confirmkey'] = $this->security->create_confirmkey();

		// hash le mdp
		$params['pwd'] = $this->security->my_hash($params['pwd']);

		parent::insert($params);

		//return l'objet user crée
	}
}