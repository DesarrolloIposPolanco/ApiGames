<?php

class SQLProcedures
{
    public function __construct(){ }

    public function Login($Username, $Password){
        return "CALL `Login`('".$Username."', '".$Password."')";
    }

    public function Register($Username, $Password, $Name, $Email){
        return "CALL `RegisterUser`('".$Username."', '".$Password."', '".$Name."', '".$Email."')";
    }

    public function ValidateUsername($Username){
        return "CALL `ValidUsername`('".$Username."')";
    }

    public function GetProductos($Empresa){
        return "CALL `GetProductosEmpresa`('".$Empresa."')";
    }
}

?>