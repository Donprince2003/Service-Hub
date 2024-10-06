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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Worker Confirmation Dashboard</title>
    <script>
       
    </script>
</head>

<body>



    <?php
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM user_tab";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row['user_id'];

            $user_img = "d.png";
            $sql = "SELECT img_id FROM pro_img WHERE user_id = '$user_id'";
            $img_result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($img_result) > 0) {
                $img_data = mysqli_fetch_assoc($img_result);
                $user_img = "image/" . $img_data['img_id'];
            }

            echo "<div id='jobcontainer' class='jobcontainer'>";
            echo "<div id='jobbox' class='jobbox'>";
            echo "<div class='grid'>";
            echo "<div class='col'>";
            echo "<img class='homeimg' src='" . $user_img . "'alt='profile pic'><br><br>";
            echo "</div>";
            echo "<div class='col'>";
            echo "<p>";
            echo "name : " . $row['user_name'] . "<br>";
            echo "address : " . $row['user_address'] . "<br>";
            echo "mobile : " . $row['user_contact'] . "<br>";
            echo "gender : " . $row['user_gender'] . "<br>";
            echo "role :  " . $row['user_role'] . "<br>";
            if ($row['user_role'] == 'worker') {
                $user_sql = "SELECT * FROM `worker_tab` WHERE user_id='$user_id'";
                $user_result = mysqli_query($conn, $user_sql);
                $row1 = mysqli_fetch_assoc($user_result);

                echo "worker id : " . $row1['user_id'] . "<br>";
                echo "job : " . $row1['worker_job_field'] . "<br>";
                echo "work experience : " . $row1['worker_experience'] . "<br>";
                echo "</p>";
            }



            echo "</div>";
            echo "<div class='col'></div>";
            echo "<div class='col'>";
            echo "<form action='manage_user2.php' method='POST'>";
            echo "<input type='hidden' name='user_id' value='$user_id'>";
            if ($row['user_status'] == 1) {
                echo "<button class='greenbutton' type='submit' name='action' value='confirm'>unblock user</button>";
            }
            if ($row['user_status'] == 0) {
                echo "<button class='redbutton' type='submit' name='action' value='decline'>block user</button>";
            }
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "No workers available.";
    }

    

    ?>

</body>

</html>