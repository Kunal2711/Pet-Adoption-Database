<?php
class AnimalDisplay {

    public function __construct() {

    }

    // Print out specified animals on the customer.php homepage
    public function printAnimal($n, $a, $g, $b, $t, $h){
        echo '<div class="animal-card">';
        echo '<div class="animal-image">';
        //echo '<span class="img-wrap">';
        $this->defaultImage($t);
        //echo '</span>';
        echo '</div>';
        echo '<div class="animal-content">';
        echo '<h3 class="animal-name">'. "Name: ".$n."</h3>";
        echo '<p class="animal-age">'. "Age: ".$a."</p>";
        echo '<p class="animal-gender">'. "Gender: ". $g."</p>";
        echo '<p class="animal-breed">'. "Breed: ".$b."</p>";
        echo '<p class="animal-type">'. "Type: ".$t."</p>";
        echo '<p class="animal-hypo">'. "Hypoallergenic: ".$h."</p>";
        echo "</div>";
        echo "</div>";
    }

    // Helper function to display default animal profile images
    public function defaultImage($s) {
        switch ($s) {
            case 'Dog':
                echo "<img src='/img/dog.PNG'>";
                break;
            case 'Cat':
                echo "<img src='/img/cat.PNG'>";
                break;
            case 'Rabbit':
                echo "<img src='/img/rabbit.png'>";
                break;
        }
    }

    // Helper function to generate sql queries when finding an animal by gender, breed, or type
    public function queryProcessor($listofitems){
        $counter_string = array();
        for($x = 0; $x < sizeof($listofitems); $x++){
            if(in_array($listofitems[$x], array("Gender","Breed","Type",NULL,'selectone'))){
                
                
        } else {
            switch($x){
                case 0:
                array_push($counter_string,"a.name LIKE '%".$listofitems[$x]."%'");
                //$counter_string = $counter_string." a.name LIKE '%".$listofitems[$x]."%' AND ";
                break;
                    
                case 1:
                array_push($counter_string, "a.gender ='".$listofitems[$x]."'");
                //$counter_string = $counter_string." a.gender ='".$listofitems[$x]."' AND ";
                break;
                    
                case 2:
                array_push($counter_string, "a.breed ='".$listofitems[$x]."'");
                //$counter_string = $counter_string." a.breed ='".$listofitems[$x]."' AND ";
                break;

                case 3:
                array_push($counter_string, "j.type ='".$listofitems[$x]."'");
                //$counter_string = $counter_string." j.type ='".$listofitems[$x]."' ";
                break;
            }
        }        
    }
    $final_condition = "";
    for($y = 0; $y < sizeof($counter_string); $y++){
        if($y == 0 && sizeof($counter_string)!=1){
            $final_condition = $final_condition.$counter_string[$y]." AND ";
        } else {
            if($y == 0 && sizeof($counter_string)==1){
                $final_condition = $final_condition.$counter_string[$y];
            } else {
                if($y == sizeof($counter_string)-1){
                    $final_condition = $final_condition.$counter_string[$y];
            } else {
                $final_condition = $final_condition.$counter_string[$y]." AND ";
                }
            }
        }  
    }
    return $final_condition; 
}

    // function to execute sql query to print tables based on specific filtering (customer.php)
    public function sqlQuery($listofitems, $con) {
        $sql = "CREATE VIEW joined_type AS SELECT animal_id,'Dog' As type FROM Dog UNION SELECT animal_id, 'Cat' As type FROM Cat UNION ALL SELECT animal_id, 'Rabbit' FROM Rabbit";
        $con->query($sql);
        $conditions = $this->queryProcessor($listofitems);
        $sql = "SELECT DISTINCT a.name, a.age, a.gender, a.breed, j.type, a1.hypoallergenic FROM animal a, joined_type j, animal_r2 a1 WHERE a.animal_id = j.animal_id AND a.breed = a1.breed AND ".$conditions;
        $result = $con->query($sql);
        if( $result == TRUE){
            $this->printTable($result);
        } else {
            echo "<div class='submit-button alert alert-danger'>Error: " . $sql . "<br>" . $con->error.".</div>";
        }

        $sql = "DROP VIEW joined_type";
        $con->query($sql);

    }


    // Print specified sql query
    public function printTable($result) {
        echo '<div class="col-md-12">';
        echo '<hr>';
        if ($result->num_rows > 0) {
            // output data of each row
            echo '<div class="animal-table">';
            while($row = $result->fetch_assoc()) {
                if(is_null($row['hypoallergenic'])){
                    $counter = "NA";
                } else {
                    $counter = $row['hypoallergenic'];
                }
                $this->printAnimal($row['name'], $row['age'], $row['gender'], $row['breed'],$row['type'], $counter);
            }
            echo '</div>';
        } else {
            echo "No Animal in the Database";
        }
        echo '</div>';
    }

    // Print out all animals in database
    public function printAll($con){
        $sql = "CREATE VIEW joined_type AS SELECT animal_id,'Dog' As type FROM Dog UNION SELECT animal_id, 'Cat' As type FROM Cat UNION ALL SELECT animal_id, 'Rabbit' FROM Rabbit";
        $con->query($sql);
        $sql = "SELECT * FROM animal a, joined_type j, animal_r2 a1 WHERE a.animal_id = j.animal_id AND a.breed = a1.breed";
        $result = $con->query($sql);
        $sql = "DROP VIEW joined_type";
        $con->query($sql);
        $this->printTable($result);
    }

    // Print out dropdown form for filtering by animal
    public function printForm($dv, $cn, $title){
        echo '<select class="form-control" id="'.$cn.'Select"'.'onchange="dropdownSearch('.'&quot;'.$cn.'&quot;'.')" '.'name="'.$cn.'">';
        echo '<option value="selectone">'.$title.'</option>';
        while($row = $dv->fetch_assoc()){
            echo '<option '.'value="'.$row[$cn].'">'.$row[$cn].'</option>';
        }
        echo '</select>';
    }

    // Count the total number of animal in database
    public function countAnimal($con){
        $sql = "SELECT Count(*) from animal";
        $result = $con->query($sql);
        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            return $row["Count(*)"];
        } else {
            return 0;
        }
    }

    // Count the number of animals in a particular breed (dog, cat, rabbit)
    public function CountBreed($con, $type){
        switch($type) {
            case 'dog':
                $sql = "SELECT Count(*) from dog";
                $result = $con->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    return $row["Count(*)"];
                } else {
                    return 0;
                }
                break;
            
            case 'cat':
                $sql2 = "SELECT Count(*) from cat";
                $result = $con->query($sql2);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    return $row["Count(*)"];
                } else {
                    return 0;
                }
                break;

            case 'rabbit':
                $sql3 = "SELECT Count(*) from rabbit";
                $result = $con->query($sql3);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    return $row["Count(*)"];
                } else {
                    return 0;
                }
                break;
        }  
    }
}
?>