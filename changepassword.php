<?php
session_start();
include("conn.php");
include("index.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit'])) {

    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $user_password = mysqli_real_escape_string($conn, $_POST['password']);
    $user_password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);

    $sql = "SELECT user_password FROM user_tab WHERE user_id = '$user_id'";
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);
    $password = $row['user_password'];
    if ($old_password == $password) {
        if ($user_password !== $user_password_confirm) {
            $errors[] = "Passwords do not match.";
        } elseif (strlen($user_password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        } else {
            $sql = "UPDATE `user_tab` SET `user_password`='$user_password'";
            $result = $conn->query($sql);
            $sql = "SELECT user_password FROM user_tab WHERE user_id = '$user_id'";
            $result = $conn->query($sql);
            $row = mysqli_fetch_assoc($result);
            $new_password = $row['user_password'];
            if ($user_password == $new_password) {
                header("Location: profile.php");
                exit();
            } else {
                echo "<script>alert('Some thing went wrong !');</script>";
            }
        }
    } else {
        echo "<script>alert('Wrong password!');</script>";
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
                <label>Old Password:</label>
                <input type="password" name="old_password" required><br><br>

                <label>New Password:</label>
                <input type="password" name="password" required><br><br>

                <label>Confirm New Password:</label>
                <input type="password" name="password_confirm" required><br><br>
        </div>
        <input type="submit" name="submit" value="Submit">
        </form>
        <div>        <br>If you have forgot your password <a href="s-check.php">click here.</a></p>.
        </div>

    </div>
</body>

</html>