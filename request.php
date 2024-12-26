<?php
session_start();
include("conn.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// If this is an AJAX request (to update jobs dynamically)
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
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
            $user_img = "d.png";

            // Get user image
            $sql = "SELECT img_id FROM pro_img WHERE user_id = '$job_user'";
            $img_result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($img_result) > 0) {
                $img_data = mysqli_fetch_assoc($img_result);
                $user_img = "image/" . $img_data['img_id'];
            }

            echo "<div class='homebox'>";
            echo "<div class='centerbox'>";
            echo "<img class='homeimg' src='".$user_img."' alt='Profile Picture'><br><br>";
            echo "</div>";
            echo "<p>";
            echo "Name: " . $user_info['user_name'] . "<br>";
            echo "Address: " . $user_info['user_address'] . "<br>";
            echo "Mobile: " . $user_info['user_contact'] . "<br>";
            echo "Job Description: " . $row['job_work'] . "<br>";
            echo "Job Date: " . $row['job_date'] . "<br>";
            echo "</p>";

            echo "<div>";
            if ($row['job_status'] == 1 && $row['requst'] == 0) {
                echo "<div class='accepted-box'>Job accepted</div>";
                echo "<form action='start-request.php' method='POST' class='item-actions'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<input type='hidden' name='worker_id' value='$worker_id'>";
                echo "<input type='hidden' name='job_user' value='$job_user'>";
                echo "<button class='yellowbutton' type='submit' name='action' value='confirm'>Start Job</button>";
                echo "</form>";
            }            
            elseif ($row['job_status'] == 1 && $row['requst'] == 1) {
                echo "<div class='paywaiting-box'>Waiting for conformation</div>";
            } elseif ($row['job_status'] == 1 && $row['requst'] == 2) {
                echo "<div class='accepted-box'>Job Started</div>";
                echo "<form action='end-request.php' method='POST' class='item-actions'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<input type='hidden' name='worker_id' value='$worker_id'>";
                echo "<input type='hidden' name='job_user' value='$job_user'>";
                echo "<button class='yellowbutton' type='submit' name='action' value='confirm'>Job completed</button>";
                echo "</form>";
            } elseif ($row['job_status'] == 1 && $row['requst'] == 3) {
                echo "<div class='paywaiting-box'>Waiting for conformation</div>";
            } 
           
            elseif ($row['job_status'] == 0) {
                echo "<div class='declined-box'>Job declined</div>";
            } 
            elseif ($row['job_status'] == 2 && $row['requst'] == 4) {
                echo "<div class='accepted-box'>Job Completed</div>";
                echo "<form action='offline_con.php' method='POST' class='item-actions'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<input type='hidden' name='worker_id' value='$worker_id'>";
                echo "<input type='hidden' name='job_user' value='$job_user'>";
                echo "<button class='yellowbutton' type='submit' name='action' value='confirm'>Click here if you have received the payment Payment </button>";
                echo "</form>";
            }

            elseif ($row['requst'] == 4) {
                echo "<form action='worker_rating.php' method='POST' class='item-actions'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<input type='hidden' name='worker_id' value='$worker_id'>";
                echo "<input type='hidden' name='job_user' value='$job_user'>";
                echo "<button class='jobdone-box' type='submit' name='action' value='confirm'>job completed,<br>Rate now</button>";
                echo "</form>";
            }
            elseif ($row['job_status'] == 2) {
                echo "<div class='jobdone-box'>Job Completed</div>";

                echo "<div class='waiting-box'>Waiting for payment</div>";
            } 
            elseif ($row['requst'] == 5) {
                echo "<div class='jobdone-box'>Job Completed</div>";
            } elseif ($row['job_status'] == 3) {
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

    // Stop further execution as we have returned the AJAX response
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include("index.php");
    include("chatbutton.php");


    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Job Confirmation Dashboard</title>
    <script>
        // Function to update job listings in the homecontainer div
        function updateJobs() {
            fetch(window.location.href, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    document.querySelector('.homecontainer').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }

        setInterval(updateJobs, 1000); // 1 seconds
    </script>
</head>

<body onload="updateJobs()">
    <div class="homecontainer">
        <!-- This will be updated dynamically by JavaScript -->
    </div>
</body>

</html>