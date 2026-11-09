
<?php
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/functions.php';

require_admin();

// Siapkan tabel testimoni jika belum ada
$pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(150) NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    rating INT NOT NULL DEFAULT 5,
    review TEXT NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Tampil',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        csrf_check();

        $name = trim($_POST['customer_name'] ?? '');
        $review = trim($_POST['review'] ?? '');
        $rating = (int)($_POST['rating'] ?? 5);
        $status = $_POST['status'] ?? 'Tampil';

        if ($name === '' || $review === '') {
            throw new Exception('Nama dan ulasan wajib diisi.');
        }

        if ($rating < 1 || $rating > 5) {
            throw new Exception('Rating harus 1 sampai 5.');
        }

        if (!in_array($status, ['Tampil', 'Sembunyi'], true)) {
            throw new Exception('Status tidak valid.');
        }

        $photo = null;

        if (!empty($_FILES['photo']['name'])) {
            if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Upload foto gagal.');
            }

            if ($_FILES['photo']['size'] > 3 * 1024 * 1024) {
                throw new Exception('Foto maksimal 3 MB.');
            }

            $info = getimagesize($_FILES['photo']['tmp_name']);
            $types = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp'
            ];

            if (!$info || !isset($types[$info['mime']])) {
                throw new Exception('Foto harus JPG, PNG, atau WEBP.');
            }

            $folder = dirname(__DIR__) . '/uploads/testimonials/';

            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            $photo = bin2hex(random_bytes(12)) . '.' . $types[$info['mime']];

            if (!move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                $folder . $photo
            )) {
                throw new Exception('Foto tidak berhasil disimpan.');
            }
        }

        $stmt = $pdo->prepare(
            "INSERT INTO testimonials
            (customer_name, photo, rating, review, status)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->execute([$name, $photo, $rating, $review, $status]);

        $_SESSION['flash'] = 'Testimoni berhasil ditambahkan.';
        header('Location: ' . BASE_URL . 'admin/testimonials.php');
        exit;

    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

if (isset($_POST['delete_id'])) {
    csrf_check();

    $id = (int)$_POST['delete_id'];

    $stmt = $pdo->prepare('DELETE FROM testimonials WHERE id = ?');
    $stmt->execute([$id]);

    $_SESSION['flash'] = 'Testimoni berhasil dihapus.';
    header('Location: ' . BASE_URL . 'admin/testimonials.php');
    exit;
}

$items = $pdo->query(
    'SELECT * FROM testimonials ORDER BY created_at DESC'
)->fetchAll();

$pageTitle = 'Kelola Testimoni';
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="admin-nav py-4 text-white">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">Kelola Testimoni</h1>
        <a href="<?= BASE_URL ?>admin/index.php"
           class="btn btn-outline-light">Dashboard</a>
    </div>
</div>

<main class="container py-4">

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success">
            <?= e($_SESSION['flash']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="card admin-card p-4 mb-4">
        <h2 class="h5 fw-bold mb-3">Tambah Testimoni Pelanggan</h2>

        <form method="post" enctype="multipart/form-data">

            <input type="hidden" name="csrf_token"
                   value="<?= e(csrf_token()) ?>">

            <div class="mb-3">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" name="customer_name"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Pelanggan</label>
                <input type="file" name="photo"
                       class="form-control"
                       accept="image/jpeg,image/png,image/webp">
            </div>

            <div class="mb-3">
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select">
                    <option value="5">★★★★★ - 5 Bintang</option>
                    <option value="4">★★★★ - 4 Bintang</option>
                    <option value="3">★★★ - 3 Bintang</option>
                    <option value="2">★★ - 2 Bintang</option>
                    <option value="1">★ - 1 Bintang</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ulasan Pelanggan</label>
                <textarea name="review" class="form-control"
                          rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Tampil">Tampilkan</option>
                    <option value="Sembunyi">Sembunyikan</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary px-4">
                <i class="fa-solid fa-floppy-disk me-2"></i>
                Simpan Testimoni
            </button>

        </form>
    </div>

    <div class="card admin-card p-4">
        <h2 class="h5 fw-bold mb-3">Daftar Testimoni</h2>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Rating</th>
                        <th>Ulasan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <?php if ($item['photo']): ?>
                                <img src="<?= BASE_URL ?>uploads/testimonials/<?= e($item['photo']) ?>"
                                     width="45" height="45"
                                     style="object-fit:cover;border-radius:50%">
                            <?php endif; ?>
                            <?= e($item['customer_name']) ?>
                        </td>

                        <td class="text-warning">
                            <?= str_repeat('★', (int)$item['rating']) ?>
                        </td>

                        <td><?= e($item['review']) ?></td>
                        <td><?= e($item['status']) ?></td>

                        <td>
                            <form method="post"
                                  onsubmit="return confirm('Hapus testimoni ini?')">
                                <input type="hidden" name="csrf_token"
                                       value="<?= e(csrf_token()) ?>">
                                <input type="hidden" name="delete_id"
                                       value="<?= (int)$item['id'] ?>">
                                <button class="btn btn-sm btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (!$items): ?>
                    <tr>
                        <td colspan="5" class="text-center text-secondary">
                            Belum ada testimoni pelanggan.
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

</main>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
