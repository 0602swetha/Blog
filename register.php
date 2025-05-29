<?php
include 'db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);
    // Server-side validation
    if (empty($username)) {
        $errors['username'] = "Username is required!";
    } elseif (strlen($username) < 1) {
        $errors['username'] = "Username must be at least 3 characters long!";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required!";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long!";
    }
    // Confirm password validation
    if (empty($cpassword)) {
        $errors['cpassword'] = "Confirm password is required.";
    } elseif ($password !== $cpassword) {
        $errors['cpassword'] = "Passwords do not match.";
    }

    // Check if username exists
    if (empty($errors)) {
        $checkQuery = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errors['username'] = "Username already taken!";
        }
        mysqli_stmt_close($stmt);
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $passwordHash);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: login.php");
            exit;
        } else {
            $errors['general'] = "Error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="icon" href="logo_icon.webp">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

   
</head>

<body class="reg">
<div class="topbar">
        <div class="logo">
            <img src="perennial logo1.png" class="logo-image">
        </div>
        <div class="content">
            <div class="home">
                <a href="index.php" class="">home</a>
            </div>
            <div class="about">
                <a href="about.php" class="">About</a>
            </div>

            <div class="contact">
                <a href="contact.php" class="">Contact us</a>
            </div>
        </div>

        <div class="signin1up">

            <div class="login">
                <a href="login.php" class="signin">Signin</a>
            </div>
            <div class="register">
                <a href="register.php" class="signup">Signup</a>
            </div>

        </div>
    </div>

    <div class="reg-container">
        <img src="perennial logo1.png" class="company-logo">
        <div class="reg-title">Registration Form</div>

        <!-- Display Server-side Validation Errors -->
        <?php if (!empty($errors['general'])) : ?>
            <p class="error"><?= $errors['general'] ?></p>
        <?php endif; ?>

        <form action="" class="form" id="regform" method="POST" onsubmit="return validateForm()">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">User Name:</span>
                    <input type="text" id="username" name="username">
                    <span class="error" id="username-error"><?= $errors['username'] ?? '' ?></span>
                </div>
                <div class="input-box">
                    <span class="details">Password:</span>
                    <input type="password" id="password" name="password" >
                    <span class="error" id="password-error"><?= $errors['password'] ?? '' ?></span>
                </div>
                <div class="input-box">
                    <span class="details">Confirm Password:</span>
                    <input type="password" id="cpassword" name="cpassword" >
                    <span class="error" id="password-error"><?= $errors['cpassword'] ?? '' ?></span>
                </div>
            </div>

            <div class="button">
                <input type="submit" value="Register">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>

           
        </form>
    </div>

    <script>
        function validateForm() {
            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value.trim();
            let valid = true;

            // Username validation
            if (username === "") {
                document.getElementById("username-error").textContent = "Username is required!";
                valid = false;
            } else if (username.length < 6) {
                document.getElementById("username-error").textContent = "Username must be at least 3 characters long!";
                valid = false;
            } else {
                document.getElementById("username-error").textContent = "";
            }

            // Password validation
            if (password === "") {
                document.getElementById("password-error").textContent = "Password is required!";
                valid = false;
            } else if (password.length < 6) {
                document.getElementById("password-error").textContent = "Password must be at least 6 characters long!";
                valid = false;
            } else {
                document.getElementById("password-error").textContent = "";
            }

            return valid;
        }
    </script>

</body>

</html>