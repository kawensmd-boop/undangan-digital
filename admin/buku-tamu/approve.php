<?php
include '../check-auth.php';

$id = intval($_GET['id'] ?? 0);
if ($conn->query("UPDATE guestbook_entries SET status = 'approved' WHERE id = $id")) {
    redirect('index.php');
}
?>
