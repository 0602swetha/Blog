<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete the user
    $conn->query("DELETE FROM users WHERE id = $user_id");

    // Reset IDs
    $conn->query("SET @count = 0");
    $conn->query("UPDATE users SET id = (@count := @count + 1) ORDER BY id");

    // Reset AUTO_INCREMENT
    $conn->query("ALTER TABLE users AUTO_INCREMENT = 1");
}

header("Location: manageusers.php");
