<?php

session_start();

unset($_SESSION['webphone_user_logged_in']);

header('Location: login.php');