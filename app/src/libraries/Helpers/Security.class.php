<?php

require_once ROOT_PATH."src/libraries/Helpers/FormKey.class.php";

class Security
{
	private $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function get_FormKey()
	{
		return $this->form_key;
	}
	
	public function validate($params)
	{
		$args = array(
			'comment' => array(
				'filter'    => FILTER_VALIDATE_REGEXP,
				'options'   => array("regexp" => "/([0-9]|\w)+.{0,500}/")),
			'user_id' => FILTER_SANITIZE_NUMBER_INT,
			'picture_id' => FILTER_SANITIZE_NUMBER_INT,
			'like_id' => FILTER_SANITIZE_NUMBER_INT,
			'frames' => array(),
			'base64data' => FILTER_SANITIZE_STRING,
			'form_key' => FILTER_SANITIZE_STRING,
			'confirmkey' => FILTER_SANITIZE_STRING,
			'login' => array(
				'filter'    => FILTER_VALIDATE_REGEXP,
				'options'   => array("regexp" => "/^(?=.*\w).{3,50}$/")),
			'mail'  => FILTER_VALIDATE_EMAIL,
			'pwd'   => array(
				'filter'    => FILTER_VALIDATE_REGEXP,
				'options'   => array("regexp" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,255}$/")));
		
		return filter_input_array($params, $args, false);
	}

	public function create_key()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$uniqid = uniqid(mt_rand(), true);

		return ($this->my_hash($ip.$uniqid));
	}

	public function my_hash($str)
	{	
		return hash('whirlpool', $str);
	}

	public function check_csrf($redirect, $ajax = false)
	{	
		if (!($this->container->get_form_key()->validate()))
		{	
			if ($ajax)
				return 1;
			$_SESSION['message'] = "CSRF attack spotted.";
			header("Location: ".$redirect);
			exit;
		}
	}

	public function validate_inputs_format($params, $redirect, $ajax = false)
	{	
		foreach ($params as $key => $param) {
			if (empty($param))
			{
				// slowing brute force
				sleep(1);
				if ($ajax)
					return 1;
				$_SESSION['message'] = "Incorrect ".ucfirst($key)." field.";
				header("Location: ".$redirect);
				exit;
			}
		}
	}

	public function ajax_secure_and_display($user, $no_validation, $no_csrf, $form_key)
	{
		if (!$user) 
		{
			echo json_encode(['message' => 'log', 'csrf' => $form_key]);
			return 1;
		}
		else if ($no_validation) 
		{
			echo json_encode(['message' => 'validation', 'csrf' => $form_key]);
			return 1;
		}
		else if ($no_csrf) 
		{
			echo json_encode(['message' => 'csrf', 'csrf' => $form_key]);
			return 1;
		}
	}
}