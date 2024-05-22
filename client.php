<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

$categories_query = $dbh->prepare("SELECT * FROM `Client_Category`;");
$categories_query->execute();

$governorate_query = $dbh->prepare("SELECT * FROM `Governorate`;");
$governorate_query->execute();

$caza_query = $dbh->prepare("SELECT * FROM `Caza`;");
$caza_query->execute();

$clients_query = $dbh->prepare("SELECT * FROM `Client`");
$clients_query->execute();

$client_type_query = $dbh->prepare("SELECT * FROM `Client_Type`");
$client_type_query->execute();

$user_query = $dbh->prepare("SELECT * FROM `Client` WHERE Client_Id = :client_id");
$user_query->bindValue(':client_id', $_SESSION["user_id"], PDO::PARAM_INT);

$user_query->execute();

$user = $user_query->fetch(PDO::FETCH_ASSOC);
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
                <h2>Add a Client</h2>
              </div>
              <div class="card-body">
                <form class="row g-3 mt-1" action="client_action.php" method="POST" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <div class="input-group">
                      <label class="input-group-text" for="clientLogo">Client Logo</label>
                      <input type="file" class="form-control" id="clientLogo" name="clientLogo">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <?php if ($user["Client_Type_Id"] == 1 ): ?>
                      <span class="input-group-text">Client Type</span>
                      <select id="clientType" name="clientType" class="form-select">
                        <?php foreach ($client_type_query as $row) { ?>
                        <?php if ($row["Client_Type"] == "Client"): ?>
                        <option value="<?= $row["Client_Type_Id"] ?>" selected><?= $row["Client_Type"] ?></option>
                        <?php else: ?>
                        <option value="<?= $row["Client_Type_Id"] ?>"><?= $row["Client_Type"] ?></option>
                        <?php endif; ?>
                        <?php } ?>
                      </select>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="clientFullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="clientFullName" name="clientFullName" required/>
                  </div>
                  <div class="col-md-6">
                    <label for="clientEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="clientEmail" name="clientEmail" required/>
                  </div>
                  <div class="col-md-6">
                    <label for="clientPassword" class="form-label">Client Password</label>
                    <input type="password" class="form-control" id="clientPassword" name="clientPassword" required/>
                  </div>
                  <div class="col-md-6">
                    <label for="clientPhoneNumber" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="clientPhoneNumber" name="clientPhoneNumber" required/>
                  </div>
                  <div class="col-12">
                    <label for="clientAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="clientAddress" name="clientAddress" required/>
                  </div>
                  <div class="col-md-4">
                    <label for="clientCategory" class="form-label">Category</label>
                    <div class="input-group">
                      <a class="btn btn-outline-primary" href="client_category.php">Add</a>
                      <select class="form-select" id="clientCategory" name="clientCategory">
                        <option value="Choose">Choose Category...</option>
                        <?php
                        foreach ($categories_query as $row) {
                          echo "<option value=\"" . $row["Client_Category_Id"] ."\">" . $row["Client_Category"] ."</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="clientGovernorate" class="form-label">Governorate</label>
                    <div class="input-group">
                      <a class="btn btn-outline-primary" href="governorate.php">Add</a>
                      <select class="form-select" id="clientGovernorate" name="clientGovernorate">
                        <option value="Choose">Choose Governorate...</option>
                        <?php
                        foreach ($governorate_query as $row) {
                          echo "<option value=" . $row["Governorate_Id"] .">" . $row["Governorate_Name"] ."</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="clientCaza" class="form-label">Caza</label>
                    <div class="input-group">
                      <a class="btn btn-outline-primary" href="caza.php">Add</a>
                      <select class="form-select" id="clientCaza" name="clientCaza">
                        <option value="Choose">Choose Caza...</option>
                        <?php
                        foreach ($caza_query as $row) {
                          echo "<option value=" . $row["Caza_Id"] .">" . $row["Caza_Name"] . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Add Client</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <?php if ($clients_query->rowCount() > 0): ?>
          <div class="container mt-3 mb-3">
            <div class="card">
              <div class="card-header">
                <h2>
                  Clients
                </h2>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover text-center m-0">
                  <thead class="table-dark">
                    <th scope="col">#</th>
                    <th scope="col">Category</th>
                    <th scope="col">Actions</th>
                  </thead>
                  <tbody>
                    <?php $x = 1; ?>
                    <?php foreach ($clients_query as $client) { ?>
                    <tr id="clientRow<?= $client['Client_Id'] ?>">
                      <td>
                        <span><?= $x++ ?></span>
                      </td>
                      <td>
                        <span id="clientSpan<?= $client['Client_Id'] ?>">
                          <?= $client['Client_FullName'] ?>
                        </span>
                      </td>
                      <td>
                        <button class="btn btn-outline-danger fa-solid fa-trash fs-5" onclick="removeClient(<?= $client["Client_Id"] ?>)"></button>
                        <button class="btn btn-outline-warning fa-solid fa-pen-to-square fs-5" onclick="editClient(<?= $client["Client_Id"] ?>)"></button>
                      </td>
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
    <script src="js/client.js"></script>
  </body>
</html>
