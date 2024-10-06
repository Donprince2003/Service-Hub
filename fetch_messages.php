<?php
session_start();
include("conn.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    exit('User not logged in.');
}

// Get the logged-in user's ID
$sender = $_SESSION['user_id'];

// Get the receiver ID from the query parameter
if (isset($_GET['receiver'])) {
    $receiver = $_GET['receiver'];
} else {
    exit('No receiver specified.');
}

// Fetch chat messages between the sender and receiver
$sql = "SELECT sender, receiver, message, time 
        FROM chat_tab 
        WHERE (sender = ? AND receiver = ?) 
        OR (sender = ? AND receiver = ?) 
        ORDER BY time ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $sender, $receiver, $receiver, $sender);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// Return messages in JSON format
header('Content-Type: application/json');
echo json_encode($messages);
?>
