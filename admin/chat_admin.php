<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: login_admin.php");
  exit();
}

$admin_id = $_SESSION['user']['id'];
$chat_with = isset($_GET['with']) ? intval($_GET['with']) : null;

if ($chat_with) {
  $check = $conn->prepare("SELECT id FROM users WHERE id = ? AND role = 'buyer'");
  $check->bind_param("i", $chat_with);
  $check->execute();
  $result_check = $check->get_result();
  if ($result_check->num_rows === 0) {
    $chat_with = null;
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>📨 แอดมินแชทกับลูกค้า</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Kanit', sans-serif;
      background: linear-gradient(to right, #fce4ec, #f3e5f5);
      min-height: 100vh;
      padding: 15px;
    }
    .chat-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      padding: 15px;
    }
    #chat-box {
      height: 400px;
      overflow-y: auto;
      background: #ffffff;
      border-radius: 12px;
      padding: 10px;
      box-shadow: inset 0 2px 6px rgba(0,0,0,0.05);
      margin-bottom: 15px;
      font-size: 0.95rem;
    }
    .msg-left, .msg-right {
      max-width: 85%;
      padding: 10px 15px;
      border-radius: 16px;
      margin: 6px 0;
      word-break: break-word;
    }
    .msg-left {
      background: #e0e0e0;
      color: #333;
      align-self: flex-start;
    }
    .msg-right {
      background: #2196F3;
      color: #fff;
      align-self: flex-end;
      margin-left: auto;
    }
    .user-list a {
      display: block;
      padding: 8px 12px;
      margin-bottom: 5px;
      border-radius: 25px;
      text-decoration: none;
      color: #333;
      background: rgba(255,255,255,0.85);
      transition: 0.2s;
    }
    .user-list a:hover {
      background: #f3e5f5;
    }
    .user-active {
      font-weight: bold;
      background: #ce93d8 !important;
      color: white !important;
    }
    .form-control, .btn {
      border-radius: 10px;
    }
    .btn-primary {
      background-color: #2196F3;
      border: none;
    }
    .btn-primary:hover {
      background-color: #1976d2;
    }
    @media (max-width: 768px) {
      #chat-box {
        height: 300px;
      }
      .input-group {
        flex-wrap: wrap;
      }
      .input-group .form-control {
        margin-bottom: 5px;
      }
    }
  </style>
</head>
<body>
  <div class="container chat-container">
    <h4 class="mb-4 text-center">📨 ระบบแชทลูกค้า (Admin)</h4>
    <div class="row">
      <!-- ✅ Sidebar รายชื่อลูกค้า -->
      <div class="col-md-4 border-end mb-3 mb-md-0">
        <h6>👤 ลูกค้า</h6>
        <input type="text" id="searchInput" class="form-control form-control-sm rounded-pill px-3 shadow-sm mb-2" placeholder="🔍 ค้นหาชื่อลูกค้า...">
        <div class="user-list" id="userList">
          <?php
          $result = $conn->query("SELECT id, username FROM users WHERE role = 'buyer'");
          while ($u = $result->fetch_assoc()) {
            $uid = $u['id'];
            $stmt = $conn->prepare("SELECT COUNT(*) AS unread FROM messages WHERE sender_id = ? AND receiver_id = ? AND is_read = 0");
            $stmt->bind_param("ii", $uid, $admin_id);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            $unread = $res['unread'];

            $active = ($chat_with == $uid) ? "user-active" : "";
            $badge = $unread > 0 ? " <span class='badge bg-danger'>ใหม่</span>" : "";
            echo "<a href='?with={$uid}' class='d-block px-3 py-2 mb-1 rounded-pill text-decoration-none {$active}'><i class='bi bi-person-circle me-1'></i>" . htmlspecialchars($u['username']) . $badge . "</a>";
          }
          ?>
        </div>
      </div>

      <!-- ✅ กล่องแชท -->
      <div class="col-md-8">
        <?php if ($chat_with): ?>
          <div id="chat-box">⏳ กำลังโหลดข้อความ...</div>

          <form method="post" action="chat_admin_send.php" enctype="multipart/form-data" class="mt-2" id="sendForm">
            <input type="hidden" name="receiver_id" value="<?= $chat_with ?>">
            <div class="input-group shadow-sm">
              <input type="text" name="message" id="messageInput" class="form-control" placeholder="พิมพ์ข้อความของคุณ...">
              <input type="file" name="images[]" accept="image/*" multiple class="form-control">
              <button class="btn btn-primary px-4">📤 ส่ง</button>
            </div>
          </form>

          <script>
          function loadChat() {
            const box = document.getElementById("chat-box");
            fetch("chat_admin_fetch.php?with=<?= $chat_with ?>")
              .then(res => res.text())
              .then(html => {
                box.innerHTML = html;
                box.scrollTop = box.scrollHeight;
              });
            fetch("mark_as_read.php?with=<?= $chat_with ?>");
          }
          loadChat();
          setInterval(loadChat, 1000);

          document.getElementById("sendForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = new FormData(this);
            fetch("chat_admin_send.php", {
              method: "POST",
              body: form
            }).then(() => {
              document.getElementById("messageInput").value = "";
              this.querySelector("input[type='file']").value = "";
              loadChat();
            });
          });
          </script>
        <?php else: ?>
          <p class="text-muted">🟡 เลือกลูกค้าเพื่อเริ่มแชท</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
<script>
document.getElementById('searchInput').addEventListener('input', function () {
  const search = this.value.toLowerCase();
  const links = document.querySelectorAll('#userList a');
  links.forEach(link => {
    const name = link.textContent.toLowerCase();
    link.style.display = name.includes(search) ? 'block' : 'none';
  });
});
</script>
</html>
