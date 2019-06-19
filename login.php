<?php
session_start();

require_once "config.php";

$username = $password = $type = "";
$username_err = $password_err = $type_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err)){
        $sql = "SELECT ID, username, password, type FROM users WHERE username = ?";
        $type = "SELECT type FROM users WHERE username = ?";

         if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if(mysqli_stmt_execute($stmt)){
              mysqli_stmt_store_result($stmt);

              if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $type);
                //mysqli_stmt_bind_result($stmt, $id, $username, $password, $type);

                if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                        //$pass = "SELECT password FROM users WHERE username = ?";
                        //if(password_verify($password, $pass)){
                          session_start();
                          $_SESSION["loggedin"] = true;
                          $_SESSION["id"] = $id;
                          $_SESSION['username'] = $username;
                          $_SESSION['type'] = $type;
                          if($type == "customer")
                            header("location: customer.php");
                          else if($type == "admin")
                            header("location: administrator.php");
                          else if($type == "doctor")
                            header("location: doctordash.php");
                        } else {
                          $password_err = "The password you entered does not match the username";
                          }
                        }
              } else {
                $username_err = "This username does not exist";
                }
              } else {
              echo "Something went wrong. Please try again later.";
            }
          }
          mysqli_stmt_close($stmt);
        }
        mysqli_close($link);
      }
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">

</head>
<body>
    <div class="wrapper">
        <h1>Welcome to the Animal Shelter</h1>
        <h2>Login</h2>
        <p>Please enter your username and password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
                <!-- <input type="submit" class="btn btn-primary" value="Login as Administrator">
                <input type="submit" class="btn btn-primary" value="Login as Doctor"> -->
            </div>
            <p>New User? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>
