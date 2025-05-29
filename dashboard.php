<?php
session_start();
include 'db.php'; // Database connection

$user_id = $_SESSION['user_id'] ?? null;

// Get search term from the URL
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Modify SQL query to include search filtering
$sql = "SELECT blogs.id, blogs.title, blogs.subtitle, blogs.description, blogs.image, blogs.created_at, users.username, 
          (SELECT COUNT(*) FROM comments WHERE comments.blog_id = blogs.id AND comments.status = 1) AS comment_count 
        FROM blogs 
        JOIN users ON blogs.user_id = users.id 
        WHERE blogs.status = 1";

if (!empty($search)) {
    $sql .= " AND (blogs.title LIKE '%$search%' 
              OR blogs.subtitle LIKE '%$search%' 
              OR blogs.description LIKE '%$search%')";
}

$sql .= " ORDER BY blogs.created_at DESC";


$result = mysqli_query($conn, $sql);

// Function to format timestamps into "time ago"
function timeAgo($datetime)
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
function highlightTerm($text, $term)
{
    if (empty($term)) return htmlspecialchars($text);
    return preg_replace("/(" . preg_quote($term, '/') . ")/i", "<span class='highlight'>$1</span>", htmlspecialchars($text));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>PIS</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>
    <?php include 'topbar.php'; ?>
    <?php include 'sidebar.php'; ?>


    <div class="container">
        <div class="srch">
            <h2 class="blog">All Blogs</h2>
            <div class="search">
                <form method="GET" action="">
                    <label>
                        <input type="text" name="search" placeholder="Search here..." value="<?= htmlspecialchars($search) ?>">
                    </label>
                </form>
            </div>

        </div>
    </div>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="post-container1">
                <div class="content">
                    <div class="left">
                        <h3><?= highlightTerm($row['title'], $search) ?></h3><br>
                        <p><?= highlightTerm($row['subtitle'], $search) ?></p><br>
                        <p><?= highlightTerm($row['description'], $search) ?></p><br>

                        <p> <small>Posted by <strong><?= htmlspecialchars($row['username']) ?></strong> on <?= timeAgo($row['created_at']) ?></small></p><br>
                        <!-- Comment Icon with Count -->
                        <a href="javascript:void(0);" class="comment-icon" onclick="openCommentModal(<?= $row['id'] ?>)">
                            <i class="fa-solid fa-comment"></i> <span class="count"><?= $row['comment_count'] ?></span>
                        </a>
                    </div>
                    <div class="right">
                        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Blog Image">
                    </div>
                </div>


            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No results found for "<strong><?= htmlspecialchars($search) ?></strong>".</p>
    <?php endif; ?>
    </div>

    <!-- Comment Modal -->
    <div id="commentModal" class="modal">
        <span class="close" onclick="closeCommentModal()">&times;</span>
        <div class="modal-content">
            <iframe id="commentFrame" src="" frameborder="0"></iframe>
        </div>
    </div>

    <script>
        function openCommentModal(postId) {
            document.getElementById('commentFrame').src = 'viewpost.php?id=' + postId;
            document.getElementById('commentModal').style.display = 'block';
            document.body.style.overflow = "hidden"; // Disable scrolling
        }

        function closeCommentModal() {
            document.getElementById('commentModal').style.display = 'none';
            document.getElementById('commentFrame').src = '';
            document.body.style.overflow = "auto"; // Enable scrolling
        }

        // Close modal when clicking outside of content
        window.onclick = function(event) {
            var modal = document.getElementById('commentModal');
            if (event.target === modal) {
                closeCommentModal();
            }
        }
    </script>



</body>

</html>