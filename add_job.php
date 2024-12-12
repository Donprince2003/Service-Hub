<?php
session_start();
include("conn.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $user_id = $_POST['user_id'];
    $worker_id = $_POST['user_worker_id'];
    $job_date = $_POST['job_date'];
    $job_work = $_POST['job_work'];
    $job_mess = "You have a new job for - " . $job_work." on ". $job_date;

    $sql = "INSERT INTO chat_tab (sender, receiver, message) VALUES ('$user_id', '$worker_id', '$job_mess')";
    mysqli_query($conn, $sql);

    $sql = "INSERT INTO job_tab (job_user, job_worker, job_date, job_work) VALUES ('$user_id', '$worker_id', '$job_date', '$job_work')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Job successfully added!');</script>";
    } else {
        echo "<script>alert('Error adding job.');</script>";
    }

    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
header("Location: request_status.php");
