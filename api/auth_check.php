<?php
require_once __DIR__ . '/lib/jwt.php';

$JWT_SECRET = getenv('JWT_SECRET') ?: 'dev-secret-change-this';

$token = $_COOKIE['auth_token'] ?? null;
if (!$token) {
    header("Location: /api/login.php");
    exit();
}

$payload = jwt_verify($token, $JWT_SECRET);
if ($payload === false) {
    // Token invalid/expired
    setcookie('auth_token', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    header("Location: /api/login.php");
    exit();
}

$userId = $payload['sub'] ?? null;
$usernameFromToken = $payload['username'] ?? 'Pengguna';
