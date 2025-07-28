<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
  header('Location: ../shop.php');
  exit();
}

// หาคู่สนทนา: admin คนแรก
$adminResult = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
$adminRow = $adminResult->fetch_assoc();
$admin_id = $adminRow['id'];
$my_id = $_SESSION['user']['id'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>💬 แชทกับแอดมิน</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Kanit', sans-serif;
      margin: 0;
      background: #f0f2f5;
    }
    .chat-container {
      max-width: 100%;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .chat-header {
      padding: 12px 16px;
      background: white;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      font-weight: bold;
      font-size: 1.1rem;
    }
    #chat-box {
      flex: 1;
      overflow-y: auto;
      padding: 15px;
      background: #e5ddd5;
      display: flex;
      flex-direction: column;
    }
    .msg-left, .msg-right {
      padding: 10px 15px;
      border-radius: 20px;
      margin: 5px 0;
      max-width: 75%;
      word-wrap: break-word;
      display: inline-block;
    }
    .msg-left {
      background: #ffffff;
      align-self: flex-start;
    }
    .msg-right {
      background: #0084ff;
      color: white;
      align-self: flex-end;
    }
    .chat-footer {
      padding: 10px 15px;
      background: white;
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      align-items: center;
      box-shadow: 0 -1px 2px rgba(0,0,0,0.05);
    }
    .chat-footer input[type="text"],
    .chat-footer input[type="file"] {
      flex: 1;
      border-radius: 20px;
    }
    .btn-send {
      background: #0084ff;
      border: none;
      color: white;
      border-radius: 20px;
      padding: 8px 20px;
    }

    @media (max-width: 768px) {
      .chat-footer input[type="text"] {
        min-width: 100%;
      }
    }
  </style>
</head>
<body>

<div class="chat-container">
  <div class="chat-header">👤 แชทกับแอดมิน</div>

  <div id="chat-box">⏳ กำลังโหลดข้อความ...</div>

  <form method="post" action="chat_buyer_send.php" enctype="multipart/form-data" id="sendForm" class="chat-footer">
    <input type="hidden" name="receiver_id" value="<?= $admin_id ?>">
    <input type="text" name="message" id="messageInput" class="form-control" placeholder="พิมพ์ข้อความ...">
    <input type="file" name="images[]" accept="image/*" class="form-control">
    <button class="btn btn-send" type="submit">ส่ง</button>
  </form>
</div>

<script>
function loadChat() {
  fetch("chat_buyer_fetch.php?with=<?= $admin_id ?>")
    .then(res => res.text())
    .then(html => {
      const box = document.getElementById("chat-box");
      const shouldScroll = box.scrollTop + box.clientHeight >= box.scrollHeight - 100;
      box.innerHTML = html;
      if (shouldScroll) {
        box.scrollTop = box.scrollHeight;
      }
    });
}
loadChat();
setInterval(loadChat, 1000);

document.getElementById("sendForm").addEventListener("submit", function(e) {
  e.preventDefault();
  const form = new FormData(this);
  fetch("chat_buyer_send.php", {
    method: "POST",
    body: form
  }).then(() => {
    document.getElementById("messageInput").value = "";
    this.querySelector("input[type='file']").value = "";
    loadChat();
  });
});
</script>

</body>
</html>
