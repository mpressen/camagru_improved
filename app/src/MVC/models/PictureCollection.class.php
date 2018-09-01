<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";

class PictureCollection extends Model
{
	public function __construct($pdo, $security, $container)
	{
		parent::__construct($pdo, $security, $container);
	}

	public function new($params)
	{
		parent::insert($params);

		//return $this->container->getPicture($params);
	}

	public function user_pictures($user_id)
	{
		return parent::find_all('user_id', $user_id);
	}
}