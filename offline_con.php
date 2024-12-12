<?php
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['job_id'])) {
    $worker_id = $_POST['worker_id'];
    echo $job_id = $_POST['job_id'];
    $job_user = $_POST['job_user'];

    $sql = "SELECT `job_cost` FROM `job_tab` WHERE job_id = '$job_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
         $job_cost = $row['job_cost'];
    }

    $sql = "SELECT `worker_finance` FROM `worker_tab` WHERE user_id = '$worker_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
         $old_job_cost = $row['worker_finance'];
    }
    $new_job_cost = $old_job_cost + $job_cost;



    $sql = "UPDATE worker_tab SET worker_finance = '$new_job_cost' WHERE user_id = '$worker_id'";
    if ($conn->query($sql) === TRUE) {
    } else {
         "Error updating job cost: " . $conn->error;
    }



    $accept_query = "UPDATE `job_tab` SET requst=4, `job_status`='4', job_pay_status=1 WHERE job_id='$job_id' AND job_worker='$worker_id'";
    $update = mysqli_query($conn, $accept_query);
    if ($update) {
        echo "<script>window.location.href='request.php';</script>";
    }
}
