<?php
    // Initialize database
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
?>

<?php 

    // function to update animal info in database
    function updateAnimalInfo() {
        global $con;
        $sql = "UPDATE Animal
                SET name = '{$_POST["newName"]}', age = '{$_POST["newAge"]}', gender = '{$_POST["newGender"]}', breed = '{$_POST["newBreed"]}'
                WHERE animal_id =  '{$_POST["id"]}'";
        $result = $con->query($sql);
        // echo json_encode($result);
    }

    // Function to delete animal from database
    function deleteAnimal($s) {
        global $con;
        $sql = "DELETE FROM Animal
        WHERE animal_id =  ".$s;
        // $sql = "SELECT *
        //         FROM Animal
        //         where animal_id = 1";
        $result = $con->query($sql);
       // echo json_encode($result);
    }

    // Update fuctions for user to be able to update address
    function updataeCustomerAddress() {
        global $con;
        $sql = "SELECT *
                FROM Customer_R1
                where postalcode = '{$_POST["newPostalCode"]}'";
        $result = $con->query($sql);
        if ($result->num_rows <= 0){
            $sql = "INSERT Customer_R1
                    VALUES ('{$_POST["newPostalCode"]}', '{$_POST["newCity"]}', '{$_POST["newProvince"]}') ";
            $result = $con->query($sql);
        }
        $sql = "UPDATE Customer
                    SET street = '{$_POST["newStreet"]}', postalcode = '{$_POST["newPostalCode"]}'
                WHERE customer_id = {$_POST["id"]}";
            $result = $con->query($sql);

    }

    // Division Query: Shows the customer who viewed all the animals (at least once) in the database
    function showCustomersWithMostViewed() {
        global $con;
        $sql = "SELECT c.customer_id, c.name FROM Customer c  WHERE NOT EXISTS (SELECT * FROM Animal a WHERE NOT EXISTS (SELECT * FROM Viewing_Sets v WHERE v.AnimalID = a.animal_id AND v.CustomerID = c.customer_id));";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<h2>Customer that has viewed all animals: <span class='label label-default'>ID: ".$row["customer_id"]." - ".$row["name"]."</span></h2>";
            }
        } else { if ($result->num_rows == 0){
            echo "<h2>Customer that has viewed all animals: <span class='label label-default'> No Customer Has Viewed Every Animal</span></h2>";

        }}
    }
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'updateAnimal':
                updateAnimalInfo();
                break;
            case 'deleteAnimal':
                deleteAnimal();
                break;
            case 'updateCustomerAddress':
                updataeCustomerAddress();
                break;
            case 'updateCustomerAddress':
            showCustomersWithMostViewed();
                break;
        }
        exit;
    }

    // Print out information about customers who signed up for website
    function getCustomers() {
        global $con;
        $result = $con->query("SELECT *, Name FROM Customer");
        if ($result->num_rows > 0){
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                      <td scope="row">' . $row["customer_id"]. '</td>
                      <td>' . $row["Name"] .'</td>
                      <td> '.$row["street"] .'</td>
                      <td> '.$row["postalcode"] .'</td>
                      <td> 
                        
                      <a class="btn btn-info" href="#customerModal" data-toggle="modal" 
                        data-id='.$row["customer_id"].'
                        >Edit Address</a>
                      </td>
                      </tr>';	
            }
        } 
    }

    include "ExceptionHandler.php";

// Print out the animals in the data base
function getAnimals() {
    global $con;
    $result = $con->query("SELECT *, Name FROM Animal");
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                  <td scope="row">' . $row["animal_id"]. '</td>
                  <td>' . $row["Name"] .'</td>
                  <td> '.$row["age"] .'</td>
                  <td> '.$row["gender"] .'</td>
                  <td> '.$row["breed"] .'</td>
                  <td> '.$row["donation_date"] .'</td>
                  <td> '.$row["customer_id"] .'</td>
                  <td> '.$row["AdminID"] .'</td>
                  <td> 
                    
                  <a class="btn btn-info" href="#AnimalModal" data-toggle="modal" 
                    data-id='.$row["animal_id"].'
                    data-name='.$row["Name"].'
                    data-age='.$row["age"].'
                    data-gender='.$row["gender"].'
                    data-breed="'.$row["breed"].'"
                    >Edit</a>
                    <form style="display: inline;" method="POST"><input style="display: none;"id="deleteAnimal" type="text" class="btn btn-danger"
                    value='.$row["animal_id"].' name=deleteactual></input><input id="deleteAnimal" type="submit" class="btn btn-danger"
                    data-id='.$row["animal_id"].'
                    value="Delete" name="deletecounter"></input></form>
                  </td>
                  </tr>';	
        }
    } 
}

// Print animal name along with the room id they belong to
function printAnimalRoom(){
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
    $result = $con->query("SELECT s.room_id, a.name FROM animal a, stay_in s WHERE a.animal_id = s.animal_id");
    if ($result->num_rows > 0) {
        // output data of each row
        echo '<table class="table table-striped table-hover">';
        echo '<thead><tr><th>Room ID</th><th>Name</th></tr></thead>';
        echo '<h3>Room Assignment</h3>';
        echo '<p>Here are a list of animal room listing:</p>';
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>'.$row['room_id'].'</td><td>'.$row['name'].'</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "No Animals Assigned to room";
    }
    mysqli_close($con);
}
?>
