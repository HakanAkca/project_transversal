<?php

class User
{
    private $email;
    private $pass;

    public function __construct($email, $pass)
    {
        $this->setEmail($email);
        $this->setPass($pass);
        $this->setFirstName('');
        $this->setLastName('');
    }

    public function getEmail()
    {
        return $this -> email;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPass()
    {
        return $this->pass;
    }
    
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    public function getFirstName()
    {
        return $this->firstname;
    }
    
    public function setFirstName($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastName()
    {
        return $this->lastname;
    }
    
    public function setLastName($lastname)
    {
        $this->lastname = $lastname;
    }
}