<?php

class Auth
{
    private $container;
    private $security;

    private $auth_user;

    public function __construct($container)
    {
        $this->container = $container;
        $this->security = $container->get_security();
    }

    public function connect($user)
    {
        $user->set_current_session_id(session_id());
        $user->update('current_session_id', session_id());
        $this->auth_user = $user;
    }

    public function disconnect()
    {
        $disconnect = $this->security->create_key();
        $this->auth_user->set_current_session_id($disconnect);
        $this->auth_user->update('current_session_id', $disconnect);
        unset($this->auth_user);
    }

    public function being_auth($bool, $ajax = false)
    {  
        $this->auth_user = $this->container->get_users()->find('current_session_id', session_id());
        if ($ajax === true && !$this->auth_user)
            return NULL;
        else if (!$this->auth_user && $bool === true)
        {
            $_SESSION['message'] = 'You must be connected.';
            header("Location: /user/signin");
            exit;
        }
        else if ($this->auth_user && $bool === false)
        {
            $_SESSION['message'] = 'You are already logged in.';
            header("Location: /");
            exit;
        }
        return $this->auth_user;
    }

}