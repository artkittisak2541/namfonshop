<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

if (!isset($_GET['id'])) {
  die("❌ ไม่พบคำสั่งซื้อที่ต้องการดู");
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user']['id'];

// ตรวจสอบคำสั่งซื้อ
$check = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$check->bind_param("ii", $order_id, $user_id);
$check->execute();
$order = $check->get_result()->fetch_assoc();

if (!$order) {
  die("❌ ไม่พบคำสั่งซื้อหรือคุณไม่มีสิทธิ์ดูรายการนี้");
}

// ดึงสินค้าในคำสั่งซื้อ
$stmt = $conn->prepare("
  SELECT oi.*, p.name 
  FROM order_items oi 
  JOIN products p ON oi.product_id = p.id 
  WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายละเอียดคำสั่งซื้อ #<?= $order_id ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom, #eaf3fb, #f5f9fc);
      font-family: 'Sarabun', sans-serif;
    }
    .container {
      max-width: 900px;
      margin-top: 50px;
    }
    .card {
      border-radius: 16px;
      border: none;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .badge {
      font-size: 0.9rem;
      padding: 0.5em 1em;
      border-radius: 20px;
    }
    .back-btn {
      border-radius: 8px;
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4 text-center text-primary">📦 รายละเอียดคำสั่งซื้อ #<?= $order_id ?></h2>

  <div class="card mb-4">
    <div class="card-body">
      <p><strong>👤 ชื่อผู้สั่ง:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
      <p><strong>🗓 วันที่สั่งซื้อ:</strong> <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
      <p><strong>📌 สถานะ:</strong>
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
        <span class="badge bg-<?= $badge_class ?>"><?= $status ?></span>
      </p>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-light text-center">
        <tr>
          <th>สินค้า</th>
          <th>จำนวน</th>
          <th>ราคาต่อหน่วย</th>
          <th>รวม</th>
        </tr>
      </thead>
      <tbody>
        <?php $total = 0; ?>
        <?php while ($item = $items->fetch_assoc()): ?>
          <?php $sum = $item['qty'] * $item['price']; $total += $sum; ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td class="text-center"><?= $item['qty'] ?></td>
            <td class="text-end"><?= number_format($item['price']) ?> บาท</td>
            <td class="text-end"><?= number_format($sum) ?> บาท</td>
          </tr>
        <?php endwhile; ?>
        <tr class="fw-bold bg-light text-end">
          <td colspan="3">รวมทั้งหมด</td>
          <td><?= number_format($total) ?> บาท</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-4">
    <a href="my_orders.php" class="btn btn-outline-secondary back-btn">← กลับไปยังคำสั่งซื้อของฉัน</a>
  </div>
</div>

</body>
</html>
