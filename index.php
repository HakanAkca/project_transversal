<?php

session_start();

require('config/config.php');
require('Router/Router.php');

$router = new Router($routes);
if (!empty($_GET['action']))
    $router->callAction($_GET['action']);
else
    $router->callAction('home');
