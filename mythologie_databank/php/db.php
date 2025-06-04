<?php

$servername = "localhost";
$username = "nicolas_admin";
$password = "prD<*W=QnbRe48Mj(X3[";
$db = "nicolas_mytische_wezens";


$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
