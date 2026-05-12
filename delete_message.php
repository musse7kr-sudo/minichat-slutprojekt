<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"]) || !isset($_GET["chat_user"])) {
    header("Location: users.php");
    exit();
}

$message_id = $_GET["id"];
$chat_user = $_GET["chat_user"];
$current_user = $_SESSION["user_id"];

$sql = "DELETE FROM messages WHERE id = ? AND sender_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $message_id, $current_user);
$stmt->execute();

header("Location: chat.php?user_id=" . $chat_user);
exit();
?>