<?php

namespace application\core;

class Router {
    protected $routes = [];
    protected $params = [];

    public function __construct(){
        $arr = require 'application/config/routes.php';
        foreach ($arr as $key => $value){
            $this->add($key, $value);
        }
    }

    public function add($route, $params){
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    public function match(){
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params){
            if (preg_match($route, $url, $matches)){
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function run(){
        if($this->match()){
            $controller = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller.php';
            if (class_exists($controller)){
                echo 'OK';
            } else {
                echo 'Не найден: ' . $controller;
            }
        } else {
            echo 'Маршрут не найден';
        }
    }
}