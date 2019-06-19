<?php 

class ExceptionHandler{

    private $errCode;
    private $foreignKeyName;
    private $primaryKeyName;
    private $primaryKeyErrorCode = 1062;
    private $foreignKeyErrorCode = 1452;


    public function __construct($errCode,  $primaryKeyName, $foreignKeyName) {
        $ser="localhost";
        $user="root";
        $pass="mysql";
        $db="petadoption";
        $this->con=mysqli_connect($ser, $user, $pass, $db) or die("Connection Failed...");
        $this->errCode = $errCode;
        $this->foreignKeyName = $foreignKeyName;
        $this->primaryKeyName = $primaryKeyName;

    }

    public function printErrorMsg(){
        if($this->errCode == 1062){ //Duplicate PRMARY KEY Error
            echo "<p>Primary Key $this->primaryKeyName already exists.</p>";
        }
        if($this->errCode == 1452){ // Non-existent foreign key Error
            echo "<p>Foreign Key $this->foreignKeyName does not exist.</p>";
        }
    }
}
?>