<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
  exit('Unauthorized');
}

$sender_id = $_SESSION['user']['id'];
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// 🔍 ดึงแอดมินคนแรก
$admin_result = $conn->query("SELECT id FROM users WHERE role = 'admin' ORDER BY id ASC LIMIT 1");
if ($admin_result && $admin_result->num_rows > 0) {
  $admin_row = $admin_result->fetch_assoc();
  $receiver_id = $admin_row['id'];
} else {
  exit('❌ ไม่พบแอดมิน');
}

// ✅ ส่งข้อความ
if (!empty($message)) {
  $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())");
  $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
  if (!$stmt->execute()) {
    error_log("❌ Error insert message: " . $stmt->error);
  }
}

// ✅ ส่งรูปภาพ (หลายไฟล์)
if (!empty($_FILES['images']['name'][0])) {
  $uploadDir = '../uploads/';
  if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

  $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp' ,'jfif'];

  foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
    if ($_FILES['images']['error'][$i] === 0) {
      $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
      if (!in_array(strtolower($ext), $allowed_exts)) continue;

      $filename = uniqid('img_') . '.' . strtolower($ext);
      $targetPath = $uploadDir . $filename;

      if (move_uploaded_file($tmp, $targetPath)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, image, is_read, created_at) VALUES (?, ?, ?, 0, NOW())");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $filename);
        if (!$stmt->execute()) {
          error_log("❌ Error insert image: " . $stmt->error);
        }
      }
    }
  }
}
?>
