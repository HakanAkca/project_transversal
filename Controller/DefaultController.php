<?php

namespace Controller;

use Model\UserManager;

class DefaultController extends BaseController
{
    public function homeAction()
    {
        $manager = UserManager::getInstance();
        $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
        $pageActuel = $_GET['action'];
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
                                'pageActuel' => $pageActuel
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
        if(isset($_POST['submitBuyDeal'])){
            if($manager->chechBuyDeal($_POST['IDdeal'])){
                $manager->buyDeal($_POST['IDdeal']);
                header('Location:?action=offers');
                $manager->getAvailableUserDeals();
            }
        }
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
        $errors = array();
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $res = $manager->checkPartner($_POST);
                if ($res['isFormGood']) {
                    $manager->bePartner($res['data']);
                } else {
                    $errors = $res['errors'];
                }
            }
            echo $this->renderView('partner.html.twig',
                                    [
                                        'user' => $user,
                                        'allDeals' => $allDeals,
                                        'bottlesRecycled' => $bottlesRecycled,
                                        'errors' => $errors,
                                    ]);
        }
        else{
            $message = "Connecter vous avant tout";
            echo $this->renderView('partner.html.twig',
                                    [
                                        'message' => $message,
                                        
                                    ]);
        }
    }

    public function aboutAction(){
        echo $this->renderView('about.html.twig');
    }
}
