<?php
include("connection.php");

$land_id = intval($_GET['land_id']);

$stmt = $conn->prepare("
    SELECT s.id, s.title, s.synopsis 
    FROM stories s
    JOIN story_country sc ON s.id = sc.story_id
    WHERE sc.country_id = ?
");
$stmt->bind_param("i", $land_id);
$stmt->execute();
$result = $stmt->get_result();

$verhalen = [];
while ($row = $result->fetch_assoc()) {
    $verhalen[] = $row;
}

header("Content-Type: application/json");
echo json_encode($verhalen);
?>
