<?php
require 'db.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($order_id <= 0) {
  die("❌ ไม่พบคำสั่งซื้อที่ระบุ");
}

// ✅ อัปเดตสถานะคำสั่งซื้อ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['new_status'])) {
    $new_status = $_POST['new_status'];
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_order_detail.php?id=$order_id");
    exit();
  }

  if (isset($_POST['update_address']) && isset($_POST['new_address'])) {
    $new_address = trim($_POST['new_address']);
    $stmt = $conn->prepare("UPDATE orders SET address=? WHERE id=?");
    $stmt->bind_param("si", $new_address, $order_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_order_detail.php?id=$order_id");
    exit();
  }
}

// ✅ ดึงข้อมูลคำสั่งซื้อ พร้อม username
$stmt = $conn->prepare("
  SELECT o.*, u.username, u.phone AS user_phone, u.address AS user_address
  FROM orders o 
  LEFT JOIN users u ON o.user_id = u.id 
  WHERE o.id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();
$stmt->close();

if (!$order) {
  die("❌ ไม่พบข้อมูลคำสั่งซื้อ");
}

$address = $order['address'] ?: $order['user_address'];
$phone = $order['phone'] ?: $order['user_phone'];

// ✅ ดึงรายการสินค้า
$stmtItems = $conn->prepare("
  SELECT oi.*, p.name 
  FROM order_items oi 
  JOIN products p ON oi.product_id = p.id 
  WHERE oi.order_id = ?
");
$stmtItems->bind_param("i", $order_id);
$stmtItems->execute();
$items = $stmtItems->get_result();
$stmtItems->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>รายละเอียดคำสั่งซื้อ #<?= $order_id ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f5f8ff;
      font-family: 'Sarabun', sans-serif;
    }
    .order-box {
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      margin-top: 40px;
    }
    h3 {
      color: #0d6efd;
    }
    .table th, .table td {
      vertical-align: middle;
    }
  </style>
</head>
<body class="container">

<div class="order-box">
  <h3 class="mb-3">📄 คำสั่งซื้อ #<?= $order_id ?></h3>

  <div class="d-flex justify-content-between mb-2">
    <p class="mb-0"><strong>ลูกค้า:</strong> <?= htmlspecialchars($order['customer_name']) ?> (<?= htmlspecialchars($order['username']) ?>)</p>
    <p class="mb-0"><strong>วันที่สั่งซื้อ:</strong> <?= date("d/m/Y H:i", strtotime($order['created_at'])) ?></p>
  </div>
  <p><strong>เบอร์โทร:</strong> <?= htmlspecialchars($phone) ?></p>
  <p><strong>ที่อยู่:</strong> <?= nl2br(htmlspecialchars($address)) ?></p>

  <!-- ✅ ฟอร์มอัปเดตสถานะ -->
  <form method="post" class="mb-4">
    <label for="new_status" class="form-label"><strong>สถานะ:</strong></label>
    <div class="input-group" style="max-width: 300px;">
      <select name="new_status" id="new_status" class="form-select">
        <?php
        $statuses = ['รอดำเนินการ', 'กำลังจัดส่ง', 'สำเร็จ', 'ยกเลิก'];
        foreach ($statuses as $status) {
          $selected = ($status === $order['status']) ? 'selected' : '';
          echo "<option value=\"$status\" $selected>$status</option>";
        }
        ?>
      </select>
      <button type="submit" class="btn btn-primary">อัปเดต</button>
    </div>
  </form>

  <!-- ✅ ฟอร์มแก้ไขที่อยู่ -->
  <form method="post" class="mb-4">
    <label for="new_address" class="form-label"><strong>แก้ไขที่อยู่ลูกค้า:</strong></label>
    <div style="max-width: 600px;">
      <textarea name="new_address" id="new_address" class="form-control" rows="3"><?= htmlspecialchars($address) ?></textarea>
      <button type="submit" name="update_address" class="btn btn-outline-primary mt-2">💾 บันทึกที่อยู่ใหม่</button>
    </div>
  </form>

  <h5 class="mb-3">🛍️ รายการสินค้า:</h5>
  <div class="table-responsive">
    <table class="table table-bordered text-center align-middle">
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
  </div>

  <div class="mt-4 d-flex gap-2">
    <a href="admin_orders.php" class="btn btn-secondary">← กลับ</a>
    <a href="print_invoice.php?id=<?= $order_id ?>" target="_blank" class="btn btn-outline-success">🖨️ พิมพ์ใบเสร็จ</a>
  </div>
</div>

</body>
</html>
