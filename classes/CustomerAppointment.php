<?php 
class Appointment {
    private $viewing_id;
    private $animal_id;
    private $customer_id;
    private $animal_name;
    private $date;
    private $con;

    // Initialize the creation of a new appointment
    public function __construct($a, $c, $d) {
        $ser="localhost";
        $user="root";
        $pass="";
        $db="petadoption";
        $this->con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
        $last = $this->con->query("SELECT MAX(ViewingID) FROM viewing_sets");
        $last_id = $last->fetch_assoc()['MAX(ViewingID)'];
        $this->viewing_id = intval($last_id) + 1;
        $this->animal_id = $a;
        $this->customer_id = $c;
        $counter = $this->con->query('SELECT name FROM animal WHERE animal_id='.'"'.$this->animal_id.'"');
        if ($counter == TRUE){
            $counter->fetch_assoc()['name'];
        } else {
            $this->animal_name = NULL;
        }
         
        $this->date = $d;
    }
    
    // Insert viewing appoints into database
    public function insertData() {
        $sql = "INSERT INTO viewing_sets VALUES (".'"'.$this->viewing_id.'"'.','.'"'.$this->animal_id.'"'.','.'"'.$this->customer_id.'",'.'"'.$this->date.'")';
        if ($this->con->query($sql) === TRUE) {
            echo "<div class='submit-button alert alert-success'>Thank you, your request has been processed. Click <a href='/viewing.php'>here</a> to refresh the page.</div>";
        } else {
            if(strpos($this->con->error, "FOREIGN KEY (`AnimalID`)") == TRUE){
                echo "<div class='submit-button alert alert-danger'>Please enter a valid pet ID</div>";
            } else {
                echo "<div class='submit-button alert alert-danger'>Error: " . $sql . "<br>" . $this->con->error."</div>";
            }         
            //echo "<p>There is something wrong, please try and input your data again</p>";
        }
    }
}

// Helper function for deleting viewing appointmnets (cancellation)
function cancelAppointment($a){
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
    $sql = "DELETE FROM viewing_sets WHERE ViewingID=".$a;
    if ($con->query($sql) == TRUE){
        echo"<div class='submit-button alert alert-success'>Thank you, your cancellation has been processed. Click <a href='/viewing.php'>here</a> to refresh the page.</div>";
    } else {
        echo "<div class='submit-button alert alert-danger'>Error: " . $sql . "<br>" . $con->error."</div>";
    }
    mysqli_close($con);
}

// Helper function for updating viewing appointmnets
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

// Print out animal ID for dropdown option for viewing.php
function printAnimalID(){
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
    $result = $con->query("SELECT animal_id, name FROM animal");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $AnimalId=$row["animal_id"];
            $AnimalName=$row["name"];
            echo "<option value='$AnimalId'> $AnimalName - $AnimalId </option>";
        }
    }
    mysqli_close($con);
}

// Print out Customer schedules
function printAppointments(){
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
    $result = $con->query("SELECT animal_id, name, ViewingID, Date_view FROM viewing_sets INNER JOIN animal ON animal.animal_id = viewing_sets.AnimalID WHERE CustomerID=".$_SESSION['id']." AND Date_view >= CURDATE() ORDER BY Date_view ASC");
    if ($result->num_rows > 0) {
        // output data of each row
        echo '<table class="table table-striped table-hover">';
        echo '<thead><tr><th>Animal ID</th><th>Name</th><th>Viewing ID</th><th>Appointment Date</th><th>Delete</th><th>Update</th></tr></thead>';
        echo '<p>Here are your current list of Appointments:</p>';
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row['animal_id'].'</td><td>'.$row['name'].'</td><td>'.$row['ViewingID'].'</td><td>'.$row['Date_view'].'</td>';
            echo '<td><form class="delete-button" method="POST"><input style="display: none;" type="text" name="data" value='.$row['ViewingID'].'><input class="btn btn-primary" type="submit" value="Delete" name="delete"></input></form></td>';
            echo '<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#viewingid'.$row['ViewingID'].'">Update</button></td>';
            echo '<div class="modal fade" id="viewingid'.$row['ViewingID'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" id="UpdateForm">Change Appointment</h3>
                <div class="modal-body">
                <p> Please fill out the following form to update your appointment for '.$row['Date_view'].'</p>
                <form method="POST">
                  <label>Change Animal ID</label>
                  <input style="display: none;" class="form-control" type="text" name="viewing_id1" value='.$row['ViewingID'].'>
                   <select class="form-control" type="text" name="animal_id1" value='.$row['animal_id'].'>';
                   printAnimalID();                   
                   echo '</select><br>
                  <label>Change Date</label>
                  <input class="form-control datepicker" type="text" autocomplete="off" name="date1" value='.$row['Date_view'].'>
                      <div class="submit-button">       
                          <input type="submit" value="Submit Change" class="btn btn-primary" name="submitupdate">       
                      </div>
                </form>
              </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No Future Appointments in the Database";
    }
    mysqli_close($con);
}
?>