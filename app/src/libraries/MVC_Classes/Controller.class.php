<?php

abstract class Controller
{
	protected $container;
	protected $auth;
	protected $security;
	protected $form_key;
	protected $users;
	protected $image_helper;

	public function __construct($container)
	{
		$this->container = $container;
		$this->auth = $container->get_auth();
		$this->security = $container->get_security();
		$this->form_key = $container->get_form_key();
		$this->users = $container->get_users();
		$this->image_helper = $container->get_ImageHelper();
	}

	protected function ajax($class, $params, $route)
	{
		//ajax validation
		$user     = $this->auth->being_auth(true, true);
		$csrf     = $this->security->check_csrf('osef', true);
		$response = ['key' => $this->form_key->get_key()];

		if ($this->security->ajax_secure_and_display($params, $user, $csrf, $response))
			exit;

		$class->$route($params, $response, $user);
	}
}