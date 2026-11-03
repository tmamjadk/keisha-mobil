<?php
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function setting(PDO $pdo, string $key, string $default = ''): string {
    static $cache = [];
    if (!array_key_exists($key, $cache)) {
        $stmt = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = ?');
        $stmt->execute([$key]);
        $cache[$key] = $stmt->fetchColumn();
    }
    return $cache[$key] === false || $cache[$key] === null ? $default : (string)$cache[$key];
}
function rupiah($value): string {
    return $value > 0 ? 'Rp ' . number_format((float)$value, 0, ',', '.') : 'Hubungi kami';
}
function wa_url(PDO $pdo, string $message = ''): string {
    $number = preg_replace('/\D+/', '', setting($pdo, 'whatsapp', '6281234567890'));
    return 'https://wa.me/' . $number . ($message !== '' ? '?text=' . rawurlencode($message) : '');
}
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_check(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(419);
        exit('Sesi formulir tidak valid. Silakan kembali dan coba lagi.');
    }
}
function is_admin(): bool {
    return !empty($_SESSION['admin_id']);
}
function require_admin(): void {
    if (!is_admin()) {
        header('Location: ' . BASE_URL . 'admin/login.php');
        exit;
    }
}
function make_slug(string $text): string {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'));
    return $slug ?: 'mobil-' . time();
}
function upload_car_image(array $file): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload foto gagal.');
    if ($file['size'] > 5 * 1024 * 1024) throw new RuntimeException('Ukuran foto maksimal 5 MB.');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) throw new RuntimeException('Foto harus JPG, PNG, atau WEBP.');
    $dir = dirname(__DIR__) . '/uploads';
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) throw new RuntimeException('Folder upload tidak dapat dibuat.');
    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) throw new RuntimeException('Tidak dapat menyimpan foto.');
    return $name;
}
function delete_car_image(?string $name): void {
    if (!$name || !preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/', $name)) return;
    $path = dirname(__DIR__) . '/uploads/' . $name;
    if (is_file($path)) unlink($path);
}
