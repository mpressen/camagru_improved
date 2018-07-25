<?php
session_start();

$PATH = getenv('PROJECT_ROOT');

require_once($PATH."src/config/setup.php");
require_once($PATH."src/config/router.php");
?>