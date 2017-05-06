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
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $manager = UserManager::getInstance();
                $res = $manager->userCheckRegister($_POST);
                if($res['isFormGood']){
                    $manager->userRegister($res['data']);
                    $this->redirect('home');
                }
            }
            echo $this->renderView('register.html.twig');
        }
    }

}
