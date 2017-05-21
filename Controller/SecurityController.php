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
                    $donnee = $res['data'];

                    $manager->userRegister($res['data']);

                    $email = $donnee['email'];
                    $object = "Inscription-Tritus";
                    $content = "Bonjour et merci de vous être inscrit chez Tritus. Merci de nous rejoindre dans notre aventure écologique. Adieu les bouteilles vides et vive les coupons de réductions. Découvrez notre liste de partenaire en cliquant sur le lien ci-dessous et participez à chaque fin de mois à l'élection de vos enseignes préférées. Alors tous ensemble pour le recyclage et à très bientôt sur notre site ! Pour rappel votre Nom de compte est le : Cordialement, L'équipe Tritus
";
                    $this->sendMail($email,$object,$content,'...');
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
