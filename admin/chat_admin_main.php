<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  exit('Unauthorized');
}

$conn->query("UPDATE users SET last_active = NOW() WHERE id = " . $_SESSION['user']['id']);

$chat_with = isset($_GET['with']) ? intval($_GET['with']) : 0;

if ($chat_with > 0) {
  $conn->query("UPDATE messages SET is_read = 1 WHERE sender_id = $chat_with AND receiver_id = " . $_SESSION['user']['id']);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Admin Chat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .chat-box { max-height: 400px; overflow-y: auto; padding: 15px; background: #f8f9fa; border: 1px solid #ccc; border-radius: 10px; }
    .chat-message { margin-bottom: 10px; }
    .chat-left { text-align: left; }
    .chat-right { text-align: right; }
    .bubble { display: inline-block; padding: 10px 14px; border-radius: 16px; max-width: 70%; word-wrap: break-word; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .buyer { background-color: #cce5ff; color: #004085; }
    .admin { background-color: #e2e3e5; color: #383d41; }
    .timestamp { font-size: 0.75rem; color: #888; margin-top: 5px; }
  </style>
</head>
<body class="p-3">
  <h3>💼 แชทกับผู้ซื้อ</h3>
  <div class="row">
    <div class="col-md-3">
      <h5>รายชื่อผู้ซื้อ</h5>
      <ul class="list-group">
        <?php
        $stmt = $conn->prepare("
          SELECT u.id, u.fullname,
            (SELECT COUNT(*) FROM messages m WHERE m.sender_id = u.id AND m.receiver_id = ? AND m.is_read = 0) AS unread
          FROM users u WHERE u.role = 'buyer'
        ");
        $stmt->bind_param("i", $_SESSION['user']['id']);
        $stmt->execute();
        $users = $stmt->get_result();
        while ($u = $users->fetch_assoc()):
        ?>
        <a href="?with=<?= $u['id'] ?>" class="list-group-item list-group-item-action <?= ($chat_with == $u['id']) ? 'active' : '' ?>">
          <?= htmlspecialchars($u['fullname']) ?>
          <?php if ($u['unread'] > 0): ?>
            <span class="badge bg-danger float-end">🔔 <?= $u['unread'] ?></span>
          <?php endif; ?>
        </a>
        <?php endwhile; ?>
      </ul>
    </div>

    <div class="col-md-9">
      <?php if ($chat_with): ?>
        <div id="chat-box-container"></div>

        <form id="chat-form" class="mt-3 d-flex">
          <input type="text" id="message-input" class="form-control me-2" placeholder="พิมพ์ข้อความ..." required>
          <button type="submit" class="btn btn-primary">ส่ง</button>
        </form>
      <?php else: ?>
        <p>⬅ เลือกผู้ซื้อจากเมนูด้านซ้าย</p>
      <?php endif; ?>
    </div>
  </div>

  <script>
    <?php if ($chat_with): ?>
    function loadChat() {
      $('#chat-box-container').load('chat_admin_fetch_messages.php?with=<?= $chat_with ?>');
    }

    setInterval(loadChat, 2000);
    loadChat();

    $('#chat-form').submit(function (e) {
      e.preventDefault();
      var msg = $('#message-input').val();
      if (msg.trim() !== '') {
        $.post('chat_admin_send.php', { receiver_id: <?= $chat_with ?>, message: msg }, function () {
          $('#message-input').val('');
          loadChat();
        });
      }
    });
    <?php endif; ?>
  </script>
</body>
</html>
