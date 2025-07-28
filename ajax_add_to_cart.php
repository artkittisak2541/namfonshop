<?php
session_start();
require 'db.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) exit;
$id = $_POST['id'];
$qty = $_POST['qty'] ?? 1;

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
echo "OK";
