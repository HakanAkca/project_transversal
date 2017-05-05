<?php

namespace Controller;

use Model\UserManager;

class UserController extends BaseController
{
    public function profilAction()
    {
        if (!empty($_SESSION['user_id'])) {
            $manager = UserManager::getInstance();
            $recycledObjects = $manager->recycledObjects();
            $bottlesNumber = $manager->getBottlesNumber($_SESSION['user_id']);
            $level = $manager->getLevel($_SESSION['user_id']);
            $offers = $manager->getOffers();
            $user = $manager->getUserById($_SESSION['user_id']);


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $res = $manager->pushBottles($_POST);
                if (is_array($res) && !empty($res)) {
                    $manager->addCodeBar($res);
                    header('Loaction:?action=profil');
                }

            }
            echo $this->renderView('profil.html.twig',
                [
                    'recycledObjects' => $recycledObjects,
                    'bottlesNumber' => $bottlesNumber,
                    'level' => $level,
                    'offers' => $offers,
                    'user' => $user
                ]);
        } else {
            echo $this->redirect('login');
        }

    }
    public function adminAction()
    {
        if (!empty($_SESSION['user_username'] == 'adminHakan') ||
            !empty($_SESSION['user_username'] == 'adminOmar') ||
            !empty($_SESSION['user_username'] == 'adminNath')
        ) {
            $manager = UserManager::getInstance();
            $user = array();
            if(!empty($_SESSION['user_id'])) {
                $user = $manager->getUserById($_SESSION['user_id']);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $res = $manager->checkCatalogue($_POST);
                if ($res['isFormGood']) {
                    $manager->addCatalogue($_POST);
                }
            }
            echo $this->renderView('admin.html.twig', ['user' => $user]);
        } else {
            echo $this->redirect('home');
        }
    }
}