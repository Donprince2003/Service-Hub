<?php
session_start();
include("conn.php");
include("index.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['job_id'])) {
    $job_worker = $_POST['worker_id'];
    $job_id = $_POST['job_id'];
    $job_user = $_POST['job_user'];
} else {
    echo "No data found";
    exit();
}
$_SESSION['job_id'] = $job_id;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Pay</title>
</head>

<body>

    <div class="log">
        <?php
        // Fetch hourly rate from worker_tab
        $sql = "SELECT hour_rate FROM worker_tab WHERE user_id='$job_worker'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hour_rate = $row['hour_rate'];
        } else {
            echo "Worker not found.";
            exit();
        }

        // Fetch job start and end times from job_tab
        $sql = "SELECT job_start, job_end FROM job_tab WHERE job_id='$job_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $job_start = $row['job_start'];
                $job_end = $row['job_end'];

                // Convert to DateTime objects
                $start_time = new DateTime($job_start);
                $end_time = new DateTime($job_end);

                // Calculate the time difference
                $interval = $start_time->diff($end_time);

                // Convert the time difference into hours
                $hours = $interval->h + ($interval->days * 24) + ($interval->i / 60) + ($interval->s / 3600);

                // Calculate total cost
                $total_cost = $hours * $hour_rate;

                // Display the result
                echo "<h1>Total cost: " . number_format($total_cost, 2) . " Rs</h1>";
                echo "<p class='paypage'>Job start: " . $job_start . " | Job end: " . $job_end . " | Hours worked: " . number_format($hours, 2) . "</p>";

                // Update the job_cost in job_tab
                $sql = "UPDATE job_tab SET job_cost = " . number_format($total_cost, 2) . " WHERE job_id = '$job_id'";
                if ($conn->query($sql) === TRUE) {
                } else {
                    echo "Error updating job cost: " . $conn->error;
                }
            }
        } else {
            echo "No job found.";
        }
        ?>

        <h1>Select payment method</h1>
        <form action="check.php" method="POST" class="item-actions">
            <?php
            echo "<input type='hidden' name='cost' value='$total_cost'>";
            echo "<input type='hidden' name='job_id' value='$job_id'>";
            echo "<input type='hidden' name='worker_id' value='$job_worker'>";
            echo "<input type='hidden' name='job_user' value='$job_user'>";
            ?>
            <button class='submit' type='submit' name='action' value='confirm'>Online</button>
        </form>
        <br>
        <form action="offline-check.php" method="POST" class="item-actions">
            <?php
            echo "<input type='hidden' name='job_id' value='$job_id'>";
            echo "<input type='hidden' name='worker_id' value='$job_worker'>";
            echo "<input type='hidden' name='job_user' value='$job_user'>";
            ?>
            <button class='submit' type='submit' name='action' value='confirm'>Offline</button>
        </form>
        <br><br>
    </div>

</body>

</html>