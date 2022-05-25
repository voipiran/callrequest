<?php

defined("INTERNAL_ACCESS") or die('Not Wise!');

/**
 * Database credentials can be set from here
 */
 
 $config = parse_ini_file('database.ini' , true) ;
$RetryTime = $config['RetryTime'] ;


$server_name = $config['server_name'] ;
$db_port = $config['db_port'] ;
$db_name = $config['db_name'] ;
$db_user = $config['db_user'] ;
$db_password = $config['db_password'] ;
$db_settings_table = $config['db_settings_table'] ;
$db_prompts_table = $config['db_prompts_table'] ;

/*

$server_name = 'localhost';
$db_port = 3306;
$db_name = 'voipiran_webcallback';
$db_user = 'voipiran_webcallback';
$db_password = 'kalagh!!!!!!!!';
$db_settings_table = 'settings';
$db_prompts_table = 'prompts';
*/

/**
 * There is nothing more you can do!
 */

$conn = null;
try {
    $conn = new PDO("mysql:host=$server_name;dbname=$db_name;port=$db_port;charset=utf8", $db_user, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $error .= "اتصال به پایگاه داده به دلیل " . $e->getMessage() . " با شکست روبرو شد.";
}

