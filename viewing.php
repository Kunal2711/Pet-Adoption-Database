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
include "classes/CustomerAppointment.php";
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
<h2>My Appointments</h2>
<?php printAppointments();?>
</div>
<div class="container">
<h2>Make an Appointment</h2>
<p> Please fill out the form below to request a viewing with one of our animals.</p>
<form method="POST">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Select an animal</label>
            <select class="form-control" type="text" name="animal_id">
            <?php printAnimalID();?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Requested Date</label>
            <input class="form-control datepicker" type="text" autocomplete="off" name="date">
        </div>
    </div>
    <div class="submit-button">       
            <input type="submit" value="Submit" class="btn btn-primary" name="submitform">       
    </div>
</form>
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