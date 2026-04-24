<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "minichat_slutprojekt";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Kopplingen misslyckades: " . $conn->connect_error);
}
?>