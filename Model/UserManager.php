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

    public function pushBottles($data)
    {
        $res = array();
        $numberOfBottles = $data["numberOfBottles"];
        if (is_numeric($numberOfBottles)) {
            $number = (int)$numberOfBottles;
            $res['numberOfBottles'] = $number;
            $res['barCode'] = $this->barCode();
            $res['user_id'] = (int)$_SESSION['user_id'];
        }
        if(!empty($res)){
            //To Do Later
            $this->setPoints($res['numberOfBottles'],$_SESSION['user_id']);
        }
        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . $_SESSION['user_username'] . ' add bottles' . "\n";
        $this->DBManager->watch_action_log('action.log', $write);
        return $res;
    }

    public function addCodeBar($data)
    {
        $code_bar['Code'] = $data['barCode'];
        $code_bar['nb_bouteille'] = $data['numberOfBottles'];
        $code_bar['user_id'] = $data['user_id'];
        $this->DBManager->insert('code_barre', $code_bar);
        $this->setBottlesNumber($code_bar['nb_bouteille'], $code_bar['user_id']);
    }

    public function barCode()
    {
        $characters = '0123456789';
        $randstring = '';
        for ($i = 0; $i < 6; $i++) {
            $randstring .= $characters[mt_rand(0, 9)];
        }
        return $randstring;
    }

    public function setBottlesNumber($number, $user_id)
    {
        $user = $this->getUserById($user_id);
        $bottlesNumber = (int)$user["bottlesNumber"] + $number;
        $this->DBManager->findOneSecure("UPDATE users SET bottlesNumber = :bottlesNumber WHERE id=:user_id",
            [
                "user_id" => $user_id,
                "bottlesNumber" => $bottlesNumber
            ]
        );
        if ($bottlesNumber >= 10 && $bottlesNumber <= 15) {
            $this->setLevel(2, $user_id);
        } elseif ($bottlesNumber > 15 && $bottlesNumber <= 30) {
            $this->setLevel(3, $user_id);
        } elseif ($bottlesNumber > 30 && $bottlesNumber < 50) {
            $this->setLevel(4, $user_id);
        } else {
            $this->setLevel(5, $user_id);
        }
        //TODO: revoir les niveau par rapport au bouteilles déposer, faire un resset des level chaque mois
    }

    public function getBottlesNumber($user_id)
    {
        $user = $this->getUserById($user_id);
        return $user['bottlesNumber'];
    }

    public function getLevel($user_id)
    {
        $user = $this->getUserById($user_id);
        return $user['level'];
    }

    public function setLevel($level, $user_id)
    {
        $level = (int)$level;
        $this->DBManager->findOneSecure("UPDATE users SET level = :level WHERE id=:user_id",
            [
                "user_id" => $user_id,
                "level" => $level
            ]
        );
    }
    
    public function getOffers(){
        return $this->DBManager->findAllSecure("SELECT * FROM offres_catalogue");
    }

    public function setPoints($point,$user_id){
        $user = $this->getUserById($user_id);
        $points = (int)$user["points"] + $point;
        $this->DBManager->findOneSecure("UPDATE users SET points = :points WHERE id=:user_id",
            [
                "user_id" => $user_id,
                "points" => $points
            ]
        );
    }
    public function recycledObjects()
    {
        $res = 0;
        $data = $this->DBManager->findAllSecure("SELECT bottlesNumber FROM users ");
        foreach ($data as $collected) {
            $res += (int)$collected["bottlesNumber"];
        }
        return $res;
    }

    public function checkCatalogue($data){
        $errors = array();
        $res = array();
        $isFormGood = true;

        if (!isset($data['partner']) || empty($data['partner'])) {
            $errors['partner'] = 'Veuillez remplir le champs partenaire';
            $isFormGood = false;
        }

        if(!isset($data['secteur']) || empty($data['secteur'])){
            $errors['secteur'] = "Veiller remplir le secteur ";
            $isFormGood = false;
        }
        if(!isset($data['reduction']) || empty($data['reduction'])){
            $errors['reduction'] = "Veiller mettre la reduction";
            $isFormGood = false;
        }
        if(!isset($data['cout']) || !is_numeric($data['cout'])){
            $errors['cout'] = "Veiller mettre le coût";
            $isFormGood = false;
        }
        $res['isFormGood'] = $isFormGood;
        $res['errors'] = $errors;
        $date = $this->DBManager->take_date();
        $write = $date . ' -- ' . $_SESSION['user_username'] . ' new catalog send ' . $data['partner'] . ' '
                                . $data['secteur'] . ' ' . $data['reduction']
                                . ' ' . $data['cout'] . "\n";
        $this->DBManager->watch_action_log('admin.log', $write);
        return $res;
    }
    public function addCatalogue($data){
        $catalogue['Partenaire'] = $data['partner'];
        $catalogue['Secteur'] = $data['secteur'];
        $catalogue['Reduction'] = $data['reduction'];
        $catalogue['Cout'] = (int)$data['cout'];
        $this->DBManager->insert('offres_catalogue', $catalogue);
    }
}
