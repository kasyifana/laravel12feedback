<?php

// Disable all error reporting for now
error_reporting(0);
ini_set('display_errors', 0);

// Basic PDO connection to check directly
try {
    $db_config = include(__DIR__ . '/config/database.php');
    $connection = $db_config['connections']['mysql'];
    
    $host = $connection['host'];
    $database = $connection['database'];
    $username = $connection['username'];
    $password = $connection['password'];
    
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Count records
    $stmt = $pdo->query("SELECT COUNT(*) FROM laporan");
    $count = $stmt->fetchColumn();
    echo "Number of laporan records: $count\n";
    
    // Get sample records
    $stmt = $pdo->query("SELECT * FROM laporan LIMIT 5");
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    echo "Sample records:\n";
    foreach ($records as $record) {
        $user_id = $record->user_id ?? 'null';
        echo "ID: {$record->id_laporan}, User ID: {$user_id}, Title: {$record->judul}, Status: {$record->status}\n";
    }
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

