<?php
session_start();
$user_id = $_SESSION['user_id'];

// Connect to the database
include 'conn.php';  // Include your DB connection file

// Get data from the fetch request
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data['userMessage'];

// Insert the chat into the database
$sql = "INSERT INTO `chat_tab`(`sender`, `receiver`, `message`) VALUES ('$user_id','ai','$userMessage'), ('ai', 'user', '$aiResponse')";
$result = mysqli_query($conn, $sql);

// Return status
if ($result) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'error' => mysqli_error($conn)]);
}
?>
