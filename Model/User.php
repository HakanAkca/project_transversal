<?php

class User
{
    private $pseudo;
    private $email;
    private $pass;
    private $city;

    public function __construct($pseudo, $email, $pass, $city)
    {
        $this->setPseudo($pseudo);
        $this->setEmail($email);
        $this->setPass($pass);
        $this->setCity($city);
    }

    public function getEmail(){
        return $this -> email;
    }
    
    public function setEmail($email){
        $this->email = $email;
    }

    public function getPass(){
        return $this->pass;
    }
    
    public function setPass($pass){
        $this->pass = $pass;
    }

    public function getPseudo(){
        return $this->pseudo;
    }
    
    public function setPseudo($pseudo){
        $this->pseudo = $pseudo;
    }

    public function getCity(){
        return $this->city;
    }
    
    public function setCity($city){
        $this->city = $city;
    }
}