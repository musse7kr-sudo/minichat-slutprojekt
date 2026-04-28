<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$sender_id = $_SESSION["user_id"];
$receiver_id = $_POST["receiver_id"];
$message = $_POST["message"];

$sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
$stmt->execute();

header("Location: chat.php?user_id=" . $receiver_id);
exit();
?>