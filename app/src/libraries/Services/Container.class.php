<?php

require_once ROOT_PATH."src/libraries/Helpers/Security.class.php";
require_once ROOT_PATH."src/libraries/Helpers/FormKey.class.php";

require_once ROOT_PATH."src/libraries/Helpers/ImageHelper.class.php";

require_once ROOT_PATH."src/libraries/Helpers/Auth.class.php";

require_once ROOT_PATH."src/libraries/MVC_Classes/View.class.php";

require_once ROOT_PATH."src/MVC/models/UserCollection.class.php";
require_once ROOT_PATH."src/MVC/models/UserModel.class.php";

require_once ROOT_PATH."src/MVC/models/PictureCollection.class.php";
require_once ROOT_PATH."src/MVC/models/PictureModel.class.php";

require_once ROOT_PATH."src/MVC/models/LikeCollection.class.php";
require_once ROOT_PATH."src/MVC/models/LikeModel.class.php";

require_once ROOT_PATH."src/MVC/models/CommentCollection.class.php";
require_once ROOT_PATH."src/MVC/models/CommentModel.class.php";

require_once ROOT_PATH."src/libraries/Services/Mailer.class.php";

class Container
{
	private $pdo;
	private $security;
	private $auth;
	private $form_key;
	private $users;

	public function __construct()
	{	
		$this->set_pdo();
		$this->security = new Security($this);
		$this->auth = new Auth($this);
		$this->form_key = new FormKey($this);
		$this->users = new UserCollection($this);
	}

	private function set_pdo()
	{
		try
		{
			require_once ROOT_PATH."database/db_env.php";
			$options  = array(
				PDO::MYSQL_ATTR_FOUND_ROWS   => TRUE,
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			);
			$this->pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $options);
			$this->pdo->exec(file_get_contents(ROOT_PATH."database/db_init.sql"));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
	}

	public function get_pdo()
	{
		return $this->pdo;
	}

	public function get_security()
	{
		return $this->security;
	}

	public function get_auth()
	{
		return $this->auth;
	}

	public function get_form_key()
	{
		return $this->form_key;
	}

	public function get_users()
	{
		return $this->users;
	}



	public function get_ImageHelper()
	{
		return new ImageHelper();
	}

	public function get_Mailer()
	{
		return new Mailer();
	}


	public function get_View($template, $data)
	{
		return new View($template, $data);
	}

	public function get_User($params)
	{
		return new User($params, $this);
	}


	public function get_Picture($params)
	{
		return new Picture($params, $this);
	}

	public function get_PictureCollection()
	{
		return new PictureCollection($this);
	}

	public function get_Like($params)
	{
		return new Like($params, $this);
	}

	public function get_LikeCollection()
	{
		return new LikeCollection($this);
	}

	public function get_Comment($params)
	{
		return new Comment($params, $this);
	}

	public function get_CommentCollection()
	{
		return new CommentCollection($this);
	}
}