<?php

require('Controller/DefaultController.php');

class Router
{
    private $routes;

    public function __construct($routes = [])
    {
        $this->routes = $routes;
    }

    public function callAction($route)
    {
        if (isset($this->routes[$route]))
        {
            $parts = explode(':', $this->routes[$route]);
            $controller = $parts[0].'Controller';
            $method = $parts[1].'Action';
            $controller_class = $controller;
            $controller = new $controller_class();
            call_user_func([$controller, $method]);
        }
        else
            die('Illegal route');
    }
}