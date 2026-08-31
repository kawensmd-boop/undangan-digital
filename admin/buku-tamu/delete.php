<?php
include '../check-auth.php';

$id = intval($_GET['id'] ?? 0);
if ($conn->query("DELETE FROM guestbook_entries WHERE id = $id")) {
    redirect('index.php');
}
?>
