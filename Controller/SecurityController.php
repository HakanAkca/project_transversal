<?php

namespace Controller;

use Model\UserManager;

class SecurityController extends BaseController
{
    public function loginAction()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $manager = UserManager::getInstance();
            if ($manager->userCheckLogin($_POST))
            {
                $manager->userLogin($_POST['username']);
                $this->redirect('profil');
            }
            else {
                $error = "Invalid username or password";
            }
        }
        echo $this->renderView('login.html.twig', ['error' => $error]);
    }

    public function logoutAction()
    {
        session_destroy();
        echo $this->redirect('login');
    }

    public function registerAction()
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('home');
        }
        else{
            $errors = array();
            $manager = UserManager::getInstance();
            $recycledObjects = $manager->recycledObjects();
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $res = $manager->userCheckRegister($_POST);
                if ($res['isFormGood'])
                {
                    $manager->userRegister($_POST);
                    $this->redirect('home');
                }
                else {
                    $errors = $res['errors'];
                }
            }
            echo $this->renderView('register.html.twig',
                [
                    'recycledObjects' => $recycledObjects,
                    'errors' => $errors
                ]);
        }
    }

    public function profilAction(){
        if(!empty($_SESSION['user_id'])){
            $manager = UserManager::getInstance();
            $recycledObjects = $manager->recycledObjects();
            $username = $_SESSION['user_username'];
            $bottlesNumber = $manager->getBottlesNumber($_SESSION['user_id']);
            $level = $manager->getLevel($_SESSION['user_id']);

            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $res = $manager->pushBottles($_POST);
                if(is_array($res) && !empty($res)){
                    $manager->addCodeBar($res);
                }

            }
            echo $this->renderView('profil.html.twig',
                [
                    'recycledObjects' => $recycledObjects,
                    'username' => $username,
                    'bottlesNumber' => $bottlesNumber,
                    'level' => $level
                ]);
        } else {
            echo $this->redirect('login');
        }
    }

    public function adminAction(){
        if(!empty($_SESSION['user_username'] == 'adminHNO')) {
            echo 'ok';
        }else{
            echo $this->redirect('home');
        }
    }
}
