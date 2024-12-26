<?php
session_start();
include("conn.php");
include("index.php");
include("chatbutton.php");

$user_id = 'ooo';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

$sql = "SELECT u.user_name, u.user_address, u.user_contact, u.user_rating, w.worker_id, w.user_id, w.worker_job_field, w.worker_experience, w.worker_status, w.hour_rate, p.img_id 
        FROM user_tab u 
        LEFT JOIN worker_tab w ON u.user_id = w.user_id
        LEFT JOIN pro_img p ON u.user_id = p.user_id
        WHERE u.user_role = 'worker'";
$result = mysqli_query($conn, $sql);
$img_path = "image/d.png";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        .checked {
            color: orange;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<div class="homecontainer">
    <?php
    $match_found = false;

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['Search'])) {
        $search_string  = $_GET['Search'];

        if ($result && mysqli_num_rows($result) > 0) :
            while ($worker = mysqli_fetch_assoc($result)) :
                if ($worker['worker_status'] == 1 && $worker['user_id'] != $user_id) {
                    // Check for a match in any of the fields (user_name, user_address, user_contact, etc.)
                    $worker_values = array(
                        $worker['user_name'],
                        $worker['user_address'],
                        $worker['user_contact'],
                        $worker['worker_job_field'],
                        $worker['worker_experience'],
                        $worker['hour_rate']
                    );

                    // Match search string in any of the fields
                    foreach ($worker_values as $value) {
                        if (!is_null($value) && strpos(strtolower($value), strtolower($search_string)) !== false) {
                            $match_found = true;
                            break 2; // Exit both loops if a match is found
                        }
                    }
                }
            endwhile;
        endif;

        // Display search results
        if ($match_found) :
            mysqli_data_seek($result, 0); // Reset the result pointer
            while ($worker = mysqli_fetch_assoc($result)) :
                if ($worker['worker_status'] == 1 && $worker['user_id'] != $user_id) {
                    // Display worker details if they match the search
                    $worker_values = array(
                        $worker['user_name'],
                        $worker['user_address'],
                        $worker['user_contact'],
                        $worker['worker_job_field'],
                        $worker['worker_experience'],
                        $worker['hour_rate']
                    );
                    foreach ($worker_values as $value) {
                        if (!is_null($value) && strpos(strtolower($value), strtolower($search_string)) !== false) {
                            $img_path = ($worker['img_id'] && $worker['img_id'] != "d.png") ? "image/" . $worker['img_id'] : "image/d.png";
                            ?>
                            <div class="homebox">
                                <div class="centerbox">
                                    <img src="<?php echo $img_path; ?>" alt="Profile Picture"><br><br>
                                </div>
                                <div class="col">
                                    <p><strong>Worker ID:</strong> <?php echo htmlspecialchars($worker['worker_id']); ?></p>
                                    <p><strong>Name:</strong> <?php echo htmlspecialchars($worker['user_name']); ?></p>
                                    <p><strong>Address:</strong> <?php echo htmlspecialchars($worker['user_address']); ?></p>
                                    <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($worker['user_contact']); ?></p>
                                    <p><strong>Job:</strong> <?php echo htmlspecialchars($worker['worker_job_field']); ?></p>
                                    <p><strong>Experience (in years):</strong> <?php echo htmlspecialchars($worker['worker_experience']); ?></p>
                                    <p><strong>Hourly rate:</strong> <?php echo htmlspecialchars($worker['hour_rate']); ?>.Rs</p>

                                    <p>
                                        <?php
                                        $roundedRating = round($worker['user_rating']);
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $roundedRating) {
                                                echo "<span class='fa fa-star checked'></span>";
                                            } else {
                                                echo "<span class='fa fa-star'></span>";
                                            }
                                        }
                                        echo htmlspecialchars(number_format($worker['user_rating'], 1)); ?>/5
                                    </p>
                                </div>
                                <div class="col">
                                    <form method="POST" action="profile_vew.php">
                                        <input type="hidden" name="user_worker_id" value="<?php echo htmlspecialchars($worker['user_id']); ?>">
                                        <button class="greenbutton" type="submit" name="submit">Select worker</button>
                                    </form>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
            endwhile;
        else :
            echo "No match found.";
        endif;
    } else {
        // Default display of workers when no search is performed
        if ($result && mysqli_num_rows($result) > 0) :
            while ($worker = mysqli_fetch_assoc($result)) :
                if ($worker['worker_status'] == 1 && $worker['user_id'] != $user_id) {
                    $img_path = ($worker['img_id'] && $worker['img_id'] != "d.png") ? "image/" . $worker['img_id'] : "image/d.png";
                    ?>
                    <div class="homebox">
                        <div class="centerbox">
                            <img src="<?php echo $img_path; ?>" alt="Profile Picture"><br><br>
                        </div>
                        <div class="col">
                            <p><strong>Worker ID:</strong> <?php echo htmlspecialchars($worker['worker_id']); ?></p>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($worker['user_name']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($worker['user_address']); ?></p>
                            <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($worker['user_contact']); ?></p>
                            <p><strong>Job:</strong> <?php echo htmlspecialchars($worker['worker_job_field']); ?></p>
                            <p><strong>Experience (in years):</strong> <?php echo htmlspecialchars($worker['worker_experience']); ?></p>
                            <p><strong>Hourly rate:</strong> <?php echo htmlspecialchars($worker['hour_rate']); ?>.Rs</p>

                            <p>
                                <?php
                                $roundedRating = round($worker['user_rating']);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $roundedRating) {
                                        echo "<span class='fa fa-star checked'></span>";
                                    } else {
                                        echo "<span class='fa fa-star'></span>";
                                    }
                                }
                                echo htmlspecialchars(number_format($worker['user_rating'], 1)); ?>/5
                                </p>
                        </div>
                        <div class="col">
                            <form method="POST" action="profile_vew.php">
                                <input type="hidden" name="user_worker_id" value="<?php echo htmlspecialchars($worker['user_id']); ?>">
                                <button class="greenbutton" type="submit" name="submit">Select worker</button>
                            </form>
                        </div>
                    </div>
                <?php
                }
            endwhile;
        else :
            echo "<p>No workers found.</p>";
        endif;
    }
    ?>
</div>

</body>

</html>
