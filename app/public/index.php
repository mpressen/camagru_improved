<?php
session_start();

define('ROOT_PATH', getenv('PROJECT_ROOT'));

require_once ROOT_PATH."src/libraries/Classes/Router.class.php";

new Router();
?>