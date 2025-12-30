<?php
// api/dashboard.php

// Definisi BASE_PATH agar path aman di Vercel (naik 2 level dari api/ ke root)
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

// Include config D1 (kalau nanti mau fetch data real dari DB)
require_once BASE_PATH . '/../../app/config/config.php';

session_start();

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'] ?? 'Pengguna';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" href="assets/favicon.png" type="image/png" sizes="512x512">
  <link rel="apple-touch-icon" href="assets/favicon.png" sizes="180x180">
  <title>Dashboard • Marshal Finance</title>

  <!-- CSS -->
  <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/base.css">
  <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/dashboard.css">
  <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/navbar.css">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

  <style>
    /* Tambahan inline biar langsung jalan tanpa edit CSS banyak */
    .positive { color: #34d399; font-weight: 700; }
    .big-value { font-size: 3rem; font-weight: 700; margin: 1rem 0; }
    .quick-actions {
      text-align: center;
      margin: 3rem 0;
    }
    .btn-action {
      display: inline-block;
      padding: 1rem 2rem;
      margin: 0.5rem;
      background: rgba(99, 102, 241, 0.2);
      border: 1px solid rgba(99, 102, 241, 0.5);
      border-radius: 12px;
      color: #c7d2fe;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-action:hover {
      background: rgba(99, 102, 241, 0.4);
      transform: translateY(-4px);
      box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    }
    .highlight { border: 2px solid #6366f1; }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">
      Marshal<span>Finance</span>
    </div>

    <div class="nav-user">
      <span class="welcome">
        Halo, <?= htmlspecialchars($username) ?> 👋
      </span>
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

      <!-- Total Portofolio -->
      <div class="card">
        <h3>Total Portofolio</h3>
        <div class="big-value">Rp 999 M</div>
        <p class="small-label">Nilai aset terkini</p>
      </div>

      <!-- Return Tahunan -->
      <div class="card highlight">
        <h3>Return Tahun Ini</h3>
        <div class="big-value positive">+99.9%</div>
        <p class="small-label">Performa Year-to-Date (YTD)</p>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <a href="#" class="btn-action">💰 Top Up Saldo</a>
      <a href="#" class="btn-action">📈 Investasi Baru</a>
      <a href="#" class="btn-action">💸 Tarik Dana</a>
    </div>

    <!-- Info Tambahan -->
    <div style="text-align: center; color: #94a3b8; margin-top: 4rem;">
      <p>Fitur riwayat transaksi & grafik performa akan segera hadir!</p>
      <p style="margin-top: 1rem; font-size: 0.9rem;">
        &copy; 2025 Marshal Finance. All rights reserved.
      </p>
    </div>

  </main>

</body>
</html>