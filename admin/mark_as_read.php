<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  exit();
}

$admin_id = $_SESSION['user']['id'];
$buyer_id = isset($_GET['with']) ? intval($_GET['with']) : 0;

if ($buyer_id > 0) {
  $stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
  $stmt->bind_param("ii", $buyer_id, $admin_id);
  $stmt->execute();
}
?>
