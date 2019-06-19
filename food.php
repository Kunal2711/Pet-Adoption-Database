<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->

    <!-- jQuery library -->
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="css/general.css" rel="stylesheet">
</head>
<?php
 session_start();
 if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['type'] == 'admin') {
     //echo "Welcome to the member's area, " . $_SESSION['username'] . "!"."<br>";
 } else {
     header("location: login.php");
 }
?>
<?php
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
?>
<body class="web-body">
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Pet Shelter Database</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="/administrator.php">Admin Dash</a></li>
      <li class="active"><a href="/food.php">Food</a></li>
      <li><a href="/logout.php">Log Out</a></li>
    </ul>
  </div>
</nav>
<div class="container-fluid">
<h2>FoodOrders</h2>
<form method="POST">
    <h3>InvoiceNo</h3>
    <input type="text" name='invoice_number'>
    <h3>Name</h3>
    <input type="text" name='invoice_name'>
    <h3>Cost</h3>
    <input type="text" name="invoice_cost">
    <h3>Type</h3>
    <input type="text" name="invoice_type">
    <h3>Nutrition</h3>
    <input type="text" name="invoice_nutrition">
    <!-- <input type="submit" value="Submit", name="submitform"> -->


<?php
include "ExceptionHandler.php";

$result = $con->query("SELECT AdminId, Name FROM Administrator AnimalID ");
if ($result->num_rows > 0) {
        ?>
        <h3>Administrator</h3>
        <select name="admin_id_selector">
    <?php while ($row = $result->fetch_assoc()) {
        $AdminId=$row["AdminId"];
        $AdminName=$row["Name"];
        echo "<option value='$AdminId'> $AdminName - $AdminId </option>";
    } ?>
        </select>
        <input type="submit" value="Submit", name="submitform">
        <br>
        <br>
        <select name="columnToShow">
        
            <?php
            $result = $con->query("SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = N'FoodOrders'");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                $name=$row["COLUMN_NAME"];
                echo "<option name = 'column' value='$name'> $name </option>";
                }
            } ?>
        </select>
        <input type="submit" value="Show History", name="showform">
        </form>
        
        
<?php 
    }


    if(array_key_exists('showform', $_POST)){
        $column = $_POST['columnToShow'];
        $result = $con->query("SELECT $column
            FROM FoodOrders");
        if ($result->num_rows > 0) {
            echo "<table class='table table-striped'>                     
            <div class='table responsive'>
            <thead>
            <tr>
            <th>$column</th>
            </thead>
            <tbody>";
            while ($row = $result->fetch_assoc()) {
                if ($row[$column] == NULL){
                    continue;
                }
                echo "<tr>";
                echo "<td style ='word-break:break-all;''>" . $row[$column] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>
                    </table>
                    </div>";
        }
}

if(array_key_exists('submitform', $_POST)){
    $invoice_number = $_POST['invoice_number'];
    $invoice_name = $_POST['invoice_name'];
    $invoice_cost = $_POST['invoice_cost'];
    $invoice_type = $_POST['invoice_type'];
    $invoice_nutrition = $_POST['invoice_nutrition'];
    $selected_admin = $_POST['admin_id_selector'];


    $sql = "INSERT INTO FoodOrders VALUES ('$invoice_number',
     '$invoice_type','$invoice_name','$invoice_cost',
     '$selected_admin','$invoice_nutrition')";

    if ($con->query($sql) === TRUE) {
        header("Refresh:3");
        echo "<p>Thank you, your request has been processed. The page will refresh in 3 seconds.</p>";
    } else {
        try {  
            throw new Exception("MySQL error $con->error <br> Query:<br> $query", $con->errno);    
        } catch(Exception $e ) {
            $exception_handler = new ExceptionHandler($e->getCode(), "Number and Name", "admin ID");
            $exception_handler->printErrorMsg();
        }            
            //echo "<p>There is something wrong, please try and input your data again</p>";
        }
}
?>
    </div>
</body>


<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
