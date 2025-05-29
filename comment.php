<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $blog_id = $_POST['blog_id'];
    $user_id = $_SESSION['user_id'];
    $comment =  $_POST['comment'];
    $parent_id = $_POST['parent_id'];

    if (empty($comment)) {
        header("Location: viewpost.php?id=$blog_id&error=empty");
        exit();
    }

    $sql = "INSERT INTO comments (blog_id, user_id, comment, parent_id) VALUES ('$blog_id', '$user_id', '$comment', '$parent_id')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>window.top.location.href = 'index.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
