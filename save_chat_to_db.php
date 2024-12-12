<?php
session_start();
$user_id=$_SESSION['user_id'];
$ai="ai";
include("conn.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Capture the chat data from the POST request
    $chat = $_POST['chat'];

    // Prepare and execute an SQL query to save the data to the database
    try {
        $sql = "INSERT INTO `chat_tab`(`sender`, `receiver`, `message`) VALUES ('$user_id','$ai','$chat')";
        $result = mysqli_query($conn, $sql);
        $stmt = $pdo->prepare("INSERT INTO chat_log (message) VALUES (:chat)");
        $stmt->bindParam(':chat', $chat);
        $stmt->execute();

        // Send a response back to the client
        echo "Chat saved to database.";
    } catch (PDOException $e) {
        echo "Error saving chat: " . $e->getMessage();
    }
}
