<?php
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action == "confirm") {
        $accept_query = "UPDATE user_tab SET user_status=0 WHERE user_id='$user_id'";
        $update = mysqli_query($conn, $accept_query);
        if ($update) {
            echo "<script>window.location.href='manage_user.php';</script>";
        } else {
            echo "<script>alert('Error unblocking user.'); window.location.href='manage_user.php';</script>";
        }
    }

    if ($action == "decline") {
        $decline_query = "UPDATE user_tab SET user_status=1 WHERE user_id='$user_id'";
        $update = mysqli_query($conn, $decline_query);
        if ($update) {
            echo "<script> window.location.href='manage_user.php';</script>";
        } else {
            echo "<script>alert('Error blocking user.'); window.location.href='manage_user.php';</script>";
        }
    }
}
?>
