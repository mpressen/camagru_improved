<?php

require_once($PATH."src/libraries/Classes/Controller.class.php");

class HomeController extends Controller
{
	public function index()
	{
		echo "<h1>HOME index</h1>";
	}

	public function not_found()
	{
		echo "<h1>not found</h1>";
	}
}
?>