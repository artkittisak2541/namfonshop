<?php
require 'db.php'; // เชื่อมต่อ PostgreSQL

// ข้อมูลผู้ใช้ใหม่
$username = 'demo';
$password = password_hash('1234', PASSWORD_DEFAULT);
$fullname = 'บัญชีทดสอบ';
$address = 'กรุงเทพฯ';
$phone = '0912345678';

// เพิ่มเข้า PostgreSQL
$result = pg_query_params($conn, "
  INSERT INTO users (username, password, fullname, address, phone)
  VALUES ($1, $2, $3, $4, $5)
", [$username, $password, $fullname, $address, $phone]);

if ($result) {
  echo "✅ เพิ่มบัญชีผู้ใช้ demo สำเร็จแล้ว<br>สามารถล็อกอินด้วย<br><strong>Username: demo</strong><br><strong>Password: 1234</strong>";
} else {
  echo "❌ เพิ่มไม่สำเร็จ: " . pg_last_error($conn);
}
?>
