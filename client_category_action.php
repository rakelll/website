<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

function add_category($dbh, $new_category)
{
  $seq_cuq = $dbh->prepare("SELECT Client_Category_Sequence FROM Sequence");
  $seq_cuq->execute();
  $client_category_seq = $seq_cuq->fetch(PDO::FETCH_ASSOC)["Client_Category_Sequence"];

  $new_client_category_seq = $client_category_seq + 1;
  $seq_stmt = $dbh->prepare("UPDATE Sequence SET Client_Category_Sequence=:new_seq");
  $seq_stmt->bindValue(':new_seq', $new_client_category_seq, PDO::PARAM_INT);
  $seq_stmt->execute();

  $stmt = $dbh->prepare('INSERT INTO `Client_Category` (`Client_Category_Id`, `Client_Category`) VALUES (:client_category_seq, :new_category)');

  $stmt->bindValue(':new_category', $new_category, PDO::PARAM_STR);
  $stmt->bindValue(':client_category_seq', $client_category_seq, PDO::PARAM_STR);
  $stmt->execute();

  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function change_category($dbh, $category_id, $category_name)
{
  $cuq = $dbh->prepare("SELECT Client_Category FROM Client_Category WHERE Client_Category_Id = :category_id");
  $cuq->bindValue(":category_id", $category_id, PDO::PARAM_INT);
  $cuq->execute();

  $last_category = $cuq->fetch(PDO::FETCH_ASSOC)["Client_Category"];

  $sql = "UPDATE `Client_Category` SET `Client_Category` = :category_change WHERE `Client_Category`.`Client_Category_Id` = :category_id;";
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':category_change', $category_name, PDO::PARAM_STR);
  $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
  $stmt->execute();

  $response = [
  "category_name" => $category_name,
  "last_category" => $last_category,
  ];

  echo json_encode($response);
}

function delete_category($dbh, $category_id)
{
  $cuq = $dbh->prepare('SELECT Client_Category FROM `Client_Category` WHERE `Client_Category`.`Client_Category_Id` = :category_id;');

  $cuq->bindValue(':category_id', $category_id, PDO::PARAM_INT);
  $cuq->execute();

  $category_name = $cuq->fetch(PDO::FETCH_ASSOC)["Client_Category"];


  $response = ["category_name" => $category_name];


  $stmt = $dbh->prepare('DELETE FROM `Client_Category` WHERE `Client_Category`.`Client_Category_Id` = :category_id');

  $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
  $stmt->execute();

  echo json_encode($response);
}

try {
  if (isset($_POST["clientCategory"])) {
    add_category($dbh, $_POST["clientCategory"]);
  } elseif (isset($_POST["category_id"]) && isset($_POST["category_change"])) {
    change_category($dbh, $_POST["category_id"], $_POST["category_change"]);
  } elseif (isset($_POST["category_id"])) {
    delete_category($dbh, $_POST["category_id"]);
  }

} catch (PDOException $e) {
  echo $e->getMessage();
}
