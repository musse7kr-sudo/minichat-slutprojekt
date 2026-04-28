<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Användare</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Alla användare</h2>

    <?php while ($user = $result->fetch_assoc()): ?>
        <div class="user-card">
            <h3><?php echo htmlspecialchars($user["username"]); ?></h3>
            <p><?php echo htmlspecialchars($user["bio"]); ?></p>
            <a href="chat.php?user_id=<?php echo $user["id"]; ?>" class="btn">Chatta</a>
        </div>
    <?php endwhile; ?>

    <a href="profile.php" class="btn">Tillbaka till profil</a>
</div>

</body>
</html>