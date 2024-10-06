<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Button</title>
    <style>
        

        .message-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #0084ff;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .message-button:hover {
            background-color: #006bb3;
        }

        .message-button p {
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="message-button" onclick="goToChatPage()">
        <p>💬Chat</p>
    </div>

    <script>
        function goToChatPage() {
            window.location.href = 'chatpage.php';
        }
    </script>
    
</body>
</html>
