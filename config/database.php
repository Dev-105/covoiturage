<?php
// config/database.php
// Basic PDO connection for XAMPP. Update credentials if needed.
function getPDO() {
    $host = '127.0.0.1';
    $db   = 'covoiturage';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $opt);
    } catch (PDOException $e) {
        // In production don't echo errors like this
        echo "Database connection failed: " . htmlspecialchars($e->getMessage());
        exit;
    }
}
