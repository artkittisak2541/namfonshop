<?php
session_start();
require '../db.php';

$res = $conn->query("SELECT id, username FROM users WHERE role = 'admin'");
while ($row = $res->fetch_assoc()) {
  echo "<div><a href='?with={$row['id']}'>" . htmlspecialchars($row['username']) . "</a></div>";
}
?>
