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
                $this->redirect('home');
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
            $error = '';
            $manager = UserManager::getInstance();
            $recycledObjects = $manager->recycledObjects();
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                if ($manager->userCheckRegister($_POST))
                {
                    $manager->userRegister($_POST);
                    $this->redirect('home');
                }
                else {
                    $error = "Invalid data";
                }
            }
            echo $this->renderView('register.html.twig',
                [
                    'error' => $error,
                    'recycledObjects' => $recycledObjects
                ]);
        }
    }

    public function profilAction(){
        if(!empty($_SESSION['user_id'])){
            $manager = UserManager::getInstance();
            $recycledObjects = $manager->recycledObjects();
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $res = $manager->pushBottles($_POST);
                if(is_array($res) && !empty($res)){
                    $manager->addCodeBar($res);
                }
            }
            echo $this->renderView('profil.html.twig',
                ['recycledObjects' => $recycledObjects]);
        }else{
            echo $this->redirect('login');
        }

    }
}
