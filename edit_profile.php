<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user']['id'];
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

if (!$user) {
  die("❌ ไม่พบข้อมูลผู้ใช้งาน (ID: $user_id)");
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullname = $_POST['fullname'];
  $address = $_POST['address'];
  $phone = $_POST['phone'];

  if (!empty($_POST['new_password'])) {
    $new_pass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET fullname=?, address=?, phone=?, password=? WHERE id=?");
    $stmt->bind_param("ssssi", $fullname, $address, $phone, $new_pass, $user_id);
  } else {
    $stmt = $conn->prepare("UPDATE users SET fullname=?, address=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $fullname, $address, $phone, $user_id);
  }

  if ($stmt->execute()) {
    $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $_SESSION['user'] = $result->fetch_assoc();
    $user = $_SESSION['user'];
    $success = "✅ อัปเดตโปรไฟล์เรียบร้อยแล้ว";
  } else {
    $error = "❌ เกิดข้อผิดพลาด: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แก้ไขโปรไฟล์</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e3f2fd, #fce4ec);
      font-family: 'Kanit', sans-serif;
    }
    .profile-form {
      max-width: 600px;
      margin: 60px auto;
      padding: 30px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    h2 {
      color: #007bff;
      font-weight: 600;
      text-align: center;
      margin-bottom: 25px;
    }
    .form-label {
      font-weight: 500;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
    .btn-secondary {
      background-color: #6c757d;
      border: none;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="profile-form">
    <h2>👤 แก้ไขข้อมูลส่วนตัว</h2>

    <?php if ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">ชื่อ-นามสกุล</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">ที่อยู่</label>
        <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">เบอร์โทร</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">รหัสผ่านใหม่ (ถ้าต้องการเปลี่ยน)</label>
        <input type="password" name="new_password" class="form-control" placeholder="เว้นว่างหากไม่เปลี่ยน">
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">💾 บันทึกข้อมูล</button>
        <a href="shop.php" class="btn btn-secondary">← กลับหน้าร้าน</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
