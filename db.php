<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
  // ✅ Localhost ใช้ MySQL
  $conn = new mysqli("localhost", "root", "", "shop_db");
  if ($conn->connect_error) {
    die("❌ MySQL Connection failed: " . $conn->connect_error);
  }
  if (!defined('DB_TYPE')) define('DB_TYPE', 'mysql');
} else {
  // ✅ Render ใช้ PostgreSQL
  $host = getenv("PGHOST");
  $dbname = getenv("PGDATABASE");
  $user = getenv("PGUSER");
  $pass = getenv("PGPASSWORD");
  $port = getenv("PGPORT");

  $conn = pg_connect("host=$host dbname=$dbname user=$user password=$pass port=$port");

  if (!$conn) {
    die("❌ PostgreSQL connection failed");
  }

  if (!defined('DB_TYPE')) define('DB_TYPE', 'pgsql');
}
?>
