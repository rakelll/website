<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

$user_query = $dbh->prepare("SELECT * FROM `Client` WHERE Client_Id = :client_id");
$user_query->bindValue(':client_id', $_SESSION["user_id"], PDO::PARAM_INT);

$user_query->execute();

$user = $user_query->fetch(PDO::FETCH_ASSOC);

if($_FILES['csvFile']['name']) {

  $filename = explode(".", $_FILES['csvFile']['name']);
  if($filename[1] == 'csv'){


 $file = fopen($_FILES['csvFile']['tmp_name'],"r");

 while(! feof($file)) {
  $_line = fgetcsv($file);

  if ($_line[0] == "item_code") {
    continue;
  }

  $seq_cuq = $dbh->prepare("SELECT Client_Item_Sequence FROM Sequence");
  $seq_cuq->execute();
  $client_seq = $seq_cuq->fetch(PDO::FETCH_ASSOC)["Client_Item_Sequence"];
  $new_client_item_seq = $client_seq + 1;


  $seq_stmt = $dbh->prepare("UPDATE Sequence SET Client_Item_Sequence=:new_seq");
  $seq_stmt->bindValue(':new_seq', $new_client_item_seq, PDO::PARAM_INT);
  $seq_stmt->execute();

      $sql_stmt = "INSERT INTO `Client_Item` (`Item_Id`, `Item_Code`, `Item_Description`, `Item_Selling`, `Item_Price`, `Item_Currency_Code`, `Client_Id`) VALUES (:item_id, :item_code, :item_description, :item_selling, :item_price, :item_currency_code, :client_id)";


      $stmt =  $dbh->prepare($sql_stmt);

      $stmt->bindValue(':item_id', $new_client_item_seq, PDO::PARAM_INT);
      $stmt->bindValue(':item_code', $_line[0], PDO::PARAM_INT);
      $stmt->bindValue(':item_description', $_line[1], PDO::PARAM_STR);
      $stmt->bindValue(':item_selling', $_line[2], PDO::PARAM_INT);
      $stmt->bindValue(':item_price', $_line[3]);
      $stmt->bindValue(':item_currency_code', $_line[4], PDO::PARAM_STR);
      $stmt->bindValue(':client_id', $user["Client_Id"], PDO::PARAM_INT);
      $stmt->execute();
}

  fclose($file);
  header('Location: ' . $_SERVER['HTTP_REFERER']);

  }
 }
 