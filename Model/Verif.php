<?php

class Verif {

    public function checkValidRegister(){
        if(empty($_POST['pseudo'])){
            return "Missing Username";
        }
        if(empty($_POST['email'])){
            return "Missing Email";
        }
        if(empty($_POST['password'])){
            return "Missing Password";
        }
        if(empty($_POST['verifpassword'])){
            return "Missing the Password Verification";
        }
        if(empty($_POST['city'])){
            return "Missing City";
        }
        if($_POST['password'] !== $_POST['verifpassword']){
            return "Passwords don't match";
        }
        return true;
        // Check if User already exist ( mail / pseudo )
    }

}