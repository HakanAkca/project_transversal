<?php

class DefaultController
{
    public function homeAction(){
        echo "Welcome Home";
    }

    public function profilAction(){
        echo "Hey it's a profile page, coming soon";
    }

    public function inscriptionAction(){
        if(checkRegister)
        require('Views/inscription.php');
    }
}
