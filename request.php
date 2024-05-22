<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

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

// For Search
$clients_query = $dbh->prepare("SELECT * FROM `Client`");
$clients_query->execute();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Make a Request</title>

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
                <h2>Make a Request</h2>
              </div>
              <div class="card-body">
                <?php if ($user["Client_Type_Id"] == 1): ?>
                <div class="col-md-3 offset-md-4">
                  <select id="selectClient" class="form-select" onchange="handleClientChange(this)" name="selectClient">
                    <?php foreach ($clients_query as $client) { ?>
                    <option value="<?= $client['Client_Id'] ?>"><?= $client['Client_FullName'] ?></option>
                    <?php } ?>
                  </select>
                </div>
                <?php endif; ?>

                <form id="requestForm" class="row g-3 mt-1" action="request_action.php" method="POST" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <div class="input-group">
                      <label class="input-group-text" for="clientId">Client Id</label>
                      <input type="text" class="form-control" id="clientId" name="clientId" value="<?= $user["Client_Id"] ?>" readonly>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input-group">
                      <label class="input-group-text" for="requestAttachment">Request Attachment</label>
                      <input type="file" id="requestAttachment" name="requestAttachment" class="form-control" accept=".xlsx, .xls">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="requestDate" class="form-label">Request Date</label>
                    <input type="date" class="form-control" id="requestDate" name="requestDate" readonly required/>
                  </div>
                  <div class="col-md-6">
                  </div>
                  <div class="col-12">
                    <label for="requestMemo" class="form-label">Request Memo</label>
                    <textarea id="requestMemo" name="requestMemo" class="form-control"></textarea>
                  </div>

                  <div class="col-md-4">
                    <label for="requestType" class="form-label">Request Type</label>
                    <div class="input-group">
                      <?php if ($user["Client_Type_Id"] == 1): ?>
                      <a class="btn btn-outline-primary" href="request_type.php">Add</a>
                      <?php else: ?>
                      <a class="btn btn-outline-primary" href="request_type.php">View</a>
                      <?php endif; ?>
                      <select class="form-select" id="requestType" name="requestType">
                        <option value="Choose Type">Choose Type...</option>
                        <?php
                        foreach ($request_type_query as $row) {
                          echo "<option value=\"" . $row["Request_Type_Id"] ."\">" . $row["Request_Type"] ."</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                  </div>
                  <div class="col-md-4">
                  </div>
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Add Request</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <?php if ($requests_query->rowCount() > 0): ?>
          <div class="container mt-3 mb-3">
            <div class="card">
              <div class="card-header">
                <h2>
                  <?php if ($user["Client_Type_Id"] == 1): ?>
                  All Request
                  <?php else: ?>
                  Your Requests
                  <?php endif; ?>
                </h2>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover text-center m-0">
                  <thead class="table-dark">
                    <th scope="col">Reference</th>
                    <?php if ($user["Client_Type_Id"] == 1): ?>
                    <th scope="col">Client</th>
                    <?php endif; ?>
                    <th scope="col">Memo</th>
                    <th scope="col">Status</th>
                    <?php if ($user["Client_Type_Id"] == 1): ?>
                    <th scope="col">Actions</th>
                    <?php endif; ?>
                  </thead>
                  <tbody>
                    <?php $x = 1; ?>
                    <?php foreach ($requests_query as $request) { ?>
                    <tr id="clientRow<?= $request['Request_Id'] ?>">
                      <td>
                        <span><?= $request["Request_Id"] ?></span>
                      </td>
                      <?php if ($user["Client_Type_Id"] == 1): ?>
                      <td>
                        <span><?= $request["Client_FullName"] ?></span>
                      </td>
                      <?php endif; ?>
                      <td>
                        <span id="clientSpan<?= $request['Request_Id'] ?>">
                          <?= $request['Request_Memo'] ?>
                        </span>
                      </td>
                      <td>
                        <span id="">
                          <?php
                          $status_id = $request['Status_Id'];

                          $status  = $dbh->prepare("SELECT Status_Name FROM Status WHERE Status_Id = :status_id");
                          $status->bindValue(':status_id', $status_id, PDO::PARAM_INT);
                          $status->execute();

                          echo $status->fetch(PDO::FETCH_ASSOC)['Status_Name'];
                          ?>
                        </span>
                      </td>
                      <?php if ($user["Client_Type_Id"] == 1): ?>
                      <td>
                        <button class="btn btn-outline-danger fa-solid fa-trash fs-5" onclick="removeRequest(<?= $request["Request_Id"] ?>)"></button>
                        <button class="btn btn-outline-warning fa-solid fa-pen-to-square fs-5" onclick="editRequest(<?= $request["Request_Id"] ?>)"></button>
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
    <script src="js/request.js"></script>
    <script>
    window.addEventListener('DOMContentLoaded', (event) => {
      var currentDate = new Date().toISOString().split('T')[0];
      document.getElementById("requestDate").value = currentDate;
    });

    function handleClientChange(selectElement) {
      var clientIdBox = document.getElementById("clientId");
      clientIdBox.value = selectElement.value;
    }
    </script>
    <script>
      document.getElementById('requestForm').addEventListener('submit', function(event) {
        var fileInput = document.getElementById('requestAttachment');
        var filePath = fileInput.value;
        var allowedExtensions = /(\.xlsx|\.xls)$/i;

        if (!allowedExtensions.exec(filePath)) {
          alert('Please select a valid Excel file (xlsx or xls)');
          event.preventDefault();
          return false;
        }
      });
    </script>
  </body>
</html>
