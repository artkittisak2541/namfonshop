<?php
require 'db.php';

$sql = file_get_contents(__DIR__ . '/init_postgresql.sql');

if (!$sql) {
  die("❌ ไม่พบไฟล์ init_postgresql.sql");
}

if (DB_TYPE === 'pgsql') {
  $result = pg_query($conn, $sql);
  if ($result) {
    echo "✅ ติดตั้งตารางเรียบร้อยแล้ว";
  } else {
    echo "❌ เกิดข้อผิดพลาดในการติดตั้งตาราง: " . pg_last_error($conn);
  }
} else {
  echo "❗ ฐานข้อมูลไม่ใช่ PostgreSQL";
}
?>
