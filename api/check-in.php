<?php
// API endpoint untuk Check-in via QR Code
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $token = isset($_POST['token']) ? sanitize($_POST['token']) : '';
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;

    if (!$token || !$event_id) {
        jsonResponse(['success' => false, 'message' => 'Data tidak valid'], 400);
    }

    $guest = $conn->query("SELECT * FROM guests WHERE token = '$token' AND event_id = $event_id")->fetch_assoc();
    
    if (!$guest) {
        jsonResponse(['success' => false, 'message' => 'Tamu tidak ditemukan'], 404);
    }

    // Check if already checked in
    if ($guest['checked_in']) {
        jsonResponse(['success' => true, 'message' => 'Anda sudah melakukan check-in', 'already_checked_in' => true], 200);
    }

    // Update check-in
    $update_query = "UPDATE guests SET checked_in = 1, check_in_time = NOW() WHERE id = {$guest['id']}";
    
    if ($conn->query($update_query)) {
        // Log check-in
        $device_info = $_POST['device_info'] ?? 'Unknown';
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $log_query = "INSERT INTO checkin_logs (guest_id, event_id, device_info, ip_address) VALUES ({$guest['id']}, $event_id, '$device_info', '$ip_address')";
        $conn->query($log_query);
        
        jsonResponse(['success' => true, 'message' => 'Check-in berhasil!', 'guest' => $guest['name']]);
    } else {
        jsonResponse(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Method tidak diizinkan'], 405);
?>
