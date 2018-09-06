<?php

abstract class Model
{
	protected $container;
	protected $pdo;
	protected $security;

	public function __construct($container)
	{
		$this->container = $container;
		$this->pdo = $container->get_pdo();
		$this->security = $container->get_security();
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
		$prep = $this->pdo->prepare('
			INSERT INTO '.$class.$table_columns.' 
			VALUES'.$table_bindings);
		try
		{
			$this->pdo->beginTransaction();
			$prep->execute($params);
			$id = $this->pdo->lastInsertId();
			$this->pdo->commit();
			return $id;
		}
		catch(PDOExecption $e)
		{
			$this->pdo->rollback();
		}
	}

	public function update($attribute)
	{
		$class = strtolower(get_class($this))."s";
		$getter = "get_".$attribute;
		$prep = $this->pdo->prepare('
			UPDATE '.$class.' 
			SET '.$attribute.' = ? 
			WHERE id = ?');
		try
		{
			$this->pdo->beginTransaction();
			$prep->execute(array($this->$getter(), $this->get_id()));
			$this->pdo->commit();
		}
		catch(PDOExecption $e)
		{
			$this->pdo->rollback();
		}
	}

	public function find($field, $value, $order = 'ASC', $key = 'id')
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		$prep = $this->pdo->prepare('
			SELECT * 
			FROM '.$table.' 
			WHERE '.$field.' = ? 
			ORDER BY '.$key." ".$order);
		$prep->execute(array($value));
		$ret = $prep->fetch();
		$maker = "get_".$class;
		if ($ret)
			$ret = $this->container->$maker($ret);
		return $ret;
	}

	public function find_cross($field, $value, $field2, $value2)
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		$prep = $this->pdo->prepare('
			SELECT * 
			FROM '.$table.' 
			WHERE '.$field.' = ? 
			AND '.$field2.' = ?');
		$prep->execute(array($value, $value2));
		$ret = $prep->fetch();
		$maker = "get_".$class;
		if ($ret)
			$ret = $this->container->$maker($ret);
		return $ret;
	}

	public function find_all($field, $value, $order = 'ASC', $key = 'id', $limit = false)
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		if ($limit)
		{
			$prep = $this->pdo->prepare('
				SELECT * 
				FROM '.$table.' 
				WHERE '.$field.' = ? 
				ORDER BY '.$key." ".$order." 
				LIMIT ".$limit);
		}
		else
			$prep = $this->pdo->prepare('
				SELECT * 
				FROM '.$table.' 
				WHERE '.$field.' = ? 
				ORDER BY '.$key." ".$order);
		$prep->execute(array($value));
		$ret = $prep->fetchAll();
		$maker = "get_".$class;
		$list = [];
		foreach($ret as $object)
			array_push($list, $this->container->$maker($object));
		return $list;
	}

	public function find_all_with_join($join_field, $join_table, $field, $value, $order = 'ASC', $key = 'id')
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		$prep = $this->pdo->prepare("
			SELECT * 
			FROM ".$table." 
			LEFT JOIN ".$join_table." 
			ON ".$table.".".$join_field." = ".$join_table.".id 
			WHERE ".$table.".".$field." = ?"); 
			// ORDER BY ".$table.".".$key." ".$order);
		$prep->execute(array($value));
		$ret = $prep->fetchAll();
		return $ret;
	}

	public function find_all_before($field, $start, $order = 'ASC', $key = 'id', $limit = false)
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		if ($limit)
		{
			$prep = $this->pdo->prepare('
				SELECT * 
				FROM '.$table.' 
				WHERE '.$field.' < ? 
				ORDER BY '.$key." ".$order." 
				LIMIT ".$limit);
		}
		else
			$prep = $this->pdo->prepare('
				SELECT * 
				FROM '.$table.' 
				WHERE '.$field.' = ? 
				ORDER BY '.$key." ".$order);
		$prep->execute(array($start));
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
		$prep = $this->pdo->prepare('
			DELETE FROM '.$table.' 
			WHERE id = ?');
		try
		{
			$this->pdo->beginTransaction();
			$prep->execute(array($this->get_id()));
			$this->pdo->commit();
		}
		catch(PDOExecption $e)
		{
			$this->pdo->rollback();
		}
	}

	public function count($field, $value)
	{
		$class = str_replace('Collection', '', get_class($this));
		$table = strtolower($class)."s";
		$prep = $this->pdo->prepare('
			SELECT COUNT(*) 
			AS COUNT 
			FROM '.$table.' 
			WHERE '.$field.' = ?');
		$prep->execute(array(intval($value)));
		return $prep->fetch()['COUNT'];
	}
}
?>