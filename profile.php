<?php
session_start();
include("conn.php");
include("index.php");
include("chatbutton.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data from session
$user_id = $_SESSION['user_id'];

// Get user details
$sql = "SELECT user_id, user_name, user_address, user_contact, user_gender, user_role, user_rating FROM user_tab WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user_data = mysqli_fetch_assoc($result);

$user_name = $user_data['user_name'];
$user_address = $user_data['user_address'];
$user_contact = $user_data['user_contact'];
$user_gender = $user_data['user_gender'];
$user_role = $user_data['user_role'];
$user_rating = $user_data['user_rating'];
$user_img = "d.png";

// Get user image
$sql = "SELECT img_id FROM pro_img WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $img_data = mysqli_fetch_assoc($result);
    $user_img = "image/" . $img_data['img_id'];
}
if ($user_role == "user") {
?>
    <html>

    <head>
        <title>Profile</title>
        <style>
        .checked {
            color: orange;
        }
    </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>

        <div class="container">
            <div class="centerbox">
                <img src="<?php echo $user_img; ?>" alt="profile pic"><br><br>
            </div>
            <p>
                <!-- Dynamically display user information -->
                Name: <?php echo htmlspecialchars($user_name); ?> <br>
                Address: <?php echo htmlspecialchars($user_address); ?> <br>
                Mobile: <?php echo htmlspecialchars($user_contact); ?> <br>
                Gender: <?php echo htmlspecialchars($user_gender); ?> <br>
                Role: <?php echo htmlspecialchars($user_role); ?> <br>
                <p>
                <?php

                $roundedRating = round($user_rating);

                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $roundedRating) {
                        echo "<span class='fa fa-star checked'></span>";
                    } else {
                        echo "<span class='fa fa-star'></span>";
                    }
                }


                echo htmlspecialchars(number_format($user_rating, 1)); ?>/5
            </p>

            </p>
            <div>
                <a href="update.php"><input type="submit" name="update" value="Update User Data"></a>
            </div>
            <div>
                <a href="changepassword.php"><input type="submit" name="changepassword" value="Change Password"></a>
            </div>

        </div>





        <div class="topbar" style="display: flex;
        justify-content: center;
        align-items: center;">
                    <h1>Rating</h1>
                </div>
                <?php
                $sql = "SELECT * FROM job_tab WHERE job_user = '$user_id'";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) :
                    while ($job = mysqli_fetch_assoc($result)) :
                        if ($job['requst'] == 5 && $job['job_status'] == 5) {
                ?>
                            <div class="commentcontainer">
                                <div class="topbar">
                                    <div>
                                        <p>
                                            <?php

                                            $roundedRating = round($job['job_worker_rating']);

                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $roundedRating) {
                                                    echo "<span class='fa fa-star checked'></span>";
                                                } else {
                                                    echo "<span class='fa fa-star'></span>";
                                                }
                                            }


                                            echo htmlspecialchars(number_format($job['job_worker_rating'], 1)); ?>/5
                                        </p>
                                    </div>
                                    <div>username: <?php echo htmlspecialchars($job['job_worker']); ?></div>

                                </div>
                                <div class="commentbox">
                                    <?php echo htmlspecialchars($job['job_worker_rev']); ?>
                                    <br>
                                    <br>
                                    <div class="date">
                                        <strong>Date:</strong> <?php echo htmlspecialchars($job['job_date']); ?>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    endwhile; ?>
                <?php else : ?>
                    <div class="commentcontainer">
                        <div class="topbar" style="display: flex;
        justify-content: center;
        align-items: center;">
                            <p>No Rating found.</p>
                        </div>
                    </div>
                <?php endif; ?>





    </body>

    </html>
<?php
} elseif ($user_role == "admin") {
?>
    <html>

    <head>
        <title>Profile</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>

        <div class="container">
            <div class="centerbox">
                <img src="<?php echo $user_img; ?>" alt="profile pic"><br><br>
            </div>
            <p>
                <!-- Dynamically display user information -->
                Name: <?php echo htmlspecialchars($user_name); ?> <br>
                Address: <?php echo htmlspecialchars($user_address); ?> <br>
                Mobile: <?php echo htmlspecialchars($user_contact); ?> <br>
                Gender: <?php echo htmlspecialchars($user_gender); ?> <br>
                Role: <?php echo htmlspecialchars($user_role); ?> <br>
            </p>
        </div>
    </body>

    </html>
<?php

} elseif ($user_role == "worker") {
    // Fetch worker-specific data
    $sql = "SELECT * FROM worker_tab WHERE user_id = '$user_id'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $worker_data = mysqli_fetch_assoc($res);
    } else {
        // Insert default data if none found
        $sql = "INSERT INTO worker_tab (worker_id, user_id, worker_job_field, worker_experience) VALUES ('', '$user_id', 'no data found', 'no data found')";
        $res = mysqli_query($conn, $sql);

        $sql = "SELECT * FROM worker_tab WHERE user_id = '$user_id'";
        $res = mysqli_query($conn, $sql);
        $worker_data = mysqli_fetch_assoc($res);
    }

    $worker_job = $worker_data['worker_job_field'];
    $worker_exp = $worker_data['worker_experience'];
    $hour_rate = $worker_data['hour_rate'];

?>
    <html>

    <head>
        <title>Profile</title>
        <style>
        .checked {
            color: orange;
        }
    </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>

        <div class="container">
            <div class="centerbox">
                <img src="<?php echo $user_img; ?>" alt="profile pic"><br><br>
            </div>
            <p>
                <!-- Dynamically display user information -->
                Name: <?php echo htmlspecialchars($user_name); ?> <br>
                Address: <?php echo htmlspecialchars($user_address); ?> <br>
                Mobile: <?php echo htmlspecialchars($user_contact); ?> <br>
                Gender: <?php echo htmlspecialchars($user_gender); ?> <br>
                Role: <?php echo htmlspecialchars($user_role); ?> <br>
                Job: <?php echo htmlspecialchars($worker_job); ?> <br>
                Work Experience: <?php echo htmlspecialchars($worker_exp); ?> years <br>
                Hourly rate: <?php echo htmlspecialchars($hour_rate); ?> .Rs <br>
            <p>
                <?php

                $roundedRating = round($user_rating);

                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $roundedRating) {
                        echo "<span class='fa fa-star checked'></span>";
                    } else {
                        echo "<span class='fa fa-star'></span>";
                    }
                }


                echo htmlspecialchars(number_format($user_rating, 1)); ?>/5
            </p>

            </p>
            <div>
                <a href="update.php"><input type="submit" name="update" value="Update User Data"></a>
            </div>
            <div>
                <a href="changepassword.php"><input type="submit" name="changepassword" value="Change Password"></a>
            </div>

        </div>


        <div class="topbar" style="display: flex;
        justify-content: center;
        align-items: center;">
                    <h1>Rating</h1>
                </div>
                <?php
                $sql = "SELECT * FROM job_tab WHERE job_worker = '$user_id'";
                $result = mysqli_query($conn, $sql);

                if ($result && mysqli_num_rows($result) > 0) :
                    while ($job = mysqli_fetch_assoc($result)) :
                        if ($job['requst'] == 5 && $job['job_status'] == 5) {
                ?>
                            <div class="commentcontainer">
                                <div class="topbar">
                                    <div>
                                        <p>
                                            <?php

                                            $roundedRating = round($job['job_user_rating']);

                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $roundedRating) {
                                                    echo "<span class='fa fa-star checked'></span>";
                                                } else {
                                                    echo "<span class='fa fa-star'></span>";
                                                }
                                            }


                                            echo htmlspecialchars(number_format($job['job_user_rating'], 1)); ?>/5
                                        </p>
                                    </div>
                                    <div>username: <?php echo htmlspecialchars($job['job_user']); ?></div>

                                </div>
                                <div class="commentbox">
                                    <?php echo htmlspecialchars($job['job_user_rev']); ?>
                                    <br>
                                    <br>
                                    <div class="date">
                                        <strong>Date:</strong> <?php echo htmlspecialchars($job['job_date']); ?>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    endwhile; ?>
                <?php else : ?>
                    <div class="commentcontainer">
                        <div class="topbar" style="display: flex;
        justify-content: center;
        align-items: center;">
                            <p>No Rating found.</p>
                        </div>
                    </div>
                <?php endif; ?>


    </body>

    </html>
<?php
}
?>