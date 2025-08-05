<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
  // ✅ MySQL (localhost)
  $conn = new mysqli("localhost", "root", "", "shop_db");
  if ($conn->connect_error) {
    die("❌ MySQL Connection failed: " . $conn->connect_error);
  }
  $conn->set_charset("utf8mb4");
  define('DB_TYPE', 'mysql');

} else {
  // ✅ PostgreSQL (Render)
  $url = getenv("DATABASE_URL");
  if (!$url) {
    die("❌ DATABASE_URL is not set.");
  }

  $db = parse_url($url);
  if (!$db || !isset($db["host"])) {
    die("❌ DATABASE_URL is malformed.");
  }

  $host = $db["host"];
  $port = $db["port"] ?? 5432;
  $user = $db["user"];
  $pass = $db["pass"];
  $dbname = ltrim($db["path"], "/");

  $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");
  if (!$conn) {
    die("❌ PostgreSQL Connection failed: " . pg_last_error());
  }

  pg_query($conn, "SET client_encoding TO 'UTF8'");
  define('DB_TYPE', 'pgsql');
}
?>
