<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";

require_once ROOT_PATH."src/MVC/models/UserModel.class.php";

require_once ROOT_PATH."src/libraries/Helpers/Security.class.php";

class UserCollection extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function new($params)
	{
		$security = new Security();

		$params['confirmkey'] = $security->create_key();

		$params['pwd'] = $security->my_hash($params['pwd']);

		parent::insert($params);

		return $params;
	}
}