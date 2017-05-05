<?php

namespace Controller;

use Model\UserManager;

class DefaultController extends BaseController
{
    public function homeAction()
    {
        $error = '';
        $manager = UserManager::getInstance();
        $recycledObjects = $manager->recycledObjects();

        $user = array();
        if(!empty($_SESSION['user_id'])) {
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $manager = UserManager::getInstance();
            if ($manager->userCheckLogin($_POST)) {
                $manager->userLogin($_POST['username']);
                $this->redirect('profil');
            } else {
                $error = "Invalid username or password";
            }
        }
        echo $this->renderView('home.html.twig',
            [
                'recycledObjects' => $recycledObjects,
                'user' => $user,
                'error' => $error
            ]);
    }

    public function aboutAction()
    {
        $manager = UserManager::getInstance();
        $recycledObjects = $manager->recycledObjects();
        $user = array();
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        echo $this->renderView('about.html.twig',
            [
                'recycledObjects' => $recycledObjects,
                'user' => $user,
            ]);
    }

    public function partnerAction()
    {
        $manager = UserManager::getInstance();
        $recycledObjects = $manager->recycledObjects();
        $user = array();
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        echo $this->renderView('partner.html.twig',
            [
                'recycledObjects' => $recycledObjects,
                'user' => $user,
            ]);
    }
}
