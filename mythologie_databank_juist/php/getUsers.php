<?php
session_start();
include("connection.php");

$gekeurdResult = $conn->query("SELECT id, username FROM users WHERE is_admin = 1");
$pendingResult = $conn->query("SELECT id, username FROM users WHERE is_admin = 0");

$gekeurd = [];
while ($row = $gekeurdResult->fetch_assoc()) {
    $gekeurd[] = $row;
}

$pending = [];
while ($row = $pendingResult->fetch_assoc()) {
    $pending[] = $row;
}
echo json_encode(["gekeurd" => $gekeurd, "pending" => $pending]);
?>
