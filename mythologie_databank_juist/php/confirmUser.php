<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("connection.php");

$data = json_decode(file_get_contents("php://input"), true);


$id = intval($data['id']);

$stmt = $conn->prepare("UPDATE users SET is_admin = 1 WHERE id = ?");


$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    http_response_code(500);
    exit("Fout bij uitvoeren query: " . $stmt->error);
}

echo "Gebruiker bevestigd.";
?>
