<?php
require 'db.php';

// อัปเดตสถานะถ้ามีการส่ง form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
  $order_id = $_POST['order_id'];
  $status = $_POST['status'];
  $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
  $stmt->bind_param("si", $status, $order_id);
  $stmt->execute();
  $stmt->close();
}

// ดึงรายการคำสั่งซื้อทั้งหมด
$orders_sql = "SELECT * FROM orders ORDER BY id DESC";
$orders_result = $conn->query($orders_sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>คำสั่งซื้อทั้งหมด</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h2 class="text-primary">📦 คำสั่งซื้อทั้งหมด</h2>

  <?php if ($orders_result->num_rows > 0): ?>
    <?php while($order = $orders_result->fetch_assoc()): ?>
      <div class="card my-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
          <div>
            <strong>คำสั่งซื้อ #<?= $order['id'] ?></strong> |
            ผู้สั่ง: <?= htmlspecialchars($order['customer_name']) ?> |
            เวลา: <?= $order['created_at'] ?>
          </div>
          <form method="post" class="d-flex gap-2 align-items-center">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <select name="status" class="form-select form-select-sm w-auto">
              <?php
              $statuses = ['รอดำเนินการ', 'จัดส่งแล้ว', 'สำเร็จ'];
              foreach ($statuses as $s):
              ?>
                <option value="<?= $s ?>" <?= $order['status'] == $s ? 'selected' : '' ?>><?= $s ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-primary">อัปเดต</button>
          </form>
        </div>
        <div class="card-body">
          <p><strong>สถานะ:</strong> <?= $order['status'] ?></p>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>สินค้า</th>
                <th>จำนวน</th>
                <th>ราคา</th>
                <th>รวม</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $total = 0;
              $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
              $stmt->bind_param("i", $order['id']);
              $stmt->execute();
              $items = $stmt->get_result();

              while($item = $items->fetch_assoc()):
                $sum = $item['price'] * $item['qty'];
                $total += $sum;
              ?>
                <tr>
                  <td><?= htmlspecialchars($item['product_name']) ?></td>
                  <td><?= $item['qty'] ?></td>
                  <td><?= $item['price'] ?> บาท</td>
                  <td><?= $sum ?> บาท</td>
                </tr>
              <?php endwhile; $stmt->close(); ?>
              <tr>
                <td colspan="3" class="text-end fw-bold">รวมทั้งหมด</td>
                <td class="fw-bold"><?= $total ?> บาท</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="alert alert-info">ยังไม่มีคำสั่งซื้อ</div>
  <?php endif; ?>

  <a href="admin.php" class="btn btn-secondary">← กลับหลังบ้าน</a>
</div>
</body>
</html>
