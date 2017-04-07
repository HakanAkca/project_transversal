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
    $data = find_one_secure("SELECT * FROM users WHERE username = :username",
                            ['username' => $username]);
    return $data;
}

function all_files(){
 
        $data = find_all_secure("SELECT * FROM files ORDER BY DATE DESC",
                            []);
        return $data;
}
function file_extension($file_name){
        return strrchr($file_name, '.');
}

function user_check_register($data)
{
    if (empty($data['username']) OR empty($data['password']) OR 
        empty($data['repeat_password']) OR empty($data['secret_ask'])){
        return false;
    }
        
    $data2 = get_user_by_username($data['username']);
    if ($data2 !== false){
        return false;
    }
    // TODO : Check valid username, password, secret ask
    if(strlen($data['username'])<6){
        return false;
    }
    if($data['password'] !== $data['repeat_password']){
        return false;
    }
    
    $regexp = "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/";    
    if(!preg_match($regexp, $data['password']))
        return false;
    return true;
}

function user_hash($pass)
{
    $hash = password_hash($pass, PASSWORD_BCRYPT, ['salt' => 'saltysaltysaltysalty!!']);
    return $hash;
}

function user_register($data)
{
    $user['username'] = $data['username'];
    $user['password'] = user_hash($data['password']);
    $user['firstname'] = $data['firstname'];
    $user['lastname'] = $data['lastname'];
    $user['secret_ask'] = $data['secret_ask'];
    $user['secret_answer'] = $data['secret_answer'];
    mkdir('uploads/'.$user["username"]);
    db_insert('users', $user);
    $date = give_me_date(); 
    $actions = $date . ' -- ' .$user['username'] . ' has just registered.' ."\n"; 
    watch_action_log('access.log',$actions);
}

function user_check_login($data)
{
    if (empty($data['username']) OR empty($data['password']))
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
    $date = give_me_date(); 
    $actions = $date . ' -- ' .$username . ' has just log.' ."\n"; 
    watch_action_log('access.log',$actions);
    return true;

}
?>