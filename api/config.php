<?php
// app/config/config.php

$host = 'localhost';
$db   = 'finance_db';
$user = 'root';
$pass = ''; // Default XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    // Jangan tampilkan detail error di production, tapi untuk development boleh
    die("Koneksi database gagal: " . $e->getMessage());
}
?>