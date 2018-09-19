<?php
session_start();

define('ROOT_PATH', getenv('PROJECT_ROOT'));


require_once ROOT_PATH."src/libraries/Services/Router.class.php";

new Router();
