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




	# SETTERS
	public function set_title($value)
	{
		$this->title = $value;
	}
}