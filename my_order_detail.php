<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit();
}

$user = $_SESSION['user'];
$order_id = $_GET['id'] ?? 0;

// ตรวจสอบว่าออเดอร์นี้เป็นของ user นี้จริง
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user['id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
  die("❌ ไม่พบคำสั่งซื้อ");
}

// รายการสินค้า
$sql = "SELECT oi.*, p.name FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?";
$stmt2 = $conn->prepare($sql);
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$items = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายละเอียดคำสั่งซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">

<h3 class="text-primary mb-3">🧾 รายละเอียดคำสั่งซื้อ #<?= $order_id ?></h3>

<p><strong>วันที่สั่ง:</strong> <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
<p><strong>สถานะ:</strong> <?= $order['status'] ?></p>

<table class="table table-bordered text-center">
  <thead class="table-light">
    <tr>
      <th>สินค้า</th>
      <th>ราคา</th>
      <th>จำนวน</th>
      <th>รวม</th>
    </tr>
  </thead>
  <tbody>
    <?php $total = 0; ?>
    <?php while ($item = $items->fetch_assoc()): ?>
      <?php $sum = $item['qty'] * $item['price']; $total += $sum; ?>
      <tr>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= number_format($item['price']) ?> บาท</td>
        <td><?= $item['qty'] ?></td>
        <td><?= number_format($sum) ?> บาท</td>
      </tr>
    <?php endwhile; ?>
    <tr class="fw-bold bg-light">
      <td colspan="3">รวมทั้งหมด</td>
      <td><?= number_format($total) ?> บาท</td>
    </tr>
  </tbody>
</table>

<a href="my_orders.php" class="btn btn-secondary mt-3">← ย้อนกลับ</a>

</body>
</html>
