<?php

class Model
{
	protected $pdo;
	protected $security;
	protected $container;

	public function __construct($pdo, $security, $container)
	{
		$this->pdo = $pdo;
		$this->security = $security;
		$this->container = $container;
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
		$prep = $this->pdo->prepare('INSERT INTO '.$class.$table_columns.' VALUES'.$table_bindings);
		try
		{
			$this->pdo->beginTransaction();
			$prep->execute($params);
			$id = $this->pdo->lastInsertId();
			$this->pdo->commit();
			return $id;
		}
		catch(PDOExecption $e) {
			$this->pdo->rollback();
			throw new Exception($e->getMessage());
		}
	}

	public function update($attribute)
	{
		$class = strtolower(str_replace('Collection', '', get_class($this)))."s";
		$getter = "get_".$attribute;
		$prep = $this->pdo->prepare('UPDATE '.$class.' SET '.$attribute.' = ? WHERE id = ?');
		try
		{
			$this->pdo->beginTransaction();
			$prep->execute(array($this->$getter(), $this->get_id()));
			$this->pdo->commit();
		}
		catch(PDOExecption $e)
		{
			$this->pdo->rollback();
			throw new Exception($e->getMessage());
		}
	}

	public function find($field, $value, $order = 'ASC', $key = 'id')
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		$prep = $this->pdo->prepare('SELECT * FROM '.$table.' WHERE '.$field.' = ? ORDER BY '.$key." ".$order);
		$prep->execute(array($value));
		$ret = $prep->fetch();
		$maker = "get_".$class;
		if ($ret)
			$ret = $this->container->$maker($ret);
		return $ret;
	}

	public function find_all($field, $value, $order = 'ASC', $key = 'id')
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		$prep = $this->pdo->prepare('SELECT * FROM '.$table.' WHERE '.$field.' = ? ORDER BY '.$key." ".$order);
		$prep->execute(array($value));
		$ret = $prep->fetchAll();
		$maker = "get_".$class;
		$list = [];
		foreach($ret as $object)
			array_push($list, $this->container->$maker($object));
		return $list;
	}

	public function delete()
	{
		$table = strtolower(get_class($this))."s";
		$prep = $this->pdo->prepare('DELETE FROM '.$table.' WHERE id = ?');
		try
		{
			$this->pdo->beginTransaction();
			$prep->execute(array($this->get_id()));
			$this->pdo->commit();
		}
		catch(PDOExecption $e)
		{
			$this->pdo->rollback();
			throw new Exception($e->getMessage());
		}
	}
}
?>