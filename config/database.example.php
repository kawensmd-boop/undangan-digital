<?php
/**
 * Database Configuration
 * Copy this file to database.php and update credentials
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'undangan_digital');

// Database Connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset('utf8mb4');
    
} catch (Exception $e) {
    die('Database Error: ' . $e->getMessage());
}
?>
