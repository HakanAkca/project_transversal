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
        $data = $this->DBManager->findOne("SELECT * FROM users WHERE id = ".$id);
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
        if (empty($data['username']) OR empty($data['email']) OR empty($data['password']))
            return false;
        $data = $this->getUserByUsername($data['username']);
        if ($data !== false)
            return false;
        // TODO : Check valid email
        return true;
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
        if ($user === false)
            return false;
        $hash = $this->userHash($data['password']);
        if ($hash !== $user['password'])
        {
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
        return true;
    }

    public function pushBottles($data){
        $res = array();
        $numberOfBottles = $data["numberOfBottles"];
        if(is_numeric($numberOfBottles)){
            $number = (int)$numberOfBottles;
            $res['numberOfBottles'] = $number;
            $res['barCode'] = $this->barCode();
            $res['user_id'] = (int)$_SESSION['user_id'];
        }
        return $res;
    }

    public function addCodeBar($data){
        $code_bar['Code'] = $data['barCode'];
        $code_bar['nb_bouteille'] = $data['numberOfBottles'];
        $code_bar['user_id'] = $data['user_id'];
        $this->DBManager->insert('code_barre', $code_bar);
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
}
