<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";

class Picture extends Model
{
	private $id;
	private $user_id;
	private $path;

	public function __construct($params, $pdo, $security, $container)
	{
		parent::__construct($pdo, $security, $container);
		foreach ($params as $key => $param) {
			$this->$key = $param;
		}
	}


	# GETTERS
	public function get_id()
	{
		return $this->id;
	}

	public function get_user_id()
	{
		return $this->user_id;
	}

	public function get_path()
	{
		return $this->path;
	}

	public function get_likes()
	{
		return $this->container->get_LikeCollection()->picture_likes($this->get_id());
	}

	public function is_liked_by_auth_user($user_id)
	{
		return $this->container->get_LikeCollection()->picture_liked_by_auth_user($this->get_id(), $user_id);
	}
}