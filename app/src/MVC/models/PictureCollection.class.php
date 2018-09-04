<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";

class PictureCollection extends Model
{
	public function __construct($container)
	{
		parent::__construct($container);
	}

	public function new($params)
	{
		return $this->insert($params);
	}

	public function user_pictures($user_id)
	{
		return $this->find_all('user_id', $user_id, 'DESC');
	}

	public function all_pictures($limit)
	{
		return $this->find_all('1', '1', 'DESC', 'id', $limit);
	}

	public function all_pictures_before($start, $limit)
	{
		return $this->find_all_before('id', $start, 'DESC', 'id', $limit);
	}
}