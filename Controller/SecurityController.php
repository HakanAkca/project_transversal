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
            if (isset($_POST['submitNewsletter'])) {
                $res = $manager->newsletterCheck($_POST['newsletter']);
                if($res['isFormGood']){
                    $res = $manager->newslettersSend($res['data']);
                    $email = $res['email'];
                    $object = $res['object'];
                    $content = $res['content'];
                    $this->sendMail($email,$object,$content,'...');
                }
            }
            echo $this->renderView('register.html.twig',
                                         [
                                             'errors' => $errors,
                                             'bottlesRecycled' => $bottlesRecycled,
                                         ]);
        }
    }

}
