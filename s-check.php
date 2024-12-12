<?php
session_start();
include("conn.php");
include("index.php");

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['name']);
    $sql = "SELECT * FROM user_tab WHERE user_id = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        header("Location: s-check2.php?username=" . urlencode($username));
        exit();
    } else {
        echo "<script>alert('User ID does not exist!');</script>";
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
        <a href="forgot-password.php">
       <input type="submit" name="email" value="email">
       </a>
       <br>
       <a href="s-check1.php">
       <input type="submit" name="email" value="security question">
       </a>
<br><br>
    </div>
</body>

</html>
