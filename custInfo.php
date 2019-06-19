<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'petadoption');
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
session_start();
$Birthdate = $City = $Province = $Postalcode = $Street = $username = "";
$Birthdate_err = $City_err = $Province_err = $Postalcode_err = $Street_err = $username_err = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $user = $_SESSION['username'];
  if(empty(trim($_POST["Birthdate"]))){
        $Birthdate_err = "Please enter your Birthdate.";
    } else{
        $Birthdate = trim($_POST["Birthdate"]);
    }
    if(empty(trim($_POST["City"]))){
          $City_err = "Please enter your City.";
      } else{
          $City = trim($_POST["City"]);
      }
      if(empty(trim($_POST["Province"]))){
            $Province_err = "Please enter your Province.";
        } else{
            $Province = trim($_POST["Province"]);
        }
        if(empty(trim($_POST["Postalcode"]))){
              $Postalcode_err = "Please enter your Postal Code.";
          } else{
              $Postalcode = trim($_POST["Postalcode"]);
          }
          if(empty(trim($_POST["Street"]))){
                $Street_err = "Please enter your Street.";
            } else{
                $Street = trim($_POST["Street"]);
            }
            if(empty($Birthdate_err) && empty($username_err) && empty($City_err) && empty($Province_err)
            && empty($Postalcode_err) && empty($Street_err)){
              $sql  = "INSERT INTO customer_r1 (Postalcode, City, Province) VALUES(?, ?, ?)";
              $sql1 = "INSERT INTO customer (customer_id, name, Street, Postalcode) VALUES(?, ?, ?, ?)";
              $sql2 = "INSERT INTO customer_r2 (name, Birthdate) VALUES(?, ?)";
                if($stmt = mysqli_prepare($link, $sql)){
                    $stmt->bind_param("sss", $param_Postalcode, $param_Province, $param_city);
                    $param_city = $City;
                    $param_Province = $Province;
                    $param_Postalcode = $Postalcode;
                }
                if($stmt1 = mysqli_prepare($link, $sql1)){
                    $stmt1->bind_param("isss", $param_id, $param_username, $param_Street, $param_Postalcode);
                    $param_id = $_SESSION['id'];
                    echo $param_id;
                    $param_username = $user;
                    $param_Street = $Street;
                    $param_Postalcode = $Postalcode;
                }
                if($stmt2 = mysqli_prepare($link, $sql2)){
                    $stmt2->bind_param("ss", $param_username, $param_Birthdate);
                    $param_username = $user;
                    echo $user;
                    $param_Birthdate = $Birthdate;
                }
                if($stmt2->execute() && $stmt->execute() && $stmt1->execute()){
                    echo "passed here";
                    header("location: customer.php");
                    } else {
                        echo 'false';
                      echo 'error:'.$stmt->error;
                      echo 'error:'.$stmt1->error;
                      echo 'error:'.$stmt2->error;
                    }
                  } else {
                    echo "Something's wrong with the query: " . mysqli_error($link);
                  }
                  mysqli_close($link);
                }
 ?>

 <!DOCTYPE html>
<html>
<head>
    <title>Customer Info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <h2>Customer Info</h2>
        <p>Please fill in these additional fields.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($Birthdate_err)) ? 'has-error' : ''; ?>">
                <label>Birthdate</label>
                <input type="date" name="Birthdate" class="form-control" value="<?php echo $Birthdate; ?>" required>
                <span class="help-block"><?php echo $Birthdate_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($Street_err)) ? 'has-error' : ''; ?>">
                <label>Street</label>
                <input type="text" name="Street" class="form-control" value="<?php echo $Street; ?>" required>
                <span class="help-block"><?php echo $Street_err; ?></span>
            </div>


            <div class="form-group <?php echo (!empty($City_err)) ? 'has-error' : ''; ?>">
                <label>City</label>
                <input type="text" name="City" class="form-control" value="<?php echo $City; ?>" required>
                <span class="help-block"><?php echo $City_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($Province_err)) ? 'has-error' : ''; ?>">
                <label>Province</label>
                <input type="text" name="Province" class="form-control" value="<?php echo $Province; ?>" required>
                <span class="help-block"><?php echo $Province_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($Postalcode_err)) ? 'has-error' : ''; ?>">
                <label>Postalcode (in XXXXXX format)</label>
                <input type="text" name="Postalcode" class="form-control" value="<?php echo $Postalcode; ?>" required>
                <span class="help-block"><?php echo $Postalcode_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
        </form>
    </div>
</body>
</html>
