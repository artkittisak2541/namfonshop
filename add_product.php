<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $quantity = $_POST['quantity'];

  $imageName = "";
  if (!empty($_FILES['image']['name'])) {
    $targetDir = "images/";
    $imageName = uniqid() . "_" . basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $imageName;
    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
  }

  $stmt = $conn->prepare("INSERT INTO products (name, price, quantity, image) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("siis", $name, $price, $quantity, $imageName);
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
  <title>เพิ่มสินค้า</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0eafc, #cfdef3);
      font-family: 'Segoe UI', sans-serif;
    }

    .form-container {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      max-width: 600px;
      margin: 50px auto;
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    .btn-primary {
      width: 100%;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h1>➕ เพิ่มสินค้าใหม่</h1>

  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">ชื่อสินค้า:</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">ราคา (บาท):</label>
      <input type="number" name="price" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">จำนวนสินค้า:</label>
      <input type="number" name="quantity" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
      <label class="form-label">เลือกรูปสินค้า:</label>
      <input type="file" name="image" class="form-control" accept="image/*" required>
    </div>

    <button type="submit" class="btn btn-primary">💾 เพิ่มสินค้า</button>
    <a href="admin.php" class="btn btn-outline-secondary mt-3 w-100">← กลับไปหลังบ้าน</a>
  </form>
</div>

</body>
</html>
