<?php
session_start();
include 'db.php';
// Check if user is logged in and is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    session_destroy();
    header("Location:login.php");
    exit();
}
$result = $conn->query("
    SELECT blogs.*, users.username 
    FROM blogs 
    JOIN users ON blogs.user_id = users.id
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIS</title>
    <link rel="icon" href="logo_icon.webp">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body class="user-blogs">

    <?php include 'admintopbar.php'; ?>
    <?php include 'adminsidebar.php'; ?>

    <h2>Blogs</h2>
    <table>
        <tr>
            <th>S.No</th>
            <th>UserName</th>
            <th>Title</th>
            <th>Subtitle</th>
            <th>Image</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php
        $i = 1;
        while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['subtitle']; ?></td>
                <td><?php echo "<img src = 'uploads/{$row['image']}'width='200' height='auto'> "  ?></td>
                <td><?php echo $row['description']; ?></td>
                <td>
                    <?php if ($row['status'] != 1): ?>
                        <a href="approve_blog.php?id=<?php echo $row['id']; ?>" class="approve">Approve</a>
                    <?php endif; ?>

                    <?php if ($row['status'] != 0): ?>
                        <a href="reject_blog.php?id=<?php echo $row['id']; ?>" class="reject">Reject</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>
    </table>

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