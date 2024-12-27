<?php
ob_start(); // Start output buffering
session_start();
include("conn.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$sender = $_SESSION['user_id'];

// Get the receiver ID from the query parameter
if (isset($_GET['receiver'])) {
    $receiver = $_GET['receiver'];
} else {
    echo "No receiver specified.";
    exit();
}

// Fetch the receiver's profile data (e.g., name and profile picture)
$sql = "SELECT user_name FROM user_tab WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $receiver);
$stmt->execute();
$res = $stmt->get_result();
$resname = $res->fetch_assoc();
$receiver_name = $resname['user_name'] ?? 'User';

$sql = "SELECT img_id FROM pro_img WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $receiver);
$stmt->execute();
$img_res = $stmt->get_result();

if ($img_res->num_rows > 0) {
    $img_data = $img_res->fetch_assoc();
    $receiver_img = "image/" . $img_data['img_id'];
} else {
    $receiver_img = "d.png"; // Default image
}

// Fetch initial chat messages
$sql = "SELECT sender, receiver, message, time 
        FROM chat_tab 
        WHERE (sender = ? AND receiver = ?) 
        OR (sender = ? AND receiver = ?) 
        ORDER BY time ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $sender, $receiver, $receiver, $sender);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Interface</title>
    <link rel="stylesheet" href="chat.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Top Bar with Receiver's Profile -->
    <div class="top-bar">
        <img src="<?php echo htmlspecialchars($receiver_img); ?>" alt="User Profile Picture" class="profile-pic">
        <span class="user-name"><?php echo htmlspecialchars($receiver_name); ?></span>
    </div>

    <!-- Chat Messages Container -->
    <div class="chat-container" id="chat-container">
        <?php
        // Display fetched chat messages
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $messageSender = $row['sender'];
                $message = htmlspecialchars($row['message']); // Escape special characters
                $class = ($messageSender == $sender) ? 'sent' : 'received'; // Sent or received message
                echo "<div class='message $class'>$message</div>";
            }
        } else {
            echo "<p>No messages yet. Start the conversation!</p>";
        }
        ?>
    </div>

    <!-- Input Section for Sending New Messages -->
    <div>
        <form class="input-container" method="POST" action="">
            <input type="hidden" name="receiver" value="<?php echo htmlspecialchars($receiver); ?>">
            <input type="hidden" name="sender" value="<?php echo htmlspecialchars($sender); ?>">
            <input type="text" id="message" name="message" placeholder="Enter the text here..." required>
            <button type="submit" name="submit" class="send-button">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                    <path d="M0 0h24v24H0z" fill="none"/><path d="M2.01 21L23 12 2.01 3v7l15 2-15 2z"/>
                </svg>
            </button>
        </form>
    </div>

    <script>
        // Function to fetch new messages
        function fetchMessages() {
            $.ajax({
                url: 'fetch_messages.php', // URL of the PHP file
                type: 'GET',
                data: { receiver: '<?php echo htmlspecialchars($receiver); ?>' }, // Pass receiver ID
                dataType: 'json',
                success: function(messages) {
                    $('#chat-container').empty(); // Clear existing messages
                    messages.forEach(function(message) {
                        const className = message.sender === '<?php echo $sender; ?>' ? 'sent' : 'received';
                        $('#chat-container').append(`<div class='message ${className}'>${message.message}</div>`);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching messages:', error);
                }
            });
        }

        // Fetch messages every 0.5 seconds
        setInterval(fetchMessages, 500);

        $(document).ready(function () {
            var chatDiv = document.getElementById("chat-container");
            chatDiv.scrollTop = chatDiv.scrollHeight;
        });
    </script>
</body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $message = $_POST['message'];

    // Prepare and bind the insert statement
    $sql = "INSERT INTO chat_tab (sender, receiver, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $sender, $receiver, $message);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid resubmission
    header("Location: chatm.php?receiver=" . urlencode($receiver));
    exit();
}

ob_end_flush(); // Flush the output buffer
?>

</html>
