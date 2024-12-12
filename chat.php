<?php
session_start();
include("conn.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$sender = $_SESSION['user_id'];
$sql = "SELECT DISTINCT receiver FROM chat_tab WHERE sender = '$sender' ORDER BY time DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="chat.css">
    <style>
        .chat {
            display: none;
        }
    </style>
    <script>
        function showAi() {
            const chatFrame = document.getElementById("myIframe");
            const chatDiv = document.querySelector(".chat");

            // Show the chat iframe
            chatDiv.style.display = "block";

            // Load chat content for the selected user
            chatFrame.src = `chatai.php`;
        }


        function showChat(receiverId) {
            const chatFrame = document.getElementById("myIframe");
            const chatDiv = document.querySelector(".chat");

            // Show the chat iframe
            chatDiv.style.display = "block";

            // Load chat content for the selected user
            chatFrame.src = `chatm.php?receiver=${receiverId}`;
        }
    </script>
</head>

<body class="body">
    <div class="container">
        <div class="chat-templet">
            <button class="user-button" type="button" onclick="showAi()">
                <div class="user">

                    <img class="img" src="d.png" alt="profile picture">
                    <div class="chatdiv">
                        <p class="name">Chat Bot</p>
                    </div>
                </div>
            </button>

            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $receiver = $row['receiver'];
                if ($receiver != "ai") {
            ?>
                    <button class="user-button" type="button" onclick="showChat('<?php echo $receiver; ?>')">
                        <div class="user">
                            <?php
                            $sql = "SELECT user_name FROM user_tab WHERE user_id = '$receiver'";
                            $res = mysqli_query($conn, $sql);
                            $resname = mysqli_fetch_assoc($res);
                            $sql = "SELECT img_id FROM pro_img WHERE user_id = '$receiver'";
                            $img_res = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($img_res) > 0) {
                                $img_data = mysqli_fetch_array($img_res, MYSQLI_ASSOC);
                                $receiver_img = "image/" . $img_data['img_id'];
                            } else {
                                $receiver_img = "d.png";
                            }
                            ?>
                            <img class="img" src="<?php echo $receiver_img; ?>" alt="profile picture">
                            <div class="chatdiv">
                                <p class="name"><?php echo $resname['user_name']; ?></p>
                            </div>
                        </div>
                    </button>
            <?php
                }
            }
            ?>
        </div>

        <div class="main-content">
            <div class="chat">
                <iframe id="myIframe" src="" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</body>

</html>