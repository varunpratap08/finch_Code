<?php
$host = '127.0.0.1'; // use 127.0.0.1 for better compatibility
$port = 3307; // set to your MySQL port
$db = 'finch_db'; // Your database name
$user = 'root';
$pass = ''; // Your password

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
