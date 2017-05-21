<?php

namespace Controller;

use Model\UserManager;

class DefaultController extends BaseController
{
    public function homeAction()
    {
        $manager = UserManager::getInstance();
        $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
        if(isset($_GET['action'])){
            $pageActuel = $_GET['action'];
        }else{
            $pageActuel = 'home';
        }
        $user = array();
        $errors = array();

        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        if(isset($_POST['submitLogin'])){
            if($manager->userCheckLogin($_POST)){
                $manager->userLogin($_POST['username']);
                echo $this->redirect('profile');
            }else{
                $errors[] = "Veillez saisir un pseudo et un mot de passe valide";
            }
        }
        if (isset($_POST['submitNewsletter'])) {
            $res = $manager->newsletterCheck($_POST['newsletter']);
            if($res['isFormGood']){
                $manager->addMail($_POST);
                $res = $manager->newslettersSend($res['data']);
                $email = $res['email'];
                $object = $res['object'];
                $content = $res['content'];
                $this->sendMail($email,$object,$content,'...');
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
        $pageActuel = $_GET['action'];
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
        if (isset($_POST['submitNewsletter'])) {
            $res = $manager->newsletterCheck($_POST['newsletter']);
            if($res['isFormGood']){
                $manager->addMail($_POST);
                $res = $manager->newslettersSend($res['data']);
                $email = $res['email'];
                $object = $res['object'];
                $content = $res['content'];
                $this->sendMail($email,$object,$content,'...');
            }
        }
        echo $this->renderView('offers.html.twig',
                                [
                                    'user' => $user,
                                    'allDeals' => $allDeals,
                                    'bottlesRecycled' => $bottlesRecycled,
                                    'pageActuel' => $pageActuel,
                                ]);
    }

    public function partnerAction()
    {
        $manager = UserManager::getInstance();
        $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
        $allDeals = $manager->getAllDeals();
        $errors = array();
        $pageActuel = $_GET['action'];
        $user = array();
        if(isset($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
            if (isset($_POST['sumbitPartner'])) {
                $res = $manager->checkPartner($_POST);
                if ($res['isFormGood']) {
                    //$manager->bePartner($res['data']);
                    $data = $res['data'];
                    $email = $data['email'];
                    $object = "Tritus - Devenir partenaire";
                    $content = "On a bien reçu votre message. On vous contactera des que votre demande sera analysée";
                    $infoUser = "Nom : " . $data['name'] . "<br>Email : " . $data['email'] . "<br>Ville : " . $data['city'] . "<br>Téléphone : " . $data['phone'] . "<br>Statut : " . $data['status'] . "<br>Message " . $data['message'];
                    $this->sendMail($email, $object, $content, '...');
                    $this->sendMailBis($object, $infoUser, $altContent = null);
                } else {
                    $errors = $res['errors'];
                }
            }
                if (isset($_POST['submitNewsletter'])) {
                    $res = $manager->newsletterCheck($_POST['newsletter']);
                    if($res['isFormGood']){
                        $manager->addMail($_POST);
                        $res = $manager->newslettersSend($res['data']);
                        $email = $res['email'];
                        $object = $res['object'];
                        $content = $res['content'];
                        $this->sendMail($email,$object,$content,'...');
                    }
                }
            
        echo $this->renderView('partner.html.twig',
                                [
                                    'user' => $user,
                                    'allDeals' => $allDeals,
                                    'bottlesRecycled' => $bottlesRecycled,
                                    'errors' => $errors,
                                    'pageActuel' => $pageActuel,
                                ]);
    }

    public function aboutAction(){

        $manager = UserManager::getInstance();
        $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
        $user = array();
        $pageActuel = $_GET['action'];
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        if (isset($_POST['submitNewsletter'])) {
            $res = $manager->newsletterCheck($_POST['newsletter']);
            if($res['isFormGood']){
                $manager->addMail($_POST);
                $res = $manager->newslettersSend($res['data']);
                $email = $res['email'];
                $object = $res['object'];
                $content = $res['content'];
                $this->sendMail($email,$object,$content,'...');
            }
        }
        if(!empty($_SESSION['user_id'])){
            echo $this->renderView('about.html.twig', ['isConnected' => true, 'bottlesRecycled' => $bottlesRecycled,
                'user' => $user,'pageActuel' => $pageActuel,]);
        }
        else{
            echo $this->renderView('about.html.twig', ['bottlesRecycled' => $bottlesRecycled,'pageActuel' => $pageActuel,]);
        }
    }
}
