<?php
session_start();
echo "Are you sure you want to logout ".$_SESSION['username']."?";
echo '<form method="POST"><input align="middle" type="submit" value="Yes" class="btn btn-primary" name="logout"></form>';
if(array_key_exists('logout', $_POST)){
    session_destroy();
    header("location: login.php");
}
?>