
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

    $password = $_POST['password'] ?? '';
    $newUsername = trim($_POST['new_username'] ?? '');

    $adminId = (int) ($_SESSION['admin_id'] ?? 0);

    $stmt = $pdo->prepare(
        "SELECT id, password_hash FROM admins WHERE id = ? LIMIT 1"
    );
    $stmt->execute([$adminId]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        $error = 'Password saat ini salah.';
    } elseif (!preg_match('/^[A-Za-z0-9_.-]{3,30}$/', $newUsername)) {
        $error = 'Username 3-30 karakter. Gunakan huruf, angka, titik, atau garis bawah.';
    } else {
        $check = $pdo->prepare(
            "SELECT id FROM admins WHERE username = ? AND id != ? LIMIT 1"
        );
        $check->execute([$newUsername, $adminId]);

        if ($check->fetch()) {
            $error = 'Username sudah digunakan.';
        } else {
            $update = $pdo->prepare(
                "UPDATE admins SET username = ? WHERE id = ?"
            );
            $update->execute([$newUsername, $adminId]);

            $_SESSION['admin_username'] = $newUsername;
            $success = 'Username berhasil diganti!';
        }
    }
}

$pageTitle = 'Ganti Username | Keisha Mobil';
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container py-5" style="max-width:600px">

    <a href="<?= BASE_URL ?>admin/index.php"
       class="btn btn-outline-secondary rounded-pill mb-4">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>

    <h2 class="fw-bold">Ganti Username Admin</h2>
    <p class="text-secondary">Ubah username untuk login administrator.</p>

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
                <label class="form-label">Username Baru</label>
                <input type="text" name="new_username"
                       class="form-control form-control-lg"
                       minlength="3" maxlength="30"
                       value="<?= e($_SESSION['admin_username'] ?? '') ?>"
                       required>
            </div>

            <div class="mb-4">
                <label class="form-label">Password Saat Ini</label>
                <input type="password" name="password"
                       class="form-control form-control-lg"
                       required>
            </div>

            <button type="submit"
                    class="btn btn-primary btn-lg rounded-pill w-100">
                <i class="fa-solid fa-user-pen me-2"></i>
                Simpan Username
            </button>

        </form>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
