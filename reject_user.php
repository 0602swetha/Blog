<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

   
    $conn->query("UPDATE users SET status = 0 WHERE id=$user_id");

}

header("Location: manageusers.php");
?>
