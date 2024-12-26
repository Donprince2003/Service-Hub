<?php
session_start();
include("conn.php");
include("index.php");
$worker_id = $job_id = $action = $job_user = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['job_id'])) {
    $worker_id = $_POST['worker_id'];
    $job_id = $_POST['job_id'];
    $action = $_POST['action'];
    $job_user = $_POST['job_user'];
    $_SESSION['worker_id'] = $worker_id;
    $_SESSION['job_id'] = $job_id;
} else {
    echo ("first if not working");
}
?>

<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="logincontainer">
        <form action="" method="post">
            <h1>Rating</h1>
            <div class="loginbox">
                <div class="rateyo" id="rating"
                    data-rateyo-rating="0"
                    data-rateyo-num-stars="5"
                    data-rateyo-score="3">
                </div>
                <span class='result'>0</span>
                <input type="hidden" name="rating">
                <div>
                    <h1>Review</h1>
                    <input type="text" name="name">
                </div>
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($job_id); ?>">
            </div>
            <div><input type="submit" name="add"> </div>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script>
        $(function() {
            $(".rateyo").rateYo().on("rateyo.change", function(e, data) {
                var rating = data.rating;
                $(this).parent().find('.score').text('score :' + $(this).attr('data-rateyo-score'));
                $(this).parent().find('.result').text('rating :' + rating);
                $(this).parent().find('input[name=rating]').val(rating); //add rating value to input field
            });
        });
    </script>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $rating = floatval($_POST["rating"]);
    $worker_id = $_SESSION['worker_id'];
    $job_id = $_SESSION['job_id'];

    echo "Name: " . htmlspecialchars($name) . "<br>";
    echo "Rating: " . htmlspecialchars($rating) . "<br>";
    echo "Worker ID: " . htmlspecialchars($worker_id) . "<br>";
    echo "Job ID: " . htmlspecialchars($job_id) . "<br>";

    // Update the job_tab with sanitized input
    $sql = "UPDATE `job_tab` SET `job_user_rev`='$name', `job_user_rating`=$rating, `job_status`=5 WHERE job_id=$job_id";
    if (mysqli_query($conn, $sql)) {
        // Retrieve user data from user_tab
        $sql = "SELECT `user_rating`, `user_rating_no` FROM `user_tab` WHERE user_id = '$worker_id'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $user_rating_no = $user_data['user_rating_no'];
            $user_rating = $user_data['user_rating'];

            $user_rating_no = $user_rating_no + 1;
            $new_rating = round((($user_rating * ($user_rating_no - 1)) + $rating) / $user_rating_no, 2);

            // Update user_tab with the new rating
            $update_sql = "UPDATE `user_tab` SET `user_rating`='$new_rating', `user_rating_no`='$user_rating_no' WHERE user_id='$worker_id'";
            if (mysqli_query($conn, $update_sql)) {
                echo "<script>window.location.href='request_status.php';</script>";
            } else {
                echo "Error updating user rating: " . mysqli_error($conn);
            }
        } else {
            echo "Error retrieving user data: " . mysqli_error($conn);
        }
    } else {
        echo "Error updating job table: " . mysqli_error($conn);
    }

    unset($_SESSION['worker_id']);
    unset($_SESSION['job_id']);
    mysqli_close($conn);
}
?>
