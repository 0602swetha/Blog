<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    $conn->query("UPDATE comments SET status=0 WHERE id=$post_id");
    header("Location: managecomments.php");
}
?>
