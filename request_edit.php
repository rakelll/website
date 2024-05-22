<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

$request_id = $_GET["request_id"];

$request_status_query = $dbh->prepare("SELECT * FROM `Status`");
$request_status_query->execute();

$request_type_query = $dbh->prepare("SELECT * FROM `Request_Type`;");
$request_type_query->execute();

$client_type_query = $dbh->prepare("SELECT * FROM `Client_Type`");
$client_type_query->execute();

$user_query = $dbh->prepare("SELECT * FROM `Client` WHERE Client_Id = :client_id");
$user_query->bindValue(':client_id', $_SESSION["user_id"], PDO::PARAM_INT);

$user_query->execute();

$user = $user_query->fetch(PDO::FETCH_ASSOC);


if ($user["Client_Type_Id"] != 1) {
  $requests_query = $dbh->prepare("SELECT * FROM Request LEFT JOIN Client ON Request.Client_Id = Client.Client_Id WHERE Request.Client_Id = :user_id");
  $requests_query->bindValue(':user_id', $user["Client_Id"], PDO::PARAM_INT);
} else {
  $requests_query = $dbh->prepare("SELECT * FROM Request LEFT JOIN Client ON Request.Client_Id = Client.Client_Id");
}

$requests_query->execute();


$request_query = $dbh->prepare("SELECT * FROM Request WHERE Request_Id = :request_id");
$request_query->bindValue(':request_id', $request_id, PDO::PARAM_INT);
$request_query->execute();
$request = $request_query->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add a Client</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Linking Bootstrap -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
  </head>
  <body>

    <div class="wrapper">
      <?php include("sidebar.php") ?>

      <div class="main">
        <?php include("heading.php") ?>

        <div class="content">
          <div class="container mt-3">
            <div class="card">
              <div class="card-header">
                <h2>Edit Request</h2>
              </div>
              <div class="card-body">
                <form class="row g-3 mt-1" action="request_edit_action.php" method="POST" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <div class="input-group">
                      <label class="input-group-text" for="requestReference">Request Reference</label>
                      <input type="text" class="form-control" id="requestReference" name="requestReference" value="<?= $request["Request_Id"] ?>" readonly>
                    </div>
                  </div>

                  <div class="col-md-6 text-center">
                    <a class="btn btn-outline-primary" style="width: 100%;" href="<?= $request["Request_Attachments"] ?>">
                      Download Uploaded File
                    </a>
                  </div>

                  <div class="col-md-6">
                    <label for="requestDate" class="form-label">Request Date</label>
                    <input type="date" class="form-control" id="requestDate" name="requestDate" value="<?= $request["Request_Date"] ?>" readonly required/>
                  </div>
                  <div class="col-md-6">
                    <?php if ($user["Client_Type_Id"] == 1): ?>
                    <label for="requestDeliveryDate" class="form-label">Delivery Date</label>
                    <input type="date" class="form-control" id="requestDeliveryDate" name="requestDeliveryDate" value="<?= $request["Request_DeliveryDate"] ?>"/>
                    <?php endif; ?>
                  </div>
                  <div class="col-12">
                    <label for="requestMemo" class="form-label">Request Memo</label>
                    <textarea id="requestMemo" name="requestMemo" class="form-control"><?= trim($request["Request_Memo"]) ?></textarea>
                  </div>

                  <div class="col-md-6">
                    <label for="requestType" class="form-label">Request Type</label>
                    <div class="input-group">
                      <?php if ($user["Client_Type_Id"] == 1): ?>
                      <a class="btn btn-outline-primary" href="request_type.php">Add</a>
                      <?php else: ?>
                      <a class="btn btn-outline-primary" href="request_type.php">View</a>
                      <?php endif; ?>
                      <select class="form-select" id="requestType" name="requestType">
                        <option disabled>Choose Type...</option>
                        <?php
                        foreach ($request_type_query as $row) {
                          if ($row["Request_Type_Id"] == $request["Request_Type_Id"]) {
                            echo "<option value=" . $row["Request_Type_Id"] ." selected>" . $row["Request_Type"] ."</option>";
                          } else {
                            echo "<option value=" . $row["Request_Type_Id"] .">" . $row["Request_Type"] ."</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <?php if ($user["Client_Type_Id"] == 1): ?>
                    <label for="requestStatus" class="form-label">Request Status</label>
                    <div class="input-group">
                      <a class="btn btn-outline-primary" href="status.php">Add</a>
                      <select class="form-select" id="requestStatus" name="requestStatus">
                        <option value="Choose">Choose Status...</option>
                        <?php
                        foreach ($request_status_query as $row) {
                          if ($row["Status_Id"] == $request["Status_Id"]) {
                            echo "<option value=" . $row["Status_Id"] ." selected>" . $row["Status_Name"] ."</option>";
                          } else {
                            echo "<option value=" . $row["Status_Id"] .">" . $row["Status_Name"] ."</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <?php endif; ?>
                  </div>
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Update Requset</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

    <script src="js/app.js"></script>
    <script src="js/client.js"></script>
  </body>
</html>
