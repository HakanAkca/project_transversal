<?php

session_start();

require('config/config.php');

if (empty($_GET['action']))
    $action = 'home';
else {
    $action = $_GET['action'];
}

if (isset($routes[$action]))
{
    require('controllers/'.$routes[$action].'_controller.php');
    call_user_func($action.'_action');
}
else
    die('Illegal route , action = '.$action);
?>