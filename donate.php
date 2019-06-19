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
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['type'] == 'customer') {
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
      <li><a href="/customer.php">Adoption</a></li>
      <li class="active"><a href="/donate.php">Donation</a></li>
      <li><a href="/viewing.php">Appointment</a></li>
      <li><a href="/logout.php">Log Out</a></li>
    </ul>
  </div>
</nav>
<div class="container">
    <h2> Donation Form </h2>
    <p> Please fill out the following information to donate your animal </p>
    <form method="POST">
    <div class="form-row">

        <label>Name:</label>
        <input class="form-control" type="text" name="name" required></input>

    </div>
    <div class="form-row">

        <label>Age:</label>
        <input class="form-control" type="number" name="age" required></input>

        <label>Gender:</label>
        <select class="form-control" name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

    </div>
    <div class="form-row">
        <label>Breed:</label>
        <input class="form-control" type="text" name="breed" required></input>

        <label>Type:</label>
        <select class="form-control" name="type" required>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Rabbit">Rabbit</option>
        </select>

    </div>
    <div class="submit-button">
        <input type="submit" value="Submit" class="btn btn-primary" name="submitform">
    </div>
</form>
 


<?php
$ser="localhost";
$user="root";
$pass="";
$db="petadoption";

$con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
if(array_key_exists('submitform', $_POST)){
    $last = $con->query("SELECT MAX(animal_id) FROM animal");
    $last_id = $last->fetch_assoc()['MAX(animal_id)'];
    $var1 = intval($last_id) + 1;
    $var2 = $_POST['name'];
    $var3 = $_POST['age'];
    $var4 = $_POST['gender'];
    $var5 = $_POST['breed'];
    $var6 = date("F j, Y");
    $var8 = $_POST['type'];
    $var7 = $_SESSION['id'];

    $check_breed = $con->query("SELECT breed FROM animal_R2");
    $item_found = FALSE;
    while($row = $check_breed->fetch_assoc()){
        if($row['breed'] == $var5){
            $item_found = TRUE;
            break;
        }
    }

    if($item_found == FALSE){
        $sql = "INSERT INTO animal_R2 (breed) VALUES (".'"'.$var5.'")';
        $con->query($sql);
    }


    $sql = "INSERT INTO animal VALUES (".$var1.",".'"'.$var2.'"'.",".$var3.",".'"'.$var4.'"'.",".'"'.$var5.'"'.",".'"'.$var6.'"'.",".$var7.","."NULL)";

    if ($con->query($sql) === TRUE) {
        echo "<div class='submit-button alert alert-success'>Record has been added</div>";
    } else {
        echo "<div class='submit-button alert alert-danger'class='submit-button alert alert-success'>Error: " . $sql . "<br>" . $con->error."</div>";
    }

    if($var8 == "Dog"){
        $sql = "INSERT INTO dog VALUES (".$var1.", NULL)";
        if ($con->query($sql)==FALSE){
            echo "<div class='submit-button alert alert-danger'>Error: " . $sql . "<br>" . $con->error."</div>";
        }
        
    } else {
        if($var8 == "Cat"){
            $sql = "INSERT INTO cat VALUES (".$var1.", NULL)" ;
            $con->query($sql);
        } else {
            $sql = "INSERT INTO rabbit VALUES (".$var1.", NULL)";
            $con->query($sql);
        }
    }
}

?>
</div>
</body>


<!-- Javascript/JQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="js\customer.js"></script>