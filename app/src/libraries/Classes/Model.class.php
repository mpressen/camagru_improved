<?php

class Model
{
	protected $PDO;
	protected $SECURITY;

	public function __construct($PDO, $SECURITY)
	{
		$this->PDO = $PDO;
		$this->SECURITY = $SECURITY;
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

	public function update($attribute)
	{
		$class = strtolower(str_replace('Collection', '', get_class($this)))."s";
		$getter = "get_".$attribute;
		$update = $attribute."=".$this->$getter();
		$prep = $this->PDO->prepare('UPDATE '.$class.' SET '.$update.' WHERE id='.$this->get_id())->execute($params);
	}

	public function find($field, $value)
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		$prep = $this->PDO->prepare('SELECT * FROM '.$table.' WHERE '.$field.' = ?');
		$prep->execute(array($value));
		$ret = $prep->fetch();
		if ($ret)
			$ret = new $class($ret, $this->PDO, $this->SECURITY);
		return $ret;
	}
}
?>