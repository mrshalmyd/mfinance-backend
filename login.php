<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

require_once __DIR__ . '/../app/config/config.php';

$message = '';
$login_success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $message = "Email dan password harus diisi.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $login_success = true; // Trigger loading overlay
        } else {
            $message = "Email atau password salah.";
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
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>

    <!-- Background blob dekoratif -->
    <div class="auth-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Card login -->
    <div class="auth-container minimal">
        <div class="auth-logo">Marshal<span>Finance</span></div>

        <!-- Pesan error atau success dari signup -->
        <?php if (!empty($message)): ?>
            <div class="alert error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
            <div class="alert success">Pendaftaran berhasil! Silakan masuk.</div>
        <?php endif; ?>

        <!-- Form login -->
        <form method="POST" class="auth-form">
            <!-- Field Email -->
            <div class="form-group">
                <input type="email" name="email" id="email" required placeholder=" ">
                <label for="email">Email</label>
            </div>

            <!-- Field Password dengan Toggle Icon Berubah -->
            <div class="form-group password-group">
                <input type="password" name="password" id="password" required placeholder=" ">
                <label for="password">Password</label>
                <span class="toggle-password">
                    <!-- Icon Mata Terbuka (default - password hidden) -->
                    <svg class="eye-open" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    <!-- Icon Mata Tertutup (saat password visible) -->
                    <svg class="eye-closed" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                        <path d="M2 2l20 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
            </div>

            <button type="submit" class="btn-auth">Masuk Sekarang</button>
        </form>

        <!-- Link daftar & kembali -->
        <p class="auth-footer">
            Belum punya akun? <a href="signup.php">Daftar gratis</a>
        </p>
        <p class="back-home">
            <a href="index.html">← Kembali ke Beranda</a>
        </p>
    </div>

    <!-- Loading overlay saat login sukses -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <h3>Masuk Berhasil!</h3>
        <p>Mengalihkan ke dashboard...</p>
    </div>

    <!-- JavaScript untuk toggle password & loading -->
    <script>
        // Toggle password - ganti icon mata terbuka/tertutup
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

        // Tampilkan loading jika login sukses
        <?php if ($login_success): ?>
            document.getElementById('loadingOverlay').classList.add('active');
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 2000);
        <?php endif; ?>
    </script>

</body>
</html>