<?php
// api/lib/jwt.php

/* ------------------------------------------------------------------
   Base64URL Helpers
   ------------------------------------------------------------------ */
function base64url_encode(string $data): string {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode(string $data): string {
    return base64_decode(strtr($data, '-_', '+/'));
}

/* ------------------------------------------------------------------
   JWT Sign
   Membuat JWT dengan algoritma HS256
   ------------------------------------------------------------------ */
function jwt_sign(array $payload, string $secret, int $ttlSeconds = 3600): string {
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $now    = time();

    // Tambahkan claim standar
    $payload = array_merge($payload, [
        'iat' => $now,
        'exp' => $now + $ttlSeconds,
    ]);

    // Encode header & payload
    $headerB64  = base64url_encode(json_encode($header));
    $payloadB64 = base64url_encode(json_encode($payload));

    // Buat signature
    $signature   = hash_hmac('sha256', "$headerB64.$payloadB64", $secret, true);
    $signatureB64 = base64url_encode($signature);

    return "$headerB64.$payloadB64.$signatureB64";
}

/* ------------------------------------------------------------------
   JWT Verify
   Verifikasi token JWT dan return payload jika valid
   ------------------------------------------------------------------ */
function jwt_verify(string $token, string $secret): array|false {
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        return false;
    }

    [$h, $p, $s] = $parts;

    // Validasi signature
    $expected = base64url_encode(hash_hmac('sha256', "$h.$p", $secret, true));
    if (!hash_equals($expected, $s)) {
        return false;
    }

    // Decode payload
    $payload = json_decode(base64url_decode($p), true);
    if (!is_array($payload)) {
        return false;
    }

    // Validasi expiry
    if (!isset($payload['exp']) || time() > (int)$payload['exp']) {
        return false;
    }

    return $payload;
}
