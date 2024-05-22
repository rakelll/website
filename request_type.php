<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once('connection/connectionConfig.php');

$Request_Type_query = $dbh->prepare("SELECT * FROM `Request_Type`;");
$Request_Type_query->execute();

$user_query = $dbh->prepare("SELECT * FROM `Client` WHERE Client_Id = :client_id");
$user_query->bindValue(':client_id', $_SESSION["user_id"], PDO::PARAM_INT);

$user_query->execute();

$user = $user_query->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Request Type</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
  </head>
  <body>
    <div class="wrapper">
      <?php include('sidebar.php') ?>
      <div class="main">
        <?php include('heading.php') ?>

        <div class="content">
          <div class="container">
            <?php if ($user["Client_Type_Id"] == 1): ?>
            <div class="card">
              <div class="card-header">
                <h2>Add a Request Type</h2>
              </div>
              <div class="card-body">
                <form class="row g-3" action="request_type_action.php" method="POST">
                  <div class="col-12">
                    <label class="form-label" for="RequestType">Request Type</label>
                    <input class="form-control" type="text" id="RequestType" name="RequestType" required/>
                  </div>
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Add Request Type</button>
                  </div>
                </form>
              </div>
            </div>
            <?php endif; ?>
          </div>

          <?php if ($Request_Type_query->rowCount() > 0): ?>
          <div class="container mt-3">
            <div class="card">
              <div class="card-header">
                <h2>
                  Request Types
                </h2>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover text-center m-0">
                  <thead class="table-dark">
                    <th scope="col">#</th>
                    <th scope="col">Request Type</th>

                    <?php if ($user["Client_Type_Id"] == 1): ?>
                    <th scope="col">Actions</th>
                    <?php endif; ?>
                  </thead>
                  <tbody>
                    <?php $x = 1; ?>
                    <?php foreach ($Request_Type_query as $Request_Type) { ?>
                    <tr id="Request_TypeRow<?= $Request_Type['Request_Type_Id'] ?>">
                      <td>
                        <span><?= $x++ ?></span>
                      </td>
                      <td>
                        <span id="Request_TypeSpan<?= $Request_Type['Request_Type_Id'] ?>">
                          <?= $Request_Type['Request_Type'] ?>
                        </span>
                      </td>
                      <?php if ($user["Client_Type_Id"] == 1): ?>
                      <td>
                        <button class="btn btn-outline-danger fa-solid fa-trash fs-5" onclick="removeRequest_Type(<?= $Request_Type["Request_Type_Id"] ?>)"></button>
                        <button id="Request_TypeButton<?= $Request_Type['Request_Type_Id'] ?>" class="btn btn-outline-warning fa-solid fa-pen-to-square fs-5" onclick="editRequest_Type(<?= $Request_Type["Request_Type_Id"] ?>)"></button>
                      </td>
                      <?php endif; ?>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <script src="js/app.js"></script>
    <?php if ($user["Client_Type_Id"] == 1): ?>
    <script src="js/request_type.js"></script>
    <?php endif; ?>
  </body>
</html>
