<?php
session_start();

// ฟังก์ชันนับจำนวนรวมในตะกร้า
function getCartCount() {
  if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) return 0;

  $totalQty = 0;
  foreach ($_SESSION['cart'] as $item) {
    $totalQty += $item['qty'];
  }
  return $totalQty;
}
?>
