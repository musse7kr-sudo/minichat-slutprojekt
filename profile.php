<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Min profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Min profil</h2>

    <div class="profile-card">
        <h3><?php echo htmlspecialchars($user["username"]); ?></h3>

        <p><strong>Namn:</strong> <?php echo htmlspecialchars($user["full_name"]); ?></p>
        <p><strong>E-post:</strong> <?php echo htmlspecialchars($user["email"]); ?></p>
        <p><strong>Ålder:</strong> <?php echo htmlspecialchars($user["age"]); ?></p>
        <p><strong>Om mig:</strong> <?php echo htmlspecialchars($user["bio"]); ?></p>
    </div>

    <a href="edit_profile.php" class="btn">Redigera profil</a>
    <a href="users.php" class="btn">Se användare</a>
    <a href="index.php" class="btn">Startsida</a>
    <a href="logout.php" class="btn logout">Logga ut</a>
</div>

</body>
</html>