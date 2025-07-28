<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>สินค้า - ร้านน้ำฝนแฟชั่น</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <style>
   body::after {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  background: linear-gradient(to bottom right, var(--bg-overlay), #f8bbd0aa, #f3e5f5aa);
  transition: background 2s ease-in-out;
}



    /* ✅ พื้นหลังแบบสไลด์โชว์ */
    body::before {
      content: "";
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      z-index: -2;
      animation: slideshow 24s infinite;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      filter: brightness(0.9);
  transition: background-image 2s ease-in-out; /* 👈 fade transition */
    }

    /* ✅ สี overlay พาสเทลนุ่ม */
    body::after {
      content: "";
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background: linear-gradient(to bottom right, #fce4ecaa, #f8bbd0aa, #f3e5f5aa);
    }

    @keyframes slideshow {
      0%   { background-image: url('images/bg1.jpg'); }
      33%  { background-image: url('images/bg2.jpg'); }
      66%  { background-image: url('images/bg3.jpg'); }
      100% { background-image: url('images/bg1.jpg'); }
    }

    /* ✅ กล่องเนื้อหาสวยแบบ glassmorphism */
    .glass-card {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border-radius: 20px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
      padding: 30px;
      color: #4a4a4a;
      max-width: 500px;
      margin: auto;
      margin-top: 10%;
      text-align: center;
      animation: fadeIn 2s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .btn-pink {
      background-color: #f48fb1;
      color: white;
      padding: 10px 25px;
      border: none;
      border-radius: 25px;
      font-size: 1rem;
      margin-top: 20px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-pink:hover {
      background-color: #ec407a;
      transform: scale(1.05);
    }
    header {
  position: sticky;
  top: 0;
  z-index: 1000;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 25px 50px;
  border-radius: 0 0 30px 30px;
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);

  /* 🔥 พื้นหลังเคลื่อนไหวแบบ gradient */
  background: linear-gradient(270deg, #ffe4f1, #f3e5f5, #d1c4e9, #ffe0f0);
  background-size: 800% 800%;
  animation: gradientFlow 15s ease infinite;
  transition: all 0.4s ease;
}

@keyframes gradientFlow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* 🌈 เพิ่มเอฟเฟกต์เมื่อ hover */
header:hover {
  box-shadow: 0 15px 50px rgba(255, 105, 180, 0.3);
  transform: translateY(-3px);
}

/* ✅ สำหรับปุ่มหรือลิงก์ด้านขวา */
.header-right {
  display: flex;
  align-items: center;
  gap: 20px;
}
header h1 {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  background: linear-gradient(90deg, #ec407a, #8e24aa);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}


    .animated-title {
      font-size: 32px;
      font-weight: bold;
      background: linear-gradient(90deg, #ff3cac, #784ba0, #2b86c5);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: shimmer 4s infinite linear;
      letter-spacing: 1px;
      transition: transform 0.3s ease;
      background-size: 200% auto;
    }

    .animated-title:hover {
      transform: scale(1.05);
    }

    @keyframes shimmer {
      0% { background-position: -100%; }
      100% { background-position: 200%; }
    }

    nav a {
      margin: 0 15px;
      text-decoration: none;
      color: #333;
      font-weight: 500;
      position: relative;
      transition: color 0.3s ease;
    }

    nav a::after {
      content: "";
      display: block;
      width: 0;
      height: 2px;
      background: #e52e71;
      transition: width 0.3s;
      position: absolute;
      bottom: -5px;
      left: 0;
    }

    nav a:hover,
    nav a.active {
      color: #e52e71;
    }

    nav a:hover::after,
    nav a.active::after {
      width: 100%;
    }

    .product-card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
      height: 100%;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }

    .product-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      cursor: pointer;
    }

    .product-card h4 {
      margin: 15px 0 5px;
      color: #0d6efd;
      font-size: 1.2rem;
    }

    .product-card p {
      color: #6c757d;
      font-size: 0.95rem;
      padding: 0 15px 15px;
    }
    .footer {
      color: black;
      padding: 20px 0;
      margin-top: 140px;
      background: transparent;
      text-align: center;
    }
    :root {
  --bg-overlay: #fce4ecaa; /* เริ่มต้น */
}
:root {
  --bg-overlay: #fce4ecaa;
}

#overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  background-color: var(--bg-overlay);
  transition: background-color 3s ease-in-out;
}

body::before {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  z-index: -2;
  animation: slideshow 24s infinite;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  filter: brightness(0.9);
  transition: background-image 2s ease-in-out;
}

@keyframes slideshow {
  0%   { background-image: url('images/bg1.jpg'); }
  33%  { background-image: url('images/bg2.jpg'); }
  66%  { background-image: url('images/bg3.jpg'); }
  100% { background-image: url('images/bg1.jpg'); }
}

  </style>
</head>
<body>

<header>
  <div style="display: flex; align-items: center;">
    <h1 class="animated-title">ร้านน้ำฝนแฟชั่น</h1>
  </div>
  <nav>
    <a href="index.php">หน้าแรก</a>
    <a href="shop.php" class="active">สินค้า</a>
    <a href="about.php">เกี่ยวกับ</a>
    <a href="contact.php">ติดต่อ</a>
  </nav>
</header>

<div class="container py-5">
  <h2 class="mb-4 text-center text-primary">🛍️ สินค้าแนะนำ</h2>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="product-card">
        <img src="images/16.jpg" alt="สินค้า 1" data-bs-toggle="modal" data-bs-target="#imgModal" onclick="showImage('images/show1.jpg')">
        <h4>รองเท้าแฟชั่นผู้หญิง</h4>
        <p>ราคา 150 บาท</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="product-card">
        <img src="images/13.jpg" alt="สินค้า 2" data-bs-toggle="modal" data-bs-target="#imgModal" onclick="showImage('images/show2.jpg')">
        <h4>เสื้อแฟชั่นผู้หญิง</h4>
        <p>ราคา 150 บาท</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="product-card">
        <img src="images/14.jpg" alt="สินค้า 3" data-bs-toggle="modal" data-bs-target="#imgModal" onclick="showImage('images/show3.jpg')">
        <h4>กระโปงผู้หญิง</h4>
        <p>ราคา 150 บาท</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="imgModal" tabindex="-1" aria-labelledby="imgModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body p-0">
        <img id="modalImage" src="" class="img-fluid rounded" alt="แสดงรูปใหญ่">
      </div>
    </div>
  </div>
</div>

<style>
  /* เพิ่มเอฟเฟกต์ระยิบระยับให้กับพื้นหลังแบบ overlay */
  .sparkle-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    background-image:
      radial-gradient(circle, rgba(255,255,255,0.4) 2px, transparent 3px),
      radial-gradient(circle, rgba(255,255,255,0.2) 1px, transparent 2px);
    background-size: 100px 100px, 150px 150px;
    animation: sparkleMove 30s linear infinite;
    z-index: 1;
  }

  @keyframes sparkleMove {
    0% { background-position: 0 0, 0 0; }
    100% { background-position: 1000px 1000px, 800px 800px; }
  }

  /* ปรับให้แสงขอบกล่อง modal มี Glow */
  .login-modal-content {
    box-shadow: 0 0 25px rgba(255, 105, 180, 0.4), 0 0 40px rgba(255, 182, 193, 0.3);
    border: 2px solid rgba(255, 192, 203, 0.6);
  }

  /* เพิ่มขอบ glowing ให้ปุ่ม */
  .btn-primary {
    box-shadow: 0 0 12px rgba(0, 153, 255, 0.5);
    border: none;
  }
  .btn-outline-secondary {
    border: 1px solid #aaa;
    background-color: rgba(255, 255, 255, 0.5);
  }

  .btn:hover {
    transform: scale(1.03);
    box-shadow: 0 0 20px rgba(255, 20, 147, 0.6);
  }

  /* แอนิเมชันเรืองแสงโลโก้ */
  .logo {
    animation: glow 2s ease-in-out infinite alternate;
  }

  @keyframes glow {
    from {
      filter: drop-shadow(0 0 3px #ffb6c1);
    }
    to {
      filter: drop-shadow(0 0 8px #ff69b4);
    }
  }

  /* ปรับพื้นหลังแบบ glassmorphism ให้กล่องรูป */
  .floating-image {
    position: absolute;
    bottom: 20px;
    right: 20px;
    width: 120px;
    height: auto;
    border-radius: 16px;
    backdrop-filter: blur(4px);
    background: rgba(255, 255, 255, 0.2);
    padding: 5px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    z-index: 2;
  }
  .snowflake {
    position: fixed;
    top: -10px;
    color: #fff;
    user-select: none;
    pointer-events: none;
    animation: fall linear infinite;
    z-index: 0; /* 👈 อยู่ใต้ modal */
  }

  @keyframes fall {
    0% {
      transform: translateY(0) rotate(0deg);
      opacity: 1;
    }
    100% {
      transform: translateY(100vh) rotate(360deg);
      opacity: 0;
    }
  }
  #overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  background-color: var(--bg-overlay);
  transition: background-color 3s ease-in-out;
}

</style>

<script>
  // ❄️ สร้าง snowflake แบบสุ่ม 30 ตัว
  for (let i = 0; i < 30; i++) {
    const snow = document.createElement('div');
    snow.classList.add('snowflake');
    snow.innerHTML = '❄';
    snow.style.left = Math.random() * 100 + 'vw';
    snow.style.fontSize = Math.random() * 10 + 10 + 'px';
    snow.style.animationDuration = Math.random() * 10 + 10 + 's';
    document.body.appendChild(snow);
  }
</style>
<div id="overlay"></div>
<div class="sparkle-overlay"></div>

<!-- ใส่หลัง <body> เพื่อซ้อนแสง overlay -->
<div class="sparkle-overlay"></div>

<script>
  function showImage(src) {
    document.getElementById('modalImage').src = src;
  }
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<footer class="footer">
    <p>© 2025 ร้านน้ำฝนแฟชั่น. สงวนลิขสิทธิ์.</p>
  </footer>
  <script>
  function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.getElementById("toggleIcon");
    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    } else {
      input.type = "password";
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    }
  }
</script>
<?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error): ?>
<script>
  const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
  loginModal.show();
</script>
<?php endif; ?>
<!-- ❄️ Snowflakes -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    for (let i = 0; i < 30; i++) {
      let snowflake = document.createElement('div');
      snowflake.className = 'snowflake';
      snowflake.textContent = '❄';

      // สุ่มตำแหน่งและความเร็ว
      snowflake.style.left = Math.random() * 100 + 'vw';
      snowflake.style.animationDuration = (Math.random() * 10 + 5) + 's';
      snowflake.style.fontSize = (Math.random() * 10 + 10) + 'px';
      snowflake.style.opacity = Math.random();

      document.body.appendChild(snowflake);
    }
  });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const images = document.querySelectorAll('.product-img');
  images.forEach((img, index) => {
    setTimeout(() => {
      img.classList.add('animated-in');
    }, 400 * index); // หน่วง 0.4s ต่อภาพ
  });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const images = document.querySelectorAll('.product-img');
  images.forEach((img, index) => {
    setTimeout(() => {
      img.classList.add('animated-in', 'swing-image');
    }, 400 * index);
  });
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('productModal');
    const modalImage = document.getElementById('modalImage');
    document.querySelectorAll('.product-img').forEach(img => {
      img.addEventListener('click', () => {
        const src = img.getAttribute('data-img');
        modalImage.src = src;
      });
    });
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    function getRandomPastelColor() {
      const hue = Math.floor(Math.random() * 360);
      return `hsl(${hue}, 100%, 85%)`; // สีพาสเทลอ่อน
    }

    function updateBackgroundColor() {
      const color = getRandomPastelColor();
      document.body.style.setProperty('--bg-overlay', color + 'aa');
      document.body.style.background = color;
    }

    // เปลี่ยนสีทันทีตอนโหลด
    updateBackgroundColor();

    // เปลี่ยนสีทุก 10 วินาที
    setInterval(updateBackgroundColor, 5000);
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("overlay");

    function getRandomPastelColor() {
      const hue = Math.floor(Math.random() * 360);
      return `hsl(${hue}, 100%, 90%)`; // สีพาสเทลสว่างนุ่ม
    }

    function updateOverlayColor() {
      const color = getRandomPastelColor();
      overlay.style.backgroundColor = color;
    }

    // เปลี่ยนสีครั้งแรกทันที
    updateOverlayColor();

    // เปลี่ยนทุก 10 วินาทีแบบสมูท
    setInterval(updateOverlayColor, 10000);
  });
</script>
<!-- ❄️ เอฟเฟกต์หิมะ -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    for (let i = 0; i < 30; i++) {
      const snow = document.createElement('div');
      snow.classList.add('snowflake');
      snow.innerHTML = '❄';
      snow.style.left = Math.random() * 100 + 'vw';
      snow.style.fontSize = Math.random() * 10 + 10 + 'px';
      snow.style.animationDuration = Math.random() * 10 + 10 + 's';
      document.body.appendChild(snow);
    }
  });
</script>

<!-- 🌈 เปลี่ยนสีพื้นหลังอย่างสมูท -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("overlay");

    function getRandomPastelColor() {
      const hue = Math.floor(Math.random() * 360);
      return `hsl(${hue}, 100%, 90%)`;
    }

    function updateOverlayColor() {
      const color = getRandomPastelColor();
      overlay.style.backgroundColor = color;
    }

    updateOverlayColor(); // แรกโหลด
    setInterval(updateOverlayColor, 10000); // ทุก 10 วิ
  });
</script>

</body>
</html>
