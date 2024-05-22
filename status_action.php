<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

function add_status($dbh, $new_status)
{
  $seq_kuq = $dbh->prepare("SELECT Status_Sequence FROM Sequence");
  $seq_kuq->execute();
  $Status_seq = $seq_kuq->fetch(PDO::FETCH_ASSOC)["Status_Sequence"];

  $new_Status_seq = $Status_seq + 1;
  $seq_stmt = $dbh->prepare("UPDATE Sequence SET Status_Sequence=:new_seq");
  $seq_stmt->bindValue(':new_seq', $new_Status_seq, PDO::PARAM_INT);
  $seq_stmt->execute();

  $stmt = $dbh->prepare('INSERT INTO `Status` (`Status_Id`, `status_name`) VALUES (:Status_seq, :new_status)');

  $stmt->bindValue(':new_status', $new_status, PDO::PARAM_STR);
  $stmt->bindValue(':Status_seq', $Status_seq, PDO::PARAM_STR);
  $stmt->execute();

  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function change_status($dbh, $status_id, $status_name)
{
  $kuq = $dbh->prepare("SELECT Status_Name FROM Status WHERE Status_Id = :status_id");
  $kuq->bindValue(":status_id", $status_id, PDO::PARAM_INT);
  $kuq->execute();

  $last_status = $kuq->fetch(PDO::FETCH_ASSOC)["Status_Name"];

  $sql = "UPDATE `Status` SET `Status_Name` = :status_name WHERE `Status`.`Status_Id` = :status_id;";
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':status_name', $status_name, PDO::PARAM_STR);
  $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);
  $stmt->execute();

  $response = [
  "status_name" => $status_name,
  "last_status" => $last_status,
  ];

  echo json_encode($response);
}

function delete_status($dbh, $status_id)
{
  $kuq = $dbh->prepare('SELECT status_name FROM `Status` WHERE `Status`.`Status_Id` = :status_id;');

  $kuq->bindValue(':status_id', $status_id, PDO::PARAM_INT);
  $kuq->execute();

  $status_name = $kuq->fetch(PDO::FETCH_ASSOC)["status_name"];


  $response = ["status_name" => $status_name];


  $stmt = $dbh->prepare('DELETE FROM `Status` WHERE `Status`.`Status_Id` = :status_id');

  $stmt->bindValue(':status_id', $status_id, PDO::PARAM_INT);
  $stmt->execute();

  echo json_encode($response);
}

try {
  if (isset($_POST["formRequestStatus"])) {
    add_status($dbh, $_POST["formRequestStatus"]);
  } elseif (isset($_POST["status_id"]) && isset($_POST["status_change"])) {
    change_status($dbh, $_POST["status_id"], $_POST["status_change"]);
  } elseif (isset($_POST["status_id"])) {
    delete_status($dbh, $_POST["status_id"]);
  }

} catch (PDOException $e) {
  echo $e->getMessage();
}
