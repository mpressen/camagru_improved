<?php

require_once ROOT_PATH."src/libraries/Classes/Controller.class.php";

class PictureController extends Controller
{
	public function __construct($container)
	{
		parent::__construct($container);
	}
	
	public function workshop($params)
	{
		$user_id = $this->container->get_auth()->being_auth(true);
		$user = $this->container->get_UserCollection()->find('id', $user_id);
		
		$data = [
			'title' => 'Workshop',
			'user' => $user
		];
		$this->container->get_View("workshop.php", $data);
	}

	public function save($params)
	{
		// $params -> base64encode(str) and frames(array of objects)
		$user_id = $this->container->get_auth()->being_auth(true);
		$user = $this->container->get_UserCollection()->find('id', $user_id);
		echo $this->merge_image($params);
		// echo json_encode($params);
	}

	private function merge_image($params)
	{
		$frames = json_decode($params['frames'], true);
		$filteredData = str_replace(" ", "+", $params['base64data']);
		$filteredData = substr($filteredData, strpos($filteredData, ",") + 1);
		$unencodedData = base64_decode($filteredData);
		$destination = imagecreatefromstring($unencodedData);

		$largeur_destination = imagesx($destination);
		$hauteur_destination = imagesy($destination);

		$source = imagecreatefrompng(ROOT_PATH."public/images/".$frames[0]['name'].".png");
		$largeur_source = imagesx($source);
		$hauteur_source = imagesy($source);
		$destination_x = $frames[0]['left'];
		$destination_y = $frames[0]['top'];

		imagealphablending($source, true);
		imagesavealpha($source, true);
		imagecopy($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source);
		ob_start();
		imagepng($destination, NULL);
		$contents = ob_get_contents();
		ob_end_clean();
		imagedestroy($destination);
		return base64_encode($contents);
	}
}