<?php
session_start();
require 'db.php';

// ดึงคำสั่งซื้อทั้งหมด พร้อมรวมราคารวมของแต่ละออเดอร์
$sql = "SELECT o.id, o.customer_name, o.created_at, o.status, 
        SUM(oi.qty * oi.price) AS total_price
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        GROUP BY o.id
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>คำสั่งซื้อทั้งหมด (หลังบ้าน)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f3f6fc;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      margin-top: 50px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="container">
  <h2 class="text-primary mb-4">📦 รายการคำสั่งซื้อทั้งหมด</h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-bordered table-hover text-center align-middle">
      <thead class="table-info">
        <tr>
          <th>รหัส</th>
          <th>ชื่อลูกค้า</th>
          <th>วันที่สั่ง</th>
          <th>ยอดรวม</th>
          <th>สถานะ</th>
          <th>ดูรายการ</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td>#<?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['customer_name']) ?></td>
          <td><?= date("d/m/Y H:i", strtotime($row['created_at'])) ?></td>
          <td><?= number_format($row['total_price']) ?> บาท</td>
          <td>
            <?= $row['status'] === 'จัดส่งแล้ว' ? '<span class="badge bg-success">จัดส่งแล้ว</span>' : '<span class="badge bg-warning text-dark">รอดำเนินการ</span>' ?>
          </td>
          <td><a href="admin_order_detail.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">ดู</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="alert alert-info">ยังไม่มีคำสั่งซื้อ</div>
  <?php endif; ?>
</div>
</body>
<audio id="alertSound" src="https://notificationsounds.com/storage/sounds/file-sounds-1151-pristine.mp3" preload="auto"></audio>

<script>
let previousCount = 0;

function checkNewOrders() {
  fetch('check_new_orders.php')
    .then(res => res.text())
    .then(count => {
      const newCount = parseInt(count);
      if (newCount > previousCount) {
        document.getElementById('alertSound').play();
        alert(`🔔 คำสั่งซื้อใหม่ ${newCount} รายการ`);
      }
      previousCount = newCount;
    });
}

// ตรวจทุก 10 วินาที
setInterval(checkNewOrders, 10000);
checkNewOrders(); // ตรวจทันทีรอบแรก
</script>

</html>
