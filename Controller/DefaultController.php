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
        $errors = '';
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if($manager->userCheckLogin($_POST)){
                $manager->userLogin($_POST['username']);
                echo $this->redirect('profile');
            }else{
                $errors = "Veillez saisir un pseudo et un mot de passe valide";
            }
        }
        echo $this->renderView('home.html.twig',[
                                'user' => $user,
                                'bottlesRecycled' => $bottlesRecycled,
                                'errors' => $errors,
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

    public function partnerAction()
    {
        $manager = UserManager::getInstance();
        $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
        $allDeals = $manager->getAllDeals();
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
            echo $this->renderView('partner.html.twig',
                                    [
                                        'user' => $user,
                                        'allDeals' => $allDeals,
                                        'bottlesRecycled' => $bottlesRecycled,
                                    ]);
        }
        else{
            echo $this->renderView('partner.html.twig',
                                    [
                                        'allDeals' => $allDeals,
                                        'bottlesRecycled' => $bottlesRecycled,
                                    ]);
        }
    }
}
