function generateResponse() {
    var text = document.getElementById("text");
    var response = document.getElementById("response");

    // Fetch the response from the server (response.php)
    fetch("response.php", {
        method: "post",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            text: text.value,  // Send user input to the server
        }),
    })
    .then(res => res.text())
    .then((res) => {
        // Display the response in the div
        response.innerHTML = res;

        // After the response is displayed, save both user message and AI response
        saveChatToDB(text.value, res);  // Save both messages
    });
}

// Function to save the chat to the database
function saveChatToDB(userMessage, aiResponse) {
    // You would now send both userMessage and aiResponse to be saved in the DB
    fetch("saveChat.php", {
        method: "post",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            userMessage: userMessage,
            aiResponse: aiResponse,
        }),
    });

}


function saveChatToDB(chatData) {
    // Send the chat data to the PHP backend for database storage
    fetch('save_chat_to_db.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'chat=' + encodeURIComponent(chatData)
    })
    .then(response => response.text())
    .then(data => {
        console.log("Chat saved to DB:", data);  // Log the success message from PHP
    })
    .catch((error) => {
        console.error('Error saving to DB:', error);
    });
}
