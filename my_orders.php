<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

$user = $_SESSION['user'];

// ดึงคำสั่งซื้อของผู้ใช้
$stmt = $conn->prepare("
  SELECT o.id, o.created_at, o.status,
         SUM(oi.qty * oi.price) AS total_price
  FROM orders o
  JOIN order_items oi ON o.id = oi.order_id
  WHERE o.user_id = ?
  GROUP BY o.id, o.created_at, o.status
  ORDER BY o.created_at DESC
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ประวัติคำสั่งซื้อของฉัน</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom, #eef3f8, #d8e3f0);
      font-family: 'Sarabun', sans-serif;
      padding-bottom: 60px;
    }
    .container {
      max-width: 850px;
      margin-top: 50px;
    }
    .card {
      border-radius: 16px;
      border: none;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      transition: transform 0.2s;
    }
    .card:hover {
      transform: translateY(-3px);
    }
    .btn-outline-primary {
      border-radius: 8px;
    }
    h2 {
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4 text-center text-primary">🧾 ประวัติคำสั่งซื้อของฉัน</h2>

  <?php if ($orders->num_rows > 0): ?>
    <?php while ($order = $orders->fetch_assoc()): ?>
      <?php
        $status = $order['status'];
        $badge_class = match ($status) {
          'รอดำเนินการ' => 'warning',
          'กำลังจัดส่ง' => 'info',
          'สำเร็จ' => 'success',
          'ยกเลิก' => 'danger',
          default => 'secondary'
        };
      ?>
      <div class="card mb-3">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">คำสั่งซื้อ #<?= $order['id'] ?></h5>
            <span class="badge bg-<?= $badge_class ?>"><?= $status ?></span>
          </div>
          <p class="mb-1">🗓 วันที่: <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
          <p class="mb-0">💰 ยอดรวม: <strong><?= number_format($order['total_price']) ?> บาท</strong></p>
          <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary mt-3">ดูรายละเอียด</a>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert alert-info text-center">คุณยังไม่มีคำสั่งซื้อ</div>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="shop.php" class="btn btn-secondary">← กลับหน้าร้าน</a>
  </div>
</div>

</body>
</html>
