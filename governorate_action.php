<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

function add_governorate($dbh, $new_governorate)
{
  $seq_qfdb = $dbh->prepare("SELECT Governorate_Sequence FROM Sequence");
  $seq_qfdb->execute();
  $governorate_seq = $seq_qfdb->fetch(PDO::FETCH_ASSOC)["Governorate_Sequence"];

  $new_governorate_seq = $governorate_seq + 1;
  $seq_stmt = $dbh->prepare("UPDATE Sequence SET Governorate_Sequence=:new_seq");
  $seq_stmt->bindValue(':new_seq', $new_governorate_seq, PDO::PARAM_INT);
  $seq_stmt->execute();

  $stmt = $dbh->prepare('INSERT INTO `Governorate` (`Governorate_Id`, `Governorate_Name`) VALUES (:governorate_seq, :new_governorate)');

  $stmt->bindValue(':new_governorate', $new_governorate, PDO::PARAM_STR);
  $stmt->bindValue(':governorate_seq', $governorate_seq, PDO::PARAM_STR);
  $stmt->execute();

  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function change_governorate($dbh, $governorate_id, $governorate_name)
{
  $qfdb = $dbh->prepare("SELECT Governorate_Name FROM Governorate WHERE Governorate_Id = :governorate_id");
  $qfdb->bindValue(":governorate_id", $governorate_id, PDO::PARAM_INT);
  $qfdb->execute();

  $last_governorate = $qfdb->fetch(PDO::FETCH_ASSOC)["Governorate_Name"];

  $sql = "UPDATE `Governorate` SET `Governorate_Name` = :governorate_name WHERE `Governorate`.`Governorate_Id` = :governorate_id;";
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':governorate_name', $governorate_name, PDO::PARAM_STR);
  $stmt->bindValue(':governorate_id', $governorate_id, PDO::PARAM_INT);
  $stmt->execute();

  $response = [
  "governorate_name" => $governorate_name,
  "last_governorate" => $last_governorate,
  ];

  echo json_encode($response);
}

function delete_governorate($dbh, $governorate_id)
{
  $qfdb = $dbh->prepare('SELECT Governorate_Name FROM `Governorate` WHERE `Governorate`.`Governorate_Id` = :governorate_id;');

  $qfdb->bindValue(':governorate_id', $governorate_id, PDO::PARAM_INT);
  $qfdb->execute();

  $governorate_name = $qfdb->fetch(PDO::FETCH_ASSOC)["Governorate_Name"];


  $response = ["governorate_name" => $governorate_name];


  $stmt = $dbh->prepare('DELETE FROM `Governorate` WHERE `Governorate`.`Governorate_Id` = :governorate_id');

  $stmt->bindValue(':governorate_id', $governorate_id, PDO::PARAM_INT);
  $stmt->execute();

  echo json_encode($response);
}

try {
  if (isset($_POST["formGovernorate"])) {
    add_governorate($dbh, $_POST["formGovernorate"]);
  } elseif (isset($_POST["governorate_id"]) && isset($_POST["governorate_name"])) {
    change_governorate($dbh, $_POST["governorate_id"], $_POST["governorate_name"]);
  } elseif (isset($_POST["governorate_id"])) {
    delete_governorate($dbh, $_POST["governorate_id"]);
  }

} catch (PDOException $e) {
  echo $e->getMessage();
}
