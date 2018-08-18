<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";
require_once ROOT_PATH."src/MVC/models/UserModel.class.php";

class UserCollection extends Model
{
	public function __construct($PDO, $SECURITY)
	{
		parent::__construct($PDO, $SECURITY);
	}

	public function new($params)
	{
		$params['confirmkey'] = $this->SECURITY->create_key();

		$params['pwd'] = $this->SECURITY->my_hash($params['pwd']);

		parent::insert($params);

		return new User($params, $this->PDO, $this->SECURITY);
	}
}