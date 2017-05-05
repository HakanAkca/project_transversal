<?php

namespace Controller;

use Model\UserManager;

class SecurityController extends BaseController
{

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




<<<<<<< HEAD
=======
    public function adminAction()
    {
        if (!empty($_SESSION['user_username'] == 'adminHakan') ||
            !empty($_SESSION['user_username'] == 'adminOmar') ||
            !empty($_SESSION['user_username'] == 'adminNath')
        ) {
            $manager = UserManager::getInstance();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $res = $manager->checkCatalogue($_POST);
                if ($res['isFormGood']) {
                    $manager->addCatalogue($_POST);
                }
            }
            echo $this->renderView('admin.html.twig');
        } else {
            echo $this->redirect('home');
        }
    }

    public function offersAction(){
        $manager = UserManager::getInstance();
        $allOffres = $manager->getOffers();

        echo $this->renderView('home.html.twig',
            [
                'allOffres' => $allOffres
            ]);
    }
>>>>>>> 2b8668cf305bfcb98ecd39ec3d50f2e0b30e7ca8
}
