<?php
/**
 * Created by PhpStorm.
 * User: sow
 * Date: 05/05/17
 * Time: 20:18
 */

namespace Controller;

use Model\UserManager;

class ProfileController extends BaseController
{
    public function profileAction(){
        if(!empty($_SESSION['user_id'])){
            $manager = UserManager::getInstance();
            $user_id = $_SESSION['user_id'];
            $user = $manager->getUserById($user_id);
            $allDeals = $manager->getAllDeals();
            $userDeals = $manager->getUserDeals();
            $costs = 0;
            $bottlesNumber = 0;
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($manager->checkDump($_POST)) {
                    $manager->addBarcode($_POST);
                }
                if($manager->checkUserBarcode($_POST)) {
                    $costs = $manager->getUserCostsNumber();
                    $bottlesNumber = $manager->getUserBottlesRecycled();
                    $manager->setUserCostsNumber($costs);
                    $manager->setUserBottlesRecycled($bottlesNumber);
                    header('Location:?action=profile');
                }
            }
            echo $this->renderView('profile.html.twig',
                                    [
                                        'user' => $user,
                                        'userDeals' => $userDeals,
                                        'allDeals' => $allDeals,
                                        'costs' => $costs,
                                        'bottlesNumber' => $bottlesNumber,
                                    ]);
        }else{
            $this->redirect('home');
        }

    }

    public function adminAction(){
        if(!empty($_SESSION['user_username'] == 'adminOmar')){
            $manager = UserManager::getInstance();
            $user_id = $_SESSION['user_id'];
            $user = $manager->getUserById($user_id);
            $errors = array();
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $res = $manager->checkCatalog($_POST);
                if($res['isFormGood']){
                    $manager->addCatalog($res['data']);
                }else{
                    $errors = $res['errors'];
                }
            }
            echo $this->renderView('admin.html.twig',
                                    [
                                        'user' => $user,
                                        'errors' => $errors,
                                    ]);
        }else{
            $this->redirect('home');
        }
    }

}