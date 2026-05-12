<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["user_id"])) {
    header("Location: users.php");
    exit();
}

$current_user = $_SESSION["user_id"];
$other_user = (int)$_GET["user_id"];

if ($other_user == $current_user) {
    header("Location: users.php");
    exit();
}

$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $other_user);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows == 0) {
    header("Location: users.php");
    exit();
}

$chat_user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Chatt</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="chat-page">

<div class="chat-container">
    <h2>Chatt med <?php echo htmlspecialchars($chat_user["username"]); ?></h2>

    <div class="chat-box" id="chatBox"></div>

    <form id="messageForm" class="chat-form">
        <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($other_user); ?>">
        <textarea name="message" id="messageInput" placeholder="Skriv ett meddelande..." required></textarea>
        <button type="submit">Skicka</button>
    </form>

    <div class="chat-links">
        <a href="users.php" class="btn">Tillbaka</a>
        <a href="profile.php" class="btn">Min profil</a>
    </div>
</div>

<script>
    const chatBox = document.getElementById("chatBox");
    const messageForm = document.getElementById("messageForm");
    const messageInput = document.getElementById("messageInput");
    const otherUser = <?php echo $other_user; ?>;

    let firstLoad = true;
    let lastMessageCount = 0;

    function isNearBottom() {
        return chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 80;
    }

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function countMessages() {
        return chatBox.querySelectorAll(".message").length;
    }

    function loadMessages(shouldForceScroll = false) {
        const wasNearBottom = isNearBottom();

        fetch("get_messages.php?user_id=" + otherUser)
            .then(response => response.text())
            .then(data => {
                chatBox.innerHTML = data;

                const newMessageCount = countMessages();
                const hasNewMessage = newMessageCount > lastMessageCount;

                if (firstLoad || shouldForceScroll || (hasNewMessage && wasNearBottom)) {
                    scrollToBottom();
                }

                firstLoad = false;
                lastMessageCount = newMessageCount;
            });
    }

    messageForm.addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(messageForm);

        fetch("send_message.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            messageInput.value = "";
            loadMessages(true);
        });
    });

    loadMessages(true);
    setInterval(() => loadMessages(false), 2000);
</script>

</body>
</html>