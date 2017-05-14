<?php

namespace Model;


class UserManager
{
    private $DBManager;
    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new UserManager();
        return self::$instance;
    }
    private function __construct()
    {
        $this->DBManager = DBManager::getInstance();
    }
    public function getUserById($id)
    {
        $id = (int)$id;
        $data = $this->DBManager->findOne("SELECT * FROM users WHERE id = " . $id);
        return $data;
    }

    public function getUserByUsername($username)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM users WHERE pseudo = :username",
            ['username' => $username]);
        return $data;
    }
    public function allUsers()
    {
        return $this->DBManager->findAllSecure("SELECT * FROM users");
    }
    public function getPartnerByName($name)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM partners WHERE name = :name",
            ['name' => $name]);
        return $data;
    }
    public function getBarcodeByBarcode($barcode)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM barcodes WHERE barcode = :barcode",
            [
                'barcode' => $barcode,
            ]);
        return $data;
    }
    public function getDealById($d)
    {
        $id = (int)$d;
        $data = $this->DBManager->findOneSecure("SELECT * FROM catalogs WHERE id = :id",
            [
                'id' => $id,
            ]);
        return $data;
    }


    public function userCheckRegister($data)
    {
        $errors = array();
        $res = array();
        $isFormGood = true;

        $data['image'] = 'web/img/avatar.gif';
        if (!isset($data['username']) || !$this->usernameValid($data['username'])) {
            $errors['username'] = 'Pseudo de 6 caractères minimum';
            $isFormGood = false;
        }
        $data2 = $this->getUserByUsername($data['username']);
        if($data2 !== false){
            $errors['username'] = 'Le pseudo existe déjà';
            $isFormGood = false;
        }

        if(!$this->emailValid($data['email'])){
            $errors['email'] = "email non valide";
            $isFormGood = false;
        }

        if(!isset($data['password']) || !$this->passwordValid($data['password'])){
            $errors['password'] = "Veiller saisir un mot de passe valide ";
            $isFormGood = false;
        }
        /*if($this->passwordValid($data['password']) && $data['password'] !== $data['verifpassword']){
            $errors['password'] = "Les deux mot de passe ne sont pas identiques";
            $isFormGood = false;
        }*/


        if (!isset($data['city']) || !$this->cityValid($data['city'])){
            $errors['city'] = 'Merci de saissir une ville';
            $isFormGood = false;
        }

        if ($isFormGood) {
            echo(json_encode(array('success' => true, 'user' => $_POST)));
        } else {
            http_response_code(400);
            echo(json_encode(array('success' => false, 'errors' => $errors)));
            exit(0);
        }

        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        $res['data'] = $data;
        return $res;
    }



    private function emailValid($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    private function usernameValid($username){
        return preg_match('`^([a-zA-Z0-9-_]{6,20})$`', $username);
    }
    private function cityValid($city){
        return preg_match('`^([a-zA-Z0-9-_]{1,15})$`', $city);
    }
    //Minimum : 8 caractères avec au moins une lettre majuscule et un nombre
    private function passwordValid($password){
        return preg_match('`^([a-zA-Z0-9-_]{8,20})$`', $password);
    }
    public function phoneNumberValid($phoneNumber, $international = false){
        $phoneNumber = preg_replace('/[^0-9]+/', '', $phoneNumber);
        $phoneNumber = substr($phoneNumber, -9);
        $motif = $international ? '+33 (\1) \2 \3 \4 \5' : '0\1 \2 \3 \4 \5';
        $phoneNumber = preg_replace('/(\d{1})(\d{2})(\d{2})(\d{2})(\d{2})/', $motif, $phoneNumber);

        return $phoneNumber;
    }

    private function userHash($pass)
    {
        $hash = password_hash($pass, PASSWORD_BCRYPT, ['salt' => 'saltysaltysaltysalty!!']);
        return $hash;
    }

    public function userRegister($data)
    {
        $user['pseudo'] = $data['username'];
        $user['email'] = $data['email'];
        $user['city'] = ucwords($data['city']);
        $user['password'] = $this->userHash($data['password']);
        $user['points'] = 0;
        $user['bottlesNumber'] = 0;
        $user['level'] = 1;
        $user['date'] = $this->getDatetimeNow();
        $user['image'] = $data['image'];

        $this->DBManager->insert('users', $user);
        mkdir("uploads/". $user['pseudo']);
    }

    public function userCheckLogin($data)
    {

        /*if (empty($data['username']) OR empty($data['password']))
            return false;
        $user = $this->getUserByUsername($data['username']);
        if ($user === false) {
            $date = $this->DBManager->take_date();
            $write = $date . ' -- ' . $data['username'] .' not correct user try to connected' . "\n";
            $this->DBManager->watch_action_log('access.log', $write);
            return false;
        }
        $hash = $this->userHash($data['password']);
        if ($hash !== $user['password']) {
            $date = $this->DBManager->take_date();
            $write = $date . ' -- ' . $data['password']  . ' not correct password' . "\n";
            $this->DBManager->watch_action_log('access.log', $write);
            return false;
        }
        return true;*/
        $isFormGood = true;
        $errors = array();
        $user = $this->getUserByUsername($data['username']);
        $hash = $this->userHash($data['password']);
        if (empty($data['username']) OR empty($user) OR empty($data['password']) OR empty($hash)) {
            $errors['Connexion field'] = 'Login ou mdp incorrect';
            $isFormGood = false;
        }
        if ($isFormGood) {
            echo(json_encode(array('success' => true, 'user' => $_POST)));
        } else {
            http_response_code(400);
            echo(json_encode(array('success' => false, 'errors' => $errors)));
            exit(0);
        }
        return $isFormGood;
    }

    public function userLogin($username)
    {
        $data = $this->getUserByUsername($username);
        if ($data === false)
            return false;
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['user_username'] = $data['pseudo'];
        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . $_SESSION['user_username'] . ' is connected' . "\n";
        $this->DBManager->watch_action_log('access.log', $write);
        return true;
    }
    public function checkPartner($data)
    {
        $errors = array();
        $res = array();
        $isFormGood = true;
        if (!isset($data['name']) || empty($data['name'])) {
            $errors['name'] = 'Veuillez saisir le nom du partenaire';
            $isFormGood = false;
        }
        $data2 = $this->getPartnerByName($data['name']);
        if($data2 !== false){
            $errors['name'] = 'Le partenaire existe déjà';
            $isFormGood = false;
        }
        if(!$this->emailValid($data['email'])){
            $errors['email'] = "email non valide";
            $isFormGood = false;
        }
        if (!isset($data['city']) || empty($data['city'])) {
            $errors['city'] = 'Veuillez saisir une ville valide';
            $isFormGood = false;
        }
        if (!isset($data['phone']) || !$this->phoneNumberValid($data['phone'])) {
            $errors['phone'] = 'Veuillez saisir un numéro de téléphone valide';
            $isFormGood = false;
        }
        if (!isset($data['status']) || empty($data['status'])) {
            $errors['status'] = 'Veuillez saisir une status valide';
            $isFormGood = false;
        }
        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        $res['data'] = $data;
        return $res;
    }
    public function bePartner($data){
        $partner['name'] = $data['name'];
        $partner['email'] = $data['email'];
        $partner['city'] = ucwords($data['city']);
        $partner['phone'] = $data['phone'];
        $partner['status'] = $data['status'];

        $this->DBManager->insert('partners', $partner);
    }
    public function checkRemoveAccount($data)
    {
        if (empty($data['pseudo']))
            return false;
        $user = $this->getUserByUsername($data['pseudo']);
        if ($user === false)
            return false;
        return true;
    }
    public function deleteAccount($data){
        $pseudo = $data['pseudo'];
        return $this->DBManager->findOneSecure("DELETE FROM users WHERE pseudo =:pseudo",
                                                ['pseudo' => $pseudo]);
    }

    public function checkCatalog($data){
        $isFormGood = true;
        $errors = array();
        $res = array();
        if(isset($_FILES['image']['name']) && !empty($_FILES)){
            $data['image'] = $_FILES['image']['name'];
            $data['image_tmp_name'] = $_FILES['image']['tmp_name'];
            $res['data'] = $data;
        }
        else{
            $errors['image'] = 'Veillez choisir une image';
            $isFormGood = false;
        }
        if(!isset($data['partner']) | empty($data['partner'])){
            $errors['partner'] = "Veillez choisir un partenaire";
            $isFormGood = false;
        }
        if(!isset($data['city']) | empty($data['city'])){
            $errors['city'] = "Veillez choisir une ville";
            $isFormGood = false;
        }
        if(!isset($data['deal']) | empty($data['deal'])){
            $errors['deal'] = "Veillez choisir une offre";
            $isFormGood = false;
        }
        if(!isset($data['cost']) | empty($data['cost'])){
            $errors['cost'] = "Veillez choisir un cost";
            $isFormGood = false;
        }
        if(!isset($data['description']) | empty($data['description'])){
            $isFormGood = false;
            $errors['description'] = "Veillez remplir le champ description";
        }
        if(!isset($data['expirationDate']) | empty($data['expirationDate'])){
            $errors['expirationDate'] = "Veillez mettre une date d'expiration valide";
            $isFormGood = false;
        }
        if(isset($data['expirationDate']) && !empty($data['expirationDate'])){
            if(!$this->checkExpirationDate($data['expirationDate'])){
                $errors['expirationDate'] = "Veillez mettre une date d'expiration valide";
                $isFormGood = false;
            }else{
                $strToDate=  strtotime($data['expirationDate']);
                $expirationDate = date('Y/m/d H:i:s', $strToDate);
                $data['expirationDate'] = $expirationDate;
            }
        }

        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        return $res;
    }
    public function addCatalog($data){
        $filetmpname = $data['image_tmp_name'];
        $url = 'uploads/'.$data['image'];;
        $catalog['partner'] = $data['partner'];
        $catalog['city'] = ucwords($data['city']);
        $catalog['deal'] = $data['deal']."%";
        $catalog['cost'] = $data['cost'];
        $catalog['description'] = $data['description'];
        $catalog['image'] = $url;
        $catalog['date'] = $this->getDatetimeNow();
        $day = (int)substr($data['expirationDate'],0,2);
        $month = (int)substr($data['expirationDate'],3,2);
        $year = (int)substr($data['expirationDate'],6,4);
        $tmpDate = $month.'/'.$day.'/'.$year;
        $expirationDate =  strtotime($tmpDate);
        $catalog['expirationDate'] = date('Y/m/d H:i:s', $expirationDate);
        $this->DBManager->insert('catalogs', $catalog);
        move_uploaded_file($filetmpname,$url);
    }

    public function getAllDeals(){
        $cur = strtotime($this->getDatetimeNow());
        $currentDate = date('Y/m/d H:i:s', $cur);
        $res = array();
        $data = $this->DBManager->findAllSecure("SELECT * FROM catalogs ORDER BY date DESC");

        foreach ($data as $value) {
            $exp = strtotime($value['expirationDate']);
            $expirationDate =  date('Y/m/d H:i:s', $exp);

            $date1=date_create($currentDate);
            $date2=date_create($expirationDate);
            $diff=date_diff($date1,$date2);
            $interval =  (int)$diff->format("%R%a");
            if($interval >= 0){
                $res[] = $value;
            }
        }
        return $res;
    }

    public function getDealsByCity($data){
        $city = ucwords($data);
        $cur = strtotime($this->getDatetimeNow());
        $currentDate = date('Y/m/d H:i:s', $cur);
        $res = array();
        $data2 = $this->DBManager->findAllSecure("SELECT * FROM catalogs WHERE city =:city ORDER BY date DESC",
                                                ['city' => $city]);
        foreach ($data2 as $value) {
            $exp = strtotime($value['expirationDate']);
            $expirationDate =  date('Y/m/d H:i:s', $exp);

            $date1=date_create($currentDate);
            $date2=date_create($expirationDate);
            $diff=date_diff($date1,$date2);
            $interval =  (int)$diff->format("%R%a");
            if($interval >= 0){
                $res[] = $value;
            }
        }
        return $res;
    }
    public function getAvailableDeals(){
        $cost = $this->getUserCostsNumber();
        $user = $this->getUserById($_SESSION['user_id']);
        $city = $user['city'];
        $cur = strtotime($this->getDatetimeNow());
        $currentDate = date('Y/m/d H:i:s', $cur);
        $res = array();
        $data = $this->DBManager->findAllSecure("SELECT * FROM catalogs WHERE cost <=:cost AND city =:city ORDER BY date DESC",
                                                [
                                                    'cost' => $cost,
                                                    'city' => $city,
                                                ]);
        foreach ($data as $value) {
            $exp = strtotime($value['expirationDate']);
            $expirationDate =  date('Y/m/d H:i:s', $exp);

            $date1=date_create($currentDate);
            $date2=date_create($expirationDate);
            $diff=date_diff($date1,$date2);
            $interval =  (int)$diff->format("%R%a");
            if($interval >= 0){
                $res[] = $value;
            }
        }
        return $res;
    }
    public function getUserDeals(){
        $res = array();
        $user_id = (string)$_SESSION['user_id'];
        $data = $this->DBManager->findAllSecure("SELECT * FROM deals WHERE user_id =:user_id ORDER BY date DESC",
                                                    ['user_id' => $user_id]);
        foreach ($data as $catalog){
            $res[] = $this->getDealById($catalog['catalog_id']);
        }
        return $res;
    }

    public function checkDump($data)
    {
        return is_numeric($data['bottlesNumber']);
    }

    public function addBarcode($data){
        $barcode['barcode'] = $this->generateBarcode();
        $barcode['bottlesNumber'] = (int)$data['bottlesNumber'];
        $barcode['cost'] = $this->setCost((int)$data['bottlesNumber']);
        $barcode['barcodeUsed'] = 0;

        $this->DBManager->insert('barcodes', $barcode);
    }

    public function checkUserBarcode($data){
        $isFormGood = true;
        if(empty($data['barcode'])){
           $isFormGood = false;
        }else{
            $code = $this->getBarcodeByBarcode($data['barcode']);
            if($code == false){
                $isFormGood = false;
            }
            if($code !== false && $code['barcodeUsed'] == 1){
                $isFormGood = false;
            }
        }
        return $isFormGood;
    }
    public function barcodeUsed($data){
        $barcode = $data;
        $barcodeUsed = 1;
        return $this->DBManager->findOneSecure("UPDATE barcodes SET barcodeUsed =:barcodeUsed WHERE barcode =:barcode",
            [
                'barcode' => $barcode,
                'barcodeUsed' => $barcodeUsed,
            ]);
    }
    public function chechBuyDeal($id){
        $user_id = $_SESSION['user_id'];
        $deal = $this->getDealById($id);
        $user = $this->getUserById($user_id);
        $userCosts = (int)$user['costs'];
        $dealCosts = (int)$deal['cost'];
        return ($userCosts >= $dealCosts);
    }
    public function buyDeal($id){
        $user_id = $_SESSION['user_id'];
        $deal = $this->getDealById($id);
        $userDeal['user_id'] = $user_id;
        $userDeal['catalog_id'] = (int)$deal['id'];
        $userDeal['date'] = $this->getDatetimeNow();
        $this->setUserCostsNumber(-((int)$deal['cost']));
        $this->DBManager->insert('deals', $userDeal);
    }

    public function getAverages(){
        $users = $this->allUsers();
        $currentDate=date_create($this->getDatetimeNow());
        $average = array();
        foreach ($users as $user){
            $id = $user['id'];
            $bottlesNumber = $user['bottlesNumber'];
            $registerDate = $user['date'];
            $strToDate=  strtotime($registerDate);
            $registerDate = date('Y/m/d H:i:s', $strToDate);
            $date1=date_create($registerDate);
            $diff=date_diff($date1,$currentDate);
            $interval =  (int)$diff->format("%R%d");
            $week = (float)$interval/7;
            if($week == 0){
                $week = 1;
            }
            $average[$id] = round(($bottlesNumber)/$week);
        }
        return $average;
    }

    public function ranking(){
        $res = array();
        $averages = $this->getAverages();
        $r_averages = $averages;
        rsort($r_averages);
        $newAverage = array();
        foreach ($averages as $key => $value){
           $newAverage[$value] = $key;
        }
        foreach ($r_averages as $key => $value){
            $res[$newAverage[$value]] = $key+1;
        }
        return $res;
    }






















    public function checkExpirationDate($date){
        $day = (int)substr($date,0,2);
        $month = (int)substr($date,3,2);
        $year = (int)substr($date,6,4);
        $bool = true;
        if(substr($date,2,1) != '/' || substr($date,5,1) != '/'){
            $bool = false;
        }else{
            if(checkdate($month,$day,$year)){
               $currentDate = $this->getDatetimeNow();
               $date = $month.'/'.$day.'/'.$year;
               $strToDate=  strtotime($date);
               $expirationDate = date('Y/m/d H:i:s', $strToDate);
               $date1=date_create($currentDate);
                $date2=date_create($expirationDate);
                $diff=date_diff($date1,$date2);
                $interval =  (int)$diff->format("%R%a");
                if($interval < 0){
                    $bool = false;
                }
            }else{
                $bool = false;
            }
        }
        return $bool;
    }


    public function getUserBarcodes(){
        $user_id = $_SESSION['user_id'];
        $barcodeUsed = 0;
        return $this->DBManager->findAllSecure("SELECT * FROM barcodes WHERE user_id=:user_id AND barcodeUsed =:barcodeUsed",
                                                [
                                                    'user_id' => $user_id,
                                                    'barcodeUsed' => $barcodeUsed,
                                                ]);
    }
    public function updateLevel(){
        $user_id = $_SESSION['user_id'];
        $cost = $this->getUserCostsNumber();
        if($cost < 10){
            return ;
        }
        elseif($cost>=10 && $cost < 30){
            $level = 2;
            return $this->DBManager->findOneSecure("UPDATE users SET level = :level WHERE id=:user_id",
                [
                    'user_id' => $user_id,
                    'level' => $level,
                ]);
        }elseif($cost>=30 && $cost < 60){
            $level = 3;
            return $this->DBManager->findOneSecure("UPDATE users SET level = :level WHERE id=:user_id",
                [
                    'user_id' => $user_id,
                    'level' => $level,
                ]);
        }elseif($cost>=60 && $cost < 100){
            $level = 4;
            return $this->DBManager->findOneSecure("UPDATE users SET level = :level WHERE id=:user_id",
                [
                    'user_id' => $user_id,
                    'level' => $level,
                ]);
        }else{
            $level = 5;
            return $this->DBManager->findOneSecure("UPDATE users SET level = :level WHERE id=:user_id",
                [
                    'user_id' => $user_id,
                    'level' => $level,
                ]);
        }

    }
    public function getAllUsersBottlesRecycled(){
        $res = 0;
        $barcodeUsed = 1;
        $data = $this->DBManager->findAllSecure("SELECT bottlesNumber FROM barcodes WHERE barcodeUsed =:barcodeUsed",
                                                    ['barcodeUsed' => $barcodeUsed]);
        foreach ($data as $bottles){
            $res += (int)$bottles['bottlesNumber'];
        }
        return $res;
    }

    public function getUserBottlesRecycled($id)
    {
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT bottlesNumber FROM users WHERE  id =:id",
            [
                'id' => $id,
            ]);
        foreach ($data as $bottles){
            $res += (int)$bottles['bottlesNumber'];
        }
        return $res;
    }
    public function setUserBottlesRecycled($data){
        $user_id = $_SESSION['user_id'];
        $bottlesNumber = (int)$data+$this->getUserBottlesRecycled($user_id);
        return $this->DBManager->findOneSecure("UPDATE users SET bottlesNumber = :bottlesNumber WHERE id=:user_id",
            [
                'user_id' => $user_id,
                'bottlesNumber' => $bottlesNumber,
            ]);
    }
    public function setUserCostsNumber($data){
        $user_id = $_SESSION['user_id'];
        $costs = (int)($data+$this->getUserCostsNumber());
        return $this->DBManager->findOneSecure("UPDATE users SET costs = :costs WHERE id=:user_id",
                                                [
                                                    'user_id' => $user_id,
                                                    'costs' => $costs,
                                                ]);

    }
    public function getUserCostsNumber(){
        $user_id = $_SESSION['user_id'];
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT costs FROM users WHERE id =:user_id",
                                                [
                                                    'user_id' => $user_id,
                                                ]);
        foreach ($data as $cost){
            $res += (int)$cost['costs'];
        }
        return $res;
    }
    public function setCost($data){
        return $data*2;
    }
    public function getDatetimeNow() {
        date_default_timezone_set('Europe/Paris');
        return date("Y/m/d H:i:s");
    }
    public function generateBarcode()
    {
        $characters = '0123456789';
        $barcode = '';
        for ($i = 0; $i < 6; $i++) {
            $barcode .= $characters[mt_rand(0, 9)];
        }
        return $barcode;
    }

    public function newsletterCheck($data){
        $errors = array();
        $res = array();
        $isFormGood = true;

        if(!$this->emailValid($data)){
            $errors['lol'] = "email non valide";
            $isFormGood = false;
        }

        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        $res['data'] = $data;
        return $res;
    }

    public function newslettersSend($d)
    {
        $res = array();
        $email = $d;
        $object = 'Inscription au newsletter';
        $content = '<html>
                <head>
                <title>Vous avez réservé sur notre site ...</title>
                </head>
                <body>
                <p>Vous êtes inscript au newsletter. Merci</p>
                </body>
                </html>';
        $res['email'] = $email;
        $res['object'] = $object;
        $res['content'] = $content;
        return $res;
    }

    public function checkRemoveOffers($data)
    {
        if (empty($data['offers']))
            return false;
        $offers = $this->DBManager->findAllSecure("SELECT * FROM catalogs");
        if ($offers === false)
            return false;
        return true;
    }

    /*public function deleteOffers($data){
        $offers = $data['offers'];
        return $this->DBManager->findOneSecure("DELETE FROM catalogs WHERE partner = :partner",
            ['partner' => $offers]);
    }*/

    /*public function checkUpdateOffers($data)
    {
        if (empty($data['offers']))
            return false;
        $offers = $this->DBManager->findAllSecure("SELECT * FROM catalogs");
        if ($offers === false)
            return false;
        return true;
    }

    public function updateOffers($data){

        $offers = $data['offers'];
        $new_partner = $data['update-partner'];
        return $this->DBManager->findOneSecure("UPDATE catalogs SET partner='$new_partner' WHERE id=:offers",
            [
                'id' => $offers
        ]);
    }*/

}
