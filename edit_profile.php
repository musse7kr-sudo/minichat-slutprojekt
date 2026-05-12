<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $full_name = $_POST["full_name"];
    $age = $_POST["age"];
    $bio = $_POST["bio"];

    if (empty($age)) {
        $age = NULL;
    }

    $sql = "UPDATE users SET username = ?, full_name = ?, age = ?, bio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $username, $full_name, $age, $bio, $user_id);

    if ($stmt->execute()) {
        $_SESSION["username"] = $username;
        $message = "Profilen har uppdaterats!";
    } else {
        $message = "Något gick fel.";
    }
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
    <title>Redigera profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Redigera profil</h2>

    <?php if (!empty($message)): ?>
        <p class="success"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <input 
            type="text" 
            name="username" 
            placeholder="Användarnamn"
            value="<?php echo htmlspecialchars($user["username"]); ?>" 
            required
        >

        <input 
            type="text" 
            name="full_name" 
            placeholder="Ditt namn"
            value="<?php echo htmlspecialchars($user["full_name"]); ?>"
        >

        <input 
            type="number" 
            name="age" 
            placeholder="Ålder"
            value="<?php echo htmlspecialchars($user["age"]); ?>"
        >

        <textarea name="bio" placeholder="Skriv något om dig..."><?php echo htmlspecialchars($user["bio"]); ?></textarea>

        <button type="submit">Spara ändringar</button>
    </form>

    <br>

    <a href="profile.php" class="btn">Tillbaka till profil</a>
    <a href="index.php" class="btn">Startsida</a>
</div>

</body>
</html>