<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}

if (empty($_SESSION['cart'])) {
  die("❌ ไม่มีสินค้าในตะกร้า");
}

$user = $_SESSION['user'];
$cart = $_SESSION['cart'];

// ✅ ตรวจสอบ stock
foreach ($cart as $item) {
  $stmt = $conn->prepare("SELECT quantity FROM products WHERE id = ?");
  $stmt->bind_param("i", $item['id']);
  $stmt->execute();
  $stock = $stmt->get_result()->fetch_assoc()['quantity'];
  $stmt->close();

  if ($item['qty'] > $stock) {
    die("❌ สินค้า '{$item['name']}' มีไม่พอในสต๊อก (เหลือ $stock ชิ้น)");
  }
}

// ✅ บันทึกคำสั่งซื้อ
$stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, created_at) VALUES (?, ?, NOW())");
$stmt->bind_param("is", $user['id'], $user['fullname']);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// ✅ เพิ่มรายการสินค้า + หัก stock
$stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, size, qty, price) VALUES (?, ?, ?, ?, ?)");
$stmt_update = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");

foreach ($cart as $item) {
  $size = isset($item['size']) ? $item['size'] : '-';
  $stmt_item->bind_param("iisii", $order_id, $item['id'], $size, $item['qty'], $item['price']);
  $stmt_item->execute();

  $stmt_update->bind_param("ii", $item['qty'], $item['id']);
  $stmt_update->execute();
}
$stmt_item->close();
$stmt_update->close();

// ✅ ล้างตะกร้า
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ยืนยันการสั่งซื้อ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f8ff;
      font-family: 'Sarabun', sans-serif;
    }
    .container {
      max-width: 600px;
      margin-top: 60px;
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    img.qr {
      max-width: 250px;
      border: 1px solid #ccc;
      padding: 10px;
    }
  </style>
</head>
<body>

<div class="container text-center">
  <h3 class="text-success">✅ คำสั่งซื้อของคุณถูกบันทึกเรียบร้อยแล้ว</h3>
  <p>เลขที่คำสั่งซื้อของคุณคือ <strong>#<?= $order_id ?></strong></p>

  <h4 class="mt-4">📷 สแกนจ่ายผ่านแอปธนาคาร</h4>
  <img src="images/2.jfif" class="qr mb-3" alt="QR Payment"><br>

  <p>โอนเงินไปที่บัญชี:<br>
    <strong>ธนาคารกสิกรไทย 0901291322</strong><br>
    ชื่อบัญชี: <strong>นางสาว สุภาพร นามคำ</strong></p>
    <div class="alert alert-info mt-3">
  📌 <strong>ชำระแล้วส่งสลิปมาที่ช่องทางแชทนะครับ</strong>
</div>

  <a href="shop.php" class="btn btn-primary mt-4">← กลับหน้าร้าน</a>
</div>

</body>
</html>
