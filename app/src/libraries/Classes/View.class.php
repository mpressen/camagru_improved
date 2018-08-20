<?php

class View
{
	private $path;

	public function __construct($template, $data, $auth)
	{
		$this->path = ROOT_PATH."src/MVC/views/";
		require_once($this->path."layout.php");
	}
}