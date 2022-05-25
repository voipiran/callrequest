<?php

define("INTERNAL_ACCESS",true);
$error = '';

session_start();

if(isset($_SESSION['webphone_user_logged_in'])){
    header('Location: settings.php');
    exit(0);
}
elseif (isset($_POST['username'], $_POST['password'], $_POST['jameasal'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $jameasal = $_POST['jameasal'];
    if (strlen($jameasal) === 0) {
        include_once 'inc/users.php';
        foreach ($users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                session_start();
                $_SESSION['webphone_user_logged_in'] = $user['username'];
                header('Location: settings.php');
                exit(0);
            }
        }
        $error = 'اطلاعات وارد شده معتبر نیست!';
    }
}
include_once 'inc/template/login-template.php';

