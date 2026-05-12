<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    exit();
}

if (!isset($_POST["receiver_id"]) || !isset($_POST["message"])) {
    exit();
}

$sender_id = $_SESSION["user_id"];
$receiver_id = (int)$_POST["receiver_id"];
$message = trim($_POST["message"]);

if ($message == "") {
    exit();
}

$sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);
$stmt->execute();

echo "Meddelandet skickades";
?>