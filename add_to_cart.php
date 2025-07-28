<?php
// ------------------------
// add_to_cart.php
// ------------------------
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $qty = max(1, (int)$_POST['qty']);
    $size = $_POST['size'] ?? 'M';

    // ดึงข้อมูลสินค้า
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $key = $id . '_' . $size; // ใช้ key ผสม ID+ไซส์

        if (!isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'qty' => $qty,
                'size' => $size
            ];
        } else {
            $_SESSION['cart'][$key]['qty'] += $qty;
        }
    }
}

header("Location: cart.php");
exit();