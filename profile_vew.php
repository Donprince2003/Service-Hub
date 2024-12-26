<?php
session_start();
include("conn.php");
include("index.php");
include("chatbutton.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$today = date("Y-m-d");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_worker_id'])) {
    $user_worker_id = mysqli_real_escape_string($conn, $_POST['user_worker_id']);
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT * FROM user_tab WHERE user_id = '$user_worker_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);

        $user_name = htmlspecialchars($user_data['user_name']);
        $user_address = htmlspecialchars($user_data['user_address']);
        $user_contact = htmlspecialchars($user_data['user_contact']);
        $user_gender = htmlspecialchars($user_data['user_gender']);
        $user_role = htmlspecialchars($user_data['user_role']);
        $user_rating = number_format($user_data['user_rating'], 1);

        // Retrieve image ID or set default
        $sql = "SELECT img_id FROM pro_img WHERE user_id = '$user_worker_id'";
        $result = mysqli_query($conn, $sql);
        $user_img = "image/" . (mysqli_num_rows($result) > 0 ? mysqli_fetch_assoc($result)['img_id'] : "d.png");

        if ($user_role == "worker") {
            $sql = "SELECT * FROM worker_tab WHERE user_id = '$user_worker_id'";
            $res = mysqli_query($conn, $sql);

            if (mysqli_num_rows($res) > 0) {
                $worker_data = mysqli_fetch_assoc($res);
            } else {
                $sql = "INSERT INTO worker_tab (worker_id, user_id, worker_job_field, worker_experience) VALUES ('', '$user_worker_id', 'no data found', 'no data found')";
                mysqli_query($conn, $sql);

                $res = mysqli_query($conn, $sql);
                $worker_data = mysqli_fetch_assoc($res);
            }

            $worker_id = htmlspecialchars($worker_data['worker_id']);
            $worker_job = htmlspecialchars($worker_data['worker_job_field']);
            $worker_exp = htmlspecialchars($worker_data['worker_experience']);
            $hour_rate = htmlspecialchars($worker_data['hour_rate']);
            $unique_id = "rateYo_" . $worker_id;
?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="style.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
                <style>
                    .checked {
                        color: orange;
                    }
                </style>
                <title>Profile Page</title>
            </head>

            <body>
                <div class="container">
                    <div class="centerbox">
                        <img src="<?php echo $user_img; ?>" alt="profile pic"><br><br>
                    </div>
                    <p><strong>Name:</strong> <?php echo $user_name; ?></p>
                    <p><strong>Address:</strong> <?php echo $user_address; ?></p>
                    <p><strong>Mobile Number:</strong> <?php echo $user_contact; ?></p>
                    <p><strong>Gender:</strong> <?php echo $user_gender; ?></p>
                    <p><strong>User Role:</strong> <?php echo $user_role; ?></p>
                    <p><strong>Worker ID:</strong> <?php echo $worker_id; ?></p>
                    <p><strong>Job:</strong> <?php echo $worker_job; ?></p>
                    <p><strong>Work Experience:</strong> <?php echo $worker_exp; ?></p>
                    <p><strong>Hourly Rate:</strong> <?php echo $hour_rate; ?> .Rs</p>

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

                    <form method="POST" action="add_job.php">
                        <label for="job_date">Job Date:</label>
                        <input type="date" id="job_date" name="job_date" required min="<?php echo $today; ?>"><br><br>

                        <label for="job_work">Job Description:</label>
                        <textarea id="job_work" name="job_work" required></textarea>

                        <input type="hidden" name="user_worker_id" value="<?php echo $user_worker_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"><br><br>

                        <input type="submit" name="submit" value="Submit Job Request">
                    </form>
                </div>







                <div class="topbar" style="display: flex;
        justify-content: center;
        align-items: center;">
                    <h1>Rating</h1>
                </div>
                <?php
                $sql = "SELECT * FROM job_tab WHERE job_worker = '$user_worker_id'";
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
    } else {
        echo ("<script>alert('Worker not found');</script>");
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>