<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bio = $_POST["bio"];

    $sql = "UPDATE users SET bio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $bio, $user_id);
    $stmt->execute();
}

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
        <p><strong>E-post:</strong> <?php echo htmlspecialchars($user["email"]); ?></p>
        <p><strong>Om mig:</strong> <?php echo htmlspecialchars($user["bio"]); ?></p>
    </div>

    <h3>Ändra profiltext</h3>

    <form method="POST">
        <textarea name="bio" placeholder="Skriv något om dig..."><?php echo htmlspecialchars($user["bio"]); ?></textarea>
        <button type="submit">Spara</button>
    </form>

    <br>

    <a href="users.php" class="btn">Se användare</a>
    <a href="index.php" class="btn">Startsida</a>
    <a href="logout.php" class="btn logout">Logga ut</a>
</div>

</body>
</html>