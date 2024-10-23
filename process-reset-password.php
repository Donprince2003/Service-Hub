<?php

if (!isset($_POST["token"], $_POST["password"], $_POST["password_confirmation"])) {
    die("Invalid request.");
}

$token = $_POST["token"];
$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

if ($mysqli->connect_errno) {
    die("Failed to connect to the database: " . $mysqli->connect_error);
}

$sql = "SELECT * FROM user_tab WHERE reset_token_hash = ?";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $mysqli->error);
}

$stmt->bind_param("s", $token_hash);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired at " . $user["reset_token_expires_at"]);
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

// No hashing for the password, storing it in plain text (insecure)
$password_plain = $_POST["password"];

$sql = "UPDATE user_tab
        SET user_password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE user_id = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $mysqli->error);
}

$stmt->bind_param("ss", $password_plain, $user["user_id"]);

if (!$stmt->execute()) {
    die("Password update failed: " . $stmt->error);
}

if ($stmt->affected_rows === 0) {
    die("Password was not updated. Either no matching user found or the new password matches the old one.");
}

// Show success message and redirect after a few seconds
echo "";
echo '<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="log">
<h1>Password updated successfully!</h1>
<p> You will be redirected to the login page in 3 seconds...</p>
    <script>
    setTimeout(function() {
        window.location.href = "/mini2/login.php";
    }, 3000); // Redirect after 3 seconds
</script>
</div>
</body>
</html';

exit;

?>

