<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
}

require_once("connection/connectionConfig.php");

$clients_query = $dbh->prepare("SELECT * FROM `Client`");
$clients_query->execute();
