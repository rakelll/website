<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

function add_Request_Type($dbh, $new_Request_Type)
{
  $seq_luq = $dbh->prepare("SELECT Request_Type_Sequence FROM Sequence");
  $seq_luq->execute();
  $Request_Type_seq = $seq_luq->fetch(PDO::FETCH_ASSOC)["Request_Type_Sequence"];

  $new_Request_Type_seq = $Request_Type_seq + 1;
  $seq_stmt = $dbh->prepare("UPDATE Sequence SET Request_Type_Sequence=:new_seq");
  $seq_stmt->bindValue(':new_seq', $new_Request_Type_seq, PDO::PARAM_INT);
  $seq_stmt->execute();

  $stmt = $dbh->prepare('INSERT INTO `Request_Type` (`Request_Type_Id`, `Request_Type`) VALUES (:Request_Type_seq, :new_Request_Type)');

  $stmt->bindValue(':new_Request_Type', $new_Request_Type, PDO::PARAM_STR);
  $stmt->bindValue(':Request_Type_seq', $Request_Type_seq, PDO::PARAM_STR);
  $stmt->execute();

  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function change_Request_Type($dbh, $Request_Type_id, $request_type)
{
  $luq = $dbh->prepare("SELECT Request_Type FROM Request_Type WHERE Request_Type_Id = :Request_Type_id");
  $luq->bindValue(":Request_Type_id", $Request_Type_id, PDO::PARAM_INT);
  $luq->execute();

  $last_Request_Type = $luq->fetch(PDO::FETCH_ASSOC)["Request_Type"];

  $sql = "UPDATE `Request_Type` SET `Request_Type` = :Request_Type_change WHERE `Request_Type`.`Request_Type_Id` = :Request_Type_id;";
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':Request_Type_change', $request_type, PDO::PARAM_STR);
  $stmt->bindValue(':Request_Type_id', $Request_Type_id, PDO::PARAM_INT);
  $stmt->execute();

  $response = [
  "request_type" => $request_type,
  "last_Request_Type" => $last_Request_Type,
  ];

  echo json_encode($response);
}

function delete_Request_Type($dbh, $Request_Type_id)
{
  $luq = $dbh->prepare('SELECT Request_Type FROM `Request_Type` WHERE `Request_Type`.`Request_Type_Id` = :Request_Type_id;');

  $luq->bindValue(':Request_Type_id', $Request_Type_id, PDO::PARAM_INT);
  $luq->execute();

  $request_type = $luq->fetch(PDO::FETCH_ASSOC)["Request_Type"];


  $response = ["request_type" => $request_type];


  $stmt = $dbh->prepare('DELETE FROM `Request_Type` WHERE `Request_Type`.`Request_Type_Id` = :Request_Type_id');

  $stmt->bindValue(':Request_Type_id', $Request_Type_id, PDO::PARAM_INT);
  $stmt->execute();

  echo json_encode($response);
}

try {
  if (isset($_POST["RequestType"])) {
    add_Request_Type($dbh, $_POST["RequestType"]);
  } elseif (isset($_POST["Request_Type_id"]) && isset($_POST["Request_Type_change"])) {
    change_Request_Type($dbh, $_POST["Request_Type_id"], $_POST["Request_Type_change"]);
  } elseif (isset($_POST["Request_Type_id"])) {
    delete_Request_Type($dbh, $_POST["Request_Type_id"]);
  }

} catch (PDOException $e) {
  echo $e->getMessage();
}
