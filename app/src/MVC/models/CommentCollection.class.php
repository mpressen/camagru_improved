<?php

require_once ROOT_PATH."src/libraries/Classes/Model.class.php";

class CommentCollection extends Model
{
	public function __construct($container)
	{
		parent::__construct($container);
	}

	public function new($params)
	{
		return $this->insert($params);
	}

	public function picture_comments_with_user_infos($picture_id)
	{
		return $this->find_all_with_join('user_id', 'users', 'picture_id', $picture_id, 'DESC');
	}
}