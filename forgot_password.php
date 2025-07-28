<?php
require 'db.php';

$message = '';

// ✅ ตั้งค่า API จาก ThaiBulkSMS
define('THAIBULKSMS_API_KEY', 'YOUR_API_KEY');
define('THAIBULKSMS_API_SECRET', 'YOUR_API_SECRET');
define('THAIBULKSMS_SENDER', 'THSMS'); // หรือชื่อที่ระบบอนุมัติไว้

function sendSMS($toPhone, $text) {
  $url = 'https://otp.thaibulksms.com/sms/json';
  $data = [
    'apikey' => THAIBULKSMS_API_KEY,
    'secret' => THAIBULKSMS_API_SECRET,
    'msisdn' => $toPhone,
    'message' => $text,
    'sender' => THAIBULKSMS_SENDER,
  ];

  $options = [
    'http' => [
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data),
    ]
  ];

  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  return $result;
}

// ✅ ประมวลผลแบบ POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $phone = $_POST['phone'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE phone = ?");
  $stmt->bind_param("s", $phone);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user) {
    $new_password_plain = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8);
    $new_password_hashed = password_hash($new_password_plain, PASSWORD_DEFAULT);

    $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update->bind_param("si", $new_password_hashed, $user['id']);
    $update->execute();

    // ✅ ส่ง SMS
    $sms_message = "ร้านน้ำฝนแฟชั่น\nรหัสผ่านใหม่ของคุณคือ: $new_password_plain";
    $sms_result = sendSMS($phone, $sms_message);

    $message = "✅ ส่งรหัสผ่านใหม่ไปยังเบอร์ $phone เรียบร้อยแล้ว";
  } else {
    $message = "❌ ไม่พบเบอร์โทรนี้ในระบบ";
  }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ลืมรหัสผ่าน - น้ำฝนแฟชั่น</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Kanit', sans-serif;
      background-image: url('images/background1.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      min-height: 100vh;
      margin: 0;
    }
    .container {
      max-width: 400px;
      margin: 100px auto;
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #007bff;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>🔑 ลืมรหัสผ่าน</h2>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= $message ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">กรอกเบอร์โทรที่ลงทะเบียนไว้</label>
      <input type="text" name="phone" class="form-control" required placeholder="เช่น 0812345678">
    </div>
    <div class="d-grid gap-2">
      <button type="submit" class="btn btn-primary">ส่งรหัสผ่านใหม่</button>
      <a href="login.php" class="btn btn-outline-secondary">ย้อนกลับ</a>
    </div>
  </form>
</div>

</body>
</html>
