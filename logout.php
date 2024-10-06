<?php
session_start();
include("conn.php");
$user_id = $_SESSION['user_id'];
$update_sql = "UPDATE user_tab SET user_status = 0 WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $update_sql);
session_destroy();
header("Location: login.php");
?>