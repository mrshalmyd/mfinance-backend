<?php
// api/config.php

class D1Client {
    private $workerUrl;
    private $headers;

    public function __construct() {
        // Ambil dari env Vercel, fallback ke default (untuk local testing)
        $baseUrl = rtrim(getenv('D1_WORKER_URL') ?: 'https://mfinance.mrshalmyd.workers.dev', '/');
        
        // Pastikan trailing slash dihapus, lalu tambah endpoint
        $this->workerUrl = $baseUrl . '/api/query';
        
        $this->headers = [
            'Content-Type: application/json',
            // Tambah ini kalau kamu mau auth sederhana nanti
            // 'X-API-Key: ' . getenv('WORKER_API_KEY'),
        ];
    }

    public function query($sql, $params = []) {
        // Validasi minimal
        if (empty(trim($sql))) {
            throw new Exception("SQL query tidak boleh kosong");
        }

        $payload = json_encode([
            'sql'    => $sql,
            'params' => (array) $params  // pastikan selalu array
        ], JSON_THROW_ON_ERROR);  // akan throw kalau json_encode gagal

        $ch = curl_init($this->workerUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => $this->headers,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 20,          // naikkan sedikit untuk safety
            CURLOPT_FAILONERROR    => false,       // biar bisa baca body error
            CURLOPT_HEADER         => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception("cURL error: $curlError (ke $this->workerUrl)");
        }

        if ($httpCode !== 200) {
            throw new Exception("Worker returned HTTP $httpCode: " . substr($response, 0, 200));
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Invalid JSON from Worker: " . json_last_error_msg() . " - Raw: " . substr($response, 0, 300));
        }

        if (!isset($data['success']) || !$data['success']) {
            $errorMsg = $data['error'] ?? 'Unknown error from Worker';
            throw new Exception("Query gagal: $errorMsg");
        }

        // Yang paling penting: sesuai struktur Worker kamu
        // env.DB.prepare().all() return object { results: array, meta: {...} }
        return $data['results'] ?? [];
    }

    public function execute($sql, $params = []) {
        // Untuk INSERT/UPDATE/DELETE, hasilnya sama, tapi bisa cek affected rows via meta kalau perlu
        return $this->query($sql, $params);
    }
}

// Global instance (mirip PDO)
try {
    $db = new D1Client();
} catch (Exception $e) {
    error_log("D1 init error: " . $e->getMessage());
    if (php_sapi_name() === 'cli' || !headers_sent()) {
        http_response_code(500);
    }
    // Output JSON error supaya API-friendly
    exit(json_encode([
        'success' => false,
        'error'   => 'Koneksi database gagal. Cek log server.'
    ]));
}