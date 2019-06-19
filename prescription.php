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
?>
<body class="web-body">
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Pet Shelter Database</a>
    </div>
    <ul class="nav navbar-nav">
    <li><a href="/doctordash.php">Dashboard</a></li> <!-- Added additional top menu bar -->
      <li><a href="/prescription.php">Prescription</a></li> <!-- Added additional top menu bar -->
      <li><a href="/healthstatus.php">Health Status</a></li> <!-- Added additional top menu bar -->
      <li><a href="/logout.php">Log Out</a></li>
    </ul>
  </div>
</nav>
<div class="container">
<?php
include 'classes/DoctorDisplay.php';
printPrescription();
?>
    <h2> Prescription Form </h2>
    <p> Enter the Drug information below: </p>
    <form method="POST">
    <div class="form-row">

        <label>Name</label>
        <select class="form-control" name="DrugName" required>
                            <option selected hidden value="">-- Select Drug  --</option>
                            <option value="None">None</option>
                            <option value="Morphine">Morphine</option>
                            <option value="Aspirin">Aspirin</option>
                            <option value="Metronidazole">Metronidazole</option>
                            <option value="Doxycycline">Doxycycline</option>
                            <option value="Ivermectin">Ivermectin </option>     
        </select>

    </div>
    <br>
    <div class="form-row">

    <label>Dosage (in mL)</label>
        <input class="form-control" type="number" name="DrugDosage" required></input>

    </div>
    <br>
    <div class="submit-button">
        <input type="submit" value="Submit" class="btn btn-primary" name="submitform">
    </div>
</form>

<?php
if(array_key_exists('submitform', $_POST)){
$DrugName = $_POST['DrugName'];
$DrugDosage = $_POST['DrugDosage'];
$DoctorID = $_SESSION['id'];

if (!empty($DrugName) || !empty($DrugDosage)) {
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "petadoption";
    //create connection
    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
    if (mysqli_connect_error()) {
     die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    } else {
     $SELECT = "SELECT name From drugs_prescribes Where name = ? Limit 1";
     $INSERT = "INSERT Into drugs_prescribes (name, doctor_id, dosage) values(?,?, ?)";
     //Prepare statement
     $stmt = $conn->prepare($SELECT);
     $stmt->bind_param("s", $DrugName);
     $stmt->execute();
     $stmt->bind_result($DrugName);
     $stmt->store_result();
     $rnum = $stmt->num_rows;
     if ($rnum==0) {
      $stmt->close();
      $stmt = $conn->prepare($INSERT);
      $stmt->bind_param("sii", $DrugName, $DoctorID, $DrugDosage);
      $stmt->execute();
      echo "<div class='submit-button alert alert-success'>Record has been added</div>";
     } else {
      echo "<div class='submit-button alert alert-danger'class='submit-button alert alert-success'>This prescription has already been added</div>.";
     }
     $stmt->close();
     $conn->close();
    }
} else {
 echo "All fields are required";
 die();
}
header("Refresh:3");

}

if(array_key_exists('delete', $_POST)){
    cancelAppointment($_POST['data']);
}
?>

<!-- Javascript/JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="js\customer.js"></script>
