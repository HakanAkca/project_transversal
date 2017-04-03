<?php

$dbh = null;

class Database {

    public function connectToDb(){
        global $db_config;
        $dsn = 'mysql:dbname='.$db_config['name'].';host='.$db_config['host'];
        $user = $db_config['user'];
        $password = $db_config['pass'];
        
        try {
            $dbh = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        
        return $dbh;
    }

    public function getDbh(){
        global $dbh;
        $dbh = New Database();
        if ($dbh === null)
            $dbh->connectToDb();
        return $dbh;
    }
}