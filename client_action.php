//<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

function upload_logo()
{
  $uploaddir = 'images/client_logos/';
  $uploadfile = $uploaddir . basename($_FILES['clientLogo']['name']);

  if (move_uploaded_file($_FILES['clientLogo']['tmp_name'], $uploadfile)) {
    return $uploadfile;
  } else {
    return 'images/client_logos/default.svg';
  }
}

function add_client($dbh)
{
  $logo_path = upload_logo();

  $seq_cuq = $dbh->prepare("SELECT Client_Sequence FROM Sequence");
  $seq_cuq->execute();
  $client_seq = $seq_cuq->fetch(PDO::FETCH_ASSOC)["Client_Sequence"];

  $new_client_seq = $client_seq + 1;
  $seq_stmt = $dbh->prepare("UPDATE Sequence SET Client_Sequence=:new_seq");
  $seq_stmt->bindValue(':new_seq', $new_client_seq, PDO::PARAM_INT);
  $seq_stmt->execute();

  $client_type_id = $_POST["clientType"];
  $client_fullname = $_POST["clientFullName"];
  $client_email = $_POST["clientEmail"];
  $client_phone_number = $_POST["clientPhoneNumber"];
  $client_password = password_hash($_POST["clientPassword"], PASSWORD_DEFAULT);
  $client_address = $_POST["clientAddress"];
  $client_category = $_POST["clientCategory"];
  $client_governorate = $_POST["clientGovernorate"];
  $client_caza = $_POST["clientCaza"];

  $sql_stmt = "INSERT INTO `Client` (`Client_Id`, `Client_FullName`, `Client_Email`, `Client_Phone`, `Client_Password`,`Client_Address`,  `Client_Logo`,`Client_Category_Id`, `Governorate_Id`, `Caza_Id`, `Client_Type_Id`) VALUES (:client_id, :client_fullname, :client_email, :client_phone_number, :client_password, :client_address, :client_logo, :client_category, :client_governorate, :client_caza, :client_type_id);";
  $stmt = $dbh->prepare($sql_stmt);

  $stmt->bindValue(':client_id', $client_seq, PDO::PARAM_INT);
  $stmt->bindValue(':client_fullname', $client_fullname, PDO::PARAM_STR);
  $stmt->bindValue(':client_email', $client_email, PDO::PARAM_STR);
  $stmt->bindValue(':client_phone_number', $client_phone_number, PDO::PARAM_STR);
  $stmt->bindValue(':client_password', $client_password, PDO::PARAM_STR);
  $stmt->bindValue(':client_address', $client_address, PDO::PARAM_STR);
  $stmt->bindValue(':client_logo', $logo_path, PDO::PARAM_STR);
  $stmt->bindValue(':client_category', $client_category, PDO::PARAM_INT);
  $stmt->bindValue(':client_governorate', $client_governorate, PDO::PARAM_INT);
  $stmt->bindValue(':client_caza', $client_caza, PDO::PARAM_INT);
  $stmt->bindValue(':client_type_id', $client_type_id, PDO::PARAM_INT);

  $stmt->execute();

  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function delete_client($dbh, $client_id)
{
  $client_querry = $dbh->prepare('SELECT Client_FullName FROM `Client` WHERE `Client`.`Client_Id` = :client_id;');

  $client_querry->bindValue(':client_id', $client_id, PDO::PARAM_INT);
  $client_querry->execute();

  $client_name = $client_querry->fetch(PDO::FETCH_ASSOC)["Client_FullName"];


  $response = ["client_name" => $client_name];


  $stmt = $dbh->prepare('DELETE FROM `Client` WHERE `Client`.`Client_Id` = :client_id');

  $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);
  $stmt->execute();

  echo json_encode($response);
}

try {
  if (isset($_POST["clientFullName"])) {
    add_client($dbh);
  } else if (isset($_POST["client_id"])) {
    delete_client($dbh, $_POST["client_id"]);
  }
} catch (PDOException $e) {
  echo $e->getMessage();
}
