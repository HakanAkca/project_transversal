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
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $res = $manager->userCheckRegister($_POST);
                if($res['isFormGood']){
                    $manager->userRegister($res['data']);
                    $this->redirect('home');
                }else{
                    $errors = $res['errors'];
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
