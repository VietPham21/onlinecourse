<?php
session_start();
require_once 'config/Database.php';
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';
$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerPath = "controllers/$controllerClass.php";

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controllerObject = new $controllerClass();
    if (method_exists($controllerObject, $actionName)) {
        $controllerObject->$actionName();
    } else {
        echo "Lỗi 404: Action '$actionName' không tồn tại trong $controllerClass.";
    }
} else {
    echo "Lỗi 404: Controller '$controllerClass' không tìm thấy.";
}
?>
