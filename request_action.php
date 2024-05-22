<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

function upload_file()
{
  $uploaddir = 'file_upload/';
  $uploadfile = $uploaddir . basename($_FILES['requestAttachment']['name']);

  if (move_uploaded_file($_FILES['requestAttachment']['tmp_name'], $uploadfile)) {
    return $uploadfile;
  } else {
    return '';
  }
}

function add_request($dbh, $request_data) {
    $file_path = upload_file();

    $seq_qfdb = $dbh->prepare("SELECT Request_Sequence FROM Sequence");
    $seq_qfdb->execute();
    $request_seq = $seq_qfdb->fetch(PDO::FETCH_ASSOC)["Request_Sequence"];

    $new_request_seq = $request_seq + 1;
    $seq_stmt = $dbh->prepare("UPDATE Sequence SET Request_Sequence=:new_seq");
    $seq_stmt->bindValue(':new_seq', $new_request_seq, PDO::PARAM_INT);
    $seq_stmt->execute();

    $stmt = $dbh->prepare('INSERT INTO `Request` (`Request_Id`, `Request_Memo`, `Request_Date`, `Request_Attachments`, `Client_Id`, `Status_Id`) VALUES (:request_seq, :request_memo, :request_date, :request_attachments, :client_id, 1)');

    $stmt->bindValue(':request_seq', $new_request_seq, PDO::PARAM_INT);
    $stmt->bindValue(':request_memo', $request_data["requestMemo"]);
    $stmt->bindValue(':request_date', $request_data["requestDate"]);
    $stmt->bindValue(':request_attachments', $file_path);
    $stmt->bindValue(':client_id', $request_data["clientId"]);

    $stmt->execute();

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
function change_request($dbh, $request_id, $request_data)
{
  $qfdb = $dbh->prepare("SELECT * FROM request WHERE Request_Id = :request_id");
  $qfdb->bindValue(":request_id", $request_id, PDO::PARAM_INT);
  $qfdb->execute();

  $last_request = $qfdb->fetch(PDO::FETCH_ASSOC);

  $sql = "UPDATE `request` SET `Request_Memo` = :request_memo, `Request_Reference` = :request_reference, `Request_Date` = :request_date, `Request_Attachments` = :request_attachments, `Request_DelivaryDate` = :request_delivery_date, `Request_ApprovedDate` = :request_approved_date, `Client_Id` = :client_id, `Status_Id` = :status_id, `Request_Type_Id` = :request_type_id, `User_Id` = :user_id WHERE `request`.`Request_Id` = :request_id;";
  $stmt = $dbh->prepare($sql);

  $stmt->bindValue(':request_memo', $request_data["RequestMemo"]);
  $stmt->bindValue(':request_reference', $request_data["RequestReference"]);
  $stmt->bindValue(':request_date', $request_data["RequestDate"]);
  $stmt->bindValue(':request_attachments', $request_data["RequestAttachment"]);
  $stmt->bindValue(':request_delivery_date', $request_data["RequestDeliveryDate"]);
  $stmt->bindValue(':request_approved_date', $request_data["RequestApprovedDate"]);
  $stmt->bindValue(':client_id', $request_data["clientfullname"]);
  $stmt->bindValue(':status_id', $request_data["statusId"]);
  $stmt->bindValue(':request_type_id', $request_data["requestType"]);
  $stmt->bindValue(':user_id', $request_data["userId"]);
  $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);
  $stmt->execute();

  $response = [
    "request_data" => $request_data,
    "last_request" => $last_request,
  ];

  echo json_encode($response);
}

function delete_request($dbh, $request_id)
{
  $qfdb = $dbh->prepare('SELECT * FROM `Request` WHERE `Request`.`Request_Id` = :request_id;');
  $qfdb->bindValue(':request_id', $request_id, PDO::PARAM_INT);
  $qfdb->execute();

  $request_data = $qfdb->fetch(PDO::FETCH_ASSOC);

  $response = [
    "request_data" => $request_data["Request_Id"],
  ];

  $stmt = $dbh->prepare('DELETE FROM `Request` WHERE `Request`.`Request_Id` = :request_id');
  $stmt->bindValue(':request_id', $request_id, PDO::PARAM_INT);
  $stmt->execute();

  echo json_encode($response);
}

try {
  if ($_POST["clientId"] && $_POST["requestMemo"]) {
    add_request($dbh, $_POST);
  } else {
    delete_request($dbh, $_POST["request_Id"]);
  }
} catch (PDOException $e) {
    echo $e->getMessage();
}
