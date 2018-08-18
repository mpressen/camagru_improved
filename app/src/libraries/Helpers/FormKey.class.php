<?php

class FormKey
{
    private $form_key;
    
    public function __construct($SECURITY)
    {
        if (!isset($_SESSION['form_key']))
            $_SESSION['form_key'] = $SECURITY->create_key();
        $this->form_key = $_SESSION['form_key'];
    }

    public function outputKey()
    {
        return "<input type='hidden' name='form_key' id='form_key' value='".$this->form_key."'/>";
    }

    public function validate()
    {
        $this->_destroy_key();
        if($_POST['form_key'] == $this->form_key)
            return true;
        return false;
    }

    private function _destroy_key()
    {
        unset($_SESSION['form_key']);
    }

}