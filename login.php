<?php
session_start();
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            header("Location: profile.php");
            exit();
        } else {
            $message = "Fel lösenord.";
        }
    } else {
        $message = "Ingen användare hittades.";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Logga in</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Logga in</h2>

    <form method="POST">
        <input type="email" name="email" placeholder="E-post" required>
        <input type="password" name="password" placeholder="Lösenord" required>
        <button type="submit">Logga in</button>
    </form>

    <p><?php echo $message; ?></p>

    <a href="register.php">Skapa konto</a>
    <br><br>
    <a href="index.php">Tillbaka till startsidan</a>
</div>

</body>
</html>