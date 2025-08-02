<?php
require 'db.php';

$sql = file_get_contents('init_postgresql_v2.sql');

if (!$sql) {
  die("❌ ไม่พบไฟล์ SQL");
}

$result = pg_query($conn, $sql);

if (!$result) {
  die("❌ รัน SQL ไม่สำเร็จ: " . pg_last_error($conn));
}

echo "✅ สร้างตารางสำเร็จ!";
?>
