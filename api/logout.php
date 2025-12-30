<?php
// api/logout.php (versi final dengan redirect ke Cloudflare)

session_start();

// Hapus semua data session
$_SESSION = [];

// Hapus cookie session kalau ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirect ke halaman utama di Cloudflare
header("Location: https://mfinance.mrshalmyd.pages.dev/");  // GANTI dengan domain Cloudflare kamu yang sebenarnya
exit();
?>