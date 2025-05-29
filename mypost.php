<?php
session_start();


include 'db.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    session_destroy();
    header("Location:login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$sql = "SELECT blogs.*, users.username,
               (SELECT COUNT(*) FROM comments WHERE comments.blog_id = blogs.id) AS comment_count 
        FROM blogs 
         JOIN users ON blogs.user_id = users.id 
        WHERE blogs.user_id = '$user_id' 
      
          AND blogs.status = '1'
        ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
// Function to display comments recursively
function timeAgo($datetime, $full = false)
{
    $timestamp = strtotime($datetime);
    $difference = time() - $timestamp;

    $units = [
        'year' => 31536000,
        'month' => 2592000,
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1,
    ];

    foreach ($units as $unit => $value) {
        if ($difference >= $value) {
            $count = floor($difference / $value);
            return $count . ' ' . $unit . ($count > 1 ? 's' : '') . ' ago';
        }
    }
    return 'Just now';
}
// Fetch user's total post count
$count_query = "SELECT COUNT(*) AS total_posts FROM blogs WHERE user_id = '$user_id'";
$count_result = mysqli_query($conn, $count_query);
$count_data = mysqli_fetch_assoc($count_result);
$total_posts = $count_data['total_posts'];

$user_query = "SELECT username FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIS</title>
    <link rel="icon" href="logo_icon.webp">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'topbar.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="profile-header">
        <h1 class="name"><?= htmlspecialchars($user['username']) ?> </h1>
        <div class="card">
            <span class="count1"><?= $total_posts ?> </span>Posts
        </div>
    </div>

    <div class="myposts-container">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="mypost">
                <div class="post-header">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <div class="dropdown">
                        <ion-icon name="ellipsis-vertical-outline" class="dropdown-toggle" onclick="toggleDropdown(<?= $row['id'] ?>)"></ion-icon>
                        <div class="dropdown-menu" id="dropdown-<?= $row['id'] ?>">
                            <a href="javascript:void(0);" class="ed" onclick="openEditModal(<?= $row['id'] ?>)">Edit</a>

                            <a href="deletepost.php?id=<?= $row['id'] ?>" class="ed" onclick="return confirm('Are you sure?')">Delete</a>

                            <a href="javascript:void(0);" onclick="closeDropdown(<?= $row['id'] ?>)">Cancel</a>
                        </div>
                    </div>
                </div>
                <p><?= htmlspecialchars($row['subtitle']) ?></p><br>
                <?php if (!empty($row['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Blog Image" class="my-image">
                <?php endif; ?>
                <p><?= nl2br(htmlspecialchars($row['description'])) ?></p><br>
                <small>Posted on <?= timeAgo($row['created_at']) ?></small><br><br>

                <a href="javascript:void(0);" class="comment-icon" onclick="openModal(<?= $row['id'] ?>)">
                    <ion-icon name="chatbubble-outline"></ion-icon></ion-icon><span class="count"><?= $row['comment_count'] ?></span>
                </a>
                <div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <!-- Modal Popup -->
    <div id="commentModal" class="modal1">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content1">

            <iframe id="commentIframe"></iframe>
        </div>
    </div>

    <!-- Edit Post Modal -->
    <div id="editModal" class="modal2">
        <div class="modal-content2">
            <span class="close-btn" onclick="closeEditModal()">&times;</span>
            <iframe id="editIframe"></iframe>
        </div>
    </div>


    <script>
        function openModal(postId) {
            var modal = document.getElementById("commentModal");
            var iframe = document.getElementById("commentIframe");

            iframe.src = "viewpost.php?id=" + postId; // Load the post in iframe
            modal.style.display = "block";

            // Disable background scrolling
            document.body.style.overflow = "hidden";
        }

        function closeModal() {
            var modal = document.getElementById("commentModal");
            modal.style.display = "none";
            document.getElementById("commentIframe").src = ""; // Reset iframe
            // Enable background scrolling
            document.body.style.overflow = "auto";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            var modal = document.getElementById("commentModal");
            if (event.target === modal) {
                closeModal();
            }
        }

        function toggleDropdown(postId) {
            var dropdown = document.getElementById("dropdown-" + postId);
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }

        function closeDropdown(postId) {
            document.getElementById("dropdown-" + postId).style.display = "none";
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-toggle')) {
                document.querySelectorAll(".dropdown-menu").forEach(menu => {
                    menu.style.display = "none";
                });
            }
        };


        function openEditModal(postId) {
            var modal = document.getElementById("editModal");
            var iframe = document.getElementById("editIframe");

            iframe.src = "updatepost.php?id=" + postId; // Load the edit form
            modal.style.display = "block";

            document.body.style.overflow = "hidden"; // Disable background scrolling
        }

        function closeEditModal() {
            var modal = document.getElementById("editModal");
            modal.style.display = "none";
            document.getElementById("editIframe").src = ""; // Reset iframe
            document.body.style.overflow = "auto"; // Enable background scrolling
        }
        // Function to open the modal and load addpost.php
        function openAddPostModal() {
            document.getElementById('addPostFrame').src = 'addpost.php';
            document.getElementById('addPostModal').style.display = 'block';

            // Disable background scrolling
            document.body.style.overflow = "hidden";
        }

        // Function to close the modal
        function closeAddPostModal() {
            document.getElementById('addPostModal').style.display = 'none';
            document.getElementById('addPostFrame').src = ''; // Clear iframe src

            document.body.style.overflow = "auto";
        }
    </script>
    <!-- ionicons -->
    <!-- cdn link -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

</html>