<?php 

// Print animals who have not been checked by a doctor.
function printUncheckedAnimal($con){
    $viewcreate = "CREATE VIEW joined_health AS SELECT d.animal_id, h.health_id FROM doctor_r2 d, health_status_check h WHERE d.health_id = h.health_id";
    $sql = "SELECT a.animal_id, a.name FROM animal a LEFT OUTER JOIN joined_health j ON a.animal_id = j.animal_id WHERE j.health_id IS NULL";
    $dropcreate = "DROP VIEW joined_health";
    $con->query($viewcreate);
    $result = $con->query($sql);
    if($result->num_rows > 0){
        echo '<table class="table table-striped table-hover">';
        echo '<thead><tr><th>Animal ID</th><th>Animal Name</th><th>Action</th></tr></thead>';
        echo '<p>Here are a list of animals that needs their records created:</p>';
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row['animal_id'].'</td><td>'.$row['name'].'</td>';
            echo '<td><form class="record-button" action="/healthstatus.php?animalid='.$row['animal_id'].'"><button type="submit" class="btn btn-primary" name="Create" value="'.$row['animal_id'].'">Create</button></form></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No Future Appointments in the Database";
    }
    $con->query($dropcreate);
}

// Helper function to refresh table after button is pressed
function refreshTable(){
    echo '<form method="POST"><input type="submit" value="Submit Change" class="btn btn-primary" name="submitupdate"></form>';
    if(array_key_exists('refresh', $_POST)){
        header("Refresh:0");
    }    
}

// Helper function to print prescriptions in database on website
function printPrescription(){
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
    $result = $con->query("SELECT Distinct name, dosage FROM drugs_prescribes");
    if ($result->num_rows > 0) {
        // output data of each row
        echo '<h2>Prescription Overview</h2>';
        echo '<table class="table table-striped table-hover">';
        echo '<thead><tr><th>Prescription Name</th><th>Dosage</th></tr></thead>';
        echo '<p>Here are a current list of distinct prescriptions in the Database:</p>';
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row['name'].'</td><td>'.$row['dosage'].'</td>';
            //echo '<td><form class="delete-button" method="POST"><input style="display: none;" type="text" name="data" value='.$row['name'].'>
            //<input class="btn btn-primary" type="submit" value="Delete" name="delete"></input></form></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No Prescriptions in the Database";
    }
    mysqli_close($con);
}

?>