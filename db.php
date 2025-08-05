<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
  // เชื่อม MySQL
  $conn = new mysqli("localhost", "root", "", "shop_db");
  if ($conn->connect_error) die("❌ MySQL Error: " . $conn->connect_error);
  if (!defined('DB_TYPE')) define('DB_TYPE', 'mysql');

} else {
  // เชื่อม PostgreSQL
  $host = getenv("PGHOST");
  $dbname = getenv("PGDATABASE");
  $user = getenv("PGUSER");
  $pass = getenv("PGPASSWORD");
  $port = getenv("PGPORT");

  $conn = pg_connect("host=$host dbname=$dbname user=$user password=$pass port=$port");

  if (!$conn) {
    die("❌ PostgreSQL connection failed. ตรวจสอบ .env หรือ Environment Variables");
  }

  if (!defined('DB_TYPE')) define('DB_TYPE', 'pgsql');
}
?>
