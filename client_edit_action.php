<?php
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
    echo "File is valid, and was successfully uploaded.\n";
    exit();
  } else {
    echo "Possible file upload attack!\n";
    exit();
  }

  return $uploadfile;
}

try {
  $client_id = $_POST["clientId"];
  $client_fullname = $_POST["clientFullName"];
  $client_email = $_POST["clientEmail"];
  $client_phone_number = $_POST["clientPhoneNumber"];
  $password_reset = $_POST["passwordResetCheckBox"];
  $client_logo_check_box = $_POST["clientLogoCheckBox"];
  if ($password_reset === "on") {
    $client_password = password_hash($_POST["clientPassword"], PASSWORD_DEFAULT);
    $sql_stmt = "UPDATE `Client` SET `Client_FullName` = :client_fullname, `Client_Email` = :client_email, `Client_Phone` = :client_phone_number, `Client_Password` = :client_password, `Client_Address` = :client_address, `Client_Category_Id` = :client_category, `Governorate_Id` = :client_governorate, `Caza_Id` = :client_caza, `Client_Type_Id` = :client_type_id WHERE `Client_Id` = :client_id";
  } else {
    $sql_stmt = "UPDATE `Client` SET `Client_FullName` = :client_fullname, `Client_Email` = :client_email, `Client_Phone` = :client_phone_number, `Client_Address` = :client_address, `Client_Category_Id` = :client_category, `Governorate_Id` = :client_governorate, `Caza_Id` = :client_caza, `Client_Type_Id` = :client_type_id WHERE `Client_Id` = :client_id";
  }
  $client_address = $_POST["clientAddress"];
  $client_category = $_POST["clientCategory"];
  $client_governorate = $_POST["clientGovernorate"];
  $client_caza = $_POST["clientCaza"];
  $client_type_id = $_POST["clientType"];

  $stmt = $dbh->prepare($sql_stmt);
  $stmt->bindValue(':client_fullname', $client_fullname, PDO::PARAM_STR);
  $stmt->bindValue(':client_email', $client_email, PDO::PARAM_STR);
  $stmt->bindValue(':client_phone_number', $client_phone_number, PDO::PARAM_STR);
  if ($password_reset === "on") {
    $stmt->bindValue(':client_password', $client_password, PDO::PARAM_STR);
  }
  $stmt->bindValue(':client_address', $client_address, PDO::PARAM_STR);
  $stmt->bindValue(':client_category', $client_category, PDO::PARAM_INT);
  $stmt->bindValue(':client_governorate', $client_governorate, PDO::PARAM_INT);
  $stmt->bindValue(':client_caza', $client_caza, PDO::PARAM_INT);
  $stmt->bindValue(':client_type_id', $client_type_id, PDO::PARAM_INT);
  $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);

  $stmt->execute();

  if ($client_logo_check_box === "on") {
    $file_path = upload_logo();

    $stmt = $dbh->prepare("UPDATE Client SET Client_Logo = :file_path WHERE Client_Id = :client_id");
    $stmt->bindValue(':file_path', $file_path, PDO::PARAM_STR);
    $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);
    $stmt->execute();
  }

  header('Location: ' . $_SERVER['HTTP_REFERER']);
} catch (PDOException $e) {
  echo $e->getMessage();
}
