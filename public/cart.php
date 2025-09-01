<?php

// ... khởi tạo $db
$ctrl = $_GET['controller'] ?? 'product';
$action = $_GET['action'] ?? 'index';
if ($ctrl === 'cart') {
  $c = new CartController();
  if (method_exists($c, $action)) {
    $c->$action();
  } else {
    header('HTTP/1.0 404 Not Found');
  }
} // ... các controller khác
$controllerName = $_GET['controller'] ?? 'client';
$action = $_GET['action'] ?? 'index';

$controllerClass = $controllerName . 'Controller';
require_once "./../controllers/{$controllerClass}.php";
$controller = new $controllerClass();
$controller->$action();
