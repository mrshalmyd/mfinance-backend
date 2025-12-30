<?php
// api/logout.php
setcookie('auth_token', '', [
    'expires' => time() - 3600,
    'path' => '/',
    'secure' => true, // true kalau full HTTPS
    'httponly' => true,
    'samesite' => 'Lax',
]);

header("Location: https://mfinance.mrshalmyd.workers.dev/");
exit();
