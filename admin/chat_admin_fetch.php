<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  exit('Unauthorized');
}

$admin_id = $_SESSION['user']['id'];
$chat_with = isset($_GET['with']) ? intval($_GET['with']) : 0;

if ($chat_with <= 0) {
  exit('ไม่พบผู้ใช้');
}

// ✅ อัปเดตว่าอ่านข้อความแล้ว
$stmt = $conn->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ? AND is_read = 0");
$stmt->bind_param("ii", $chat_with, $admin_id);
$stmt->execute();

// ✅ ดึงข้อความทั้งหมด
$stmt = $conn->prepare("
  SELECT * FROM messages 
  WHERE (sender_id = ? AND receiver_id = ?)
     OR (sender_id = ? AND receiver_id = ?)
  ORDER BY created_at ASC
");
$stmt->bind_param("iiii", $admin_id, $chat_with, $chat_with, $admin_id);
$stmt->execute();
$res = $stmt->get_result();

// ✅ แสดงข้อความ
while ($msg = $res->fetch_assoc()) {
  $is_admin_sender = $msg['sender_id'] == $admin_id;

  $align = $is_admin_sender ? "text-end" : "text-start";
  $bubbleColor = $is_admin_sender ? "#2196F3" : "#f1f1f1";
  $textColor = $is_admin_sender ? "white" : "#333";
  $label = $is_admin_sender ? "คุณ (Admin)" : "ลูกค้า";

  echo "<div class='{$align}' style='margin-bottom: 12px;'>";
  echo "<div style='display:inline-block; padding:10px 15px; border-radius:15px; max-width:70%;
                background-color:{$bubbleColor}; color:{$textColor}; box-shadow:0 2px 6px rgba(0,0,0,0.1); text-align:left;'>";

  echo "<div style='font-weight: bold; margin-bottom: 4px;'>{$label}</div>";

  // ✅ ข้อความ
  if (!empty($msg['message'])) {
    echo "<div style='white-space:pre-wrap;'>" . nl2br(htmlspecialchars($msg['message'])) . "</div>";
  }

  // ✅ ไฟล์แนบ
  if (!empty($msg['image'])) {
    $fileName = basename($msg['image']);
    $filePath = "../uploads/" . $fileName;
    $publicPath = "/myshop/uploads/" . $fileName;

    if (file_exists($filePath)) {
      $mime = mime_content_type($filePath);
      echo "<div style='margin-top:8px;'>";

      if (strpos($mime, 'image/') === 0 || in_array(pathinfo($fileName, PATHINFO_EXTENSION), ['jfif'])) {
        // ✅ รูปภาพ
        echo "<a href='{$publicPath}' target='_blank'>
                <img src='{$publicPath}' alt='แนบรูปภาพ'
                     style='max-width: 250px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'>
              </a>";
      } elseif (strpos($mime, 'video/') === 0) {
        // ✅ วิดีโอ
        echo "<video controls style='max-width: 100%; border-radius: 10px;'>
                <source src='{$publicPath}' type='{$mime}'>
                ไม่สามารถเล่นวิดีโอนี้ได้
              </video>";
      } else {
        // ✅ ไฟล์อื่นๆ
        echo "<a href='{$publicPath}' download style='color: {$textColor}; text-decoration: underline;'>
                ดาวน์โหลดไฟล์: {$fileName}
              </a>";
      }

      echo "</div>";
    }
  }

  // ✅ เวลา
  echo "<div style='font-size: 0.75em; color: #ccc; margin-top: 5px; text-align: right;'>"
     . htmlspecialchars($msg['created_at']) .
     "</div>";

  echo "</div></div>";
}
?>
