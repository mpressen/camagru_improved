<?php

require_once ROOT_PATH."src/libraries/Helpers/Security.class.php";

class FormKey
{
    private $form_key;
    private $old_form_key;
    
    public function __construct()
    {
        if (isset($_SESSION['form_key']))
            $this->old_form_key = $_SESSION['form_key'];
        $this->form_key = $this->generateKey();
        $_SESSION['form_key'] = $this->form_key;
    }

    private function generateKey()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $uniqid = uniqid(mt_rand(), true);

        $security = new Security();
        return ($security->my_hash($ip.$uniqid));
    }

    public function outputKey()
    {
        return "<input type='hidden' name='form_key' id='form_key' value='".$this->form_key."'/>";
    }

    public function validate()
    {
        if($_POST['form_key'] == $this->old_form_key)
            return true;
        return false;
    }

    public function get_form_key()
    {
        return $this->form_key;
    }
}