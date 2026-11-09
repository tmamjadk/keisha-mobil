
<?php
require_once dirname(__DIR__).'/config.php';
require_once dirname(__DIR__).'/includes/functions.php';

require_admin();

$keys = [
    'store_name',
    'tagline',
    'whatsapp',
    'address',
    'hours',
    'about',
    'hero_badge',
    'hero_title',
    'hero_description',
    'hero_button'
];

$labels = [
    'store_name' => 'Nama toko',
    'tagline' => 'Slogan',
    'whatsapp' => 'Nomor WhatsApp (format 62...)',
    'address' => 'Alamat showroom',
    'hours' => 'Jam operasional',
    'about' => 'Deskripsi toko',
    'hero_badge' => 'Tulisan badge hero',
    'hero_title' => 'Judul utama hero',
    'hero_description' => 'Deskripsi hero',
    'hero_button' => 'Tulisan tombol katalog'
];

$defaults = [
    'hero_badge' => 'Temukan kendaraan terbaik pilihan Anda',
    'hero_title' => 'Perjalanan baru dimulai dari',
    'hero_description' => 'Temukan mobil impian Anda dengan pilihan kendaraan berkualitas. Hubungi kami untuk informasi stok, harga, dan jadwal melihat mobil.',
    'hero_button' => 'Lihat Katalog'
];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $vals = [];

    foreach ($keys as $k) {
        $vals[$k] = trim((string)($_POST[$k] ?? ''));
    }

    $number = preg_replace('/\D+/', '', $vals['whatsapp']);

    if ($vals['store_name'] === '' || $number === '') {
        $error = 'Nama toko dan nomor WhatsApp wajib diisi.';
    } else {
        foreach ($vals as $k => $v) {
            if ($k === 'whatsapp') {
                $v = $number;
            }

            $stmt = $pdo->prepare(
                'INSERT INTO settings (setting_key, setting_value)
                 VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
            );

            $stmt->execute([$k, $v]);
        }

        $_SESSION['flash'] = 'Pengaturan toko berhasil disimpan.';

        header('Location: '.BASE_URL.'admin/settings.php');
        exit;
    }
}

$pageTitle = 'Pengaturan Toko';
require dirname(__DIR__).'/includes/header.php';
?>

<div class="admin-nav py-4 text-white">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">Pengaturan Toko</h1>
        <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-outline-light">
            Dashboard
        </a>
    </div>
</div>

<main class="container py-4" style="max-width:850px">

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success">
            <?= e($_SESSION['flash']) ?>
            <?php unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="card admin-card p-3 p-md-4">

        <p class="text-secondary">
            Ubah informasi toko dan tampilan halaman depan Keisha Mobil.
        </p>

        <form method="post">

            <input type="hidden" name="csrf_token"
                   value="<?= e(csrf_token()) ?>">

            <h5 class="mb-3">Informasi Toko</h5>

            <?php foreach (['store_name','tagline','whatsapp','address','hours','about'] as $k): ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        <?= e($labels[$k]) ?>
                    </label>

                    <?php if ($k === 'about'): ?>
                        <textarea class="form-control" rows="4"
                                  name="<?= e($k) ?>"><?= e(setting($pdo, $k, '')) ?></textarea>
                    <?php else: ?>
                        <input class="form-control"
                               name="<?= e($k) ?>"
                               value="<?= e($k === 'whatsapp' ? setting($pdo, $k, '6281234567890') : setting($pdo, $k, '')) ?>"
                               <?= in_array($k, ['store_name','whatsapp'], true) ? 'required' : '' ?>>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>

            <hr class="my-4">

            <h5 class="mb-3">Tampilan Hero Halaman Depan</h5>

            <?php foreach (['hero_badge','hero_title','hero_description','hero_button'] as $k): ?>

                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        <?= e($labels[$k]) ?>
                    </label>

                    <?php
                    $value = setting($pdo, $k, $defaults[$k]);
                    ?>

                    <?php if (in_array($k, ['hero_title','hero_description'], true)): ?>
                        <textarea class="form-control" rows="3"
                                  name="<?= e($k) ?>"><?= e($value) ?></textarea>
                    <?php else: ?>
                        <input class="form-control"
                               name="<?= e($k) ?>"
                               value="<?= e($value) ?>">
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary px-4">
                <i class="fa-solid fa-floppy-disk me-2"></i>
                Simpan Pengaturan
            </button>

        </form>
    </div>
</main>

<?php require dirname(__DIR__).'/includes/footer.php'; ?>
