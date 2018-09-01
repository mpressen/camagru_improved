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
		return $this->insert($params);
	}

	public function user_pictures($user_id)
	{
		return $this->find_all('user_id', $user_id, 'DESC');
	}
}