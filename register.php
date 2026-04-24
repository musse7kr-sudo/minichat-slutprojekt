<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password, bio) VALUES (?, ?, ?, '')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $message = "Kontot skapades! Du kan nu logga in.";
    } else {
        $message = "Något gick fel. E-post kanske redan finns.";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Skapa konto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Skapa konto</h2>

    <form method="POST">
        <input type="text" name="username" placeholder="Användarnamn" required>
        <input type="email" name="email" placeholder="E-post" required>
        <input type="password" name="password" placeholder="Lösenord" required>
        <button type="submit">Skapa konto</button>
    </form>

    <p><?php echo $message; ?></p>

    <a href="login.php">Har du redan konto? Logga in</a>
    <br><br>
    <a href="index.php">Tillbaka till startsidan</a>
</div>

</body>
</html>