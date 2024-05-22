<!-- <?php
// if (session_status() !== PHP_SESSION_ACTIVE) {
//  session_start();
// }

//if (isset($_SESSION["user"])) {
  //header("Location: dashboard.php");
  //exit();
//}
// require_once("connection/connectionConfig.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
      body{
        padding:50px;
      }
      .container{
          max-width: 600px;
          margin:0 auto;
          padding:50px;
          box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
      }
      .form-group{
          margin-bottom:30px;
      }
  	</style>
</head>
<body>

    <div class="container rounded">
        <?php
        // if (isset($_POST["login"])) {
        //     $email = $_POST["email"];
        //     $password = $_POST["password"];


        //     $sql = "SELECT * FROM Client WHERE Client_Email = :email";
        //     $result = $dbh->prepare($sql);
        //     $result->bindValue(':email', $email, PDO::PARAM_STR);
        //     $result->execute();
        //     $user = $result->fetch(PDO::FETCH_ASSOC);

           // if ($user) {

             //   if (password_verify($password, $user["Client_Password"])) {
               
                    // session_start();
                    // $_SESSION["user"] = "yes";
                    // $_SESSION["user_id"] = 1;// $user["Client_Id"];
                    // $user["Client_Id"]=1;
                    // $user["Client_Type_Id"];
                    // header("Location: dashboard.php");
              //  }else{
               //     echo "<div class='alert alert-danger'>Password does not match</div>";
             //   }
         //   }else{
                // echo "<div class='alert alert-danger'>Email does not match</div>";
           // }
        //   }
        ?>
      <div class="text-center">
        <img
          src="./images/logo.jpeg"
          style=" width: 180px;
          height: 180px;
          object-fit: cover;
          border-radius: 50%;"

          alt="Image"
          class="img-fluid rounded-circle  mb-3"
        />
      </div>
      <form action="login.php" method="post">
        <div class="form-group">
            <input type="email" placeholder="Enter Email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <input type="password" placeholder="Enter Password" name="password" class="form-control">
        </div>
        <div class="form-btn text-center">
            <input type="submit" value="Login" name="login" class="btn btn-primary">
        </div>
      </form>
    </div>
</body>
</html> -->
<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Database connection
require_once("connection/connectionConfig.php");

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        // Prepare SQL statement to fetch user data
        $sql = "SELECT * FROM Client WHERE Client_Email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the user's password
        if ($user && password_verify($password, $user["Client_Password"])) {
            // Set session variables
            $_SESSION["user"] = $user["Client_Email"];
            $_SESSION["user_id"] = $user["Client_Id"];
            $_SESSION["client_type"] = $user["Client_Type_Id"];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        body {
            padding: 50px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 50px;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        }
        .form-group {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container rounded">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="text-center">
            <img src="./images/logo.jpeg" style="width: 180px; height: 180px; object-fit: cover; border-radius: 50%;" alt="Image" class="img-fluid rounded-circle mb-3" />
        </div>
        
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password" name="password" class="form-control" required>
            </div>
            <div class="form-btn text-center">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>
