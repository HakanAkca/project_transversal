<?php

namespace Controller;

use Model\UserManager;

class DefaultController extends BaseController
{
    public function homeAction(){
        $manager = UserManager::getInstance();
        $recycledObjects = $manager->recycledObjects();
        $allOffres = $manager->getOffers();
        $user = array();
        if(!empty($_SESSION['user_id'])){
            $user = $manager->getUserById($_SESSION['user_id']);
        }
        echo $this->renderView('home.html.twig',
            [
                'recycledObjects' => $recycledObjects,
                'allOffres' => $allOffres,
                'user' => $user,
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
