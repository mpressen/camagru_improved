<?php

require_once($PATH."database/db_env.php");

try
{
	$PDO = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$PDO->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, true);
	$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$PDO->exec(file_get_contents($PATH."database/db_init.sql"));
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

?>