<?php

class View
{
	public function __construct($template, $data)
	{
		$path = getenv('PROJECT_ROOT')."src/MVC/views/";
		require_once($path."layout.php");
	}
}