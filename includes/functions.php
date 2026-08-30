<?php
/**
 * Helper Functions
 */

function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(htmlspecialchars(trim($data)));
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

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

function formatTime($time) {
    return date('H:i', strtotime($time));
}

function timeAgo($datetime) {
    $time_ago = strtotime($datetime);
    $current_time = time();
    $time_diff = $current_time - $time_ago;
    
    if ($time_diff < 1) return 'baru saja';
    if ($time_diff < 60) return $time_diff . ' detik lalu';
    if ($time_diff < 3600) return intval($time_diff / 60) . ' menit lalu';
    if ($time_diff < 86400) return intval($time_diff / 3600) . ' jam lalu';
    if ($time_diff < 604800) return intval($time_diff / 86400) . ' hari lalu';
    if ($time_diff < 2592000) return intval($time_diff / 604800) . ' minggu lalu';
    if ($time_diff < 31536000) return intval($time_diff / 2592000) . ' bulan lalu';
    return intval($time_diff / 31536000) . ' tahun lalu';
}

function generateQRCode($data, $size = 200) {
    $encoded = urlencode($data);
    return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . $encoded;
}

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect(APP_URL . '/admin/login.php');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect(APP_URL . '/admin/login.php');
    }
}

function getCurrentUser() {
    if (!isLoggedIn()) return null;
    global $conn;
    $user_id = $_SESSION['user_id'];
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    return $result->fetch_assoc();
}

function generateSlug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = preg_replace('~-+~', '-', $text);
    $text = trim($text, '-');
    return strtolower($text);
}
?>
