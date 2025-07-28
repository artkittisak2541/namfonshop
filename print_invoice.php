<?php
require 'db.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) {
  die("ไม่พบคำสั่งซื้อ");
}

// ✅ ดึงข้อมูลคำสั่งซื้อ + ข้อมูลผู้ใช้ (รวมที่อยู่จาก users ด้วย)
$stmt = $conn->prepare("
  SELECT o.*, u.username, u.phone, u.address AS user_address
  FROM orders o 
  LEFT JOIN users u ON o.user_id = u.id 
  WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
  die("ไม่พบคำสั่งซื้อ");
}

// ✅ ดึงรายการสินค้า
$stmtItems = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmtItems->bind_param("i", $order_id);
$stmtItems->execute();
$items = $stmtItems->get_result();
$stmtItems->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ใบเสร็จร้านค้า #<?= $order_id ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: 'Sarabun', sans-serif;
      padding: 40px;
    }
    .invoice-box {
      max-width: 900px;
      margin: auto;
      border: 1px solid #ccc;
      padding: 30px;
      border-radius: 10px;
    }
    .invoice-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .invoice-header h2 {
      margin-bottom: 0;
      color: #0d6efd;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>
<body>
<div class="invoice-box">
  <div class="invoice-header">
    <h2>🧾 ร้านน้ำฝนแฟชั่น</h2>
    <p>ใบเสร็จคำสั่งซื้อ #<?= $order_id ?></p>
    <p>วันที่: <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
  </div>

  <p><strong>ชื่อลูกค้า:</strong> <?= htmlspecialchars($order['customer_name']) ?> (<?= htmlspecialchars($order['username']) ?>)</p>
  <p><strong>เบอร์โทร:</strong> <?= htmlspecialchars($order['phone']) ?></p>

  <?php
    $address = !empty($order['address']) ? $order['address'] : $order['user_address'];
  ?>
  <p><strong>ที่อยู่:</strong> <?= htmlspecialchars(str_replace(["\r", "\n"], ' ', $address)) ?></p>

  <h5 class="mt-4">รายการสินค้า</h5>
  <table class="table table-bordered text-center mt-2">
    <thead class="table-light">
      <tr>
        <th>สินค้า</th>
        <th>ไซส์</th>
        <th>ราคา</th>
        <th>จำนวน</th>
        <th>รวม</th>
      </tr>
    </thead>
    <tbody>
      <?php $total = 0; ?>
      <?php while ($row = $items->fetch_assoc()): ?>
        <?php $sum = $row['price'] * $row['qty']; $total += $sum; ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['size'] ?? '-') ?></td>
          <td><?= number_format($row['price']) ?> บาท</td>
          <td><?= $row['qty'] ?></td>
          <td><?= number_format($sum) ?> บาท</td>
        </tr>
      <?php endwhile; ?>
      <tr class="fw-bold bg-light">
        <td colspan="4" class="text-end">รวมทั้งหมด</td>
        <td><?= number_format($total) ?> บาท</td>
      </tr>
    </tbody>
  </table>

  <p class="mt-4"><strong>หมายเหตุ:</strong> หากสินค้าเสียหายโปรดติดต่อร้านภายใน 3 วัน</p>

  <div class="text-end mt-5">
    <p>ลงชื่อผู้รับ ..............................................</p>
  </div>

  <div class="text-center no-print mt-4">
    <button class="btn btn-primary" onclick="window.print()">🖨️ พิมพ์ใบเสร็จ</button>
    <a href="admin_orders.php" class="btn btn-secondary">← กลับ</a>
  </div>
</div>
</body>
</html>
