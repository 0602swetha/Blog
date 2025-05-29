<?php
session_start();
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php"); // Redirect to dashboard if ID is missing
    exit();
}
include 'db.php';

$post_id = $_GET['id'];

// Fetch post details from the database
$sql = "SELECT * FROM blogs WHERE id='$post_id'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: dashboard.php");
    exit();
}

$row = mysqli_fetch_assoc($result);
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $subtitle = trim($_POST["subtitle"]);
    $description = trim($_POST["description"]);
    $image = $row["image"];

    // Validate title
    if (empty($title)) {
        $errors['title'] = "Title is required.";
    } elseif (strlen($title) > 100) {
        $errors['title'] = "Title must be less than 100 characters.";
    }

    // Validate subtitle
    if (empty($subtitle)) {
        $errors['subtitle'] = "Subtitle is required.";
    } elseif (strlen($subtitle) > 150) {
        $errors['subtitle'] = "Subtitle must be less than 150 characters.";
    }

    // Validate description
    if (empty($description)) {
        $errors['description'] = "Description is required.";
    }

    // Handle image upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($_FILES["image"]["name"])) {
        $new_image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_image;
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
            } else {
                $image = $new_image; // Use new image only if successfully uploaded
            }
        }
    }

    // If no errors, update the blog post
    if (empty($errors)) {
        $update = "UPDATE blogs SET title='$title', subtitle='$subtitle', image='$image', description='$description' WHERE id='$post_id'";
        if (mysqli_query($conn, $update)) {
            echo "<script>window.top.location.href = 'dashboard.php';</script>";
            exit();
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
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

    <!-- <style>
        body {
            font-family: Arial, sans-serif;
            background-color: whitesmoke;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            overflow: hidden;
        }

        .container {
            background: whitesmoke;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        input,
        textarea {
            width: 97%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: none;
        }

        .preview {
            width: 100px;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }

        button {
            width: 50%;
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 25%;
        }

        button:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            font-size: 12px;
            display: block;
        }
    </style> -->
</head>

<body>







    <div class="edit-container">
        <form method="post" enctype="multipart/form-data" id="updateForm">
            <h2 class="edit">Update Post</h2>
            <input type="hidden" name="id" value="<?= htmlspecialchars($post_id) ?>">

            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($row['title']) ?>">
            <span class="error" id="title-error"><?= $errors['title'] ?? '' ?></span>

            <label for="subtitle">Subtitle:</label>
            <input type="text" name="subtitle" id="subtitle" value="<?= htmlspecialchars($row['subtitle']) ?>">
            <span class="error" id="subtitle-error"><?= $errors['subtitle'] ?? '' ?></span>

            <label>Old Image:</label>
            <?php if (!empty($row['image'])) : ?>
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="preview" alt="Current Image">
            <?php endif; ?>

            <label for="image">New Image:</label>
            <input type="file" name="image" id="image" accept="image/*">
            <span class="error" id="image-error"><?= $errors['image'] ?? '' ?></span>

            <label for="description">Description:</label>
            <textarea name="description" id="description"><?= htmlspecialchars($row['description']) ?></textarea>
            <span class="error" id="description-error"><?= $errors['description'] ?? '' ?></span>

            <button type="submit">Update Post</button>
        </form>
    </div>

    <script>
        document.getElementById("updateForm").addEventListener("submit", function(event) {
            let errors = {};
            let title = document.getElementById("title").value.trim();
            let subtitle = document.getElementById("subtitle").value.trim();
            let description = document.getElementById("description").value.trim();

            if (title === "") errors.title = "Title is required.";
            if (subtitle === "") errors.subtitle = "Subtitle is required.";
            if (description === "") errors.description = "Description is required.";

            document.querySelectorAll(".error").forEach(el => el.innerHTML = "");
            for (let key in errors) {
                document.getElementById(`${key}-error`).innerHTML = errors[key];
            }

            if (Object.keys(errors).length > 0) event.preventDefault();
        });
    </script>

</body>

</html>