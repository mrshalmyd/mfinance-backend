<?php
// dashboard.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/auth_check.php';

$username = $usernameFromToken ?? 'Pengguna';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard • Marshal Finance</title>

  <!-- Favicon -->
  <link rel="icon" href="https://mfinance.mrshalmyd.workers.dev/assets/favicon.png" type="image/png" sizes="512x512">
  <link rel="apple-touch-icon" href="https://mfinance.mrshalmyd.workers.dev/assets/favicon.png" sizes="180x180">

  <!-- CSS -->
  <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/base.css">
  <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/dashboard.css">
  <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/navbar.css">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">Marshal<span>Finance</span></div>

    <!-- Hamburger untuk mobile -->
    <div class="hamburger">
      <span></span><span></span><span></span>
    </div>

    <div class="nav-user">
      <span class="welcome">Halo, <?= htmlspecialchars($username) ?> 👋</span>
      <a href="logout.php" class="btn-logout">Keluar</a>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="dashboard-content">

    <!-- Header -->
    <div class="dashboard-header">
      <h1>Dashboard Anda</h1>
      <p>Kelola keuangan dan investasi dengan cerdas & mudah</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
      <div class="card">
        <h3>Total Portofolio</h3>
        <div class="big-value">Rp 999 M</div>
        <p class="small-label">Nilai aset terkini</p>
      </div>

      <div class="card highlight">
        <h3>Return Tahun Ini</h3>
        <div class="big-value positive">+99.9%</div>
        <p class="small-label">Performa Year-to-Date (YTD)</p>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <div class="action-buttons">
        <a href="#" class="action-btn">💰 Top Up Saldo</a>
        <a href="#" class="action-btn">📈 Investasi Baru</a>
        <a href="#" class="action-btn">💸 Tarik Dana</a>
      </div>
    </div>

    <!-- Info Tambahan -->
    <div style="text-align: center; color: #94a3b8; margin-top: 4rem;">
      <p>Fitur riwayat transaksi & grafik performa akan segera hadir!</p>
      <p style="margin-top: 1rem; font-size: 0.9rem;">
        &copy; 2025 Marshal Finance. All rights reserved.
      </p>
    </div>

  </main>

  <!-- JavaScript -->
  <script>
    const hamburger = document.querySelector('.hamburger');
    const navUser   = document.querySelector('.nav-user');

    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      navUser.classList.toggle('active');
    });
  </script>

</body>
</html>
