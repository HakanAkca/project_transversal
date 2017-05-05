<?php

namespace Controller;

use Model\UserManager;

class SecurityController extends BaseController
{
    public function loginAction()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $manager = UserManager::getInstance();
            if ($manager->userCheckLogin($_POST)) {
                $manager->userLogin($_POST['username']);
                $this->redirect('profil');
            } else {
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
        } else {
            $errors = array();
            $manager = UserManager::getInstance();
            $recycledObjects = $manager->recycledObjects();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $res = $manager->userCheckRegister($_POST);
                if ($res['isFormGood']) {
                    $manager->userRegister($_POST);
                    $this->redirect('home');
                } else {
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




}
