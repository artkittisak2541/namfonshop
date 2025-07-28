<?php
session_start();
require '../db.php';
ob_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? AND role = 'admin'");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $admin = $result->fetch_assoc();

  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['user'] = [
      'id' => $admin['id'],
      'username' => $admin['username'],
      'role' => $admin['role']
    ];
    header("Location: chat_admin.php");
    exit();
  } else {
    $error = "❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง หรือไม่ใช่แอดมิน";
  }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เข้าสู่ระบบแอดมิน</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100" style="background: linear-gradient(to right, #fce4ec, #f3e5f5); font-family: 'Kanit', sans-serif;">
  <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
    <h4 class="mb-3 text-center">🔐 เข้าสู่ระบบแอดมิน</h4>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label for="username" class="form-label">ชื่อผู้ใช้</label>
        <input type="text" name="username" id="username" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">รหัสผ่าน</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
    </form>
  </div>
</body>
</html>
