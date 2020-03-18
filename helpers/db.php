<?php

$servername = "localhost";
$username = "rasmus";
$password = "1337";

try {
    $db = new PDO("mysql:host=$servername;dbname=test", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>