<?php

require_once ROOT_PATH."src/libraries/MVC_Classes/Model.class.php";
require_once ROOT_PATH."src/MVC/models/UserModel.class.php";

class UserCollection extends Model
{
	public function __construct($container)
	{
		parent::__construct($container);
	}

	public function new($params)
	{
		$params['confirmkey'] = $this->security->create_key();

		$params['pwd'] = $this->security->my_hash($params['pwd']);

		$params['current_session_id'] = $this->security->create_key();

		$params['id'] = $this->insert($params);

		return $this->container->get_User($params);
	}
}