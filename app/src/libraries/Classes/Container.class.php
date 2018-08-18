<?php

require_once ROOT_PATH."src/libraries/Helpers/Security.class.php";
require_once ROOT_PATH."src/libraries/Helpers/FormKey.class.php";

require_once ROOT_PATH."src/libraries/Classes/View.class.php";

require_once ROOT_PATH."src/MVC/models/UserCollection.class.php";
require_once ROOT_PATH."src/MVC/models/UserModel.class.php";

require_once ROOT_PATH."src/libraries/Services/Mailer.class.php";

class Container
{
	private $PDO;
	private $SECURITY;

	public function __construct()
	{	
		$this->SECURITY = new Security();
	}

	public function set_PDO()
	{
		try
		{
			require_once ROOT_PATH."database/db_env.php";
			$options  = array(
				PDO::MYSQL_ATTR_FOUND_ROWS   => TRUE,
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			);
			$this->PDO = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $options);
			$this->PDO->exec(file_get_contents(ROOT_PATH."database/db_init.sql"));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
	}

	public function get_PDO()
	{
		return $this->PDO;
	}

	public function get_SECURITY()
	{
		return $this->SECURITY;
	}

	public function get_FormKey()
	{
		return new FormKey($this->SECURITY);
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
		if (!isset($this->PDO))
			$this->set_PDO();
		return new User($params, $this->PDO, $this->SECURITY);
	}

	public function get_UserCollection()
	{
		if (!isset($this->PDO))
			$this->set_PDO();
		return new UserCollection($this->PDO, $this->SECURITY);
	}
}