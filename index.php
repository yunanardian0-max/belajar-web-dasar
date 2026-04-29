<?php
session_start();
include __DIR__ . "/koneksi.php";

$query = mysqli_query($conn, "SELECT * FROM videos");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yunan Ardian — Portfolio</title>

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- HEADER -->
<header>
    <div class="logo">YA</div>
    <nav>
        <a href="#videos">Video</a>
        <a href="#contact">Kontak</a>
    </nav>
</header>

<!-- HERO -->
<section class="hero">
    <p class="hero-label">Portfolio</p>
    <h1 class="hero-title">
        Yunan <br><span>Ardian</span>
    </h1>
    <p class="hero-sub">Web Developer & Content Creator</p>

    <a href="#videos" class="cta">Lihat Karya ↓</a>
</section>

<!-- VIDEOS -->
<section id="videos" class="videos">

    <h2>Video Terbaru</h2>

    <div class="video-grid">

        <?php while ($v = mysqli_fetch_assoc($query)) : ?>

        <div class="video-card">

            <div class="video-wrapper">
                <iframe 
                    src="https://www.youtube.com/embed/<?= $v['youtube_id']; ?>" 
                    allowfullscreen>
                </iframe>
            </div>

            <div class="video-content">
                <h3><?= $v['judul']; ?></h3>
                <p><?= $v['deskripsi']; ?></p>
            </div>

        </div>

        <?php endwhile; ?>

    </div>

</section>

<!-- CONTACT -->
<section id="contact" class="contact">

    <h2>Kontak</h2>

    <?php if (!empty($_SESSION['status']) && $_SESSION['status'] === 'sukses'): ?>
        <p class="success">Pesan berhasil dikirim 🚀</p>
        <?php unset($_SESSION['status']); ?>
    <?php endif; ?>

    <form action="kirim.php" method="post" class="contact-form" id="contactForm">

        <div class="form-row">
            <input type="text" id="nama" name="nama" placeholder="Nama" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
        </div>

        <textarea id="pesan" name="pesan" placeholder="Pesan..." required></textarea>

        <button type="submit">Kirim Pesan</button>

    </form>

</section>

</body>
</html>