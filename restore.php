<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Restore the blog by updating is_active to 1
    $sql = "UPDATE blogs SET is_active = 1 WHERE id = '$post_id'";

    if (mysqli_query($conn, $sql)) {

        header("Location: recyclebin.php"); 
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
