<?php

abstract class Controller
{
	protected $container;
	protected $auth;
	protected $security;
	protected $form_key;
	protected $users;

	public function __construct($container)
	{
		$this->container = $container;
		$this->auth = $container->get_auth();
		$this->security = $container->get_security();
		$this->form_key = $container->get_form_key();
		$this->users = $container->get_users();
	}
}