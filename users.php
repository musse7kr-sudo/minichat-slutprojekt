<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$current_user = $_SESSION["user_id"];

$sql = "SELECT * FROM users WHERE id != ? ORDER BY username ASC";
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

    <div class="users-grid">
        <?php while ($user = $result->fetch_assoc()): ?>
            <div class="user-card-small">
                <div class="user-icon">
                    <?php echo strtoupper(substr($user["username"], 0, 1)); ?>
                </div>

                <h3><?php echo htmlspecialchars($user["username"]); ?></h3>

                <p>
                    <?php 
                    if (!empty($user["bio"])) {
                        echo htmlspecialchars($user["bio"]);
                    } else {
                        echo "Ingen profiltext";
                    }
                    ?>
                </p>

                <a href="chat.php?user_id=<?php echo $user["id"]; ?>" class="btn small-btn">Chatta</a>
            </div>
        <?php endwhile; ?>
    </div>

    <br>

    <a href="profile.php" class="btn">Tillbaka till profil</a>
</div>

</body>
</html>