<?php
require 'db.php';
$sql = "ALTER TABLE users ADD remember_token VARCHAR(64) DEFAULT NULL";
if ($conn->query($sql) === TRUE) {
  echo "เพิ่ม remember_token สำเร็จ";
} else {
  echo "เกิดข้อผิดพลาด: " . $conn->error;
}
?>
