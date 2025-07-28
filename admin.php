<?php
require 'db.php';
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการสินค้า - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom right, #f0f4ff, #ffffff);
      font-family: 'Sarabun', sans-serif;
    }
    .admin-box {
      background: #ffffff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.07);
    }
    h2 {
      font-weight: bold;
      color: #0d6efd;
    }
    .btn {
      border-radius: 12px;
    }
    .img-thumbnail {
      border-radius: 12px;
    }
    .badge-stock {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="admin-box">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>🛠 จัดการสินค้า</h2>
      <a href="add_product.php" class="btn btn-success shadow-sm">+ เพิ่มสินค้า</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th width="50">#</th>
            <th width="130">ภาพ</th>
            <th>ชื่อสินค้า</th>
            <th width="150">ไซส์</th> <!-- ✅ เพิ่มคอลัมน์ไซส์ -->
            <th width="120">คงเหลือ</th>
            <th width="120">ราคา</th>
            <th width="200">จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td>
                <?php if ($row['image']): ?>
                  <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="img" class="img-thumbnail" style="max-height:80px;">
                <?php else: ?>
                  <img src="https://via.placeholder.com/100x80?text=No+Image" class="img-thumbnail">
                <?php endif; ?>
              </td>
              <td class="text-start"><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['size']) ?: '-' ?></td> <!-- ✅ แสดงไซส์ ถ้ามี -->
              <td>
                <?php
                $stock = $row['quantity'];
                if ($stock > 10) {
                  echo '<span class="badge bg-success badge-stock">เหลือมาก</span><br>' . $stock;
                } elseif ($stock > 0) {
                  echo '<span class="badge bg-warning text-dark badge-stock">ใกล้หมด</span><br>' . $stock;
                } else {
                  echo '<span class="badge bg-danger badge-stock">หมด</span>';
                }
                ?>
              </td>
              <td class="text-end"><?= number_format($row['price']) ?> บาท</td>
              <td>
                <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning me-1">✏️ แก้ไข</a>
                <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันการลบสินค้านี้?')">🗑 ลบ</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      <a href="index.php" class="btn btn-outline-secondary">← กลับหน้าร้าน</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
