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
            $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
            $user_id = $_SESSION['user_id'];
            $user = $manager->getUserById($user_id);
            $dealByCity = $manager->getDealsByCity($user['city']);
            $userDeals = $manager->getAvailableDeals();
            $costs = 0;
            $errorBarcode = '';
            $yourBarcode = '';
            $userBarcode = $manager->getUserBarcodes();
            if(isset($_POST['submitBottles'])) {
                if ($manager->checkDump($_POST)) {
                    $manager->addBarcode($_POST);
                }
            }
            if(isset($_POST['submitBarcode'])) {
                if($manager->checkUserBarcode($_POST)) {
                    $costs = $manager->getUserCostsNumber();
                    $barcode = $manager->getBarcodeByBarcode($_POST['barcode']);
                    $manager->setUserBottlesRecycled($barcode['bottlesNumber']);
                    $manager->setUserCostsNumber($barcode['cost']);
                    $manager->updateLevel();
                    $manager->barcodeUsed($_POST['barcode']);
                    header('Location:?action=profile');
                }else{
                    $errorBarcode = "Veillez saisir un code barre valide";
                }
            }
            echo $this->renderView('profile.html.twig',
                                    [
                                        'user' => $user,
                                        'userDeals' => $userDeals,
                                        'dealByCity' => $dealByCity,
                                        'costs' => $costs,
                                        'bottlesRecycled' => $bottlesRecycled,
                                        'errorBarcode' => $errorBarcode,
                                        'yourBarcode' => $yourBarcode,
                                        'userBarcode' => $userBarcode,
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
            if(isset($_POST['submitCatalog'])){
                $res = $manager->checkCatalog($_POST);
                if($res['isFormGood']){
                    $manager->addCatalog($res['data']);
                }else{
                    $errors = $res['errors'];
                }
            }
            if(isset($_POST['submitAccount'])){
                if($manager->checkRemoveAccount($_POST)){
                    $manager->deleteAccount($_POST);
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