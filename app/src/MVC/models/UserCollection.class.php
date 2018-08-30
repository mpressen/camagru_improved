<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";
require_once ROOT_PATH."src/MVC/models/UserModel.class.php";

class UserCollection extends Model
{
	public function __construct($pdo, $security, $container)
	{
		parent::__construct($pdo, $security, $container);
	}

	public function new($params)
	{
		$params['confirmkey'] = $this->security->create_key();

		$params['pwd'] = $this->security->my_hash($params['pwd']);

		parent::insert($params);

		return $this->container->getUser($params);
	}
}