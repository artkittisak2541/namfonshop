<?php
// ------------------------
// update_cart.php
// ------------------------
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['qty'] as $key => $qty) {
        $qty = max(1, (int)$qty);
        $size = $_POST['size'][$key] ?? 'M';

        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['qty'] = $qty;
            $_SESSION['cart'][$key]['size'] = $size;
        }
    }
}

header("Location: cart.php");
exit();
