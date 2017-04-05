<?php

require('config/config.php');

class DBManager
{
    private $dbh;
    
    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new DBManager();
        return self::$instance;
    }
    
    private function __construct()
    {
        $this->dbh = null;
    }
    
    private function connectToDb()
    {
        $dsn = 'mysql:dbname=transversal;host=localhost';
        $user = 'root';
        $password = '';
        
        try {
            $dbh = new PDO($dsn, $user, $password);
            echo "co ok";
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        
        return $dbh;
    }
    
    protected function getDbh()
    {
        if ($this->dbh === null)
            $this->dbh = $this->connectToDb();
        return $this->dbh;
    }
    
    public function insert($table)
    {
        $dbh = $this->getDbh();/*
        $query = 'INSERT INTO `' . $table . '` VALUES ("",';
        $first = true;
        foreach ($_POST AS $k => $value)
        {
            if (!$first)
                $query .= ', ';
            else
                $first = false;
            $query .= ':'.$k;
        }
        $query .= ')';
        $sth = $dbh->prepare($query);
        $sth->execute($_POST);
        return true;*/
        $query = "INSERT INTO `users` (`Pseudo`, `Email`, `City`, `Password`, `Points`, `BottlesNumber`, `Level`) VALUES (:username, :password, :email, :a, :b, :c, :d);";
            $sth = $dbh->prepare($query);
        $sth->execute([
                        'username' => $_POST['pseudo'],
                        'password' => $_POST['password'],
                        'email' => $_POST['email'],
                        'a' => $_POST['pseudo'],
                        'b' => $_POST['password'],
                        'c' => $_POST['email'],
                        'd' => $_POST['email'],
                    ]);
    }
    
    function findOne($query)
    {
        $dbh = $this->getDbh();
        $_POST = $dbh->query($query, PDO::FETCH_ASSOC);
        $result = $_POST->fetch();
        return $result;
    }
    
    function findOneSecure($query)
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($_POST);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    function findAll($query)
    {
        $dbh = $this->getDbh();
        $_POST = $dbh->query($query, PDO::FETCH_ASSOC);
        $result = $_POST->fetchAll();
        return $result;
    }
    
    function findAllSecure($query)
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($_POST);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}