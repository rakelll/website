<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

function add_caza($dbh, $new_caza, $governorate_id)
{
  $seq_qfdb = $dbh->prepare("SELECT Caza_Sequence FROM Sequence");
  $seq_qfdb->execute();
  $caza_seq = $seq_qfdb->fetch(PDO::FETCH_ASSOC)["Caza_Sequence"];

  $new_caza_seq = $caza_seq + 1;
  $seq_stmt = $dbh->prepare("UPDATE Sequence SET Caza_Sequence=:new_seq");
  $seq_stmt->bindValue(':new_seq', $new_caza_seq, PDO::PARAM_INT);
  $seq_stmt->execute();

  $stmt = $dbh->prepare('INSERT INTO `Caza` (`Caza_Id`, `Caza_Name`, `Governorate_Id`) VALUES (:caza_seq, :new_caza, :governorate_id)');

  $stmt->bindValue(':caza_seq', $caza_seq, PDO::PARAM_STR);
  $stmt->bindValue(':new_caza', $new_caza, PDO::PARAM_STR);
  $stmt->bindValue(':governorate_id', $governorate_id, PDO::PARAM_STR);
  $stmt->execute();

  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function change_caza($dbh, $caza_id, $caza_name)
{
  $qfdb = $dbh->prepare("SELECT Caza_Name FROM Caza WHERE Caza_Id = :caza_id");
  $qfdb->bindValue(":caza_id", $caza_id, PDO::PARAM_INT);
  $qfdb->execute();

  $last_caza = $qfdb->fetch(PDO::FETCH_ASSOC)["Caza_Name"];

  $sql = "UPDATE `Caza` SET `Caza_Name` = :caza_name WHERE `Caza`.`Caza_Id` = :caza_id;";
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':caza_name', $caza_name, PDO::PARAM_STR);
  $stmt->bindValue(':caza_id', $caza_id, PDO::PARAM_INT);
  $stmt->execute();

  $response = [
  "caza_name" => $caza_name,
  "last_caza" => $last_caza,
  ];

  echo json_encode($response);
}

function delete_caza($dbh, $caza_id)
{
  $qfdb = $dbh->prepare('SELECT Caza_Name FROM `Caza` WHERE `Caza`.`Caza_Id` = :caza_id;');

  $qfdb->bindValue(':caza_id', $caza_id, PDO::PARAM_INT);
  $qfdb->execute();

  $caza_name = $qfdb->fetch(PDO::FETCH_ASSOC)["Caza_Name"];


  $response = ["caza_name" => $caza_name];


  $stmt = $dbh->prepare('DELETE FROM `Caza` WHERE `Caza`.`Caza_Id` = :caza_id');

  $stmt->bindValue(':caza_id', $caza_id, PDO::PARAM_INT);
  $stmt->execute();

  echo json_encode($response);
}

try {
  if (isset($_POST["formCaza"]) && isset($_POST["formGovernorate"])) {
    add_caza($dbh, $_POST["formCaza"], $_POST["formGovernorate"]);
  } elseif (isset($_POST["caza_id"]) && isset($_POST["caza_name"])) {
    change_caza($dbh, $_POST["caza_id"], $_POST["caza_name"]);
  } elseif (isset($_POST["caza_id"])) {
    delete_caza($dbh, $_POST["caza_id"]);
  }

} catch (PDOException $e) {
  echo $e->getMessage();
}
