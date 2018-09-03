<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";

class LikeCollection extends Model
{
	public function __construct($pdo, $security, $container)
	{
		parent::__construct($pdo, $security, $container);
	}

	public function new($params)
	{
		return $this->insert($params);
	}

	public function picture_likes($picture_id)
	{
		return $this->count('picture_id', $picture_id);
	}

	public function picture_liked_by_auth_user($picture_id, $user_id)
	{
		return $this->find_cross('picture_id', $picture_id, 'user_id', $user_id);
	}
}