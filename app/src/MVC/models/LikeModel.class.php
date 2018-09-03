<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";

class Like extends Model
{
	private $id;
	private $user_id;
	private $picture_id;

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

	public function get_picture_id()
	{
		return $this->picture_id;
	}
}