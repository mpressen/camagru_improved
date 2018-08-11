<?php

require_once ROOT_PATH."src/libraries/Helpers/FormKey.class.php";

class Security
{
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
		$csrf = new FormKey();
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