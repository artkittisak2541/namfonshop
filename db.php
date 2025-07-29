<?php
// 🔗 เชื่อมต่อ MySQL ด้วย MySQLi
$host = "localhost";
$user = "root";
$pass = ""; // ถ้า XAMPP ยังไม่ได้ตั้งรหัสผ่าน root จะว่าง
$dbname = "shop_db"; // ✅ ฐานข้อมูลต้องมีอยู่จริง

$conn = new mysqli($host, $user, $pass, $dbname);

// ❌ ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
  die("❌ การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4"); // ✅ รองรับภาษาไทย
?>
