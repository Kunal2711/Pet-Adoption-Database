<!-- Class CSS -->
<link href="css/customer.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link href="css/general.css" rel="stylesheet">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<body class="web-body">
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Pet Shelter Database</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="/cs304/customer.php">Adoption</a></li>
      <li><a href="/donate.php">Donation</a></li>
      <li><a href="/viewing.php">Appointment</a></li>
      <li><a href="/logout.php">Log Out</a></li>
    </ul>
  </div>
</nav>
<div class="container-fluid">
<?php
include "classes/AnimalDisplay.php";

session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['type'] == 'customer') {
    //echo "Welcome to the member's area, " . $_SESSION['username'] . "!"."<br>";
} else {
    header("location: login.php");
}

$display = new AnimalDisplay();

$ser="localhost";
$user="root";
$pass="";
$db="petadoption";

$con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");

$gender = $con->query("SELECT DISTINCT gender from animal");
$breed = $con->query("SELECT DISTINCT breed from animal");
$type = $con->query("SELECT DISTINCT type from (SELECT animal_id,'Dog' As type FROM Dog UNION SELECT animal_id, 'Cat' As type FROM Cat
UNION ALL SELECT animal_id, 'Rabbit' FROM Rabbit) AS tb1");
// $result = $con->query("SELECT * from animal NATURAL JOIN (SELECT animal_id,'Dog' As type FROM Dog UNION SELECT animal_id, 'Cat' As type FROM Cat
// UNION ALL SELECT animal_id, 'Rabbit' FROM Rabbit) AS tb1");

echo '<form method="POST"><input type="text" id="searchInput" onkeyup="SearchFunction()" placeholder="Search" title="Search for an animal" name="searchbar"></input><div class="form-row"><div class="form-group col-md-4">';
$display->printForm($gender, 'gender', 'Gender');
echo '</div><div class="form-group col-md-4">';
$display->printForm($breed, 'breed', 'Breed');
echo '</div><div class="form-group col-md-4">';
$display->printForm($type, 'type', 'Type');
echo '</div><div class="form-row"><div style="text-align: center" class="form-group col-md-12"><input align="middle" type="submit" value="Search for Animal" class="btn btn-primary" name="findPet"></div></div></div></form>';
echo '<form method="POST"><div class="form-row"><div style="text-align: center" class="form-group col-md-12"><input align="middle" type="submit" value="Reset" class="btn btn-primary" name="reset"></div></div></form>';
echo '<div class="col-md-12" style="text-align: center;">';
echo 'There are currently <b>'. $display->countAnimal($con).'</b> animals looking for a home <br>';
echo 'There are currently <b>'.$display->CountBreed($con, 'dog').'</b> dogs in the shelter <br>';
echo 'There are currently <b>'.$display->CountBreed($con, 'cat').'</b> cats in the shelter <br>';
echo 'There are currently <b>'.$display->CountBreed($con, 'rabbit').'</b> rabbits in the shelter <br>';
echo '</div>';

if(array_key_exists('findPet', $_POST)){
  $searchbar = $_POST['searchbar'];
  $genderselect = $_POST['gender'];
  $breedselect = $_POST['breed'];
  $typeselect = $_POST['type'];
  $searchlist = array($searchbar, $genderselect, $breedselect, $typeselect);
  if($searchbar == NULL && $genderselect == 'selectone' && $breedselect == 'selectone' && $typeselect == 'selectone'){
    $display->printAll($con);
  } else {
    $display->sqlQuery($searchlist, $con);
  }
  
} else {
  if(array_key_exists('reset', $_POST)){
    $display->printAll($con);
} else {
  $display->printAll($con);
}
    
}




?>
</div>
</body>

<!-- Javascript/JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="js\customer.js"></script>
