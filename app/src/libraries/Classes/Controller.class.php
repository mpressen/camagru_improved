<?php

class Controller
{
	private $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function get_container()
	{
		return $this->container;
	}
}