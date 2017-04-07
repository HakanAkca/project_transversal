<?php

require_once('model/db.php');

function get_user_by_id($id)
{
    $id = (int)$id;
    $data = find_one("SELECT * FROM users WHERE id = ".$id);
    return $data;
}

function get_user_by_username($username)
{
    $data = find_one_secure("SELECT * FROM users WHERE Pseudo = :username",
                            ['username' => $username]);
    return $data;
}

function user_check_register($data)
{
    if (empty($data['username']) OR empty($data['password'])
        OR empty($data['verifpassword']) OR empty($data['email'])
        OR empty($data['city'])){
        return false;
    }
    if(strlen($data['username'])<6){
        return false;
    }

    $data2 = get_user_by_username($data['username']);
    if ($data2 !== false){
        return false;
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    if(!validatePASSWORD($data['password'])){
        return false;
    }

    if($data['password'] !== $data['verifpassword']){
        return false;
    }

    return true;
}

function validatePASSWORD($PASSWORD){
    $uppercase = preg_match('@[A-Z]@', $PASSWORD);
    $lowercase = preg_match('@[a-z]@', $PASSWORD);
    $number    = preg_match('@[0-9]@', $PASSWORD);

    if(!$uppercase || !$lowercase || !$number || strlen($PASSWORD) < 8) {
        return false;
    }else{
        return true;
    }
}
function user_hash($pass)
{
    $hash = password_hash($pass, PASSWORD_BCRYPT, ['salt' => 'saltysaltysaltysalty!!']);
    return $hash;
}

function user_register($data) {
    $user['username'] = $data['username'];
    $user['email'] = $data['email'];
    $user['city'] = $data['city'];
    $user['password'] = user_hash($data['password']);
    $user['points'] = 0;
    $user['bottlesNumber'] = 0;
    $user['level'] = 1;
    echo "<pre>";
        var_dump($user);
    echo "</pre>";
    db_insert('users', $user);
}

function user_check_login($data)
{
    if (empty($data['username']) OR empty($data['password'])
    OR empty($data['verifpassword']) OR empty($data['city']))
        return false;
    $user = get_user_by_username($data['username']);
    if ($user === false)
        return false;
    $hash = user_hash($data['password']);
    if ($hash !== $user['password'])
    {
        return false;
    }
    return true;
}

function user_login($username)
{
    $data = get_user_by_username($username);
    if ($data === false)
        return false;
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['user_username'] = $data['username'];
    return true;

}
?>