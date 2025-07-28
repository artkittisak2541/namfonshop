<?php
session_start();
require '../db.php'; // ปรับ path ตามโครงสร้างของคุณ

if (!isset($_SESSION['user'])) {
  http_response_code(401);
  exit("ไม่ได้เข้าสู่ระบบ");
}

// ตรวจสอบว่าไฟล์ถูกอัปโหลดหรือไม่
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
  exit("❌ ไม่พบไฟล์ที่อัปโหลดหรือเกิดข้อผิดพลาด");
}

$uploadDir = '../uploads/';
$originalName = $_FILES['file']['name'];
$tmpName = $_FILES['file']['tmp_name'];
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

// ✅ กรองเฉพาะนามสกุลที่อนุญาต
$allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'jfif', 'mp4'];
if (!in_array($ext, $allowedExt)) {
  exit("❌ ไฟล์ประเภทนี้ไม่ได้รับอนุญาต");
}

// ✅ แปลง .jfif เป็น .jpg
if ($ext === 'jfif') {
  $ext = 'jpg';
}

// ✅ สร้างชื่อไฟล์ใหม่ป้องกันชื่อซ้ำ
$newName = uniqid('file_', true) . '.' . $ext;
$destPath = $uploadDir . $newName;

// ✅ ตรวจสอบว่าเป็นรูปจริง (หรือวิดีโอ)
$mime = mime_content_type($tmpName);
if (strpos($mime, 'image/') !== 0 && strpos($mime, 'video/') !== 0) {
  exit("❌ รองรับเฉพาะภาพหรือวิดีโอเท่านั้น");
}

// ✅ ย้ายไฟล์ไปที่โฟลเดอร์ปลายทาง
if (!move_uploaded_file($tmpName, $destPath)) {
  exit("❌ ไม่สามารถบันทึกไฟล์ได้");
}

// ✅ บันทึกชื่อไฟล์ในฐานข้อมูล (เช่น ฟิลด์ image หรือ file)
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, image) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $_SESSION['user']['id'], $_POST['receiver_id'], $newName);
$stmt->execute();

echo "✅ อัปโหลดสำเร็จ";
