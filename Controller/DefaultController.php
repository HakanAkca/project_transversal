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
        $admin = false;
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
            if($_SESSION['user_id'] == '1'){
                $admin = true;
            }
        }
        if(isset($_POST['submitLogin'])){
            if($manager->userCheckLogin($_POST)){
                $manager->userLogin($_POST['username']);
                echo $this->redirect('profile');
            }else{
                $errors[] = "Veillez saisir un pseudo et un mot de passe valide";
            }
        }
        $newsRegister = "";

        if (isset($_POST['submitNewsletter'])) {
            $res = $manager->newsletterCheck($_POST['newsletter']);
            if($res['isFormGood']){
                $manager->addMail($_POST);
                $res = $manager->newslettersSend($res['data']);
                $email = $res['email'];
                $object = $res['object'];
                $content = $res['content'];
                $this->sendMail($email,$object,$content,'...');
                $newsRegister = "Merci de vous etre abonnés a la NewsLetter, nous vous avons envoyé un email afin de vérifier votre adresse !";

            }
        }
        echo $this->renderView('home.html.twig',[
                                'user' => $user,
                                'bottlesRecycled' => $bottlesRecycled,
                                'errors' => $errors,
                                'pageActuel' => $pageActuel,
                                'newsRegister' => $newsRegister,
                                'admin' => $admin,
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
                echo $this->redirect('profile');
            }
        }
        $newsRegister = "";

        if (isset($_POST['submitNewsletter'])) {
            $res = $manager->newsletterCheck($_POST['newsletter']);
            if($res['isFormGood']){
                $manager->addMail($_POST);
                $res = $manager->newslettersSend($res['data']);
                $email = $res['email'];
                $object = $res['object'];
                $content = $res['content'];
                $this->sendMail($email,$object,$content,'...');
                $newsRegister = "Merci de vous etre abonnés a la NewsLetter, nous vous avons envoyé un email afin de vérifier votre adresse !";

            }
        }
        echo $this->renderView('offers.html.twig',
                                [
                                    'user' => $user,
                                    'allDeals' => $allDeals,
                                    'bottlesRecycled' => $bottlesRecycled,
                                    'pageActuel' => $pageActuel,
                                    'newsRegister' => $newsRegister,
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
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        if (isset($_POST['sumbitPartner'])) {
            $res = $manager->checkPartner($_POST);
            if ($res['isFormGood']) {
                //$manager->bePartner($res['data']);
                $data = $res['data'];
                $email = $data['email'];
                $object = "Tritus - Devenir partenaire";
                $content = "Bonjour ".$data['name']."<br>

Nous avons recu votre demande et sommes acutellement en train de l'analyser, <br>

Merci de l'attention que vous avez portez a notre Projet, <br>

Nous restons à votre disposition pour toute demande d'information.<br>

Cordialement,<br>
L'équipe Tritus";
                $infoUser = "Nom : " . $data['name'] . "<br>Email : " . $data['email'] . "<br>Ville : " . $data['city'] . "<br>Téléphone : " . $data['phone'] . "<br>Statut : " . $data['status'] . "<br>Message " . $data['message'];
                $this->sendMail($email, $object, $content, '...');
                $this->sendMailBis($object, $infoUser, $altContent = null);
            } else {
                $errors = $res['errors'];
            }
        }


        $newsRegister = "";

        if (isset($_POST['submitNewsletter'])) {
                $res = $manager->newsletterCheck($_POST['newsletter']);
                if($res['isFormGood']){
                    $manager->addMail($_POST);
                    $res = $manager->newslettersSend($res['data']);
                    $email = $res['email'];
                    $object = $res['object'];
                    $content = $res['content'];
                    $this->sendMail($email,$object,$content,'...');
                    $newsRegister = "Merci de vous etre abonnés a la NewsLetter, nous vous avons envoyé un email afin de vérifier votre adresse !";

                }
            }


        echo $this->renderView('partner.html.twig',
                                [
                                    'user' => $user,
                                    'allDeals' => $allDeals,
                                    'bottlesRecycled' => $bottlesRecycled,
                                    'errors' => $errors,
                                    'pageActuel' => $pageActuel,
                                    'newsRegister' => $newsRegister,
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
        $newsRegister = "";

        if (isset($_POST['submitNewsletter'])) {
            $res = $manager->newsletterCheck($_POST['newsletter']);
            if($res['isFormGood']){
                $manager->addMail($_POST);
                $res = $manager->newslettersSend($res['data']);
                $email = $res['email'];
                $object = $res['object'];
                $content = $res['content'];
                $this->sendMail($email,$object,$content,'...');
                $newsRegister = "Merci de vous etre abonnés a la NewsLetter, nous vous avons envoyé un email afin de vérifier votre adresse !";

            }
        }
        if(!empty($_SESSION['user_id'])){
            echo $this->renderView('about.html.twig', ['isConnected' => true, 'bottlesRecycled' => $bottlesRecycled,
                'user' => $user,'pageActuel' => $pageActuel,
                'newsRegister' => $newsRegister]);
        }
        else{
            echo $this->renderView('about.html.twig', ['bottlesRecycled' => $bottlesRecycled,'pageActuel' => $pageActuel,
                'newsRegister' => $newsRegister]);
        }
    }
}
