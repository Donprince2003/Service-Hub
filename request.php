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
        $worker_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM job_tab WHERE job_worker='$worker_id'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $job_id = $row['job_id'];
                $job_user = $row['job_user'];

                // Get job user details from user_tab
                $user_sql = "SELECT `user_name`, `user_address`, `user_contact` FROM `user_tab` WHERE user_id='$job_user'";
                $user_result = mysqli_query($conn, $user_sql);
                $user_info = mysqli_fetch_assoc($user_result);

                echo "<div class='homebox'>";
                echo "<div class='centerbox'>";
                echo "<img class='homeimg' src='d.png' alt='Profile Picture'><br><br>"; // Placeholder profile picture
                echo "</div>";
                echo "<p>";
                echo "Name: " . $user_info['user_name'] . "<br>";
                echo "Address: " . $user_info['user_address'] . "<br>";
                echo "Mobile: " . $user_info['user_contact'] . "<br>";
                echo "Job Description: " . $row['job_work'] . "<br>";
                echo "</p>";

                // Job acceptance/rejection status or buttons
                echo "<div>";
                if ($row['job_status'] == 1) {
                    echo "<div class='accepted-box'>Job accepted</div>";
                } elseif ($row['job_status'] == 0) {
                    echo "<div class='declined-box'>Job declined</div>";
                } elseif ($row['job_status'] == 3) {
                    // Show Accept/Decline buttons if job is pending
                    echo "<form action='request2.php' method='POST' class='item-actions'>";
                    echo "<input type='hidden' name='job_id' value='$job_id'>";
                    echo "<input type='hidden' name='worker_id' value='$worker_id'>";
                    echo "<input type='hidden' name='job_user' value='$job_user'>";

                    echo "<button class='greenbutton' type='submit' name='action' value='confirm'>Accept</button>";
                    echo "<button class='redbutton' type='submit' name='action' value='decline'>Reject</button>";
                    echo "</form>";
                }
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "No jobs available.";
        }
        ?>
    </div>
</body>

</html>
