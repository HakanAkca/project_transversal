<?php

require('Model/User.php');
require('Model/Verif.php');
require('Model/Database.php');

class DefaultController
{
    public function homeAction(){
        echo "Welcome Home";
    }

    public function profilAction(){
        echo "Hey it's a profile page, coming soon";
    }

    public function inscriptionAction(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $testInscription = new UserManager();
            if($testInscription->userCheckRegister($_POST) === true){
                $testInscription->userRegister($_POST);
            }
            else{
                print($testInscription->userCheckRegister($_POST));
            }
        }
        require('Views/inscription.php');
    }
}
