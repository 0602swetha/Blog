<?php
session_start();
include 'db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Server-side validation
    if (empty($username)) {
        $errors['username'] = "Username is required!";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required!";
    }

    if (empty($errors)) {
        // Use prepared statements to prevent SQL injection
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {

               
                if ($user['status'] != 1) {
                    $errors['login'] = "Your account is not approved yet.";
                }
                 else {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
            
                    if ($user['role'] == 'admin') {
                        header("Location: admin_dashboard.php");
                        exit;
                    } else {
                        header("Location: dashboard.php");
                        exit;
                    }
                }
            
            } else {
                $errors['login'] = "Invalid username or password!";
            }
            
        } else {
            $errors['login'] = "User not found!";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIS</title>
    <link rel="icon" href="logo_icon.webp">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

   
</head>

<body class="user-login">
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

    <div class="login-container">
        <div class="login-left">
            <p class="hello">Hello,</p>
            <p class="welcome">Welcome!</p>
            <p class="quote">Your story begins here. Log in and start writing!</p>
        </div>
        <div class="login-right">
            <form action="" class="form" id="loginform" method="POST" onsubmit="return validateForm()">
                <div class="login-box">
                    <img src="perennial logo1.png" class="company-logo">
                    <h1>LOGIN</h1>

                    <?php if (!empty($errors['login'])) : ?>
                        <p class="error"><?= $errors['login'] ?></p>
                    <?php endif; ?>

                    <div class="input-control">
                        <label for="username">User Name:</label>
                        <input id="username" name="username" type="text">
                        <span class="error" id="username-error"><?= $errors['username'] ?? '' ?></span>
                    </div>

                    <div class="input-control">
                        <label for="password">Password:</label>
                        <input id="password" name="password" type="password">
                        <span class="error" id="password-error"><?= $errors['password'] ?? '' ?></span>
                    </div>

                    <div class="btn">
                        <input type="submit" value="Sign In">
                        <p class="not-registered">Not registered?<a href="register.php">Register</a></p>
                    </div>

                   
                </div>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value.trim();
            let valid = true;

            if (username === "") {
                document.getElementById("username-error").textContent = "Username is required!";
                valid = false;
            } else {
                document.getElementById("username-error").textContent = "";
            }

            if (password === "") {
                document.getElementById("password-error").textContent = "Password is required!";
                valid = false;
            } else {
                document.getElementById("password-error").textContent = "";
            }

            return valid;
        }
    </script>

</body>

</html>