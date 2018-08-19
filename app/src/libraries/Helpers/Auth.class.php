<?php

class Auth
{
    private $user_id;

    public function __construct()
    {
        if (isset($_SESSION['user_id']))
            $this->user_id = $_SESSION['user_id'];
    }
    public function connect($id)
    {
        $_SESSION['user_id'] = $id;
    }

    public function disconnect()
    {
        unset($_SESSION['user_id']);
    }

    public function get_user_id()
    {
        return $this->user_id;
    }

    public function is_connected()
    {
        if (isset($this->user_id))
            return true;
        return false;
    }


    public function being_auth($bool)
    {
        if (!isset($this->user_id) && $bool === true)
        {
            $_SESSION['message'] = 'You must be connected.';
            header("Location: /user/signin");
            exit;
        }
        else if (isset($this->user_id) && $bool === false)
        {
            $_SESSION['message'] = 'You are already logged in.';
            header("Location: /");
            exit;
        }
        return $this->user_id;
    }

}