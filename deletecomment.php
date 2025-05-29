<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];


    $sql = "DELETE FROM comments WHERE id = '$post_id'";

    if (mysqli_query($conn, $sql)) {
        echo "comments permanently deleted!";
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
