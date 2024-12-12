<?php
include("index.php");
?><!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="logincontainer">
        <h1>Forgot Password </h1>
        <div class="loginbox">
            <form method="post" action="send-password-reset.php">
                <input type="email" placeholder="Enter Your Email" name="email" id="email">
        </div>
        <input type="submit" value="Submit">
    </div>

    </form>

</body>

</html>