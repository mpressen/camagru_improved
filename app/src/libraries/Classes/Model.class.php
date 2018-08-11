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
				$this->PDO = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
				$this->PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$this->PDO->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, true);
				$this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
		$class = strtolower(str_replace('Collection', '', get_class($this)))."s";
		$prep = $this->PDO->prepare('SELECT '.$field.' FROM '.$class.' WHERE '.$field.' = ?');
		$prep->execute(array($value));
		$ret = $prep->fetch()[0];
		return $ret;
	}
}
?>