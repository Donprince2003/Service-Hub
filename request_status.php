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
    // Fetch jobs for the user
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
            $user_img = "d.png";

            // Get user image
            $sql = "SELECT img_id FROM pro_img WHERE user_id = '$job_worker'";
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
            if ($row['job_status'] == 1 && $row['requst'] == 1) {
                echo "<form action='start-pesmission.php' method='POST' class='item-actions'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<input type='hidden' name='worker_id' value='$job_worker'>";
                echo "<input type='hidden' name='job_user' value='$user_id'>";
                echo "<button class='greenbutton' type='submit' name='action' value='confirm'>Worker ready for work, <br>Start work now</button>";
                echo "</form>";
            }
            elseif ($row['job_status'] == 5) {
                echo "<div class='jobdone-box'>Job Completed</div>";
            }

            elseif ($row['job_status'] == 4) {
                echo "<form action='user_rating.php' method='POST' class='item-actions'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<input type='hidden' name='worker_id' value='$job_worker'>";
                echo "<input type='hidden' name='job_user' value='$user_id'>";
                echo "<button class='jobdone-box' type='submit' name='action' value='confirm'>job completed,<br>Rate now</button>";
                echo "</form>";
            } 

            elseif ($row['job_status'] == 2 && $row['requst'] == 4) {
                echo "<div class='paywaiting-box'>Waiting for conformation</div>";
            } 
            
            
            elseif ($row['job_status'] == 1 && $row['requst'] == 0) {
                echo "<div class='accepted-box'>Job accepted</div>";
            } 
            
            
            elseif ($row['job_status'] == 1 && $row['requst'] == 2) {

                echo "<div class='accepted-box'>Job Started</div>";
            } 
            
            
            elseif ($row['job_status'] == 0) {
                echo "<div class='declined-box'>Job declined</div>";
            } 
            
            
            elseif ($row['job_status'] == 1 && $row['requst'] == 3) {
                echo "<form action='end-pesmission.php' method='POST' class='item-actions'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<input type='hidden' name='worker_id' value='$job_worker'>";
                echo "<input type='hidden' name='job_user' value='$user_id'>";
                echo "<button class='yellowbutton' type='submit' name='action' value='confirm'>job completed,<br>Conform now</button>";
                echo "</form>";
            } 
            
            
            elseif ($row['job_status'] == 2) {
                echo "<div class='jobdone-box'>Job Completed</div>";
                echo "<form action='payinvoice.php' method='POST' class='item-actions'>";
                echo "<input type='hidden' name='job_id' value='$job_id'>";
                echo "<input type='hidden' name='worker_id' value='$job_worker'>";
                echo "<input type='hidden' name='job_user' value='$user_id'>";
                echo "<button class='yellowbutton' type='submit' name='action' value='confirm'>Pay now</button>";
                echo "</form>";
            } 
            
            
            elseif ($row['job_status'] == 3) {
                echo "<div class='waiting-box'>Waiting Response</div>";
            }
            echo "</div>"; // Close inner div
            echo "</div>"; // Close homebox
        }
    } else {
        echo "<div class='homecontainer'>No request pending</div>";
    }
    exit(); // End the script for AJAX requests
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $action = $_POST['action'];
    $job_worker = $_POST['worker_id']; // Ensure job_worker is available from the POST data

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

        // Auto-refresh every 1 second
        setInterval(updateJobs, 1000); // 1 second
    </script>
</head>

<body onload="updateJobs()">
    <div class="homecontainer">
        <!-- This will be updated dynamically by JavaScript -->
    </div>
</body>

</html>