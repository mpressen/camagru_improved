<?php

class Model
{
	private $PDO;

	public function __construct()
	{
		if (!isset($this->PDO))
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
	}

	public function insert($params)
	{
		$class = strtolower(str_replace('Collection', '', get_class($this)))."s";

		$table_columns = "(";
		$table_bindings = "(";
		foreach(array_keys($params) as $key)
		{
			$table_columns .= $key.", ";
			$table_bindings .= ":".$key.", "; 
		}
		$table_columns = substr($table_columns, 0, -2).")";
		$table_bindings = substr($table_bindings, 0, -2).")";
		
		$prep = $this->PDO->prepare('INSERT INTO '.$class.$table_columns.' VALUES'.$table_bindings)->execute($params);
	}

	public function find($field, $value)
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		$prep = $this->PDO->prepare('SELECT * FROM '.$table.' WHERE '.$field.' = ?');
		$prep->execute(array($value));
		$ret = $prep->fetch();
		if ($ret)
			$ret = new $class($ret);
		return $ret;
	}
}
?>