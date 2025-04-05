<?php
require 'config.php';

echo "Testing SQLite database...\n";

try {
    // Check if tables exist
    $tables = $conn->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll();
    
    echo "Database working properly!\n";
    echo "Tables found: " . count($tables) . "\n";
    
    foreach ($tables as $table) {
        echo "- " . $table['name'] . "\n";
        
        // Show first 3 rows from each table
        $data = $conn->query("SELECT * FROM " . $table['name'] . " LIMIT 3")->fetchAll();
        echo "  Sample data (" . count($data) . " rows):\n";
        print_r($data);
    }
    
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
