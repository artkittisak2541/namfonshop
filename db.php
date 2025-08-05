<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
  // ✅ เชื่อมต่อ MySQL (Localhost)
  $host = "localhost";
  $user = "root";
  $pass = "";
  $dbname = "shop_db";

  $conn = new mysqli($host, $user, $pass, $dbname);
  if ($conn->connect_error) {
    die("❌ MySQL Connection failed: " . $conn->connect_error);
  }

  $conn->set_charset("utf8mb4");
  if (!defined('DB_TYPE')) define('DB_TYPE', 'mysql');

} else {
  // ✅ เชื่อมต่อ PostgreSQL (Render)
  $host     = getenv("PGHOST");
  $dbname   = getenv("PGDATABASE");
  $user     = getenv("PGUSER");
  $pass     = getenv("PGPASSWORD");
  $port     = getenv("PGPORT");

  $conn = pg_connect("host=$host dbname=$dbname user=$user password=$pass port=$port");
  if (!$conn) {
    die("❌ PostgreSQL Connection failed.");
  }

  if (!defined('DB_TYPE')) define('DB_TYPE', 'pgsql');
}
?>
