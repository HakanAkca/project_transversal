<?php

require_once('model/user.php');

function login_action()
{
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (user_check_login($_POST))
        {
            user_login($_POST['username']);
            header('Location: ?action=home');
            exit(0);
        }
        else {
            $error = "Invalid username or password";
            $username = $_POST['username'];
            $date = give_me_date(); 
            $actions = $date . ' -- ' .$username . ' could not log.' ."\n"; 
            watch_action_log('security.log',$actions);
        }
    }
    require('views/login.php');
}

function logout_action()
{
    session_destroy();
    header('Location: ?action=login');
    exit(0);
}


function register_action()
{
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (user_check_register($_POST))
        {
            user_register($_POST);
            header('Location: ?action=login');
            exit(0);
        }
        else {
            $error = "Invalid data";
        }    
    }
    require('views/register.php');
}
