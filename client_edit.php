<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

if($_SESSION['user'] == ""){
    header("location:./login.php");
}
require_once("connection/connectionConfig.php");

$client_id = $_GET["client_id"];

$categories_query = $dbh->prepare("SELECT * FROM `Client_Category`;");
$categories_query->execute();

$governorate_query = $dbh->prepare("SELECT * FROM `Governorate`;");
$governorate_query->execute();

$caza_query = $dbh->prepare("SELECT * FROM `Caza`;");
$caza_query->execute();

$client_type_query = $dbh->prepare("SELECT * FROM `Client_Type`");
$client_type_query->execute();

$client_query = $dbh->prepare("SELECT * FROM `Client` WHERE Client_Id = :client_id");
$client_query->bindValue(':client_id', $client_id, PDO::PARAM_INT);

$client_query->execute();

$client = $client_query->fetch(PDO::FETCH_ASSOC);

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
    <title><?= $client["Client_FullName"] ?>'s Profile</title>


    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Linking Bootstrap -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.css">
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
    <div class="wrapper">
      <?php include("sidebar.php") ?>

      <div class="main">

        <?php include("heading.php") ?>

        <div class="content">
          <div class="container-fluid p-0">
            <div class="card">
              <div class="card-header">
                <h2><?= $client["Client_FullName"] ?>'s Profile</h2>
              </div>
              <div class="card-body">
                <div class="text-center mb-2">
                  <img class="rounded" src="<?= $client["Client_Logo"] ?>" alt="<?= $client["Client_FullName"] ?>'s Logo" width="30%">
                </div>

                <form class="row g-3" action="client_edit_action.php" method="POST" enctype="multipart/form-data">
                  <div class="col-md-6">
                    <div class="input-group">
                      <label for="clientId" class="input-group-text">Client Id</label>
                      <input class="form-control" type="text" value="<?= $client["Client_Id"] ?>" name="clientId" id="clientId" readonly required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input-group">
                      <?php if ($user["Client_Type_Id"] == 1 ): ?>
                      <span class="input-group-text">Client Type</span>
                      <select id="clientType" name="clientType" class="form-select">
                        <?php foreach ($client_type_query as $row) { ?>
                        <?php if ($row["Client_Type_Id"] == $client["Client_Type_Id"]): ?>
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
                    <input type="text" class="form-control" id="clientFullName" name="clientFullName" value="<?= $client["Client_FullName"] ?>" required/>
                  </div>
                  <div class="col-md-6">
                    <label for="clientEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="clientEmail" name="clientEmail" value="<?= $client["Client_Email"] ?>" required/>
                  </div>
                  <div class="col-12">
                    <label for="clientPassword" class="form-label">Reset Client Password</label>

                    <div class="input-group">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" name="passwordResetCheckBox">
                      </div>
                      <input type="password" class="form-control" id="clientPassword" name="clientPassword"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="clientLogo" class="form-label">Change Client Logo</label>

                    <div class="input-group">
                      <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" name="clientLogoCheckBox">
                      </div>
                      <input type="file" class="form-control" id="clientLogo" name="clientLogo">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="clientPhoneNumber" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="clientPhoneNumber" name="clientPhoneNumber" value="<?= $client["Client_Phone"] ?>" required/>
                  </div>
                  <div class="col-12">
                    <label for="clientAddress" class="form-label">Address</label>
                    <input type="text" class="form-control" id="clientAddress" name="clientAddress" value="<?= $client["Client_Address"] ?>" required/>
                  </div>
                  <div class="col-md-4">
                    <label for="clientCategory" class="form-label">Category</label>
                    <div class="input-group">
                      <?php if ($user["Client_Type_Id"] == 1 ): ?>
                      <a class="btn btn-outline-primary" href="client_category.php">Add</a>
                      <?php else: ?>
                      <a class="btn btn-outline-primary" href="client_category.php">View</a>
                      <?php endif; ?>
                      <select class="form-select" id="clientCategory" name="clientCategory">
                        <option value="Choose">Choose Category...</option>
                        <?php
                        foreach ($categories_query as $row) {
                          if ($row["Client_Category_Id"] == $client["Client_Category_Id"]) {
                            echo "<option value=\"" . $row["Client_Category_Id"] ."\" selected>" . $row["Client_Category"] ."</option>";
                          } else {
                            echo "<option value=\"" . $row["Client_Category_Id"] ."\">" . $row["Client_Category"] ."</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="clientGovernorate" class="form-label">Governorate</label>
                    <div class="input-group">
                      <?php if ($user["Client_Type_Id"] == 1 ): ?>
                      <a class="btn btn-outline-primary" href="governorate.php">Add</a>
                      <?php else: ?>
                      <a class="btn btn-outline-primary" href="governorate.php">View</a>
                      <?php endif; ?>
                      <select class="form-select" id="clientGovernorate" name="clientGovernorate">
                        <option value="Choose">Choose Governorate...</option>
                        <?php
                        foreach ($governorate_query as $row) {
                          if ($row["Governorate_Id"] == $client["Governorate_Id"]) {
                            echo "<option value=" . $row["Governorate_Id"] ." selected>" . $row["Governorate_Name"] ."</option>";
                          } else {
                            echo "<option value=" . $row["Governorate_Id"] .">" . $row["Governorate_Name"] ."</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="clientCaza" class="form-label">Caza</label>
                    <div class="input-group">
                      <?php if ($user["Client_Type_Id"] == 1 ): ?>
                      <a class="btn btn-outline-primary" href="caza.php">Add</a>
                      <?php else: ?>
                      <a class="btn btn-outline-primary" href="caza.php">View</a>
                      <?php endif; ?>
                      <select class="form-select" id="clientCaza" name="clientCaza">
                        <option value="Choose">Choose Caza...</option>
                        <?php
                        foreach ($caza_query as $row) {
                          if ($row["Caza_Id"] == $client["Caza_Id"]) {
                            echo "<option value=" . $row["Caza_Id"] ." selected>" . $row["Caza_Name"] . "</option>";
                          } else {
                            echo "<option value=" . $row["Caza_Id"] .">" . $row["Caza_Name"] . "</option>";
                          }
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Update Client</button>

                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="js/app.js"></script>
  </body>
</html>
