<?php

namespace Controller;

use Model\UserManager;

class DefaultController extends BaseController
{
    public function homeAction(){
        $manager = UserManager::getInstance();
        $recycledObjects = $manager->recycledObjects();
        echo $this->renderView('home.html.twig',
            [
                'recycledObjects' => $recycledObjects
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
