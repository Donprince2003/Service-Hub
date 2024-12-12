<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Bot</title>
    <link rel="stylesheet" href="chat.css">

</head>

<body>
    <div class="top-bar">
        <h1>Chat Bot</h1>
    </div>
    <div class="chat-container" id="chat-container">

        <div class='message received'>
            <div id="response">

            </div>
        </div>
    </div>
    <br><br>
    <div id="response"></div>
    <div class="input-container">
        <input type="text" id="text">
        <button type="submit" name="submit" class="send-button" onclick="generateResponse()">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                <path d="M0 0h24v24H0z" fill="none" />
                <path d="M2.01 21L23 12 2.01 3v7l15 2-15 2z" />
            </svg>
        </button>

    </div>



    <script src="script.js"></script>
</body>

</html>