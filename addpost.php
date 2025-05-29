<?php
session_start();

include 'db.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'user') {
    session_destroy();
    header("Location:login.php");
    exit();
}
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $subtitle = trim($_POST["subtitle"]);
    $description = trim($_POST["description"]);
    $user_id = $_SESSION["user_id"];
    $image = "";

    // Validate title
    if (empty($title)) {
        $errors['title'] = "Title is required.";
    } elseif (strlen($title) > 100) {
        $errors['title'] = "Title must be less than 100 characters.";
    }

    // Validate subtitle (optional)
    if (!empty($subtitle) && strlen($subtitle) > 150) {
        $errors['subtitle'] = "Subtitle must be less than 150 characters.";
    } else if (empty($subtitle)) {
        $errors['subtitle'] = "subtitle is required.";
    }

    // Validate description
    if (empty($description)) {
        $errors['description'] = "Description is required.";
    } elseif (strlen($description) < 10) {
        $errors['description'] = "Description must be at least 10 characters.";
    }

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Validate image
    if (!empty($_FILES["image"]["name"])) {
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $errors['image'] = "File is not an image.";
        } elseif ($_FILES["image"]["size"] > 2 * 1024 * 1024) { // 2MB limit
            $errors['image'] = "File size too large (Max: 2MB).";
        } elseif (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $errors['image'] = "Allowed formats: JPG, JPEG, PNG, GIF.";
        } else {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $errors['image'] = "Error uploading file.";
            }
        }
    }


    // Insert into database if no errors
    if (empty($error)) {
        $sql = "INSERT INTO blogs (user_id, title, subtitle, image, description) 
                VALUES ('$user_id', '$title', '$subtitle', '$image', '$description')";

        if (mysqli_query($conn, $sql)) {

            echo "<script>window.top.location.href = 'dashboard.php';</script>";
            exit();
        } else {
            echo "Database error: " . mysqli_error($conn);
        }
    }
}
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

    <div class="add-container">
        <h2 class="add">Add a New Blog Post</h2>

        <form id="postForm" method="post" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" placeholder="Enter title">
            <span class="error"><?= $errors['title'] ?? '' ?></span>

            <label for="subtitle">Subtitle:</label>
            <input type="text" id="subtitle" name="subtitle" placeholder="Enter subtitle">
            <span class="error"><?= $errors['subtitle'] ?? '' ?></span>

            <label for="image">Upload Image:</label>
            <input type="file" id="image" name="image" accept="image/*">
            <span class="error"><?= $errors['image'] ?? '' ?></span>

            <label for="description">Description:</label>
            <textarea id="description" name="description" placeholder="Enter blog content"></textarea>
            <span class="error"><?= $errors['description'] ?? '' ?></span><br>

            <button type="submit">Post Blog</button>
        </form>
    </div>


    <script>
        document.getElementById("postForm").addEventListener("submit", function(event) {
            let errors = {};
            let title = document.getElementById("title").value.trim();
            let subtitle = document.getElementById("subtitle").value.trim();
            let description = document.getElementById("description").value.trim();
            let imageInput = document.getElementById("image");
            let image = imageInput.value;

            // Validate title
            if (title === "") {
                errors.title = "Title is required.";
            } else if (title.length > 100) {
                errors.title = "Title must be less than 100 characters.";
            }

            // Validate subtitle
            if (subtitle.length > 200) {
                errors.subtitle = "Subtitle must be less than 200characters.";
            } else if (subtitle === "") {
                errors.subtitle = "Subtitle is required.";
            }

            // Validate description
            if (description === "") {
                errors.description = "Description is required.";
            } else if (description.length < 10) {
                errors.description = "Description must be at least 10 characters.";
            }

            // Validate image
            if (image === "") {
                errors.image = "image is required.";
            } else if (image) {
                let allowedExtensions = /\.(jpg|jpeg|png|gif)$/i;
                let fileSize = imageInput.files[0].size / 1024 / 1024; // MB

                if (!allowedExtensions.test(image)) {
                    errors.image = "Only JPG, JPEG, PNG, and GIF are allowed.";
                } else if (fileSize > 2) {
                    errors.image = "File size too large (Max: 2MB).";
                }
            }

            // Show errors and prevent form submission if necessary
            if (Object.keys(errors).length > 0) {
                event.preventDefault();
                document.querySelectorAll(".error").forEach(el => el.innerHTML = "");
                for (let key in errors) {
                    document.querySelector(`#${key} + .error`).innerHTML = errors[key];
                }
            }
        });
    </script>
    <!-- ionicons -->
    <!-- cdn link -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>

</html>