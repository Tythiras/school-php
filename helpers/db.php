<?php

$servername = $config['servername'];
$username = $config['username'];
$password = $config['password'];
$db = $config['dbname'];

try {
    $db = new PDO("mysql:host=$servername;dbname=$db;", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>