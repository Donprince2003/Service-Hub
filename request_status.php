<?php
session_start();
include("conn.php");
include("index.php");
include("chatbutton.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Job Confirmation Dashboard</title>
</head>

<body>
    <div class="homecontainer">
        <?php
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM job_tab WHERE job_user='$user_id'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $job_id = $row['job_id'];
                $job_worker = $row['job_worker'];

                $user_sql = "SELECT `user_name`, `user_address`, `user_contact` FROM `user_tab` WHERE user_id='$job_worker'";
                $user_result = mysqli_query($conn, $user_sql);
                $user_info = mysqli_fetch_assoc($user_result);

                $worker_sql = "SELECT * FROM worker_tab WHERE user_id = '$job_worker'";
                $worker_result = mysqli_query($conn, $worker_sql);
                $user_data = mysqli_fetch_assoc($worker_result);

                echo "<div class='homebox'>";
                echo "<div class='centerbox'>";
                echo "<img class='homeimg' src='d.png' alt='Profile Picture'><br><br>";
                echo "</div>";
                echo "<p>";
                echo "Name: " . $user_info['user_name'] . "<br>";
                echo "Address: " . $user_info['user_address'] . "<br>";
                echo "Mobile: " . $user_info['user_contact'] . "<br>";
                echo "Job Description: " . $row['job_work'] . "<br>";
                echo "</p>";

                echo "<div>";
                if ($row['job_status'] == 1) {
                    echo "<div class='accepted-box'>Job accepted</div>";
                } elseif ($row['job_status'] == 0) {
                    echo "<div class='declined-box'>Job declined</div>";
                } elseif ($row['job_status'] == 3) {
                    echo "<div class='waiting-box'>Waiting Response</div>";
                }
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo '<div class="homecontainer">';
            echo "No request pending";
            echo "</div>";
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['job_id'])) {
            $job_id = $_POST['job_id'];
            $action = $_POST['action'];

            if ($action == "confirm") {
                $accept_query = "UPDATE job_tab SET job_status=1 WHERE job_id='$job_id' AND job_worker='$job_worker'";
                $update = mysqli_query($conn, $accept_query);
                if ($update) {
                    echo "<script>alert('Job confirmed');</script>";
                    header("Location: request.php");
                    exit();
                } else {
                    echo "<script>alert('Error confirming job');</script>";
                }
            } elseif ($action == "decline") {
                $decline_query = "UPDATE job_tab SET job_status=0 WHERE job_id='$job_id' AND job_worker='$job_worker'";
                $update = mysqli_query($conn, $decline_query);
                if ($update) {
                    echo "<script>alert('Job declined');</script>";
                    header("Location: request.php");
                    exit();
                } else {
                    echo "<script>alert('Error declining job');</script>";
                }
            }
        }
        ?>
    </div>
</body>

</html>
