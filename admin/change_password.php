
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

require_admin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $username = $_SESSION['admin_username'] ?? '';

    $stmt = $pdo->prepare(
        "SELECT id, password_hash FROM admins WHERE username = ? LIMIT 1"
    );
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($old, $admin['password_hash'])) {
        $error = 'Password lama salah.';
    } elseif (strlen($new) < 8) {
        $error = 'Password baru minimal 8 karakter.';
    } elseif ($new !== $confirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } elseif ($old === $new) {
        $error = 'Password baru harus berbeda.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);

        $update = $pdo->prepare(
            "UPDATE admins SET password_hash = ? WHERE id = ?"
        );
        $update->execute([$hash, $admin['id']]);

        $success = 'Password berhasil diganti!';
    }
}

$pageTitle = 'Ganti Password | Keisha Mobil';
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container py-5" style="max-width:600px">
    <a href="<?= BASE_URL ?>admin/index.php"
       class="btn btn-outline-secondary rounded-pill mb-4">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>

    <h2 class="fw-bold mb-2">Ganti Password Admin</h2>
    <p class="text-secondary mb-4">Perbarui keamanan akun administrator.</p>

    <div class="card admin-card shadow-sm border-0 p-4">

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token"
                   value="<?= e(csrf_token()) ?>">

            <div class="mb-3">
                <label class="form-label">Password Lama</label>
                <input type="password" name="old_password"
                       class="form-control form-control-lg" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input type="password" name="new_password"
                       class="form-control form-control-lg"
                       minlength="8" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password"
                       class="form-control form-control-lg"
                       minlength="8" required>
            </div>

            <button type="submit"
                    class="btn btn-primary btn-lg w-100 rounded-pill">
                <i class="fa-solid fa-lock me-2"></i>Simpan Password
            </button>
        </form>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
