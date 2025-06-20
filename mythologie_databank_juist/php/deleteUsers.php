<?php
session_start();

include("connection.php");

$data = json_decode(file_get_contents("php://input"), true);


$id = intval($data['id']);

$stmt = $conn->prepare("UPDATE users SET is_admin = 0 WHERE id = ?");


$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    http_response_code(500);
    exit("Fout bij uitvoeren query: " . $stmt->error);
}

echo "Gebruiker bevestigd.";
?>
