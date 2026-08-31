<?php
include '../check-auth.php';

$id = intval($_GET['id'] ?? 0);
$event = $conn->query("SELECT * FROM events WHERE id = $id AND user_id = {$user['id']}")->fetch_assoc();

if ($event) {
    $conn->query("DELETE FROM events WHERE id = $id");
}

redirect('index.php');
?>
