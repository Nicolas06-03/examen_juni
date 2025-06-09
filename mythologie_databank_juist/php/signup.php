<?php
include("connection.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['gebruikersnaam'], $data['wachtwoord'])) {
    http_response_code(400);
    echo "Ongeldige input.";
    exit;
}

$username = trim($data['gebruikersnaam']);
$passwordHash = password_hash($data['wachtwoord'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $passwordHash);

if ($stmt->execute()) {
    echo "Registratie gelukt! Je kan nu inloggen.";
} else {
    echo "Fout bij registreren: " . $conn->error;
}
?>
