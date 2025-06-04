<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
require_once("db.php");

// ID veilig ophalen
$id = intval($_GET['id'] ?? 0);


if ($id === 0) {
  http_response_code(400);
  echo json_encode(["error" => "Geen geldig ID"]);
  exit;
}

// Verhaalgegevens
$stmt = $conn->prepare("SELECT title, text, synopsis, period FROM stories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo json_encode(['error' => 'Verhaal niet gevonden']);
    exit;
}

// Landen ophalen
$countries = [];
$cstmt = $conn->prepare("SELECT c.name FROM countries c JOIN story_country sc ON c.id = sc.country_id WHERE sc.story_id = ?");
$cstmt->bind_param("i", $id);
$cstmt->execute();
$cres = $cstmt->get_result();
while ($row = $cres->fetch_assoc()) {
    $countries[] = $row['name'];
}

// Wezens ophalen
$creatures = [];
$wstmt = $conn->prepare("SELECT c.id, c.name FROM creatures c JOIN story_creature sc ON c.id = sc.creature_id WHERE sc.story_id = ?");
$wstmt->bind_param("i", $id);
$wstmt->execute();
$wres = $wstmt->get_result();
while ($row = $wres->fetch_assoc()) {
    $creatures[] = $row;
}

// JSON-uitvoer
echo json_encode([
    'title' => $result['title'],
    'text' => $result['text'],
    'synopsis' => $result['synopsis'],
    'period' => $result['period'],
    'countries' => $countries,
    'creatures' => $creatures
]);
?>
