<?php

namespace Controller;

use Model\UserManager;

class DefaultController extends BaseController
{
    public function homeAction()
    {
        $manager = UserManager::getInstance();
        $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
        $user = array();
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if($manager->userCheckLogin($_POST)){
                $manager->userLogin($_POST['username']);
                echo $this->redirect('profile');
            }
        }
        echo $this->renderView('home.html.twig',[
                                'user' => $user,
                                'bottlesRecycled' => $bottlesRecycled,
                                ]);
    }
    public function offersAction(){
        $manager = UserManager::getInstance();
        $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
        $user = array();
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        $allDeals = $manager->getAllDeals();
        echo $this->renderView('offers.html.twig',
                                [
                                    'user' => $user,
                                    'allDeals' => $allDeals,
                                    'bottlesRecycled' => $bottlesRecycled,
                                ]);
    }

    public function aboutAction()
    {

        echo $this->renderView('about.html.twig');
    }

    public function partnerAction()
    {
        echo $this->renderView('partner.html.twig');
    }
}
