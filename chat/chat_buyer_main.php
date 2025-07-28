<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
  exit('กรุณาเข้าสู่ระบบในฐานะผู้ซื้อ');
}

$buyer_id = $_SESSION['user']['id'];

// 🔹 ดึง admin คนแรก
$admin_result = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
$admin_row = $admin_result->fetch_assoc();
$admin_id = $admin_row ? (int)$admin_row['id'] : 1;

// 🔹 ถ้า POST ส่งข้อความ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
  $message = trim($_POST['message']);
  if ($message !== '') {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $buyer_id, $admin_id, $message);
    $stmt->execute();
    exit;
  }
}

// 🔹 โหลดข้อความผ่าน AJAX
if (isset($_GET['fetch']) && $_GET['fetch'] == 1) {
  $stmt = $conn->prepare("SELECT * FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?) 
    ORDER BY created_at ASC");
  $stmt->bind_param("iiii", $buyer_id, $admin_id, $admin_id, $buyer_id);
  $stmt->execute();
  $res = $stmt->get_result();
  ?>
  <div class="chat-box">
    <?php while ($msg = $res->fetch_assoc()): ?>
      <?php
        $is_buyer = $msg['sender_id'] == $buyer_id;
        $message = htmlspecialchars($msg['message']);
        $time = htmlspecialchars($msg['created_at']);
        $align = $is_buyer ? 'chat-right' : 'chat-left';
        $type = $is_buyer ? 'buyer' : 'admin';
      ?>
      <div class="chat-message <?= $align ?>">
        <div class="bubble <?= $type ?>">
          <?= nl2br($message) ?>
          <div class="timestamp"><?= $time ?></div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
  <?php exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แชทกับแอดมิน</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .chat-box {
      max-height: 400px;
      overflow-y: auto;
      padding: 15px;
      background: #f7f7f7;
      border: 1px solid #ddd;
      border-radius: 10px;
    }
    .chat-message {
      margin-bottom: 10px;
      clear: both;
    }
    .chat-right { text-align: right; }
    .chat-left { text-align: left; }
    .bubble {
      display: inline-block;
      padding: 10px 14px;
      border-radius: 16px;
      max-width: 70%;
      word-wrap: break-word;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .buyer {
      background-color: #cce5ff;
      color: #004085;
    }
    .admin {
      background-color: #e2e3e5;
      color: #383d41;
    }
    .timestamp {
      font-size: 0.75rem;
      color: #888;
      margin-top: 5px;
    }
  </style>
</head>
<body class="p-3">

  <h3 class="mb-3">💬 สนทนากับแอดมิน</h3>

  <div id="chat-box-container"></div>

  <form id="chat-form" class="mt-3 d-flex">
    <input type="text" name="message" id="message-input" class="form-control me-2" placeholder="พิมพ์ข้อความ..." required autocomplete="off">
    <button type="submit" class="btn btn-primary" id="send-btn">ส่ง</button>
  </form>

  <script>
    function loadChat() {
      $('#chat-box-container').load('?fetch=1', function () {
        var box = document.querySelector(".chat-box");
        if (box) box.scrollTop = box.scrollHeight;
      });
    }

    loadChat();
    setInterval(loadChat, 2500); // โหลดทุก 2.5 วิ

    $('#chat-form').submit(function (e) {
      e.preventDefault();
      const $input = $('#message-input');
      const $btn = $('#send-btn');
      const message = $input.val().trim();

      if (message !== '') {
        $btn.prop('disabled', true); // ป้องกัน spam
        $.post('', { message: message }, function () {
          $input.val('');
          loadChat();
          $btn.prop('disabled', false);
        });
      }
    });
  </script>

</body>
</html>
