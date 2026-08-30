<?php
// API endpoint untuk Guest Book
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
    $guest_id = isset($_POST['guest_id']) ? intval($_POST['guest_id']) : null;
    $guest_name = isset($_POST['guest_name']) ? sanitize($_POST['guest_name']) : '';
    $message = isset($_POST['message']) ? sanitize($_POST['message']) : '';

    if (!$event_id || !$guest_name || !$message) {
        jsonResponse(['success' => false, 'message' => 'Data tidak lengkap'], 400);
    }

    $query = "INSERT INTO guestbook_entries (event_id, guest_id, guest_name, message, status, created_at)
              VALUES ($event_id, " . ($guest_id ? $guest_id : 'NULL') . ", '$guest_name', '$message', 'pending', NOW())";
    
    if ($conn->query($query)) {
        jsonResponse(['success' => true, 'message' => 'Ucapan berhasil dikirim']);
    } else {
        jsonResponse(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Method tidak diizinkan'], 405);
?>
