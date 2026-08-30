<?php
// API endpoint untuk RSVP
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $guest_id = isset($_POST['guest_id']) ? intval($_POST['guest_id']) : 0;
    $status = isset($_POST['status']) ? sanitize($_POST['status']) : '';
    $num_guests = isset($_POST['num_guests']) ? intval($_POST['num_guests']) : 1;
    $dietary_notes = isset($_POST['dietary_notes']) ? sanitize($_POST['dietary_notes']) : '';

    if (!$guest_id || !in_array($status, ['confirmed', 'rejected'])) {
        jsonResponse(['success' => false, 'message' => 'Data tidak valid'], 400);
    }

    $query = "UPDATE guests SET status = '$status', num_guests = $num_guests, dietary_notes = '$dietary_notes', updated_at = NOW() WHERE id = $guest_id";
    
    if ($conn->query($query)) {
        jsonResponse(['success' => true, 'message' => 'Konfirmasi berhasil dikirim']);
    } else {
        jsonResponse(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
    }
}

jsonResponse(['success' => false, 'message' => 'Method tidak diizinkan'], 405);
?>
