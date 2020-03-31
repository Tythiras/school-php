<?php

$servername = $config['servername'];
$username = $config['username'];
$password = $config['password'];

try {
    $db = new PDO("mysql:host=$servername;dbname=test", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>