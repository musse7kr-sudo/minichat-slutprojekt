<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION["user_id"];
$other_user = $_GET["user_id"];

$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $other_user);
$stmt->execute();
$user_result = $stmt->get_result();
$chat_user = $user_result->fetch_assoc();

$sql = "SELECT * FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?)
        OR (sender_id = ? AND receiver_id = ?)
        ORDER BY created_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $current_user, $other_user, $other_user, $current_user);
$stmt->execute();

$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Chatt</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Chatt med <?php echo htmlspecialchars($chat_user["username"]); ?></h2>

    <div class="chat-box">
        <?php while ($msg = $messages->fetch_assoc()): ?>
            <?php if ($msg["sender_id"] == $current_user): ?>
                <div class="message mine">
                    <?php echo htmlspecialchars($msg["message"]); ?>
                    <small><?php echo $msg["created_at"]; ?></small>
                </div>
            <?php else: ?>
                <div class="message theirs">
                    <?php echo htmlspecialchars($msg["message"]); ?>
                    <small><?php echo $msg["created_at"]; ?></small>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>

    <form method="POST" action="send_message.php">
        <input type="hidden" name="receiver_id" value="<?php echo $other_user; ?>">
        <textarea name="message" placeholder="Skriv ett meddelande..." required></textarea>
        <button type="submit">Skicka</button>
    </form>

    <a href="users.php" class="btn">Tillbaka</a>
</div>

</body>
</html>