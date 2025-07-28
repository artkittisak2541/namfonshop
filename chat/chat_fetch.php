<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user'])) {
  http_response_code(401);
  exit("ไม่ได้เข้าสู่ระบบ");
}

$my_id = $_SESSION['user']['id'];
$my_role = $_SESSION['user']['role'];

$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0;
if (!$receiver_id) exit;

// ดึงข้อความทั้งหมด
$stmt = $conn->prepare("SELECT * FROM messages 
  WHERE (sender_id = ? AND receiver_id = ?) 
     OR (sender_id = ? AND receiver_id = ?) 
  ORDER BY created_at ASC");
$stmt->bind_param("iiii", $my_id, $receiver_id, $receiver_id, $my_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  $isMine = $row['sender_id'] == $my_id;
  $isBuyer = $my_role === 'buyer';

  // จัดตำแหน่งซ้าย/ขวา
  $alignClass = ($isBuyer && $isMine) || (!$isBuyer && !$isMine)
    ? 'justify-content-end text-end'
    : 'justify-content-start text-start';
  $isRight = strpos($alignClass, 'justify-content-end') !== false;

  $bgColor = $isRight ? '#2196F3' : '#f1f1f1';
  $textColor = $isRight ? 'white' : '#333';
  $borderRadius = $isRight ? '20px 20px 0 20px' : '20px 20px 20px 0';
  $senderLabel = $isRight ? 'คุณ' : 'คู่สนทนา';

  echo "<div class='d-flex mb-2 {$alignClass}'>";
  echo "<div style='display: inline-block; background: {$bgColor}; color: {$textColor}; 
                border-radius: {$borderRadius}; padding: 10px 14px; max-width: 90%;'>";

  echo "<div style='font-weight: bold; margin-bottom: 5px;'>{$senderLabel}</div>";

  // ✅ ข้อความ
  if (!empty($row['message'])) {
    echo "<div style='white-space: pre-wrap;'>" . nl2br(htmlspecialchars($row['message'])) . "</div>";
  }

  // ✅ ไฟล์แนบ
  if (!empty($row['image'])) {
    $fileName = basename($row['image']);
    $filePath = '../uploads/' . $fileName;

    if (file_exists($filePath)) {
      $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
      $mime = mime_content_type($filePath);
      echo "<div style='margin-top: 8px;'>";

      // ✅ ภาพ (รวม .jfif)
      if (strpos($mime, 'image/') === 0 || $ext === 'jfif') {
        echo "<img src='" . htmlspecialchars($filePath) . "' 
                 style='max-width: 100%; max-height: 200px; border-radius: 10px; 
                        box-shadow: 0 2px 6px rgba(0,0,0,0.1);' />";
      
      // ✅ วิดีโอ
      } elseif (strpos($mime, 'video/') === 0) {
        echo "<video controls style='max-width: 100%; border-radius: 10px;'>
                <source src='" . htmlspecialchars($filePath) . "' type='" . htmlspecialchars($mime) . "'>
                เบราว์เซอร์ของคุณไม่รองรับวิดีโอ
              </video>";
      
      // ✅ ไฟล์อื่น
      } else {
        echo "<a href='" . htmlspecialchars($filePath) . "' download 
                 style='color: {$textColor}; text-decoration: underline;'>
                 ดาวน์โหลดไฟล์: " . htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8') . "
              </a>";
      }

      echo "</div>";
    } else {
      echo "<div style='margin-top: 8px; color: red;'>⚠️ ไม่พบไฟล์แนบ</div>";
    }
  }

  // ✅ เวลา
  echo "<div style='font-size: 0.75em; color: #ddd; margin-top: 5px; text-align: right;'>"
     . htmlspecialchars($row['created_at']) .
     "</div>";

  echo "</div></div>";
}
?>
