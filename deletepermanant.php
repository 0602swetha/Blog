<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    
    $sql = "DELETE FROM blogs WHERE id = '$post_id'";

    if (mysqli_query($conn, $sql)) {
       
        $reset_sql = "ALTER TABLE blogs AUTO_INCREMENT = 1";
        mysqli_query($conn, $reset_sql);

        echo "Blog permanently deleted!";
        header("Location: recyclebin.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
