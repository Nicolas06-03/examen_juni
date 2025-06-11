<?php
include("connection.php");

$id = intval($_GET['id']);

// Haal het wezen op
$stmt = $conn->prepare("SELECT * FROM creatures WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Haal de categorieën van dit wezen op
    $catStmt = $conn->prepare("
        SELECT categories.id, categories.name 
        FROM creature_category 
        JOIN categories ON creature_category.category_id = categories.id 
        WHERE creature_category.creature_id = ?
    ");
    $catStmt->bind_param("i", $id);
    $catStmt->execute();
    $catResult = $catStmt->get_result();

    $categories = [];
    while ($cat = $catResult->fetch_assoc()) {
        $categories[] = $cat;
    }

    // Voeg categorieën toe aan het wezen
    $row['categories'] = $categories;

    header("Content-Type: application/json");
    echo json_encode($row);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Wezen niet gevonden"]);
}
?>
