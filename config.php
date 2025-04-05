<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// SQLite database file
$dbFile = __DIR__ . '/database.sqlite';

try {
    $conn = new PDO("sqlite:$dbFile");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Create tables if they don't exist
    $conn->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        business_name TEXT,
        first_name TEXT,
        last_name TEXT,
        business_address TEXT,
        email TEXT UNIQUE,
        password TEXT,
        role TEXT DEFAULT 'user'
    )");
    
    $conn->exec("CREATE TABLE IF NOT EXISTS shipping_data (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        invoice_number TEXT,
        invoice_date TEXT,
        trans_date TEXT,
        cust_po TEXT,
        ship_via TEXT,
        comment TEXT,
        ship_to_name TEXT,
        item_code TEXT,
        description TEXT,
        qty_ordered INTEGER,
        qty_shipped INTEGER,
        qty_backorder INTEGER,
        pro_number TEXT
    )");
    
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Function to redirect with message
function redirect($url, $message = null) {
    if ($message) {
        $_SESSION['message'] = $message;
    }
    header("Location: $url");
    exit();
}
?>
