<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="css/general.css" rel="stylesheet">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>

<?php 
    session_start();
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['type'] == 'doctor') {
        //echo "Welcome to the member's area, " . $_SESSION['username'] . "!"."<br>";
    } else {
        header("location: login.php");
    }
    include 'classes/DoctorDisplay.php';
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
    <li><a href="/doctordash.php">Dashboard</a></li>
      <li><a href="/prescription.php">Prescription</a></li> <!-- Added additional top menu bar -->
      <li><a href="/healthstatus.php">Health Status</a></li> <!-- Added additional top menu bar -->
      <li><a href="/logout.php">Log Out</a></li>
    </ul>
  </div>
</nav>
<div class="container">
    <h2> Welcome Doctor </h2>
    <?php 
    printUncheckedAnimal($con);
    ?>
    <!-- <form method="POST">
    <div class="form-row">

        <label>Doctor ID:</label>
        <input class="form-control" type="number" name="DoctorID" required></input>

    </div>
    <div class="form-row">

        <label>Doctor Name:</label>
        <input class="form-control" type="text" name="DoctorName" required></input>

    </div>
    <br>
    <div class="submit-button">
        <input type="submit" value="Submit" class="btn btn-primary" name="submitform">
    </div>
</form> -->

<?php
if(array_key_exists('submitform', $_POST)){
    $DoctorID = $_POST['DoctorID'];
    $DoctorName = $_POST['DoctorName'];

if (!empty($DoctorID) || !empty($DoctorName)) {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "petadoption";
    //create connection
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
    if (mysqli_connect_error()) {
     die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    } else {
     $SELECT = "SELECT doctor_id From doctor_r2 Where doctor_id = ? Limit 1";
     $INSERT = "INSERT Into doctor (doctor_id, name) values(?, ?)";
     //Prepare statement
     $stmt = $conn->prepare($SELECT);
     $stmt->bind_param("i", $DoctorID);
     $stmt->execute();
     $stmt->bind_result($DoctorID);
     $stmt->store_result();
     $rnum = $stmt->num_rows;
     if ($rnum==0) {
      $stmt->close();
      $stmt = $conn->prepare($INSERT);
      $stmt->bind_param("is", $DoctorID, $DoctorName);
      $stmt->execute();
      echo "New Doctor record inserted successfully";
     } else {
      echo "Someone already registered using this Doctor Information";
     }
     $stmt->close();
     $conn->close();
    }
} else {
 echo "All fields are required";
 die();
}
}


?>
<body>
    <h2> Report: Previous Pet Diseases </h2>
    <div id="chart-container">
      <canvas id="mycanvas"></canvas>
    </div>

    <!-- javascript -->

</body>

<!-- Javascript/JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="js\customer.js"></script>
<script type="text/javascript" src="js/Chart.min.js"></script>
<script type="text/javascript" src="js/diagram.js"></script>
