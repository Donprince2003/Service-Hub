<?php
session_start();
include("conn.php");
include("index.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data from session
$user_id = $_SESSION['user_id'];

$sql = "SELECT user_id, user_name, user_address, user_contact, user_role FROM user_tab WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);

    $user_name = $user_data['user_name'];
    $user_address = $user_data['user_address'];
    $user_contact = $user_data['user_contact'];
    $user_role = $user_data['user_role'];

    $sql = "SELECT img_id FROM pro_img WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $img_data = mysqli_fetch_assoc($result);
        if ($img_data['img_id'] != "d.png") {
            $img_id = $img_data['img_id'];
            $id = "image/" . $img_id;
        } else {
            $id = "0";
        }
    }

    $sql = "SELECT * FROM worker_tab WHERE user_id = '$user_id'";
    $res = mysqli_query($conn, $sql);
    
    if ($res && mysqli_num_rows($res) > 0) {
        $worker_data = mysqli_fetch_assoc($res);
    

        $worker_job = $worker_data['worker_job_field'];
        $worker_exp = $worker_data['worker_experience'];
        $hour_rate = $worker_data['hour_rate'];

        $blue_collar_jobs = [
            "Electrician",
            "Plumber",
            "Carpenter",
            "Welder",
            "Mechanic",
            "Construction Worker",
            "Truck Driver",
            "Painter",
            "Mason",
            "HVAC Technician",
            "Landscaper",
            "Roofer",
            "Glazier",
            "Pest Control Worker",
            "Sheet Metal Worker",
            "Insulation Worker",
            "Maintenance Worker",
            "Pipefitter",
            "Steelworker",
            "Assembler"
        ];
    }
    else{
        $worker_job = null;
        $worker_exp =null;
        $hour_rate =null;

        $blue_collar_jobs = [
            "Electrician",
            "Plumber",
            "Carpenter",
            "Welder",
            "Mechanic",
            "Construction Worker",
            "Truck Driver",
            "Painter",
            "Mason",
            "HVAC Technician",
            "Landscaper",
            "Roofer",
            "Glazier",
            "Pest Control Worker",
            "Sheet Metal Worker",
            "Insulation Worker",
            "Maintenance Worker",
            "Pipefitter",
            "Steelworker",
            "Assembler"
        ];
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <title>Edit Profile</title>
        <script>
            // Function to show/hide worker fields
            function toggleWorkerFields() {
                var roleSelect = document.getElementById("role");
                var workerFields = document.getElementById("worker-fields");

                if (roleSelect.value === "worker") {
                    workerFields.style.display = "block"; // Show worker fields
                } else {
                    workerFields.style.display = "none"; // Hide worker fields
                }
            }

            // Run this function when the page loads to set the correct state
            window.onload = function() {
                toggleWorkerFields(); // Ensure the correct fields are shown when the page loads
            };
        </script>
    </head>

    <body>
        <div class="container">
            <h1>Edit Profile</h1>
            <div class="updatebox">

                <form method="POST" enctype="multipart/form-data">
                    <label for="img">Profile Picture:</label>
                    <input type="file" id="img" name="img"><br><br>

                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required><br><br>

                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_address); ?>" required><br><br>

                    <label for="contact">Mobile Number:</label>
                    <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($user_contact); ?>" required maxlength="10"><br><br>

                    <label for="role">Role:</label>
                    <select id="role" name="role" required onchange="toggleWorkerFields()">
                        <option value="user" <?php if ($user_role == 'user') echo 'selected'; ?>>User</option>
                        <option value="worker" <?php if ($user_role == 'worker') echo 'selected'; ?>>Worker</option>
                    </select><br><br>

                    <div id="worker-fields" style="display:none;">
                        <label for="job">Job:</label>
                        <select id="job" name="job" required>
                            <?php foreach ($blue_collar_jobs as $job) : ?>
                                <option value="<?php echo htmlspecialchars($job); ?>" <?php if ($worker_job == $job) echo 'selected'; ?>><?php echo htmlspecialchars($job); ?></option>
                            <?php endforeach; ?>
                        </select><br><br>

                        <label for="exp">Experience (in years):</label>
                        <input type="number" id="exp" name="exp" value="<?php echo htmlspecialchars($worker_exp); ?>" min="0"><br><br>
                        <br>
                        <label for="exp">Wage (in Hours):</label>
                        <input type="number" id="exp" name="rate" value="<?php echo htmlspecialchars($hour_rate); ?>" min="0"><br><br>


                    </div>

                    <input type="submit" value="Update Profile">
                </form>
                <a href="profile.php">Back to Profile</a>
            </div>
    </body>

    </html>

<?php
} else {
    echo "<script>alert('User not found!'); window.location.href = 'login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_address = mysqli_real_escape_string($conn, $_POST['address']);
    $new_contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $new_role = mysqli_real_escape_string($conn, $_POST['role']);
    $new_job = isset($_POST['job']) ? mysqli_real_escape_string($conn, $_POST['job']) : null;
    $new_exp = isset($_POST['exp']) ? mysqli_real_escape_string($conn, $_POST['exp']) : null;
    $new_hour_rate = isset($_POST['rate']) ? mysqli_real_escape_string($conn, $_POST['rate']) : null;

    if (isset($_FILES['img']) && $_FILES['img']['error'] === 0) {
        $img_name = $_FILES['img']['name'];
        $img_size = $_FILES['img']['size'];
        $tmp_name = $_FILES['img']['tmp_name'];

        if ($img_size > 5500000) {
            $em = "Sorry, your file is too large. The size should be smaller than 5.5MB";
            header("Location: profile_updation.php?error=$em");
            exit();
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png");
            if (in_array($img_ex_lc, $allowed_exs)) {
                if ($id == 0) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = 'image/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
                } else {
                    if (!unlink($img_id)) {
                        echo "$id cannot be deleted due to an error";
                    }
                    $sql = "DELETE FROM `pro_img` WHERE `user_id` ='$user_id'";
                    mysqli_query($conn, $sql);

                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = 'image/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
                }

                $sql = "INSERT INTO pro_img(img_id, user_id) VALUES('$new_img_name', '$user_id') ON DUPLICATE KEY UPDATE img_id='$new_img_name'";
                mysqli_query($conn, $sql);
            } else {
                $em = "You can't upload files of this type";
                header("Location: profile_updation.php?error=$em");
                exit();
            }
        }
    }
    $_SESSION['user_role'] = $new_role;
    $update_sql = "UPDATE user_tab SET user_name = '$new_name', user_address = '$new_address', user_contact = '$new_contact', user_role = '$new_role' WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $update_sql);

    if ($new_role == "worker") {

        $update_worker_sql = "INSERT INTO `log_tab`(`worker_id`, `old_job`, `new_job`) VALUES ('$user_id','$worker_job','$new_job')";
        mysqli_query($conn, $update_worker_sql);
        $update_worker_sql = "UPDATE worker_tab SET worker_job_field = '$new_job', worker_experience = '$new_exp', hour_rate='$new_hour_rate' WHERE user_id = '$user_id'";
        $result_worker = mysqli_query($conn, $update_worker_sql);
    }

    if ($result && ($new_role != "worker" || $result_worker)) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile!');</script>";
    }
}

$conn->close();
?>