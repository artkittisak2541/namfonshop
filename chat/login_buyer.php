<?php
session_start();
require 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users_buyer WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $buyer = $result->fetch_assoc();

  if ($buyer && password_verify($password, $buyer['password'])) {
    $_SESSION['buyer'] = $buyer;
    header("Location: shop.php");
    exit();
  } else {
    $error = "❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
  }
}
?>
