<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    exit();
}

if (!isset($_GET["user_id"])) {
    exit();
}

$current_user = $_SESSION["user_id"];
$other_user = (int)$_GET["user_id"];

$sql = "SELECT * FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?)
        OR (sender_id = ? AND receiver_id = ?)
        ORDER BY created_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $current_user, $other_user, $other_user, $current_user);
$stmt->execute();

$messages = $stmt->get_result();

if ($messages->num_rows == 0) {
    echo "<p>Inga meddelanden ännu. Skriv första meddelandet!</p>";
}

while ($msg = $messages->fetch_assoc()):
?>

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