<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
  // ✅ Localhost (MySQL)
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
  // ✅ Render (PostgreSQL)
  $database_url = getenv("DATABASE_URL");

  if (!$database_url) {
    die("❌ DATABASE_URL environment variable is not set.");
  }

  $conn = pg_connect($database_url);

  if (!$conn) {
    die("❌ PostgreSQL Connection failed: " . pg_last_error());
  }

  pg_query($conn, "SET client_encoding TO 'UTF8'");
  if (!defined('DB_TYPE')) define('DB_TYPE', 'pgsql');
}
?>
