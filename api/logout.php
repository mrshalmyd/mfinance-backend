<?php
// api/logout.php

/* ------------------------------------------------------------------
   Hapus cookie JWT (auth_token)
   ------------------------------------------------------------------ */
setcookie('auth_token', '', [
    'expires'  => time() - 3600, // Expired 1 jam lalu
    'path'     => '/',
    'secure'   => true,          // aktifkan kalau full HTTPS
    'httponly' => true,
    'samesite' => 'Lax',
]);

/* ------------------------------------------------------------------
   Redirect ke halaman utama
   ------------------------------------------------------------------ */
header("Location: https://mfinance.mrshalmyd.workers.dev/");
exit();
