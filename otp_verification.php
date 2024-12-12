<?php
session_start();
include('conn.php');
include('index.php');


// Check if OTP is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];

    // Retrieve the OTP stored in the session
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $user_otp) {
        // OTP matched, insert the user's data into the database
        $user_data = $_SESSION['user_data'];
        $user_email = mysqli_real_escape_string($conn, $user_data['email']);
        $user_name = mysqli_real_escape_string($conn, $user_data['name']);
        $user_password = mysqli_real_escape_string($conn, $user_data['password']);
        $user_role = mysqli_real_escape_string($conn, $user_data['role']);
        $user_gender = mysqli_real_escape_string($conn, $user_data['gender']);
        $user_address = mysqli_real_escape_string($conn, $user_data['address']);
        $user_contact = mysqli_real_escape_string($conn, $user_data['contact']);
        $sec_qus = mysqli_real_escape_string($conn, $user_data['sec_qus']);
        $sec_ans = mysqli_real_escape_string($conn, $user_data['sec_ans']);

        // Insert the user's data into the database
        $sql = "INSERT INTO user_tab (user_id, user_name, user_password, user_role, user_gender, user_address, user_contact, sec_qus, sec_ans) 
                VALUES ('$user_email', '$user_name', '$user_password', '$user_role', '$user_gender', '$user_address', '$user_contact', '$sec_qus', '$sec_ans')";

        if (mysqli_query($conn, $sql)) {
            // Clear the session after successful registration
            unset($_SESSION['otp']);
            unset($_SESSION['user_data']);

            // Redirect to login page after successful registration
            echo "<script>
                    alert('Registration successful. Please login.');
                    window.location.href = 'login.php';
                  </script>";
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // OTP didn't match
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="regcontainer">
        <div class="regbox">
            <h1>OTP Verification</h1>
            <form action="" method="post">
                <label>Enter OTP:</label>
                <input type="text" name="otp" required maxlength="6"><br><br>
        </div>

        <input type="submit" value="Verify OTP">
        </form>
    </div>
</body>

</html>