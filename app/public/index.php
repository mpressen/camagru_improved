<?php
session_start();

define('ROOT_PATH', getenv('PROJECT_ROOT'));

require_once ROOT_PATH."src/libraries/Classes/Container.class.php";
require_once ROOT_PATH."src/libraries/Classes/Router.class.php";

$container = new Container();
new Router($container);