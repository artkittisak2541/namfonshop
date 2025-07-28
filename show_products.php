<?php
require 'db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

echo "<h1>รายการสินค้า</h1>";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<p>{$row['name']} - {$row['price']} บาท</p>";
    }
} else {
    echo "ไม่มีสินค้าในระบบ";
}

$conn->close();
?>
