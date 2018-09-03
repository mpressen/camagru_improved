<?php

class Auth
{
    private $container;

    private $user;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function connect($user)
    {
        $user->set_current_session_id(session_id());
        $user->update('current_session_id', session_id());
        $this->user = $user;
    }

    public function disconnect()
    {
        $disconnect = $this->container->get_security()->create_key();
        $this->user->set_current_session_id($disconnect);
        $this->user->update('current_session_id', $disconnect);
        unset($this->user);
    }

    public function being_auth($bool)
    {  
        $this->user = $this->container->get_UserCollection()->find('current_session_id', session_id());
        if (!$this->user && $bool === true)
        {
            $_SESSION['message'] = 'You must be connected.';
            header("Location: /user/signin");
            exit;
        }
        else if ($this->user && $bool === false)
        {
            $_SESSION['message'] = 'You are already logged in.';
            header("Location: /");
            exit;
        }
        return $this->user;
    }

}