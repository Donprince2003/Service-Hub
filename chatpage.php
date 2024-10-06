<?php
session_start();
include("conn.php");
include("index.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="style.css">
    <style>
       
       html, body {
            margin: 0;
            height: 97%; /* Optional if you want full-page background color or centering */
        }

        .main-content {
            
            height: 100%; /* Full viewport height to center the iframe */
        }

        #myIframe {
            width: 100%; /* Fixed width */
            height: 100%; /* Fixed height */
            border: none; /* Remove default border */
        }

        
    </style>
    
</head>

<body>
    <div class="main-content">
            <iframe id="myIframe" src="chat.php" frameborder="0"></iframe>
    </div>
</body>

</html>
