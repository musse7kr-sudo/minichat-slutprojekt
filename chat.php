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
$other_user = $_GET["user_id"];

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
        <?php if ($messages->num_rows == 0): ?>
            <p>Inga meddelanden ännu. Skriv första meddelandet!</p>
        <?php endif; ?>

        <?php while ($msg = $messages->fetch_assoc()): ?>
            <?php if ($msg["sender_id"] == $current_user): ?>
                <div class="message mine">
                    <p><?php echo htmlspecialchars($msg["message"]); ?></p>
                    <small><?php echo date("H:i", strtotime($msg["created_at"])); ?></small>

                    <a 
                        href="delete_message.php?id=<?php echo $msg["id"]; ?>&chat_user=<?php echo $other_user; ?>" 
                        class="delete-message"
                        onclick="return confirm('Vill du radera meddelandet?');"
                    >
                        Radera
                    </a>
                </div>
            <?php else: ?>
                <div class="message theirs">
                    <p><?php echo htmlspecialchars($msg["message"]); ?></p>
                    <small><?php echo date("H:i", strtotime($msg["created_at"])); ?></small>
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
    <a href="profile.php" class="btn">Min profil</a>
</div>

</body>
</html>