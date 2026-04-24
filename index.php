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

<div class="container">
    <h1>MiniChat Slutprojekt</h1>
    <p>En enkel webbplats där användare kan skapa konto, logga in och chatta med andra.</p>

    <?php if (isset($_SESSION["user_id"])): ?>
        <a href="profile.php" class="btn">Min profil</a>
        <a href="users.php" class="btn">Användare</a>
        <a href="logout.php" class="btn logout">Logga ut</a>
    <?php else: ?>
        <a href="register.php" class="btn">Skapa konto</a>
        <a href="login.php" class="btn">Logga in</a>
    <?php endif; ?>
</div>

</body>
</html>