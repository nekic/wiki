<?php

spl_autoload_register(function ($class_name) {
    require_once '../src/' . $class_name . '.php';
});

$controller = isset($_GET['c'] ) ? $_GET['c'] : '';
$action = isset($_GET['a']) ? $_GET['a'] :'';

$isLogin = false;

if($controller == 'user' && $action =='login') {
    $isLogin = user::login();
    if(!$isLogin) {
        $response = array(
            'status' => 0,
            'info' => '账号或密码错误'
        );

    } else {
        $response = array(
            'status' => 1,
            'info' => '登陆成功'
        );
    }

    echo json_encode($response);
    die;
}

$isLogin = user::checkAuth();
// 检查是否已经登陆

if(!$isLogin) {
    include 'static/login.html';
    die;
}


if(!$controller) {
    $notes = new notes();
    $trees = $notes->lists();
    include 'static/main.html';

} else {
    $controller = new $controller();
    $controller->$action();
}
