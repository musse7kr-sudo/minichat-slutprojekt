<?php
session_start();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>MiniChat Slutprojekt</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="home-container">

    <div class="home-card">
        <h1>MiniChat</h1>
        <p class="home-subtitle">En enkel chatthemsida där användare kan skapa konto, ha en profil och chatta med andra.</p>

        <?php if (isset($_SESSION["user_id"])): ?>
            <p class="welcome-text">Du är inloggad som <strong><?php echo htmlspecialchars($_SESSION["username"]); ?></strong></p>

            <div class="home-buttons">
                <a href="profile.php" class="btn">Min profil</a>
                <a href="users.php" class="btn">Hitta användare</a>
                <a href="logout.php" class="btn logout">Logga ut</a>
            </div>
        <?php else: ?>
            <div class="home-buttons">
                <a href="register.php" class="btn">Skapa konto</a>
                <a href="login.php" class="btn">Logga in</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="info-boxes">
        <div class="info-box">
            <h3>Skapa profil</h3>
            <p>Registrera ett konto och skriv lite om dig själv.</p>
        </div>

        <div class="info-box">
            <h3>Hitta folk</h3>
            <p>Se andra användare och välj vem du vill chatta med.</p>
        </div>

        <div class="info-box">
            <h3>Chatta</h3>
            <p>Skicka meddelanden och radera dina egna meddelanden.</p>
        </div>
    </div>

</div>

</body>
</html>