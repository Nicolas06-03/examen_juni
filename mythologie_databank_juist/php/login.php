<?php
session_start();
include("connection.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['gebruikersnaam'], $data['wachtwoord'])) {
    http_response_code(400);
    echo "Ongeldige input.";
    exit;
}

$username = $data['gebruikersnaam'];
$password = $data['wachtwoord'];

$stmt = $conn->prepare("SELECT id, username, password_hash, is_admin FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password_hash'])) {
        $_SESSION['gebruiker_id'] = $row['id'];
        $_SESSION['gebruiker'] = $row['username'];
        $_SESSION['is_admin'] = $row['is_admin']; 
        $_SESSION['logged_in'] = true;
        $_SESSION['is_admin'] = $row['is_admin']; 
        echo "Succes! Je bent ingelogd.";

    } else {
        echo "Wachtwoord incorrect.";
    }
} else {
    echo "Gebruiker niet gevonden.";
}
?>
