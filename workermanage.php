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
    <title>Worker Confirmation Dashboard</title>
</head>

<body>

    <?php
    $worker_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM worker_tab";
    $result = mysqli_query($conn, $sql);



    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $worker_id = $row['worker_id'];
            $user_id = $row['user_id'];

            if ($row['worker_status'] == 3) {

                $user_sql = "SELECT `user_name`,`user_gender`, `user_address`, `user_contact` FROM `user_tab` WHERE user_id='$user_id'";
                $user_result = mysqli_query($conn, $user_sql);
                $user_info = mysqli_fetch_assoc($user_result);

                echo "<div class='jobcontainer'>";
                echo "<div class='jobbox'>";
                echo "<div class='grid'>";
                echo "<div class='col'>";
                echo "<img class='homeimg' src='d.png' alt='profile pic'><br><br>";
                echo "</div>";
                echo "<div class='col'>";
                echo "<p>";
                echo "name : " . $user_info['user_name'] . "<br>";
                echo "address : " . $user_info['user_address'] . "<br>";
                echo "mobile : " . $user_info['user_contact'] . "<br>";
                echo "gender : " . $user_info['user_gender'] . "<br>";
                echo "role : worker<br>";
                echo "job : " . $row['worker_job_field'] . "<br>";
                echo "work experience : " . $row['worker_experience'] . "<br>";
                echo "</p>";
                echo "</div>";
                echo "<div class='col'></div>";
                echo "<div class='col'>";
                echo "<form action='workermanage2.php' method='POST'>";
                echo "<input type='hidden' name='worker_id' value='$worker_id'>";
                echo "<button class='greenbutton' type='submit' name='action' value='confirm'>Accept</button>";
                echo "<button class='redbutton' type='submit' name='action' value='decline'>Reject</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
    } 

    ?>

</body>

</html>