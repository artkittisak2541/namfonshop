<?php
require 'db.php';

$sql = "SELECT COUNT(*) AS count FROM orders WHERE status = 'รอดำเนินการ'";
$result = $conn->query($sql);
$count = $result->fetch_assoc()['count'];

echo $count;
