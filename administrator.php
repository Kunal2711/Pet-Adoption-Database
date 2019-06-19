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
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && $_SESSION['type'] == "admin") {
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
      <li class="active"><a href="/administrator.php">Donation</a></li>
      <li><a href="/food.php">Food</a></li>
      <li><a href="/logout.php">Log Out</a></li>
    </ul>
  </div>
</nav>
<?php include 'classes/AdministratorClass.php'?>
<div class="container-fluid">
<h2>Welcome Administrator</h2>
<p> Please select one of the following buttons to view information about animals, customers, and the customer who viewed the most animal</p>
<!-- <form method="POST">
    <h3>Administrator ID</h3>
    <input type="text" name='id'>
    <h3>Administrator Name</h3>
    <input type="text" name='administrator_name'>
    <input type="submit" value="Submit", name="submitform">
</form> -->
<!-- ANIMAL Modal -->
<div class="modal fade" id="AnimalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Animal Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label for="exampleForm2">Name</label>
        <input type="text" id="nameUpdate" class="form-control">
        <label for="exampleForm2">Age</label>
        <input type="text" id="ageUpdate" class="form-control">
        <label for="exampleForm2">Gender</label>
        <input type="text" id="genderUpdate" class="form-control">
        <label for="exampleForm2">Breed</label>
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="breedDropdownButton" data-toggle="dropdown"></button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <?php
                    $result = $con->query("SELECT * FROM Animal_R2 ");
                    if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $breed = $row["breed"];
                                echo "<a class='dropdown-item' href='#' data-breed=\"".$breed."\">".$breed."</a>";
                            }
                        }
                    
                ?>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <div id="saveAnimalUpdate"class="submit-button">       
            <input type="submit" value="Submit" class="btn btn-primary" name="submitform">       
        </div>      
    </div>
    </div>
  </div>
</div>

<!-- CUSOTMER Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Customer Address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label for="exampleForm2">Street</label>
        <input type="text" id="streetUpdate" class="form-control">
        <label for="exampleForm2">Postal Code</label>
        <input type="text" id="postalCodeUpdate" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <div id="saveCustomerUpdate"class="submit-button">       
            <input type="submit" value="Submit" class="btn btn-primary" name="submitform">       
        </div>      
    </div>
    </div>
  </div>
</div>

<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#animalTable" aria-expanded="false" aria-controls="collapseExample">
    Show Animal Data
  </button>
<div class="collapse" id="animalTable">
    <table class="table table-striped">                     
        <div class="table responsive">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Age</th>
                <th scope="col">Gender</th>
                <th scope="col">Breed</th>
                <th scope="col">Donation Date</th>
                <th scope="col">Customer ID</th>
                <th scope="col">Admin ID</th>
                </tr>
            </thead>
            <tbody>

        
<?php
 getAnimals();
 ?>
</tbody>
</table>
</div>
</body>
<br>
<br>
 <?php
if(array_key_exists('submitform', $_POST)){
    $userid = $_POST['id'];
    $userName = $_POST['administrator_name'];
    $sql = "INSERT INTO administrator VALUES ('$userid', '$userName')";
    if ($con->query($sql) === TRUE) {
        // header("Refresh:3");
        // echo "<p>Thank you, your request has been processed. The page will refresh in 3 seconds.</p>";
    } else {
        try {
            throw new Exception("MySQL error $con->error <br> Query:<br> $query", $con->errno);    
        } catch(Exception $e ) {
            $exception_handler = new ExceptionHandler($e->getCode(), "Administrator ID", "");
            $exception_handler->printErrorMsg();
        }            
            //echo "<p>There is something wrong, please try and input your data again</p>";
        }
}
if(array_key_exists('deletecounter', $_POST)){
    $deleteanimal = $_POST['deleteactual'];
    deleteAnimal($deleteanimal);
    echo "<meta http-equiv='refresh' content='0'>";
}
?>

<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#customerTable" aria-expanded="false" aria-controls="collapseExample">Show Customer Data</button>
<div class="collapse" id="customerTable">
    <table class="table table-striped">                     
        <div class="table responsive">
            <thead>
                <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Street</th>
                <th scope="col">Postal Code</th>
                </tr>
            </thead>
            <tbody><?php getCustomers(); ?></tbody>
        </div>
    </table>
</div><br><br>
<form style="display: inline;" method="POST">
    <input type="submit" value="Show Most Customer with the most Views"  class="btn btn-primary"  name="showMostViewed" >
</form>
<?php 
if(array_key_exists('showMostViewed', $_POST)){
    showCustomersWithMostViewed();
}
printAnimalRoom()
?>
</div>

    </div>




<script src="js/admin.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
