<?php
session_start();

if (isset($_POST['submit_otp'])) {
    $entered_otp = $_POST['otp'];

    // Check if the session has the correct OTP
    if ($_SESSION['otp'] == $entered_otp) {
        // OTP matches, now register the user in the database
        include('conn.php');

        // Retrieve user details from session
        $user_details = $_SESSION['user_details'];

        // Insert user into the database
        $sql = "INSERT INTO `user_tab` (user_id, user_name, user_password, user_role, user_gender, user_address, user_contact) 
                VALUES ('".$user_details['email']."', '".$user_details['name']."', '".$user_details['password']."', '".$user_details['role']."', '".$user_details['gender']."', '".$user_details['address']."', '".$user_details['contact']."')";

        if (mysqli_query($conn, $sql)) {
            // Registration successful, clear session and redirect to login page
            unset($_SESSION['otp']);
            unset($_SESSION['user_details']);
            echo "<script>
                    alert('Registration successful!');
                    window.location.href = 'login.php';
                  </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // OTP does not match
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style1.css">
    <title>Verify OTP</title>
</head>
<body>
<div class="container">
    <h2>Verify OTP</h2>
    <form action="" method="post">
        <label for="otp">Enter OTP:</label>
        <input type="text" name="otp" required><br><br>
        
        <input type="submit" name="submit_otp" value="Verify OTP">
    </form>
</div>
</body>
</html>

