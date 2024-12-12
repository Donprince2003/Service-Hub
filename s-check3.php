<?php
session_start();
include("conn.php");
include("index.php");

if (isset($_GET['username'])) {
    $username = urldecode($_GET['username']);
}

if (isset($_POST['submit'])) {
    $user_password = mysqli_real_escape_string($conn, $_POST['password']);
    $user_password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);
    if ($user_password !== $user_password_confirm) {
        $errors[] = "Passwords do not match.";
    } elseif (strlen($user_password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } else {
        $sql = "UPDATE `user_tab` SET `user_password`='$user_password'";
        $result = $conn->query($sql);
        $sql = "SELECT user_password FROM user_tab WHERE user_id = '$username'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_assoc($result);
        $new_password = $row['user_password'];
        if ($user_password==$new_password) {
                header("Location: profile.php" );
                exit();
            } else {
                echo "<script>alert('Some thing went wrong !');</script>";
     
            }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Forgot Password</title>
</head>

<body>
    <div class="logincontainer">
        <h1>Forgot Password</h1>
        <div class="loginbox">
            <form name="id-check" method="POST">
                <label>New Password:</label>
                <input type="password" name="password" required><br><br>

                <label>Confirm New Password:</label>
                <input type="password" name="password_confirm" required><br><br>
        </div>
        <input type="submit" name="submit" value="Submit">
        </form>
    </div>
</body>

</html>