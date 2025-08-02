<?php
require 'db.php';

// ✅ สร้างตาราง users ถ้ายังไม่มี
$sql = "
CREATE TABLE IF NOT EXISTS users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  fullname VARCHAR(100),
  address TEXT,
  phone VARCHAR(20),
  role VARCHAR(20) DEFAULT 'user'
);
";

if (DB_TYPE === 'pgsql') {
  $result = pg_query($conn, $sql);
  if (!$result) {
    die("❌ Init failed: " . pg_last_error($conn));
  } else {
    echo "✅ Created users table.";
  }
} else {
  die("❌ This init script is for PostgreSQL only.");
}
