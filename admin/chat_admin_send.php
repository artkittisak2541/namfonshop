<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  exit('Unauthorized');
}

$sender_id = $_SESSION['user']['id'];
$receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$upload_dir = '../uploads/';

if (!is_dir($upload_dir)) {
  mkdir($upload_dir, 0755, true);
}

// ✅ ส่งข้อความ
if (!empty($message) && $receiver_id > 0) {
  $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())");
  $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
  $stmt->execute();
}

// ✅ ส่งรูปภาพ (หลายไฟล์)
if (!empty($_FILES['images']['name'][0])) {
  foreach ($_FILES['images']['tmp_name'] as $index => $tmp_name) {
    if ($_FILES['images']['error'][$index] === 0) {
      $ext = pathinfo($_FILES['images']['name'][$index], PATHINFO_EXTENSION);
      $newName = uniqid('img_', true) . '.' . strtolower($ext);
      $target = $upload_dir . $newName;

      if (move_uploaded_file($tmp_name, $target)) {
        $img_path = 'uploads/' . $newName;
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, image, is_read, created_at) VALUES (?, ?, ?, 0, NOW())");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $img_path);
        $stmt->execute();
      }
    }
  }
}
?>
