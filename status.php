<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once('connection/connectionConfig.php');

$status_query = $dbh->prepare("SELECT * FROM `Status`;");
$status_query->execute();

$user_query = $dbh->prepare("SELECT * FROM `Client` WHERE Client_Id = :client_id");
$user_query->bindValue(':client_id', $_SESSION["user_id"], PDO::PARAM_INT);

$user_query->execute();

$user = $user_query->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Add a Status</title>

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
      <?php include("sidebar.php") ?>

      <div class="main">
        <?php include("heading.php") ?>

        <div class="content">

          <?php if ($user["Client_Type_Id"] == 1): ?>
          <div class="container mt-3">
            <div class="card">
              <div class="card-header">
                <h2>Add a Status</h2>
              </div>
              <div class="card-body">
                <form class="row g-3" action="status_action.php" method="POST">
                  <div class="col-12">
                    <label class="form-label" for="formRequestStatus">Status</label>
                    <input class="form-control" type="text" id="formRequestStatus" name="formRequestStatus" required/>
                  </div>
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Add Status</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <?php if ($status_query->rowCount() > 0): ?>
          <div class="container mt-3">
            <div class="card">
              <div class="card-header">
                <h2>
          Status
                </h2>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover text-center m-0">
                  <thead class="table-dark">
                    <th scope="col">#</th>
                    <th scope="col">Status</th>

                    <?php if ($user["Client_Type_Id"] == 1): ?>
                    <th scope="col">Actions</th>
                    <?php endif; ?>
                  </thead>
                  <tbody>
                    <?php $x = 1; ?>
                    <?php foreach ($status_query as $status) { ?>
                    <tr id="statusRow<?= $status['Status_Id'] ?>">
                      <td>
                        <span><?= $x++ ?></span>
                      </td>
                      <td>
                        <span id="statusSpan<?= $status['Status_Id'] ?>">
                          <?= $status['Status_Name'] ?>
                        </span>
                      </td>

                      <?php if ($user["Client_Type_Id"] == 1): ?>
                      <td>
                        <button class="btn btn-outline-danger fa-solid fa-trash fs-5" onclick="removeStatus(<?= $status["Status_Id"] ?>)"></button>
                        <button id="statusButton<?= $status['Status_Id'] ?>" class="btn btn-outline-warning fa-solid fa-pen-to-square fs-5" onclick="editStatus(<?= $status["Status_Id"] ?>)"></button>
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
    <script src="js/status.js"></script>
    <?php endif; ?>
  </body>
</html>
