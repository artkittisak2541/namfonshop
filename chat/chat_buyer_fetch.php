<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
  exit('Unauthorized');
}

$buyer_id = $_SESSION['user']['id'];

// 🔍 ดึงแอดมินคนแรก
$admin_result = $conn->query("SELECT id FROM users WHERE role = 'admin' ORDER BY id ASC LIMIT 1");
$admin_id = ($admin_result && $admin_result->num_rows > 0)
            ? $admin_result->fetch_assoc()['id']
            : 0;

if ($admin_id === 0) {
  exit('❌ ไม่พบแอดมิน');
}

// ✅ อ่านแล้ว
$conn->query("UPDATE messages SET is_read = 1 WHERE sender_id = $admin_id AND receiver_id = $buyer_id AND is_read = 0");

// ✅ ดึงข้อความทั้งหมด
$stmt = $conn->prepare("
  SELECT * FROM messages 
  WHERE (sender_id = ? AND receiver_id = ?)
     OR (sender_id = ? AND receiver_id = ?)
  ORDER BY created_at ASC
");
$stmt->bind_param("iiii", $buyer_id, $admin_id, $admin_id, $buyer_id);
$stmt->execute();
$res = $stmt->get_result();

// ✅ แสดงผลลัพธ์
while ($msg = $res->fetch_assoc()) {
  $is_buyer = $msg['sender_id'] == $buyer_id;
  $align = $is_buyer ? "text-end" : "text-start";
  $bubble_color = $is_buyer ? "#2196F3" : "#f1f1f1";
  $text_color = $is_buyer ? "white" : "#333";
  $label = $is_buyer ? "คุณ (Buyer)" : "แอดมิน";

  echo "<div class='{$align}' style='margin-bottom: 12px;'>
          <div style='display:inline-block; padding:10px 15px; border-radius:15px; max-width:70%;
                      background-color:{$bubble_color}; color:{$text_color}; text-align:left; box-shadow: 0 2px 6px rgba(0,0,0,0.1);'>";

  echo "<div style='font-weight:bold; margin-bottom:4px;'>{$label}</div>";

  // ✅ แสดงข้อความ
  if (!empty($msg['message'])) {
    echo "<div style='white-space:pre-wrap;'>" . nl2br(htmlspecialchars($msg['message'])) . "</div>";
  }

  // ✅ แสดงรูปภาพ
  if (!empty($msg['image'])) {
    $imagePath = htmlspecialchars($msg['image']);
    $imageURL = (strpos($imagePath, 'uploads/') === 0)
                ? "/myshop/{$imagePath}"
                : "/myshop/uploads/{$imagePath}";

    echo "<div style='margin-top:8px;'>
            <a href='{$imageURL}' target='_blank'>
              <img src='{$imageURL}' alt='แนบรูปภาพ' 
                   style='max-width: 250px; border-radius: 10px; box-shadow:0 2px 4px rgba(0,0,0,0.1);'>
            </a>
          </div>";
  }

  echo "<div style='font-size: 0.75em; color: #ccc; margin-top: 5px; text-align:right;'>"
        . htmlspecialchars($msg['created_at']) .
       "</div>";

  echo "</div></div>";
}
?>
