<?php
/**
 * Helper Functions
 */

// Database Connection
function getDBConnection() {
    require_once __DIR__ . '/../config/database.php';
    return $conn;
}

// Sanitize Input
function sanitize($data) {
    $conn = getDBConnection();
    return $conn->real_escape_string(htmlspecialchars(trim($data)));
}

// Validate Email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hash Password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Verify Password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate Token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Format Date Indonesia
function formatDateID($date) {
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $time = strtotime($date);
    $day = date('j', $time);
    $month = date('n', $time);
    $year = date('Y', $time);
    
    return $day . ' ' . $months[$month] . ' ' . $year;
}

// Format Time
function formatTime($time) {
    return date('H:i', strtotime($time));
}

// Time Ago
function timeAgo($datetime) {
    $time_ago = strtotime($datetime);
    $current_time = time();
    $time_diff = $current_time - $time_ago;
    
    if ($time_diff < 1) return 'baru saja';
    
    $second = 1;
    $minute = $second * 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;
    $month = $day * 30;
    $year = $day * 365;
    
    if (is_int($time_diff / $year)) return intval($time_diff / $year) . ' tahun lalu';
    if (is_int($time_diff / $month)) return intval($time_diff / $month) . ' bulan lalu';
    if (is_int($time_diff / $week)) return intval($time_diff / $week) . ' minggu lalu';
    if (is_int($time_diff / $day)) return intval($time_diff / $day) . ' hari lalu';
    if (is_int($time_diff / $hour)) return intval($time_diff / $hour) . ' jam lalu';
    if (is_int($time_diff / $minute)) return intval($time_diff / $minute) . ' menit lalu';
    
    return intval($time_diff) . ' detik lalu';
}

// Generate QR Code
function generateQRCode($data, $size = 200) {
    $encoded = urlencode($data);
    return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . $encoded;
}

// Redirect
function redirect($url) {
    header('Location: ' . $url);
    exit();
}

// Success Response
function successResponse($message, $data = null) {
    return [
        'success' => true,
        'message' => $message,
        'data' => $data
    ];
}

// Error Response
function errorResponse($message, $code = 400) {
    http_response_code($code);
    return [
        'success' => false,
        'message' => $message,
        'code' => $code
    ];
}

// JSON Response
function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Check if logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Get Current User
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    return $result->fetch_assoc();
}

// Pagination
function getPagination($total_records, $items_per_page = 10, $current_page = 1) {
    $total_pages = ceil($total_records / $items_per_page);
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'total_records' => $total_records,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'items_per_page' => $items_per_page
    ];
}

// File Upload
function uploadFile($file, $destination) {
    if (!isset($file) || $file['error'] !== 0) {
        return ['success' => false, 'message' => 'File error'];
    }
    
    $filename = basename($file['name']);
    $filepath = $destination . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => true, 'filename' => $filename, 'path' => $filepath];
    }
    
    return ['success' => false, 'message' => 'Upload failed'];
}

// Generate Slug
function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = preg_replace('~-+~', '-', $text);
    $text = trim($text, '-');
    return strtolower($text);
}

?>
