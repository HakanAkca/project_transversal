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
                'error' => $error
            ]);
    }

    public function aboutAction()
    {
        $manager = UserManager::getInstance();
        $recycledObjects = $manager->recycledObjects();
        echo $this->renderView('about.html.twig',
            [
                'recycledObjects' => $recycledObjects
            ]);
    }

    public function partnerAction()
    {
        $manager = UserManager::getInstance();
        $recycledObjects = $manager->recycledObjects();
        echo $this->renderView('partner.html.twig',
            [
                'recycledObjects' => $recycledObjects
            ]);
    }
}
