<?php

require_once ROOT_PATH."src/libraries/Classes/Controller.class.php";
require_once ROOT_PATH."src/libraries/Classes/View.class.php";

class HomeController extends Controller
{
	public function index($params)
	{
		$user = "user param";
		$data = [
			'title' => 'Home',
		];
		new View("gallery.php", $data);
	}
}