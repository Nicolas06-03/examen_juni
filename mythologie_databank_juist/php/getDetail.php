<?php
include("connection.php");
include("functies.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo "Ongeldige ID.";
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM stories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo "<h1>{$row['title']}</h1>";
    echo "{$row['texte']}</p>";
    echo "<img src='../images/{$row['id']}.jpg' alt='{$row['title']}' class='img-fluid mt-3'>";
} else {
    echo "<p>verhaal niet gevonden.</p>";
}
?>
