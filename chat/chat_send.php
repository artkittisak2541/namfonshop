<?php
session_start();
require '../db.php';

// 🔐 ตรวจสอบ session
if (!isset($_SESSION['user'])) {
  exit('Unauthorized');
}

$sender_id = $_SESSION['user']['id'];

// ✅ ถ้าไม่มี receiver_id ให้หาจาก admin
if (!isset($_POST['receiver_id']) || intval($_POST['receiver_id']) <= 0) {
  $adminRes = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
  $adminRow = $adminRes->fetch_assoc();
  if (!$adminRow) exit('ไม่พบผู้ดูแลระบบ');
  $receiver_id = $adminRow['id'];
} else {
  $receiver_id = intval($_POST['receiver_id']);
}

$message = trim($_POST['message']);

// 📌 สร้างโฟลเดอร์ uploads หากยังไม่มี
$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) {
  mkdir($uploadDir, 0755, true);
}

// ✅ บันทึกข้อความ (ถ้ามี)
if (!empty($message)) {
  $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
  $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
  $stmt->execute();
}

// ✅ บันทึกภาพหลายไฟล์ (ถ้ามี)
if (!empty($_FILES['images']['name'][0])) {
  foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
    if ($_FILES['images']['error'][$i] === 0 && is_uploaded_file($tmp)) {
      $originalName = basename($_FILES['images']['name'][$i]);
      $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

      // ✅ ถ้าเป็น .jfif → เปลี่ยนเป็น .jpg
      if ($ext === 'jfif') $ext = 'jpg';

      $safeName = uniqid('img_') . '.' . $ext;
      $target = $uploadDir . $safeName;

      if (move_uploaded_file($tmp, $target)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, image) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $safeName);
        $stmt->execute();
      }
    }
  }
}
?>
