<?php
session_start();
if (!isset($_SESSION['logged_in'])) { header("Location: login.php"); exit; }
include "koneksi.php";
$query = mysqli_query($conn, "SELECT * FROM videos");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - <?= htmlspecialchars($_SESSION['username']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">YA</div>
    <nav>
        <a href="#videos">Video</a>
        <a href="#contact">Kontak</a>
        <a href="logout.php" style="color:#ff4747; font-weight:bold;">Logout</a>
    </nav>
</header>

<section class="hero">
    <p class="hero-label">Portfolio</p>
    <h1 class="hero-title">Yunan <br><span>Ardian</span></h1>
    <p class="hero-sub">Web Developer & Content Creator</p>
    <a href="#videos" class="cta">Lihat Karya ↓</a>
</section>

<section id="videos" class="videos">
    <h2>Video Terbaru</h2>
    <div class="video-grid">
        <?php while ($v = mysqli_fetch_assoc($query)) : ?>
        <div class="video-card">
            <div class="video-wrapper">
                <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($v['youtube_id']); ?>" allowfullscreen></iframe>
            </div>
            <div class="video-content">
                <h3><?= htmlspecialchars($v['judul']); ?></h3>
                <p><?= htmlspecialchars($v['deskripsi']); ?></p>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<section id="contact" class="contact">
    <h2>Kontak Kami</h2>
    <form action="kirim.php" method="post" class="contact-form" id="contactForm">
        <div class="form-row">
            <input type="text" name="nama" placeholder="Nama" required>
            <input type="email" name="email" placeholder="Email" required>
        </div>
        <textarea name="pesan" placeholder="Pesan..." required></textarea>
        
        <input type="hidden" id="ttd_base64" name="ttd" required>
        <button type="button" id="openTtdBtn" style="background:#222; color:#fff; margin-bottom:15px; padding:10px; border:none; cursor:pointer; width:100%;">✍️ Buat Tanda Tangan</button>
        <span id="ttdStatus" style="display:none; color:#e8ff47; font-size:14px; margin-left:10px;">✔ TTD Tersimpan</span>
        
        <button type="submit">Kirim Pesan</button>
    </form>
</section>

<div id="ttdModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999;">
    <div style="background:#111; border:1px solid #333; margin:12% auto; padding:25px; width:450px; text-align:center; border-radius:8px;">
        <h3 style="color:#fff; margin-bottom:15px;">Tanda Tangan Digital</h3>
        <canvas id="signaturePad" width="400" height="200" style="border:1px dashed #555; background:#fff; cursor:crosshair; border-radius:4px;"></canvas><br><br>
        <button type="button" id="clearBtn" style="background:#333; color:#fff; padding:10px 20px; border:none; margin-right:10px; cursor:pointer;">Hapus</button>
        <button type="button" id="saveBtn" style="background:#e8ff47; color:#000; padding:10px 20px; border:none; font-weight:bold; cursor:pointer;">Gunakan TTD</button>
    </div>
</div>

<button id="bgmToggle" style="position:fixed; bottom:20px; right:20px; background:#111; color:#e8ff47; border:1px solid #333; padding:10px 15px; border-radius:20px; cursor:pointer; z-index:1000;">🔊 Play BGM</button>
<audio id="bgMusic" loop><source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" type="audio/mpeg"></audio>

<script>
    // Audio Control
    const bgm = document.getElementById('bgMusic'), toggle = document.getElementById('bgmToggle');
    toggle.onclick = () => { bgm.paused ? (bgm.play(), toggle.innerText = "🔇 Stop BGM") : (bgm.pause(), toggle.innerText = "🔊 Play BGM"); };

    // Canvas Signature & Modal Control
    const modal = document.getElementById('ttdModal'), canvas = document.getElementById('signaturePad'), ctx = canvas.getContext('2d');
    let isDrawing = false;
    document.getElementById('openTtdBtn').onclick = () => { modal.style.display = 'block'; };
    window.onclick = (e) => { if (e.target == modal) modal.style.display = 'none'; };

    function getMousePos(canvasDom, e) {
        const rect = canvasDom.getBoundingClientRect();
        return { x: e.clientX - rect.left, y: e.clientY - rect.top };
    }

    canvas.onmousedown = (e) => {
        isDrawing = true;
        const pos = getMousePos(canvas, e);
        ctx.beginPath(); ctx.moveTo(pos.x, pos.y);
    };
    canvas.onmouseup = () => isDrawing = false;
    canvas.onmouseleave = () => isDrawing = false;
    canvas.onmousemove = (e) => {
        if (!isDrawing) return;
        ctx.lineWidth = 3; ctx.lineCap = 'round'; ctx.strokeStyle = '#000000';
        const pos = getMousePos(canvas, e);
        ctx.lineTo(pos.x, pos.y); ctx.stroke();
    };
    
    document.getElementById('clearBtn').onclick = () => { ctx.clearRect(0, 0, canvas.width, canvas.height); };
    document.getElementById('saveBtn').onclick = () => {
        document.getElementById('ttd_base64').value = canvas.toDataURL('image/png');
        document.getElementById('ttdStatus').style.display = 'inline';
        modal.style.display = 'none';
    };
</script>
</body>
</html>