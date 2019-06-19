<?php 
header('Content-Type: application/json');

// SQL query and function to print out previous diseases that animal has in shelter. Visualized as a pie graph
function chartData($param){
    $ser="localhost";
    $user="root";
    $pass="";
    $db="petadoption";
    $con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
    $sql = "CREATE VIEW count_disease AS SELECT previous_diseases, COUNT(previous_diseases) as total FROM health_status_check GROUP BY previous_diseases";
    $con->query($sql);
    $select = "SELECT * FROM count_disease";
    $selectmax = "SELECT previous_diseases, MAX(previous_diseases) as total FROM count_disease;";
    $deleteview = "DROP VIEW count_disease;";
    if($param == "all"){        
        $result1 =  $con->query($select);
        $data = array();
        if($result1->num_rows > 0){
            while($row = $result1->fetch_assoc()){
                $data[] = $row;
            }
            print json_encode($data); 
        } else {
            echo "Sorry, no data available";
        }
    } else {
        $result2 = $con->query($selectmax);
        $data = array();
        if($result2->num_rows > 0){
            while($row = $result2->fetch_assoc()){
                $data[] = $row;
            }
            print json_encode($data); 
        } else {
            echo "Sorry, no data available";
        }
    }
    $con->query($deleteview);
}
chartData("all");
?>