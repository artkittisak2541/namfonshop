<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>🛒 ตะกร้าสินค้า</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0f4f8;
      font-family: 'Sarabun', sans-serif;
    }
    .cart-container {
      max-width: 900px;
      margin: auto;
      margin-top: 50px;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    }
    .btn-primary, .btn-success {
      width: 150px;
    }
    th, td {
      vertical-align: middle !important;
    }
  </style>
</head>
<body>

<div class="container cart-container">
  <h2 class="mb-4 text-center">🛒 ตะกร้าสินค้า</h2>

  <?php if (empty($_SESSION['cart'])): ?>
    <div class="alert alert-info text-center">ยังไม่มีสินค้าในตะกร้า</div>
    <div class="text-center">
      <a href="shop.php" class="btn btn-outline-primary">← กลับไปเลือกซื้อสินค้า</a>
    </div>
  <?php else: ?>
    <form method="post" action="update_cart.php">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>สินค้า</th>
            <th>ไซส์</th>
            <th>ราคา</th>
            <th>จำนวน</th>
            <th>รวม</th>
            <th>ลบ</th>
          </tr>
        </thead>
        <tbody>
          <?php $total = 0; ?>
          <?php foreach ($_SESSION['cart'] as $key => $item): ?>
            <?php
              $sum = $item['price'] * $item['qty'];
              $total += $sum;

              // 🔹 โหลดไซส์จากฐานข้อมูล
              $stmt = $conn->prepare("SELECT size FROM products WHERE id = ?");
              $stmt->bind_param("i", $item['id']);
              $stmt->execute();
              $result = $stmt->get_result()->fetch_assoc();
              $availableSizes = [];

              if ($result && $result['size']) {
                $availableSizes = array_map('trim', explode(',', $result['size']));
              }
            ?>
            <tr>
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td>
                <select name="size[<?= $key ?>]" class="form-select">
                  <?php
                    $sizesToUse = !empty($availableSizes) ? $availableSizes : ['S', 'M', 'L', 'XL'];
                    foreach ($sizesToUse as $sizeOption) {
                      $selected = ($item['size'] === $sizeOption) ? 'selected' : '';
                      echo "<option value=\"$sizeOption\" $selected>$sizeOption</option>";
                    }
                  ?>
                </select>
              </td>
              <td><?= number_format($item['price']) ?> ฿</td>
              <td>
                <input type="number" name="qty[<?= $key ?>]" value="<?= $item['qty'] ?>" min="1" class="form-control" style="width: 80px; margin: auto;">
              </td>
              <td><?= number_format($sum) ?> ฿</td>
              <td><a href="remove_from_cart.php?id=<?= $key ?>" class="btn btn-sm btn-danger">ลบ</a></td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td colspan="4" class="text-end fw-bold">รวมทั้งหมด:</td>
            <td colspan="2" class="fw-bold text-success"><?= number_format($total) ?> ฿</td>
          </tr>
        </tbody>
      </table>

      <div class="d-flex justify-content-between mt-4">
        <a href="shop.php" class="btn btn-outline-secondary">← กลับไปร้านค้า</a>
        <div>
          <button type="submit" class="btn btn-primary">อัปเดตตะกร้า</button>
          <a href="checkout.php" class="btn btn-success">ยืนยันสั่งซื้อ</a>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>

</body>
</html>
