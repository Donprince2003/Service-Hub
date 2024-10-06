<?php
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['worker_id'])) {
    $worker_id = $_POST['worker_id'];
    $action = $_POST['action'];

    if ($action == "confirm") {
        // Confirm the worker and update status
        $accept_query = "UPDATE worker_tab SET worker_status=1, worker_job_date_acpt=NOW() WHERE worker_id='$worker_id'";
        $update = mysqli_query($conn, $accept_query);
        if ($update) {
            echo "<script>alert('Worker confirmed'); window.location.href='workermanage.php';</script>";
        } else {
            echo "<script>alert('Error confirming worker'); window.location.href='workermanage.php';</script>";
        }
    } elseif ($action == "decline") {
        // Decline the worker and update status
        $decline_query = "UPDATE worker_tab SET worker_status=0, worker_job_date_acpt=NOW() WHERE worker_id='$worker_id'";
        $update = mysqli_query($conn, $decline_query);
        if ($update) {
            echo "<script>alert('Worker declined'); window.location.href='workermanage.php';</script>";
        } else {
            echo "<script>alert('Error declining worker'); window.location.href='workermanage.php';</script>";
        }
    }
}
?>
