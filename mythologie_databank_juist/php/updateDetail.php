<?php
session_start();
include("connection.php");

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    http_response_code(403);
    echo "Geen toestemming.";
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['id'])) {
    http_response_code(400);
    echo "Ongeldige data.";
    exit;
}

$id = intval($data['id']);
$title = $data['title'] ?? '';
$texte = $data['texte'] ?? '';
$synopsis = $data['synopsis'] ?? '';
$periode = $data['periode'] ?? '';
$countries = $data['countries'] ?? [];
$creatures = $data['creatures'] ?? [];

// Begin transacties
$conn->begin_transaction();

try {
    // Update stories tabel
    $stmt = $conn->prepare("UPDATE stories SET title = ?, texte = ?, synopsis = ?, periode = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $texte, $synopsis, $periode, $id);
    $stmt->execute();

    // Update landen: eerst verwijderen, dan opnieuw toevoegen
    $conn->query("DELETE FROM story_country WHERE story_id = $id");
    if (is_array($countries)) {
        $stmtInsertLand = $conn->prepare("INSERT INTO story_country (story_id, country_id) VALUES (?, ?)");
        foreach ($countries as $landId) {
            $landIdInt = intval($landId);
            $stmtInsertLand->bind_param("ii", $id, $landIdInt);
            $stmtInsertLand->execute();
        }
        $stmtInsertLand->close();
    }

    // Update wezens: eerst verwijderen, dan opnieuw toevoegen
    $conn->query("DELETE FROM story_creature WHERE story_id = $id");
    if (is_array($creatures)) {
        $stmtInsertWezen = $conn->prepare("INSERT INTO story_creature (story_id, creature_id) VALUES (?, ?)");
        foreach ($creatures as $wezenId) {
            $wezenIdInt = intval($wezenId);
            $stmtInsertWezen->bind_param("ii", $id, $wezenIdInt);
            $stmtInsertWezen->execute();
        }
        $stmtInsertWezen->close();
    }

    $conn->commit();
    echo "Verhaal succesvol bijgewerkt.";
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo "Fout bij opslaan: " . $e->getMessage();
}
?>
