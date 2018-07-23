<?php
session_start();
require_once("database.php");
try
{
	$DB = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
	$DB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$DB->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, true);
	$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$DB->exec(file_get_contents("init_db.sql", FILE_USE_INCLUDE_PATH));
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}
$login = (isset($_SESSION['loggued_on_user'])) ? $_SESSION['loggued_on_user'] : NULL;
?>