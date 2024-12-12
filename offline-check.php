<?php
include("conn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['job_id'])) {
    echo   $worker_id = $_POST['worker_id'];
    echo  $job_id = $_POST['job_id'];
    echo $job_user = $_POST['job_user'];
    
        

        $accept_query = "UPDATE `job_tab` SET `requst`='4' WHERE job_id='$job_id' AND job_worker='$worker_id'";
        $update = mysqli_query($conn, $accept_query);
        if ($update) {
            echo "<script>window.location.href='request_status.php';</script>";
        } 
    } 
