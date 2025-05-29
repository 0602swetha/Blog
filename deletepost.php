<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Soft delete the blog by setting is_active to 0
    $sql = "UPDATE blogs SET is_active = 0 WHERE id = '$post_id'";

    if (mysqli_query($conn, $sql)) {
        // Reset AUTO_INCREMENT (NOT recommended in production)
        mysqli_query($conn, "ALTER TABLE blogs AUTO_INCREMENT = 1");

        header('location:mypost.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
