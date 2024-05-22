<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "taskManagement";

try {
  $dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Unable to connect: " . $e->getMessage());
}

if (session_status() !== PHP_SESSION_ACTIVE) {
 session_start();
}
?>