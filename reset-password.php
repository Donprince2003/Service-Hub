<?php
include("index.php");


$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM user_tab
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        function validateForm() {
            const password = document.getElementById("password").value;
            const passwordConfirmation = document.getElementById("password_confirmation").value;

            // Check if password length is at least 8 characters
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }

            // Check if passwords match
            if (password !== passwordConfirmation) {
                alert("Passwords do not match.");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <div class="logincontainer">

        <h1>Reset Password</h1>
        <div class="loginbox">

            <form method="post" action="process-reset-password.php" onsubmit="return validateForm();">

                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <label for="password">New password</label>
                <input type="password" id="password" name="password">

                <label for="password_confirmation">Repeat password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
        <input type="submit" name="submit" value="Submit">
        </form>
    </div>

</body>

</html>
