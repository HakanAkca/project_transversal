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
    public function getBarcodeByBarcode($barcode)
    {
        $user_id = $_SESSION['user_id'];
        $data = $this->DBManager->findOneSecure("SELECT * FROM barcodes WHERE barcode = :barcode AND user_id =:user_id",
            [
                'barcode' => $barcode,
                'user_id' => $user_id,
            ]);
        return $data;
    }


    public function userCheckRegister($data)
    {
        $errors = array();
        $res = array();
        $isFormGood = true;

        if (!isset($data['username']) || !$this->usernameValid($data['username'])) {
            $errors['username'] = 'Veuillez saisir un pseudo de 6 caractères minimum';
            $isFormGood = false;
        }
        $data2 = $this->getUserByUsername($data['username']);
        if($data2 !== false){
            $errors['username'] = 'Le pseudo existe déjà';
            $isFormGood = false;
        }
        if(!isset($data['password']) || !$this->passwordValid($data['password'])){
            $errors['password'] = "Veiller saisir un mot de passe valide ";
            $isFormGood = false;
        }
        if($this->passwordValid($data['password']) && $data['password'] !== $data['verifpassword']){
            $errors['password'] = "Les deux mot de passe ne sont pas identiques";
            $isFormGood = false;
        }
        if(!$this->emailValid($data['email'])){
            $errors['email'] = "email non valide";
            $isFormGood = false;
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
    //Minimum : 8 caractères avec au moins une lettre majuscule et un nombre
    private function passwordValid($password){
        return preg_match('`^([a-zA-Z0-9-_]{8,20})$`', $password);
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
        $user['city'] = $data['city'];
        $user['password'] = $this->userHash($data['password']);
        $user['points'] = 0;
        $user['bottlesNumber'] = 0;
        $user['level'] = 1;
        $user['date'] = $this->getDatetimeNow();

        $this->DBManager->insert('users', $user);
    }

    public function userCheckLogin($data)
    {
        if (empty($data['username']) OR empty($data['password']))
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
        return true;
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
            $isFormGood = false;
        }

        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        return $res;
    }
    public function addCatalog($data){
        $filetmpname = $data['image_tmp_name'];
        $url = 'uploads/'.$data['image'];;
        $catalog['partner'] = $data['partner'];
        $catalog['city'] = $data['city'];
        $catalog['deal'] = $data['deal']."&euro;";
        $catalog['cost'] = $data['cost'];
        $catalog['image'] = $url;
        $catalog['date'] = $this->getDatetimeNow();

        $this->DBManager->insert('catalogs', $catalog);
        move_uploaded_file($filetmpname,$url);
    }

    public function getAllDeals(){
        return $this->DBManager->findAllSecure("SELECT * FROM catalogs ORDER BY date DESC");
    }

    public function getUserDeals(){
        $cost = $this->getUserCostsNumber();
        return $this->DBManager->findAllSecure("SELECT * FROM catalogs WHERE cost <=:cost ORDER BY date DESC",['cost' => $cost]);
    }

    public function checkDump($data)
    {
        return is_numeric($data['bottlesNumber']);
    }

    public function addBarcode($data){
        $barcode['barcode'] = $this->generateBarcode();
        $barcode['bottlesNumber'] = (int)$data['bottlesNumber'];
        $barcode['cost'] = $this->setCost((int)$data['bottlesNumber']);
        $barcode['user_id'] = $_SESSION['user_id'];

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
        }
        return $isFormGood;
    }






















    public function getAllUsersBottlesRecycled(){
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT bottlesNumber FROM barcodes");
        foreach ($data as $bottles){
            $res += (int)$bottles['bottlesNumber'];
        }
        return $res;
    }

    public function getUserBottlesRecycled()
    {
        $user_id = $_SESSION['user_id'];
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT bottlesNumber FROM barcodes WHERE  user_id =:user_id",
            ['user_id' => $user_id]);
        foreach ($data as $bottles){
            $res += (int)$bottles['bottlesNumber'];
        }
        return $res;
    }
    public function setUserBottlesRecycled($data){
        $user_id = $_SESSION['user_id'];
        $bottlesNumber = (int)$data;
        return $this->DBManager->findOneSecure("UPDATE users SET bottlesNumber = :bottlesNumber WHERE id=:user_id",
            [
                'user_id' => $user_id,
                'bottlesNumber' => $bottlesNumber,
            ]);
    }
    public function setUserCostsNumber($data){
        $user_id = $_SESSION['user_id'];
        $costs = (int)$data;
        return $this->DBManager->findOneSecure("UPDATE users SET costs = :costs WHERE id=:user_id",
                                                [
                                                    'user_id' => $user_id,
                                                    'costs' => $costs,
                                                ]);

    }
    public function getUserCostsNumber(){
        $user_id = $_SESSION['user_id'];
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT cost FROM barcodes WHERE  user_id =:user_id",
                                                ['user_id' => $user_id]);
        foreach ($data as $cost){
            $res += (int)$cost['cost'];
        }
        return $res;
    }
    public function setCost($data){
        return $data*2;
    }
    public function getDatetimeNow() {
        date_default_timezone_set('Europe/Paris');
        return date("Y-m-d H:i:s");
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
}
