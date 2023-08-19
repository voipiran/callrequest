<?php 
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */
require "db.php";

$connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$sldata = "SELECT app FROM licences WHERE app = 'callrequest' ";
$statement = $connection->query($sldata);
$publishers = $statement->fetchAll(PDO::FETCH_ASSOC);

/* if license  not found */
if(!$publishers) {
    die("license not found");
}

?>