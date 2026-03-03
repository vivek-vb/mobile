<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// MySQLi connection
$conn = mysqli_connect("localhost", "root", "", "mobile-store");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = 0;
}

?>


