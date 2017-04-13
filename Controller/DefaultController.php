<?php

namespace Controller;

use Model\UserManager;

class DefaultController extends BaseController
{
    public function homeAction(){
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            
            echo $this->renderView('home.html.twig',
                                   ['name' => $user['username']]);
        }
        else
            $this->redirect('login');
    }

    public function aboutAction()
    {
        echo $this->renderView('about.html.twig');
    }

    public function partnerAction()
    {
        if(empty($_SESSION['user_id'])){
            $user = '';
        }
        else{
            $user = $_SESSION['user_id'];
        }
        echo $this->renderView('partner.html.twig', 
                                ['log' => $user]);
    }
}
