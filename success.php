<?php
session_start();
include("conn.php");
$job_id = $_SESSION['job_id'];
 $sql = "SELECT * FROM `job_tab` WHERE job_id = '$job_id'";
 $result = $conn->query($sql);
 if ($result->num_rows > 0) {
     $row = mysqli_fetch_assoc($result);
      $job_cost = $row['job_cost'];
      $worker_id=$row['job_worker'];

      
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



 $accept_query = "UPDATE `job_tab` SET `job_status`= 4, `requst`= 4, job_pay_status=1 WHERE job_id='$job_id'";
 $update = mysqli_query($conn, $accept_query);
 if ($update) {
 }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="log">
<h1>Payment Successful!</h1>
<p>You will be redirected in 3 seconds...</p>
    <script>
    setTimeout(function() {
        window.location.href = "/mini2/request_status.php";
    }, 3000); // Redirect after 3 seconds
</script>'
</div>
</body>
</html