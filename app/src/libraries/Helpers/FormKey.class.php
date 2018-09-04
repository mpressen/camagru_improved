<?php

class FormKey
{   
    private $container;
    private $security;
    private $key;
    
    public function __construct($container)
    {   
        $this->container = $container;
        $this->security = $container->get_security();
        if (!isset($_SESSION['form_key']))
            $_SESSION['form_key'] = $this->security->create_key();
        $this->key = $_SESSION['form_key'];
    }

    public function get_key()
    {
        return $this->key;
    }

    public function outputKey()
    {
        return "<input type='hidden' name='form_key' id='form_key' value='".$this->key."'/>";
    }


    public function validate()
    {
        $check_key = $this->key;
        $this->_reset_key();
        if($_POST['form_key'] == $check_key)
            return true;
        return false;
    }

    private function _reset_key()
    {
        $_SESSION['form_key'] = $this->security->create_key();
        $this->key = $_SESSION['form_key'];
    }

}