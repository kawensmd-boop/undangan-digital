<?php
/**
 * Database Configuration
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'undangan_digital');
define('APP_NAME', 'Undangan Digital');
define('APP_URL', 'http://localhost/undangan-digital');
define('APP_TIMEZONE', 'Asia/Jakarta');

date_set_timezone(APP_TIMEZONE);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }
    
    $conn->set_charset('utf8mb4');
    
} catch (Exception $e) {
    die('Database Error: ' . $e->getMessage());
}
?>
