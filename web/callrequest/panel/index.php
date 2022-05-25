<?php

session_start();

$is_user_logged_in = isset($_SESSION['user_logged_in']);
$redirect = 'login.php';
if ($is_user_logged_in) $redirect = 'settings.php';
header("Location: $redirect");
exit(0);