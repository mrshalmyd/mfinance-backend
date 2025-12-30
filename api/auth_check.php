<?php
// api/auth_check.php
require_once __DIR__ . '/lib/jwt.php';

// Ambil secret dari environment, fallback ke dev-secret
$JWT_SECRET = getenv('JWT_SECRET') ?: 'dev-secret-change-this';

// Ambil token dari cookie
$token = $_COOKIE['auth_token'] ?? null;
if (!$token) {
    // Tidak ada token → redirect ke login
    header("Location: /api/login.php");
    exit();
}

// Verifikasi token
$payload = jwt_verify($token, $JWT_SECRET);
if ($payload === false) {
    // Token invalid atau expired → hapus cookie & redirect
    setcookie('auth_token', '', [
        'expires'  => time() - 3600,
        'path'     => '/',
        'secure'   => true,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    header("Location: /api/login.php");
    exit();
}

// Ambil data user dari payload
$userId            = $payload['sub']      ?? null;
$usernameFromToken = $payload['username'] ?? 'Pengguna';
