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
    <h2> Health Status Form </h2>
    <?php echo 'Your account id will be used in the submission. Your ID is: '.$_SESSION['id']; ?>
    <p> Enter the Health Status Information below: </p>
    <form method="POST">
    <div class="form-row">
    
    <?php 
        if(isset($_GET["Create"]))
        {
            $data = $_GET["Create"];
        } else {
            $data = 0;
        }
        echo '<label>Animal ID:</label>';
        echo '<input class="form-control" type="number" name="animalid" value="'.$data.'"required></input>';

        echo '</div>';
    ?>
    

    <br>

    <div class="form-row">

        <label>Vaccination</label>
        <select class="form-control" name="Vaccination" required></input>>
                            <option selected hidden value="">-- Select Vaccination --</option>
                            <option value="None">None</option>
                            <option value="Rabies Shot">Rabies Shot</option>
                            <option value="Parainfluenza Shot">Parainfluenza Shot</option>
                            <option value="Calcivirus Vaccine">Calcivirus Vaccine</option>
                            <option value="Ringworm Vaccine">Ringworm Vaccine</option>
                            <option value="Lyme Disease Vaccine">Lyme Disease Vaccine</option>
                        </select>

    </div>
    <br>
    <div class="form-row">

<label>Allergies</label>
<select class="form-control" name="Allergies" required></input>>
                                        <option selected hidden value="">-- Select Allergy --</option>
                                        <option value="None">None</option>
                                        <option value="Pollen Allergy">Pollen Allergy</option>
                                        <option value="Flea Saliva">Flea Saliva</option>
                                        <option value="Gluten">Gluten</option>
                                        <option value="Beef">Beef</option>
                                        <option value="Soy">Soy</option>
</select>
</div>
<br>
<div class="form-row">

<label>Previous Disease</label>
<select class="form-control" name="PreviousDisease" required></input>>
                                            <option selected hidden value="">-- Select Disease --</option>
                                            <option value="None">None</option>
                                            <option value="Rabies">Rabies</option>
                                            <option value="Dry Eye Syndrome">Dry Eye Syndrome</option>
                                            <option value="Influenza">Influenza</option>
                                            <option value="Hookworm Infection">Hookworm Infection</option>
                                            <option value="Iris Cyst">Iris Cyst</option>
                                    </select>

</div>
<br>
    <div class="submit-button">
        <input type="submit" value="submit" class="btn btn-primary" name="submitform">
    </div>
</form>

<?php
if(array_key_exists('submitform', $_POST)){

// if (!isset($_SESSION['counter'])) $_SESSION['counter'] = 0;  //initialize the session counter to zero

// if (isset($_POST['submitform'])) {  $_SESSION['counter']++; }  //increments counter by 1 when the submit button is pressed
    
// 'HealthID' = $_SESSION['counter']; //HealthID becomes the current value of counter
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "petadoption";

//create connection

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);

$animalcheck = $conn->query("SELECT animal_id FROM doctor_r2 WHERE animal_id=".$_POST['animalid']."");
if($animalcheck->num_rows == 0){
    $last = $conn->query("SELECT MAX(health_id) FROM health_status_check");
    $last_id = $last->fetch_assoc()['MAX(health_id)'];
    $HealthID = intval($last_id) + 1;
    $AnimalID = $_POST['animalid'];

    $Vaccination = $_POST['Vaccination'];
    $Allergies = $_POST['Allergies'];
    $PreviousDisease = $_POST['PreviousDisease'];
    $DoctorID = $_SESSION['id'];

if (!empty($HealthID) || !empty($Vaccination)|| 
!empty($Allergies)|| !empty($PreviousDisease)) {
    if (mysqli_connect_error()) {
     die('Connect Error('. mysqli_connect_errno().')'. mysqli_connect_error());
    } else {
    //  $SELECT = "SELECT health_id From health_status_check Where health_id = ? Limit 1";
     $INSERT = "INSERT Into health_status_check (health_id, vaccination, allergies, previous_diseases) values(?, ?, ?, ?)";
     $INSERTDOC = "INSERT INTO doctor_r2 values(?, ?, ?)";
     //Prepare statement
    //  $stmt = $conn->prepare($SELECT);
    //  $stmt->execute();
    //  $stmt->bind_result($HealthID);
    //  $stmt->store_result();
    //  $rnum = $stmt->num_rows;
    //  $stmt->close();
     $stmt = $conn->prepare($INSERT);
     $stmt2 = $conn->prepare($INSERTDOC);
     $stmt->bind_param("isss", $HealthID, $Vaccination, $Allergies, $PreviousDisease);
     $stmt2->bind_param("iii", $DoctorID, $AnimalID, $HealthID);
     if ($stmt->execute() && $stmt2->execute() ){
         echo "<div class='submit-button alert alert-success'>Health status has been processed. Click <a href='/healthstatus.php'>here</a> to refresh the page</div>";
     } else {
        echo "<div class='submit-button alert alert-danger'class='submit-button alert alert-success'>Error: " . $stmt->error."</div>";
     }
     $stmt->close();
     $stmt2->close();
     $conn->close();
    }
} else {
 echo "All fields are required";
 die();
}

} else {echo "<div class='submit-button alert alert-danger'class='submit-button alert alert-success'>Animal ID has a health ID on record. Please update the original record</div>";
}


}


?>

<!-- Javascript/JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="js\customer.js"></script>
