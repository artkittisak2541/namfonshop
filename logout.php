<?php
session_start();
session_destroy(); // ลบ session ทั้งหมด
header("Location: index.php"); // กลับไปหน้าแรก
exit();
