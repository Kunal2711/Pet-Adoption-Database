<?php
require_once "config.php";
$username = $password = $type = $stmt = "";
$username_err = $password_err = $type_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
      $sql = "SELECT ID FROM users WHERE username = ?";
      if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = trim($_POST['username']);
        if(mysqli_stmt_execute($stmt)){
          mysqli_stmt_store_result($stmt);
          if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
              } else{
              echo "Oops! Something went wrong. Please try again later.";
          }
        }
        mysqli_stmt_close($stmt);
      }
      if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
      } else{
        $password = trim($_POST["password"]);
      }
      if(empty(trim($_POST["type"]))){
        $type_err = "Please enter a type.";
      } else{
        $type = trim($_POST["type"]);
      }
      if(empty($username_err) && empty($password_err) && empty($type_err)){
        $sql = "INSERT INTO users (ID, username, password, type) VALUES (?, ?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
          mysqli_stmt_bind_param($stmt, "isss",$param_id, $param_username, $param_password, $param_type);
          $param_username = $username;
          $param_password = password_hash($password, PASSWORD_DEFAULT);
          //$param_password = $password;
          $param_type = $type;
          if($type == 'customer'){
            $last = $link->query("SELECT MAX(customer_id) FROM customer");
            $param_id = intval($last->fetch_assoc()['MAX(customer_id)']) + 1;
              if(mysqli_stmt_execute($stmt)){
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $param_id;
                $_SESSION["username"] = $username;
                $_SESSION["type"] = $type;
                header("location: custinfo.php");
              } else {
                echo "Something went wrong. Please try again later.";
              }
          } else {
              if($type == 'doctor'){
                $last = $link->query("SELECT MAX(doctor_id) FROM doctor");
                $param_id = intval($last->fetch_assoc()['MAX(doctor_id)']) + 1;
                if(mysqli_stmt_execute($stmt)){
                    $link->query("INSERT INTO doctor VALUES ('".$param_id."','".$param_username."')");
                    header("location: doctordash.php");
                  } else {
                    echo "Something went wrong. Please try again later.";
                  }
              } else {
                  if($type == 'admin'){
                    $last = $link->query("SELECT MAX(AdminId) FROM administrator");
                    $param_id = intval($last->fetch_assoc()['MAX(AdminId)']) + 1;
                    if(mysqli_stmt_execute($stmt)){
                        $link->query("INSERT INTO administrator VALUES ('".$param_id."','".$param_username."')");
                        header("location: administrator.php");
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }
                  }
              }
          }
        } else {
            echo "Something's wrong with the query: " . mysqli_error($link);
          }
    }
    mysqli_close($link);
  }
 ?>

 <!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($type_err)) ? 'has-error' : ''; ?>">
                <label>Type</label>
                <input type="text" name="type" class="form-control" value="<?php echo $type; ?>">
                <span class="help-block"><?php echo $type_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
