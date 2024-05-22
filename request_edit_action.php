<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

try {
  $request_id = $_POST["requestReference"];
  $request_memo = $_POST["requestMemo"];
  $request_type = $_POST["requestType"];
  $request_status = $_POST["requestStatus"];

  $sql_stmt = "UPDATE Request SET `Request_Memo` = :request_memo, `Request_Type_Id` = :request_type, `Status_Id` = :request_status";
  if ($_POST["requestDeliveryDate"]) {
    $sql_stmt = $sql_stmt . ", `Request_DeliveryDate` = :request_ddate";
  }

  $sql_stmt = $sql_stmt . " WHERE Request.Request_Id = :request_id";
  $stmt = $dbh->prepare($sql_stmt);

  if ($_POST["requestDeliveryDate"]) {
    $stmt->bindValue(':request_ddate', $_POST["requestDeliveryDate"], PDO::PARAM_STR);
  }

  $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);
  $stmt->bindValue(':request_memo', $request_memo, PDO::PARAM_STR);
  $stmt->bindValue(':request_type', $request_type, PDO::PARAM_INT);
  $stmt->bindValue(':request_status', $request_status, PDO::PARAM_INT);

  $stmt->execute();

  header('Location: ' . $_SERVER['HTTP_REFERER']);
} catch (PDOException $e) {
  echo $e->getMessage();
}
