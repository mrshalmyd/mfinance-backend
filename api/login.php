<?php
// api/login.php

require_once __DIR__ . '/config/config.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$message = '';
$login_success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $message = "Email dan password harus diisi.";
    } else {
        try {
            $results = $db->query("SELECT id, username, password FROM users WHERE email = ?", [$email]);
            $user = !empty($results) ? $results[0] : null;

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $login_success = true; // Trigger loading
            } else {
                $message = "Email atau password salah.";
            }
        } catch (Exception $e) {
            $message = "Terjadi kesalahan server. Silakan coba lagi.";
            error_log("Login error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk • Marshal Finance</title>
    <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/base.css">
    <link rel="stylesheet" href="https://mfinance.mrshalmyd.workers.dev/css/auth.css">
</head>
<body>

    <div class="auth-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="auth-container minimal">
        <div class="auth-logo">Marshal<span>Finance</span></div>

        <?php if (!empty($message)): ?>
            <div class="alert error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
            <div class="alert success">Pendaftaran berhasil! Silakan masuk.</div>
        <?php endif; ?>

        <form method="POST" class="auth-form">
            <div class="form-group">
                <input type="email" name="email" id="email" required placeholder=" ">
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

            <button type="submit" class="btn-auth">Masuk Sekarang</button>
        </form>

        <p class="auth-footer">
            Belum punya akun? <a href="signup.php">Daftar gratis</a>
        </p>
        <p class="back-home">
            <a href="/index.html">← Kembali ke Beranda</a>
        </p>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <h3>Masuk Berhasil!</h3>
        <p>Mengalihkan ke dashboard...</p>
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

        <?php if ($login_success): ?>
            document.getElementById('loadingOverlay').classList.add('active');
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 2000);
        <?php endif; ?>
    </script>

</body>
</html>