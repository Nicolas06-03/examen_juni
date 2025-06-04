<?php
require_once("db.php");
$id = intval($_POST['id']);
$text = $conn->real_escape_string($_POST['text']);

$conn->query("UPDATE stories SET text = '$text' WHERE id = $id");

echo "OK";
?>
