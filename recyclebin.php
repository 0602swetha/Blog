<?php
session_start();


include 'db.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    session_destroy();
    header("Location:login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
// Fetch only soft-deleted blogs (is_active = 0) for the logged-in user
$sql = "SELECT * FROM blogs WHERE  user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


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
    <div class="posts-container">
        
   

        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="post">
                <h3><?= htmlspecialchars($row['title']) ?></h3><br>
                <p><?= htmlspecialchars($row['subtitle']) ?></p><br>
                <small class=det>Deleted on <?= $row['created_at'] ?></small><br>

                <a href="restore.php?id=<?= $row['id'] ?>" class=" restore" onclick="return confirm('Restore this post?')"> Restore</a>
                <a href="deletepermanant.php?id=<?= $row['id'] ?>" class="delete" onclick="return confirm('Delete permanently?')"> Delete Permanently</a>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- ionicons -->
    <!-- cdn link -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

</html>