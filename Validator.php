<?php

class UserValidation
{
    public function ValidLogin($form){
        return (isset($form['Username']) && isset($form['Password']));
    }

    public function ValidRegister($form){
        return isset($form['Username'], $form['Password'], $form['Email'], $form['Name']);
    }
}

?>