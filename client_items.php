<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

$user_id = $_SESSION["user_id"];

$user_query = $dbh->prepare("SELECT * FROM `Client` WHERE Client_Id = :client_id");
$user_query->bindValue(':client_id', $user_id, PDO::PARAM_INT);
$user_query->execute();
$user = $user_query->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Client Items</title>

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
                <h2>Add Items Via CSV File</h2>
              </div>

              <div class="card-body">
                <form class="row" action="items_action.php" method="POST" enctype="multipart/form-data">
                  <!-- CSV File Input -->
                  <div class="col-md-6 offset-md-3">
                    <div class="input-group">
                      <label class="input-group-text" for="items_csvFile">CSV File</label>
                      <input type="file" class="form-control" id="items_csvFile" name="items_csvFile" accept=".csv" required>
                    </div>
                  </div>

                  <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary">Add Items</button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>

        <div class="content">
          <div class="container mt-3">
            <div class="card">
              <div class="card-header">
                <h2>Add Invoice Via CSV File</h2>
              </div>

              <div class="card-body">
                <form class="row" action="invoice_action.php" method="POST" enctype="multipart/form-data">
                  <!-- CSV File Input -->
                  <div class="col-md-6 offset-md-3">
                    <div class="input-group">
                      <label class="input-group-text" for="invoice_csvFile">CSV File</label>
                      <input type="file" class="form-control" id="invoice_csvFile" name="invoice_csvFile" accept=".csv" required>
                    </div>
                  </div>

                  <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary">Add Invoice</button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>

        <div class="content">
          <div class="container mt-3">
            <div class="card">
              <div class="card-header">
                <h2>Add Invoice Details Via CSV File</h2>
              </div>

              <div class="card-body">
                <form class="row" action="invoicedt_action.php" method="POST" enctype="multipart/form-data">
                  <!-- CSV File Input -->
                  <div class="col-md-6 offset-md-3">
                    <div class="input-group">
                      <label class="input-group-text" for="invoiceDtCsvFile">CSV File</label>
                      <input type="file" class="form-control" id="invoiceDtCsvFile" name="invoiceDtCsvFile" accept=".csv" required>
                    </div>
                  </div>

                  <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary">Add Invoice Details</button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

    <script>
      // Client-side validation for CSV file extensions
      document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(event) {
          var fileInput = this.querySelector('input[type="file"]');
          var filePath = fileInput.value;
          var allowedExtensions = /(\.csv)$/i;

          if (!allowedExtensions.exec(filePath)) {
            alert('Please select a valid CSV file.');
            event.preventDefault();
            return false;
          }
        });
      });
    </script>
    
  </body>
</html>
