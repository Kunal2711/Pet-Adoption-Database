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

<?php 
function updateAppointment($a, $b, $c, $d){
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
    $sql = "UPDATE viewing_sets SET AnimalID =".$b.", Date_view=".'"'.$c.'"'." WHERE ViewingID=".$d." AND CustomerID=".$a;
    if ($con->query($sql) == TRUE){
        echo"<div class='submit-button alert alert-success'>Thank you, your update has been processed. Click <a href='/viewing.php'>here</a> to refresh the page</div>";       
    } else {
        echo "<div class='submit-button alert alert-danger'>Error: " . $sql . "<br>" . $con->error.".</div>";
    }
    mysqli_close($con);
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
      <li><a href="/donate.php">Donation</a></li>
      <li class="active"><a href="/viewing.php">Appointment</a></li>
      <li><a href="/logout.php">Log Out</a></li>
    </ul>
  </div>
</nav>
<div class="container">
<h2>Current Address</h2>
<?php 
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
    $result = $con->query("SELECT c.street, c1.City, c1.Province, c.postalcode FROM customer c, customer_r1 c1, customer_r2 c2 WHERE c.customer_id=".$_SESSION['id']." AND c.postalcode = c1.Postalcode AND c.name = c2.Name");
    if ($result->num_rows > 0) {
        // output data of each row
        echo '<table class="table table-striped table-hover">';
        echo '<thead><tr><th>Street</th><th>City</th><th>Province</th><th>Postal Code</th></tr></thead>';
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row['street'].'</td><td>'.$row['City'].'</td><td>'.$row['Province'].'</td><td>'.$row['postalcode'].'</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No Address on Record";
    }
?>
<?php

if(array_key_exists('submitform', $_POST)){
    $userid = $_SESSION['id'];
    $animalid = $_POST['animal_id'];
    $date = $_POST['date'];
    $curr_viewing = new Appointment($animalid, $userid, $date);
    $curr_viewing->insertData();   
}

if(array_key_exists('delete', $_POST)){
    cancelAppointment($_POST['data']);
}

if(array_key_exists('submitupdate', $_POST)){
    $userid = $_SESSION['id'];
    $animalid = $_POST['animal_id1'];
    //echo $animalid;
    $date = $_POST['date1'];
    $viewingid = $_POST['viewing_id1'];
    updateAppointment($userid,$animalid,$date,$viewingid);
}
?>
</div>
</div>
</body>
<footer class="page-footer font-small blue pt-4">
</footer>


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!--Date picker function for Form-->
<script>
    $( function() {
        $( ".datepicker" ).datepicker({dateFormat: "yy-mm-dd", minDate:0});
    } );
</script>