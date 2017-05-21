<?php
/**
 * Created by PhpStorm.
 * User: sow
 * Date: 05/05/17
 * Time: 20:18
 */

namespace Controller;
require('phpToPDF.php');

use Model\UserManager;

class ProfileController extends BaseController
{
    public function profileAction()
    {

        if (!empty($_SESSION['user_id'])) {
            $manager = UserManager::getInstance();
            $bottlesRecycled = $manager->getAllUsersBottlesRecycled();
            $user_id = $_SESSION['user_id'];
            $user = $manager->getUserById($user_id);
            $dealByCity = $manager->getDealsByCity($user['city']);
            $userDeals = $manager->getAvailableDeals();
            $pageActuel = $_GET['action'];
            $errors = array();

            $costs = 0;
            $errorBarcode = '';
            $yourBarcode = '';
            $userBarcode = $manager->getUserBarcodes();
            $myDeals = $manager->getUserDeals();

            //Classement !!!
            $average = $manager->getAverages();
            $ranking = $manager->ranking();
            $errors = array();

            //survey (SONDAGE)
            $surveys = $manager->getSurvey();
            $allVotes = $manager->allVotes();  //for average


            if (isset($_POST['submitNewsletter'])) {
                $res = $manager->newsletterCheck($_POST['newsletter']);
                if($res['isFormGood']){
                    $manager->addMail($_POST);
                    $res = $manager->newslettersSend($res['data']);
                    $email = $res['email'];
                    $object = $res['object'];
                    $content = $res['content'];
                    $this->sendMail($email,$object,$content,'...');
                }
            }
            if (isset($_POST['submitBuyDeal'])) {
                if ($manager->chechBuyDeal($_POST['IDdeal'])) {
                    $manager->buyDeal($_POST['IDdeal']);
                    header('Location:?action=profile');
                    $manager->getUserDeals();
                }
            }
            if (isset($_POST['submitEditProfile'])) {
                $res = $manager->checkProfile($_POST);
                if($res['isFormGood']){
                    $manager->editProfile($res['data']);
                }else{
                    $errors = $res['errors'];
                }
            }

            if (isset($_POST['submitPrintOffer'])) {
                $id = (int)$_POST['IDoffer'];
                $deal = $manager->getDealById($id);
                $barcode  = $manager->generateBarcode();
                $my_html="
                    <HTML>
                            <h2>Tritus</h2><br><br>
                             <div style=\"display:block; padding:20px; border:2pt solid:#FE9A2E; background-color:#F6E3CE; font-weight:bold;\">
                                ".$_SESSION['user_username']."<br>Code barre ".$barcode."<br>Offre :".$deal['partner']."<br> City : ".$deal['city']."<br>Deal ".$deal['deal']."
                    
</div><br><br>
MERCI !!!
</HTML>";
                $pdf_options = array(
                    "source_type" => 'html',
                    "source" => $my_html,
                    "action" => 'save',
                    "save_directory" => 'uploads',
                    "file_name" => 'html_01.pdf'
                    );

                phptopdf($pdf_options);
                echo ("<a href='html_01.pdf'>Download Your PDF</a>");

            }
            if (isset($_POST['submitBarcode'])) {
                if ($manager->checkUserBarcode($_POST)) {
                    $costs = $manager->getUserCostsNumber();
                    $barcode = $manager->getBarcodeByBarcode($_POST['barcode']);
                    $manager->setUserBottlesRecycled($barcode['bottlesNumber']);
                    $manager->setUserCostsNumber($barcode['cost']);
                    $manager->updateLevel();
                    $manager->barcodeUsed($_POST['barcode']);
                    header('Location:?action=profile');
                } else {
                    $errorBarcode = "Veillez saisir un code barre valide";
                }
            }
            if (isset($_POST['userVote'])) {
                if ($manager->checkVote((int)$_SESSION['user_id'])) {
                    $manager->userVote($_POST);
                } else {
                    $errors[] = 'Vous avez déjà voté'; //
                }
            }
            //Update permission to vote
            $manager->updateSurvey();







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
                    'myDeals' => $myDeals,
                    'pageActuel' => $pageActuel,
                    'ranking' => $ranking,
                    'average' => $average,
                    'errors' => $errors,
                    'surveys' => $surveys,
                    'allVotes' => $allVotes,
                ]);
        } else {
            $this->redirect('home');
        }


    }


    public function adminAction()
    {
        $offers = array();
        if (!empty($_SESSION['user_username'] == 'adminOmar')) {
            $manager = UserManager::getInstance();
            $user_id = $_SESSION['user_id'];
            $user = $manager->getUserById($user_id);
            $errors = array();
            $manager->getAllDeals();
            $surveys = $manager->getSurvey();
            $allVotes = $manager->allVotes();  //for average
            $pageActuel = $_GET['action'];


            if (isset($_POST['submitCatalog'])) {
                $res = $manager->checkCatalog($_POST);
                if ($res['isFormGood']) {
                    $manager->addCatalog($res['data']);
                } else {
                    $errors = $res['errors'];
                }
            }

            if (isset($_POST['submitSendGeneralMail'])) {
                var_dump($_POST);
            }
            if (isset($_POST['submitAddSurvey'])) {
                $res = $manager->checkSurvey($_POST);
                if ($res['isFormGood']) {
                    $manager->addSurveyTmp($res['data']);
                    $data = $manager->countSurveyTmp();
                    foreach ($data as $value){
                        if((int)$value['COUNT(*)'] == 3){
                            $surveysTmp = $manager->getSurveyTmp();
                            foreach ($surveysTmp as $value){
                                $manager->addSurvey($value);
                            }
                            $manager->removeSurveyTmp();
                        }
                    }
                } else {
                    $errors = $res['errors'];
                }
            }

            $res_tmp = $manager->surveyNumber();
            if(is_array($res_tmp)){
                $offers[] = $res_tmp[0];
                if(isset($res_tmp[1])){
                    $offers[] = $res_tmp[1];
                }
                if(isset($res_tmp[2])){
                    $offers[] = $res_tmp[2];
                }
            }


            if (isset($_POST['submitAccount'])) {
                if ($manager->checkRemoveAccount($_POST)) {
                    $manager->deleteAccount($_POST);
                }
            }

            if (isset($_POST['deletteOffers'])) {
                if ($manager->checkRemoveOffers($_POST)) {
                    $manager->deleteOffers($_POST);
                }
            }

            $dealToUpdate = array();
            if (isset($_POST['submitChoiceOffer'])) {
                $dealToUpdate = $manager->getDealByTitle($_POST['listOffer']);
            }
            if (isset($_POST['submitUpdateOffer'])) {
                $res = $manager->checkUpdateOffer($_POST);
                if($res['isFormGood']){
                    $manager->updateOffer($res['data']);
                }
            }
            if (isset($_POST['submitRemoveOffer'])) {
                $manager->removeOffer($_POST['partner']);
            }

            if (isset($_POST['submitBottles'])) {
                if ($manager->checkDump($_POST)) {
                    $manager->addBarcode($_POST);
                }
            }
            $deals = $manager->getAllDeals();
            echo $this->renderView('admin.html.twig',
                [
                    'user' => $user,
                    'errors' => $errors,
                    'deals' => $deals,
                    'dealToUpdate' => $dealToUpdate,
                    'surveys' => $surveys,
                    'allVotes' => $allVotes,
                    'offers' => $offers,
                    'pageActuel' => $pageActuel,
                ]);
        } else {
            $this->redirect('home');
        }
    }

}