<?php
session_start();
include("conn.php");
include("index.php");

if (isset($_GET['username'])) {
    $username = urldecode($_GET['username']);
}

if (isset($_POST['submit'])) {
    $sec_qus = mysqli_real_escape_string($conn, $_POST['sec-qus']);
    $sec_ans = mysqli_real_escape_string($conn, $_POST['sec-ans']);
    $sql = "SELECT * FROM user_tab WHERE user_id = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_qus = $row['sec_qus'];
        $user_ans = $row['sec_ans'];
        if ($sec_qus == $user_qus && $sec_ans == $user_ans) {
            header("Location: s-check3.php?username=" . urlencode($username));
            exit();
        } else {
            echo "<script>alert('Wrong Answer or Question!');</script>";

        }
    }
}
$security_questions = [
    "What was the name of your first pet?",
    "What is your mother's maiden name?",
    "What was the name of your elementary school?",
    "In what city were you born?",
    "What is your favorite food?"
];
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Forgot Password</title>
</head>

<body>
    <div class="logincontainer">
        <h1>Forgot Password</h1>
        <div class="loginbox">
            <form name="id-check" method="POST">
                <label>Security Question:</label>
                <select id="sec-qus" name="sec-qus" required>
                    <?php foreach ($security_questions as $question) : ?>
                        <option value="<?php echo htmlspecialchars($question); ?>"><?php echo htmlspecialchars($question); ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <label>Security Answer:</label>
                <input type="text" name="sec-ans" required><br><br>
        </div>
        <input type="submit" name="submit" value="Submit">
        </form>
    </div>
</body>

</html>