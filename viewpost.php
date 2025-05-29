<?php
session_start();
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
include 'db.php';


// Fetch all posts
$id = $_GET['id'];
$sql = "SELECT blogs.id, blogs.title, blogs.subtitle, blogs.description, blogs.image, blogs.created_at, users.username 
        FROM blogs 
        JOIN users ON blogs.user_id = users.id 
        WHERE blogs.id = '$id' 

        ORDER BY blogs.created_at DESC";



$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: dashboard.php");
    exit();
}
$row = mysqli_fetch_assoc($result);

// Function to display comments recursively
function displayComments($blog_id, $parent_id = 0, $level = 0)
{
    global $conn;
    $comment_query = "SELECT comments.id, comments.comment, comments.parent_id, users.username 
                      FROM comments 
                      JOIN users ON comments.user_id = users.id 
                      WHERE comments.blog_id = '$blog_id' 
                      AND comments.parent_id = '$parent_id'
                      AND comments.status = 1 
                      ORDER BY comments.created_at ASC";
    $comment_result = mysqli_query($conn, $comment_query);

    if ($comment_result && mysqli_num_rows($comment_result) > 0) {
        echo '<div class="comment-wrapper">';

        while ($comment = mysqli_fetch_assoc($comment_result)) {
            //indentation replies it is used to know this replhy belogs to one comment
            $commentClass = ($parent_id == 0) ? "comment" : "comment comment-reply";



            echo '<div class="' . $commentClass . '">';
            echo '<div class="comment-header">';
            echo '<span class="comment-username">' . htmlspecialchars($comment['username']) . '</span>';
            echo '<p class="comment-text">' . nl2br(htmlspecialchars($comment['comment'])) . '</p>';
            echo '</div>';
            echo '<a class="reply" href="javascript:void(0);" onclick="setParentId(' . $comment['id'] . ')">Reply</a>';
            echo '</div>';


            // Recursively display replies, recursion means having nested things

            displayComments($blog_id, $comment['id'], $level + 1);
        }
        echo '</div>'; // Close comment-wrapper

    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIS</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body class="view-post-page">

    <div class="view-container">
        <div class="post-section">
            <?php if ($row) : ?>
                <h1><?= htmlspecialchars($row['title']) ?></h1><br>
                <?php if ($row) : ?>
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="Blog Image" class="view-img">
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['subtitle']) ?></h3><br>
                <p><?= htmlspecialchars($row['description']) ?></p><br>
                <small>Posted by <strong><?= htmlspecialchars($row['username']) ?></strong> on <?= $row['created_at'] ?></small><br>
            <?php else : ?>
                <p>Post not found.</p>
            <?php endif; ?>
            <div class="comments-section">

                <!-- Single Comment & Reply Box -->
                <?php if (isset($_SESSION['user_id'])) : ?>

                    <form method="post" action="comment.php" class="comment-box">
                        <input type="hidden" name="blog_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="parent_id" id="parent_id" value="0">
                        <input name="comment" id="comment" placeholder="Add a comment..."><br>

                        <?php if (isset($_GET['error']) && $_GET['error'] === 'empty'): ?>
                            <h6 class="error-message">Comment cannot be empty.</h6>
                        <?php endif; ?>



                        <button type="submit">Post</button>

                    </form>
               
                <?php endif; ?>

                <!-- Fetch and Display Comments -->
                <div id="comment-section">
                    <?php if ($row) displayComments($row['id']); ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function setParentId(parentId) {
            document.getElementById("parent_id").value = parentId;
            document.getElementById("comment").focus();
        }
        document.addEventListener("DOMContentLoaded", function() {
            var commentSection = document.getElementById("comment-section");
            commentSection.scrollTop = commentSection.scrollHeight; // Scroll to latest comment
        });
    </script>
</body>

</html>