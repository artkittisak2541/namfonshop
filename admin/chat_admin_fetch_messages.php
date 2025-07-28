<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  exit('Unauthorized');
}

$admin_id = $_SESSION['user']['id'];
$chat_with = isset($_GET['with']) ? intval($_GET['with']) : 0;

$stmt = $conn->prepare("SELECT * FROM messages 
  WHERE (sender_id = ? AND receiver_id = ?) 
     OR (sender_id = ? AND receiver_id = ?) 
  ORDER BY created_at ASC");
$stmt->bind_param("iiii", $admin_id, $chat_with, $chat_with, $admin_id);
$stmt->execute();
$res = $stmt->get_result();

echo '<div class="chat-box">';
while ($msg = $res->fetch_assoc()) {
  if ($msg['sender_id'] == $admin_id) {
    echo "<div class='chat-message chat-right'>
            <div class='bubble admin'>" . htmlspecialchars($msg['message']) .
            "<div class='timestamp'>" . $msg['created_at'] . "</div></div>
          </div>";
  } else {
    echo "<div class='chat-message chat-left'>
            <div class='bubble buyer'>" . htmlspecialchars($msg['message']) .
            "<div class='timestamp'>" . $msg['created_at'] . "</div></div>
          </div>";
  }
}
echo '</div>';
?>
