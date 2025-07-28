<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>ติดต่อเรา - น้ำฝนแฟชั่น</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Kanit', sans-serif;
      overflow-x: hidden;
      overflow-y: auto;
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
    }

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
      background: linear-gradient(270deg, #ffe4f1, #f3e5f5, #d1c4e9, #ffe0f0);
      background-size: 800% 800%;
      animation: gradientFlow 15s ease infinite;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    @keyframes gradientFlow {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
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
      font-size: 16px;
      position: relative;
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

    .contact-section {
      padding: 80px 20px;
      min-height: 100vh;
      box-sizing: border-box;
    }

    .contact-card {
      background: #fff;
      padding: 30px;
      border-radius: 20px;
      max-width: 600px;
      margin: 0 auto 50px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .contact-card p {
      margin: 10px 0;
      font-size: 18px;
    }

    .contact-card a {
      color: #0d6efd;
      word-break: break-word;
      text-decoration: none;
    }

    .map-responsive {
      position: relative;
      padding-bottom: 50%;
      height: 0;
      overflow: hidden;
      border-radius: 16px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      margin: 0 auto;
    }

    .map-responsive iframe {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      border: 0;
    }

    .footer {
      color: black;
      padding: 20px 0;
      text-align: center;
    }

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

    @media (max-width: 576px) {
      .map-responsive { height: 250px; }
    }

    @media (min-width: 577px) and (max-width: 992px) {
      .map-responsive { height: 320px; }
    }

    @media (min-width: 993px) {
      .map-responsive { height: 400px; }
    }
  </style>
</head>
<body>

  <div class="sparkle-overlay"></div>

  <header>
    <h1 class="animated-title">ร้านน้ำฝนแฟชั่น</h1>
    <nav>
      <a href="index.php">หน้าแรก</a>
      <a href="show.php">สินค้า</a>
      <a href="about.php">เกี่ยวกับ</a>
      <a href="contact.php" class="active">ติดต่อ</a>
    </nav>
  </header>

  <section class="contact-section">
    <div class="container">
      <h2 class="text-center text-primary mb-5">📞 ติดต่อเรา</h2>

      <div class="contact-card">
        <p><strong><i class="bi bi-telephone-fill"></i> เบอร์โทร:</strong> 
          <a href="tel:0958390637">095-839-0637</a>, 
          <a href="tel:0967380750">096-738-0750</a>
        </p>

        <p><strong><i class="bi bi-envelope-fill"></i> อีเมล:</strong> 
          <a href="mailto:artkittisak2541@gmail.com">artkittisak2541@gmail.com</a>
        </p>

        <p><strong><i class="bi bi-facebook"></i> Facebook:</strong> 
          <a href="https://facebook.com/ร้านน้ำฝนแฟชั่น" target="_blank">facebook.com/ร้านน้ำฝนแฟชั่น</a>
        </p>

        <p><strong><i class="bi bi-line"></i> LINE:</strong> 
          <a href="https://line.me/ti/p/YOUR_LINE_ID" target="_blank">@namfonfashion</a>
        </p>
      </div>

      <h4 class="text-center text-success mb-4">📍 ตำแหน่งร้านของเรา</h4>

<div class="map-responsive">
  <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.5090084796034!2d100.78932757506195!3d13.748149697378121!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x311d6618f0d367b1%3A0x435e2678129dc01!2zNDQ0LzEg4LiLLiDguInguKXguK3guIfguIHguKPguLjguIcgMTUg4LmB4LiC4Lin4LiH4Lil4Liz4Lib4Lil4Liy4LiX4Li04LinIOC5gOC4guC4leC4peC4suC4lOC4geC4o-C4sOC4muC4seC4hyDguIHguKPguLjguIfguYDguJfguJ7guKHguKvguLLguJnguITguKMgMTA1MjA!5e0!3m2!1sth!2sth!4v1752749390784!5m2!1sth!2sth"
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
</div>

    </div>
  </section>

  <footer class="footer">
    <p>© 2025 ร้านน้ำฝนแฟชั่น. สงวนลิขสิทธิ์.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>
