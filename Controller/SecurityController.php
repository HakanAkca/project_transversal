<?php

namespace Controller;

use Model\UserManager;

class SecurityController extends BaseController
{

    public function logoutAction()
    {
        session_destroy();
        echo $this->redirect('home');
    }

    public function registerAction()
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('home');
        } else {
            $errors = array();
            $manager = UserManager::getInstance();
            $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
            $pageActuel = $_GET['action'];
            if (isset($_POST['submitRegister']))
            {
                $res = $manager->userCheckRegister($_POST);
                if($res['isFormGood']){
                    $manager->userRegister($res['data']);
                    $this->redirect('home');
                }else{
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
            echo $this->renderView('register.html.twig',
                                         [
                                             'errors' => $errors,
                                             'bottlesRecycled' => $bottlesRecycled,
                                             'pageActuel' => $pageActuel,
                                             'newsRegister' => $newsRegister,
                                         ]);
        }
    }

}
