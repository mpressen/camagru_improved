<?php

require_once ROOT_PATH."src/libraries/Helpers/FormKey.class.php";

class Security
{
	private $container;

	public function __construct($container)
	{
		$this->container = $container;
	}

	public function validate($params)
	{
		$args = array(
			'frames' => array(),
			'base64data' => FILTER_SANITIZE_STRING,
			'form_key' => FILTER_SANITIZE_STRING,
			'confirmkey' => FILTER_SANITIZE_STRING,
			'login' => array(
				'filter'    => FILTER_VALIDATE_REGEXP,
				'options'   => array("regexp" => "/^(?=.*\w).{3,}$/")),
			'mail'  => FILTER_VALIDATE_EMAIL,
			'pwd'   => array(
				'filter'    => FILTER_VALIDATE_REGEXP,
				'options'   => array("regexp" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/")));
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

	public function check_csrf($redirect)
	{	
		$csrf = $this->container->get_FormKey($this);
		if (!($csrf->validate()))
		{	
			$_SESSION['message'] = "CSRF attack spotted.";
			header("Location: ".$redirect);
			exit;
		}
	}

	public function validate_inputs_format($params, $redirect)
	{	
		foreach ($params as $key => $param) {
			if (empty($param))
			{
				// slowing brute force
				sleep(1);
				
				$_SESSION['message'] = "Incorrect ".ucfirst($key)." field.";
				header("Location: ".$redirect);
				exit;
			}
		}
	}
}