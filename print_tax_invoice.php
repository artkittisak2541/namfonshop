<?php
require 'db.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) {
  die("ไม่พบคำสั่งซื้อ");
}

// ดึงข้อมูลคำสั่งซื้อ
$stmt = $conn->prepare("SELECT o.*, u.username, u.phone FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
  die("ไม่พบคำสั่งซื้อ");
}

// ดึงรายการสินค้า
$stmtItems = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmtItems->bind_param("i", $order_id);
$stmtItems->execute();
$items = $stmtItems->get_result();
$stmtItems->close();

// คำนวณ
$total = 0;
$item_rows = [];
while ($row = $items->fetch_assoc()) {
  $sum = $row['price'] * $row['qty'];
  $total += $sum;
  $item_rows[] = $row + ['sum' => $sum];
}
$vat = $total * 0.07;
$net = $total + $vat;
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ใบกำกับภาษี #<?= $order_id ?></title>
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
      margin: 0;
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
    <h2>บริษัท น้ำฝนแฟชั่น จำกัด</h2>
    <p>เลขประจำตัวผู้เสียภาษี: 0105551234567</p>
    <p>ที่อยู่: 123/4 ซอยพาณิชย์ แขวงตลาดบางเขน เขตบางเขน กรุงเทพฯ 10220</p>
    <h5 class="mt-3">ใบกำกับภาษีอย่างย่อ เลขที่: TAX-<?= str_pad($order_id, 6, '0', STR_PAD_LEFT) ?></h5>
    <p>วันที่ออก: <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
  </div>

  <p><strong>ชื่อลูกค้า:</strong> <?= htmlspecialchars($order['customer_name']) ?> (<?= htmlspecialchars($order['username']) ?>)</p>
  <p><strong>เบอร์โทร:</strong> <?= htmlspecialchars($order['phone']) ?></p>
  <p><strong>ที่อยู่:</strong><br><?= nl2br(htmlspecialchars($order['address'])) ?></p>

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
      <?php foreach ($item_rows as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['size'] ?? '-') ?></td>
          <td><?= number_format($row['price'], 2) ?></td>
          <td><?= $row['qty'] ?></td>
          <td><?= number_format($row['sum'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="4" class="text-end">รวมก่อน VAT</td>
        <td><?= number_format($total, 2) ?> บาท</td>
      </tr>
      <tr>
        <td colspan="4" class="text-end">VAT 7%</td>
        <td><?= number_format($vat, 2) ?> บาท</td>
      </tr>
      <tr class="fw-bold bg-light">
        <td colspan="4" class="text-end">รวมสุทธิ</td>
        <td><?= number_format($net, 2) ?> บาท</td>
      </tr>
    </tbody>
  </table>

  <div class="text-end mt-5">
    <p>ลงชื่อผู้รับสินค้า ..............................................</p>
  </div>

  <div class="text-center mt-4 no-print">
    <button class="btn btn-primary" onclick="window.print()">🖨️ พิมพ์ใบกำกับภาษี</button>
    <a href="admin_orders.php" class="btn btn-secondary">← กลับ</a>
  </div>
</div>
</body>
</html>
