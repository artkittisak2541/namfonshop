<?php
require 'db.php';

$id = $_GET['id'];

// ดึงข้อมูลสินค้าเดิม
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];
  $size = $_POST['size'];
  $imageName = $product['image'];

  // อัปโหลดรูปใหม่ถ้ามี
  if (!empty($_FILES['image']['name'])) {
    $targetDir = "images/";
    $newImage = uniqid() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $newImage;

    if ($product['image'] && file_exists("images/" . $product['image'])) {
      unlink("images/" . $product['image']);
    }

    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    $imageName = $newImage;
  }

  // อัปเดตข้อมูลสินค้า
  $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, quantity = ?, size = ?, image = ? WHERE id = ?");
  $stmt->bind_param("siissi", $name, $price, $quantity, $size, $imageName, $id);
  $stmt->execute();
  $stmt->close();

  header("Location: admin.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แก้ไขสินค้า</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #eef2f7;
      font-family: 'Segoe UI', sans-serif;
    }
    .edit-box {
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      max-width: 600px;
      margin: 60px auto;
    }
    h2 {
      color: #007bff;
      text-align: center;
      margin-bottom: 25px;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0056b3;
    }
    .btn-secondary {
      background-color: #6c757d;
    }
    label {
      font-weight: 500;
    }
    img {
      max-width: 100%;
      border-radius: 10px;
    }
  </style>
</head>
<body>

<div class="edit-box">
  <h2>✏️ แก้ไขสินค้า</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">ชื่อสินค้า</label>
      <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">ราคา (บาท)</label>
      <input type="number" name="price" value="<?= $product['price'] ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">จำนวนสินค้า</label>
      <input type="number" name="quantity" value="<?= $product['quantity'] ?? 0 ?>" class="form-control" min="0">
    </div>

    <div class="mb-3">
      <label class="form-label">ไซส์สินค้า (เช่น S,M,L หรือ Free size)</label>
      <input type="text" name="size" value="<?= htmlspecialchars($product['size'] ?? '') ?>" class="form-control">
    </div>

    <div class="mb-3">
      <label class="form-label">รูปเดิม:</label><br>
      <?php if ($product['image']): ?>
        <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="รูปสินค้า">
      <?php else: ?>
        <p class="text-muted">ไม่มีรูปสินค้า</p>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label">เลือกรูปใหม่ (ถ้ามี)</label>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>

    <div class="d-grid gap-2">
      <button type="submit" class="btn btn-primary">💾 บันทึกข้อมูล</button>
      <a href="admin.php" class="btn btn-secondary">← กลับ</a>
    </div>
  </form>
</div>

</body>
</html>
