<?php
session_start();
include "db.php";

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    session_destroy();
    header("Location:login.php");
    exit();
}

// Fetch dashboard statistics
$users = $conn->query("SELECT COUNT(*) as total FROM users where role='user'")->fetch_assoc();
$blogs = $conn->query("SELECT COUNT(*) as total FROM blogs")->fetch_assoc();
$comments = $conn->query("SELECT COUNT(*) as total FROM comments ")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
<title>PIS</title>
<link rel="icon" href="logo_icon.webp">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body class="admin">

    <?php include 'admintopbar.php'; ?>

    <?php include 'adminsidebar.php'; ?>

    <div class="main-content">

        <h1><span>Welcome, <?= $_SESSION['username']; ?> !</span></h1>
        <div class="dashboard-cards">
            <div class="card1">
                <div>
                    <h2>Register</h2>
                    <span class="">Total users: <?= $users['total'] ?></span>
                </div>
            </div>
            <div class="card2">
                <div>
                    <h2>Blogs</h2>
                    <span class="">Total Blogs: <?= $blogs['total'] ?></span>
                </div>
            </div>
            <div class="card3">
                <div>
                    <h2>Comments</h2>
                    <span class=""> total Comments: <?= $comments['total'] ?></span>
                </div>
            </div>


        </div>
    </div>

    <script>
        // Wait until the document is fully loaded before running the script
        document.addEventListener('DOMContentLoaded', function() {
            // Select the dropdown menu using its ID
            const dropdown = document.getElementById("profileDropdown");
            const profilePic = document.querySelector(".profile-pic");

            // Toggle dropdown visibility
            function toggleDropdown() {
                if (dropdown) {
                    dropdown.classList.toggle("show");
                } else {
                    console.error("Dropdown element is not found.");
                }
            }

            // Close dropdown if clicked outside
            window.onclick = function(event) {
                // Close the dropdown if clicked outside of profile section
                if (dropdown && !event.target.matches('.profile-pic') && !event.target.closest('.user-profile')) {
                    dropdown.classList.remove("show");
                }
            };

            // Add the toggle functionality
            if (profilePic) {
                profilePic.addEventListener("click", toggleDropdown);
            }
        });
    </script>
</body>

</html>