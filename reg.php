<?php
include('conn.php');
include("index.php");

$security_questions = [
    "What was the name of your first pet?",
    "What is your mother's maiden name?",
    "What was the name of your elementary school?",
    "In what city were you born?",
    "What is your favorite food?"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = mysqli_real_escape_string($conn, $_POST['email']);
    $user_name = mysqli_real_escape_string($conn, $_POST['name']);
    $user_password = mysqli_real_escape_string($conn, $_POST['password']);
    $user_password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);
    $user_role = mysqli_real_escape_string($conn, $_POST['role']);
    $user_gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $user_address = mysqli_real_escape_string($conn, $_POST['address']);
    $user_contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $sec_qus = mysqli_real_escape_string($conn, $_POST['sec-qus']);
    $sec_ans = mysqli_real_escape_string($conn, $_POST['sec-ans']);

    $errors = [];

    // Check if email already exists
    $email_check_query = "SELECT * FROM user_tab WHERE user_id='$user_email' LIMIT 1";
    $result = mysqli_query($conn, $email_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['user_id'] === $user_email) {
            echo "<script>
                    alert('Email already exists. Redirecting to login page.');
                    window.location.href = 'login.php';
                  </script>";
            exit();
        }
    }

    // Password validation
    if ($user_password !== $user_password_confirm) {
        $errors[] = "Passwords do not match.";
    } elseif (strlen($user_password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Contact number validation
    if (!preg_match('/^[0-9]{10}$/', $user_contact)) {
        $errors[] = "Invalid contact number. It must be exactly 10 digits.";
    }

    if (count($errors) == 0) {
        // Insert user data into the database
        $sql = "INSERT INTO user_tab (user_id, user_name, user_password, user_role, user_gender, user_address, user_contact, sec_qus, sec_ans) 
                VALUES ('$user_email', '$user_name', '$user_password', '$user_role', '$user_gender', '$user_address', '$user_contact', '$sec_qus', '$sec_ans')";

        if (mysqli_query($conn, $sql)) {
            header("location: login.php");
        } else {
            $errors[] = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // Display all error messages
        echo "<div>" . implode('<br>', $errors) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">

    <title>Register</title>
    <script>
        function validateEmail(email) {
            const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(String(email).toLowerCase());
        }

        function validateForm() {
            const email = document.getElementById('email').value;
            if (!validateEmail(email)) {
                alert('Invalid email format');
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <div class="regcontainer">
        <div class="regbox">
            <h1>Register</h1>
            <form action="" method="post" onsubmit="return validateForm()">
                <label>Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label>Name:</label>
                <input type="text" name="name" required><br><br>

                <label>Password:</label>
                <input type="password" name="password" required><br><br>

                <label>Confirm Password:</label>
                <input type="password" name="password_confirm" required><br><br>

                <label>Security Question:</label>
                <select id="sec-qus" name="sec-qus" required>
                    <?php foreach ($security_questions as $question) : ?>
                        <option value="<?php echo htmlspecialchars($question); ?>"><?php echo htmlspecialchars($question); ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <label>Security Answer:</label>
                <input type="text" name="sec-ans" required><br><br>

                <label>Role:</label>
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="worker">Worker</option>
                </select><br><br>

                <label>Gender:</label>
                <select name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select><br><br>

                <label>Contact Number:</label>
                <input type="text" name="contact" required maxlength="10"><br><br>

                <label>Address:</label>
                <textarea name="address" required></textarea><br><br>
        </div>

        <input type="submit" value="Register">
        <div>
            <p>If you have an account, <a href="login.php">login now</a></p>
        </div>
        </form>
    </div>
</body>

</html>
