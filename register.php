<?php
require 'db.php'; // ✅ เชื่อมต่อฐานข้อมูล และกำหนด DB_TYPE

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $fullname = $_POST['fullname'];
  $address = $_POST['address'];
  $phone = $_POST['phone'];

  if (defined('DB_TYPE') && DB_TYPE === 'mysql') {
    // ✅ กรณีใช้ MySQL (localhost)
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $error = "❌ ชื่อผู้ใช้นี้มีอยู่แล้ว กรุณาใช้ชื่ออื่น";
    } else {
      $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, address, phone) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("sssss", $username, $hashed_password, $fullname, $address, $phone);

      if ($stmt->execute()) {
        header("Location: login.php?registered=1");
        exit();
      } else {
        $error = "เกิดข้อผิดพลาด: " . $conn->error;
      }
      $stmt->close();
    }
    $check->close();

  } else {
    // ✅ กรณีใช้ PostgreSQL (Render)
    $checkQuery = "SELECT id FROM users WHERE username = $1";
    $checkResult = pg_query_params($conn, $checkQuery, [$username]);

    if (pg_num_rows($checkResult) > 0) {
      $error = "❌ ชื่อผู้ใช้นี้มีอยู่แล้ว กรุณาใช้ชื่ออื่น";
    } else {
      $insertQuery = "INSERT INTO users (username, password, fullname, address, phone) VALUES ($1, $2, $3, $4, $5)";
      $params = [$username, $hashed_password, $fullname, $address, $phone];
      $result = pg_query_params($conn, $insertQuery, $params);

      if ($result) {
        header("Location: login.php?registered=1");
        exit();
      } else {
        $error = "เกิดข้อผิดพลาด: " . pg_last_error($conn);
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สมัครสมาชิก | น้ำฝนแฟชั่น</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom right, #d0e9ff, #ffffff);
      font-family: 'Prompt', sans-serif;
    }
    .card {
      max-width: 500px;
      margin: 40px auto;
      padding: 30px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      border-radius: 20px;
      border: none;
    }
    h2 {
      color: #007bff;
      text-align: center;
      margin-bottom: 25px;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card">
    <h2>📝 สมัครสมาชิก</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">ชื่อผู้ใช้</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">รหัสผ่าน</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">ชื่อ-นามสกุล</label>
        <input type="text" name="fullname" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">ที่อยู่</label>
        <textarea name="address" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">เบอร์โทร</label>
        <input type="text" name="phone" class="form-control" required>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-success">✅ สมัครสมาชิก</button>
        <a href="login.php" class="btn btn-outline-secondary">← กลับไปเข้าสู่ระบบ</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
