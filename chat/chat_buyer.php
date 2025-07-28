<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
  header("Location: login.php");
  exit();
}
$buyer_id = $_SESSION['user']['id'];
$admin_id = 1;
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แชทกับแอดมิน</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    #chat-box {
      max-height: 400px;
      overflow-y: auto;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 10px;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>

<h3>💬 แชทกับแอดมิน</h3>

<div id="chat-box">
  <!-- ข้อความจะถูกโหลดมาแสดงตรงนี้ -->
</div>

<form id="chat-form" enctype="multipart/form-data">
  <input type="text" name="message" placeholder="พิมพ์ข้อความ..." required>
  <input type="file" name="images[]" multiple accept="image/*">
  <button type="submit">ส่ง</button>
</form>

<script>
function loadChat() {
  $.ajax({
    url: 'chat_buyer_fetch.php',
    success: function(data) {
      $('#chat-box').html(data);
      $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight); // auto scroll
    }
  });
}

$(document).ready(function() {
  loadChat(); // โหลดตอนแรก
  setInterval(loadChat, 3000); // โหลดซ้ำทุก 3 วิ

  $('#chat-form').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    $.ajax({
      url: 'chat_buyer_send.php',
      method: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function() {
        $('#chat-form')[0].reset(); // ล้างช่องกรอก
        loadChat(); // โหลดใหม่ทันที
      }
    });
  });
});
</script>
<script>
function loadChat() {
  fetch("chat_buyer_fetch.php")
    .then(res => res.text())
    .then(html => {
      const box = document.querySelector(".chat-box");
      box.innerHTML = html;
      box.scrollTop = box.scrollHeight;
    });
}

loadChat(); // โหลดครั้งแรก
setInterval(loadChat, 2000); // โหลดซ้ำทุก 2 วินาที (หรือเร็วสุด 1 วินาที)

// ถ้าใช้ฟอร์มส่งข้อความแบบ AJAX:
document.getElementById("sendForm").addEventListener("submit", function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  fetch("chat_buyer_send.php", {
    method: "POST",
    body: formData
  }).then(() => {
    document.getElementById("messageInput").value = "";
    document.getElementById("imageInput").value = "";
    loadChat();
  });
});
</script>

</body>
</html>
