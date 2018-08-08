<?php

class Security
{
	public function create_confirmkey()
	{
		$key = "";
		for ($i = 0; $i < 15; $i++)
			$key .= mt_rand(0,9);
		return $key;
	}

	public function my_hash($str)
	{	
		return hash('whirlpool', $str);
	}
}