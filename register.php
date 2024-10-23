<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is included

include('conn.php');
session_start();

if (isset($_POST['submit'])) {
    $user_email = $_POST['email'];
    $user_name = $_POST['name'];
    $user_password = $_POST['password'];
    $user_password_confirm = $_POST['password_confirm'];
    $user_role = $_POST['role'];
    $user_gender = $_POST['gender'];
    $user_address = $_POST['address'];
    $user_contact = $_POST['contact'];

    // Check if email already exists
    $email_check_query = "SELECT * FROM user_tab WHERE user_id='$user_email' LIMIT 1";
    $result = mysqli_query($conn, $email_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        echo "<script>alert('Email already exists');</script>";
    } elseif ($user_password !== $user_password_confirm) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);
        
        // Save OTP and user details in session for later use
        $_SESSION['otp'] = $otp;
        $_SESSION['user_details'] = [
            'email' => $user_email,
            'name' => $user_name,
            'password' => $user_password,
            'role' => $user_role,
            'gender' => $user_gender,
            'address' => $user_address,
            'contact' => $user_contact
        ];

        // Send OTP to user's email using PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'servicehub343@gmail.com'; // SMTP username
            $mail->Password = 'czzx vdln tpfu keoq';    // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('servicehub343@gmail.com', 'Service Hub');
            $mail->addAddress($user_email); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "<h3>Your OTP code is: $otp</h3>";

            $mail->send();
            echo "<script>
                    alert('OTP has been sent to your email');
                    window.location.href = 'verify_otp.php';
                  </script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style1.css">
    <title>Register</title>
</head>
<body>
<div class="container">
    <h2>Register</h2>
    <form action="" method="post">
        <!-- Email -->
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        
        <!-- Name -->
        <label>Name:</label>
        <input type="text" name="name" required><br><br>
        
        <!-- Password -->
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        
        <!-- Confirm Password -->
        <label>Confirm Password:</label>
        <input type="password" name="password_confirm" required><br><br>
        
        <!-- Role -->
        <label>Role:</label>
        <select name="role" required>
            <option value="user">User</option>
            <option value="worker">Worker</option>
        </select><br><br>
        
        <!-- Gender -->
        <label>Gender:</label>
        <select name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br><br>

        <!-- Contact Number -->
        <label>Contact Number:</label>
        <input type="text" name="contact" required maxlength="10"><br><br>
        
        <!-- Address -->
        <label>Address:</label>
        <textarea name="address" required></textarea><br><br>
        
        <!-- Submit Button -->
        <input type="submit" name="submit" value="Register">
        <div>
            <a href="login.php">login now</a>
        </div>
    </form>
</div>
</body>
</html>