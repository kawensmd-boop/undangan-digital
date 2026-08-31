<?php
include '../check-auth.php';

$id = intval($_GET['id'] ?? 0);
$guest = $conn->query("SELECT * FROM guests WHERE id = $id")->fetch_assoc();

if ($guest) {
    $conn->query("DELETE FROM guests WHERE id = $id");
}

redirect('index.php');
?>
