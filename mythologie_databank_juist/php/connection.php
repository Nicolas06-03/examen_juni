<?php

$servername = "localhost";
$username = "nicolas_admin";
$password = "&MaF@%XB1";
$db = "nicolas_mythische_wezens";


$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
