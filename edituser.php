<?php
session_start();
include 'db.php';
$user_id = $_GET['id'];


//fetch from db
$sql = "SELECT * from users where id='$user_id'";
$result = mysqli_query($conn, $sql);


if (!$result || mysqli_num_rows($result) == 0) {
    die("Error: Post not found.");
}

$row = mysqli_fetch_assoc($result);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    // Ensure username is not empty
    if (empty($username)) {
        die("Error: Username cannot be empty.");
    }


$update="UPDATE users SET username='$username'  where id='$user_id'";
$result = mysqli_query($conn, $update);
if (mysqli_query($conn, $update)) {
    echo("success");
    header("Location: manageusers.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
}

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIS</title>
    <link rel="icon" href="logo1.jpeg">
</head>

<body>

    <div class="container">

        <form method="post">
            <h2>Update user</h2>

            <input type="text" name="username" value="<?= $row['username'] ?>" required><br>

            <button type="submit">Update Post</button>

        </form>

    </div>


</body>

</html>