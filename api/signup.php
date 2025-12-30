<?php
// api/signup.php

// Definisi BASE_PATH agar path selalu aman di Vercel
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

require_once BASE_PATH . '/api/config.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$message = '';
$signup_success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $message = "Semua field wajib diisi.";
    } elseif (strlen($username) < 4) {
        $message = "Username minimal 4 karakter.";
    } elseif (strlen($password) < 6) {
        $message = "Password minimal 6 karakter.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format email tidak valid.";
    } else {
        try {
            $check = $db->query("SELECT id FROM users WHERE email = ? OR username = ?", [$email, $username]);

            if (!empty($check)) {
                $message = "Username atau email sudah digunakan.";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $db->execute("INSERT INTO users (username, email, password) VALUES (?, ?, ?)", [$username, $email, $hashed]);
                
                $signup_success = true;
            }
        } catch (Exception $e) {
            $message = "Terjadi kesalahan server. Silakan coba lagi.";
            error_log("Signup error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://mfinance.mrshalmyd.workers.dev/assets/favicon.png" type="image/png" sizes="512x512">
    <link rel="apple-touch-icon" href="https://mfinance.mrshalmyd.workers.dev/assets/favicon.png" sizes="180x180">
    <title>Daftar • Marshal Finance</title>
    <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/base.css">
    <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/auth.css">
</head>
<body>

    <div class="auth-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <?php if (!$signup_success): ?>
    <div class="auth-container minimal">
        <div class="auth-logo">Marshal<span>Finance</span></div>

        <?php if ($message): ?>
            <div class="alert error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required placeholder=" ">
                <label for="username">Username</label>
            </div>

            <div class="form-group">
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required placeholder=" ">
                <label for="email">Email</label>
            </div>

            <div class="form-group password-group">
                <input type="password" name="password" id="password" required placeholder=" ">
                <label for="password">Password</label>
                <span class="toggle-password">
                    <svg class="eye-open" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <svg class="eye-closed" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                        <path d="M2 2l20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
            </div>

            <button type="submit" class="btn-auth">Daftar Gratis</button>
        </form>

        <p class="auth-footer">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </p>
        <p class="back-home">
            <a href="/index.html">← Kembali ke Beranda</a>
        </p>
    </div>
    <?php endif; ?>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <h3>Pendaftaran Berhasil! 🎉</h3>
        <p>Mengalihkan ke halaman login...</p>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', function () {
                const input = this.parentElement.querySelector('input');
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.add('active');
                } else {
                    input.type = 'password';
                    this.classList.remove('active');
                }
            });
        });

        <?php if ($signup_success): ?>
            document.getElementById('loadingOverlay').classList.add('active');
            setTimeout(() => {
                window.location.href = 'login.php?signup=success';
            }, 2000);
        <?php endif; ?>
    </script>

</body>
</html>