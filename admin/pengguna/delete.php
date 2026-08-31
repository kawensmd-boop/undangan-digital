<?php
include '../check-auth.php';

$id = intval($_GET['id'] ?? 0);
if ($conn->query("DELETE FROM users WHERE id = $id")) {
    redirect('index.php');
}
?>
