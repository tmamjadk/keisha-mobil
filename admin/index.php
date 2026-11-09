<?php
require_once dirname(__DIR__).'/config.php';
require_once dirname(__DIR__).'/includes/functions.php';
require_admin();

$count=(int)$pdo->query('SELECT COUNT(*) FROM cars')->fetchColumn();
$available=(int)$pdo->query("SELECT COUNT(*) FROM cars WHERE status='Tersedia'")->fetchColumn();
$sold=(int)$pdo->query("SELECT COUNT(*) FROM cars WHERE status='Terjual'")->fetchColumn();
$hidden=(int)$pdo->query("SELECT COUNT(*) FROM cars WHERE status='Disembunyikan'")->fetchColumn();
$latest=$pdo->query('SELECT * FROM cars ORDER BY created_at DESC LIMIT 8')->fetchAll();
$pageTitle='Dashboard Admin | Keisha Mobil';
require dirname(__DIR__).'/includes/header.php';
?>
<div class="admin-nav py-4 text-white">
 <div class="container d-flex justify-content-between align-items-center flex-wrap gap-3">
  <div><div class="small text-white-50 mb-1">KEISHA MOBIL / ADMINISTRASI</div><h1 class="h3 mb-1">Dashboard</h1><div class="text-white-50">Selamat datang, <?= e($_SESSION['admin_username'] ?? 'admin') ?>. Ringkasan pengelolaan showroom.</div></div>
  <div class="d-flex gap-2"><a href="<?= BASE_URL ?>" target="_blank" class="btn btn-light rounded-pill"><i class="fa-solid fa-arrow-up-right-from-square me-2"></i>Lihat Website</a><a href="<?= BASE_URL ?>admin/logout.php" class="btn btn-outline-light rounded-pill" onclick="return confirm('Yakin ingin keluar?')">Keluar</a></div>
 </div>
</div>
<main class="container py-4 admin-wrap">
 <?php if(!empty($_SESSION['flash'])): ?><div class="alert alert-success"><?= e($_SESSION['flash']);unset($_SESSION['flash']); ?></div><?php endif; ?>
 <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"><div><h2 class="h5 fw-bold mb-1">Ringkasan inventaris</h2><p class="text-secondary mb-0 small">Data ini diperbarui langsung dari database Keisha Mobil.</p></div><span class="db-indicator"><i class="fa-solid fa-database"></i> Database terhubung</span></div>
 <div class="row g-3 mb-4">
  <div class="col-6 col-lg-3"><div class="admin-stat"><div class="stat-label">Total mobil</div><div class="stat-value"><?= $count ?></div><div class="small text-secondary mt-1">Semua data kendaraan</div></div></div>
  <div class="col-6 col-lg-3"><div class="admin-stat"><div class="stat-label">Tersedia</div><div class="stat-value text-success"><?= $available ?></div><div class="small text-secondary mt-1">Tampil di katalog publik</div></div></div>
  <div class="col-6 col-lg-3"><div class="admin-stat"><div class="stat-label">Terjual</div><div class="stat-value"><?= $sold ?></div><div class="small text-secondary mt-1">Arsip kendaraan terjual</div></div></div>
  <div class="col-6 col-lg-3"><div class="admin-stat"><div class="stat-label">Disembunyikan</div><div class="stat-value"><?= $hidden ?></div><div class="small text-secondary mt-1">Tidak tampil di katalog</div></div></div>
  <div class="col-md-4">
    <a class="admin-action"
       href="<?= BASE_URL ?>admin/change_password.php">
        <i class="fa-solid fa-key"></i>
        <span>
            <strong>Ganti Password</strong>
            <small>Ubah password akun administrator</small>
        </span>
    </a>
</div>
 </div>
 

 <h2 class="h5 fw-bold mb-3">Menu pengelolaan</h2>

<div class="row g-3 mb-4">
    <a class="admin-action"
       href="<?= BASE_URL ?>admin/change_username.php">
        <i class="fa-solid fa-user-pen"></i>
        <span>
            <strong>Ganti Username</strong>
            <small>Ubah username login admin</small>
        </span>
    </a>
</div>
    <div class="col-md-4">
        <a class="admin-action" href="<?= BASE_URL ?>admin/car_form.php">
            <i class="fa-solid fa-plus"></i>
            <span>
                <strong>Tambah Mobil</strong>
                <small>Masukkan kendaraan baru</small>
            </span>
        </a>
        
    </div>

    <div class="col-md-4">
        <a class="admin-action" href="<?= BASE_URL ?>admin/cars.php">
            <i class="fa-solid fa-car-side"></i>
            <span>
                <strong>Kelola Data Mobil</strong>
                <small>Edit, hapus, dan ubah status</small>
            </span>
        </a>
    </div>

    <div class="col-md-4">
        <a class="admin-action" href="<?= BASE_URL ?>admin/settings.php">
            <i class="fa-solid fa-sliders"></i>
            <span>
                <strong>Pengaturan Toko</strong>
                <small>Nama, WhatsApp, alamat, jam buka</small>
            </span>
        </a>
    </div>

    <div class="col-md-4">
        <a class="admin-action" href="<?= BASE_URL ?>admin/testimonials.php">
            <i class="fa-solid fa-star"></i>
            <span>
                <strong>Kelola Testimoni</strong>
                <small>Tambah foto, rating, dan ulasan pelanggan</small>
            </span>
        </a>
    </div>

</div>

  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3"><div><h2 class="h5 fw-bold mb-1">Data mobil terbaru</h2><p class="small text-secondary mb-0">Delapan kendaraan terakhir yang tersimpan.</p></div><a href="<?= BASE_URL ?>admin/cars.php" class="btn btn-outline-primary btn-sm rounded-pill">Lihat semua data</a></div>
  <div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Nama kendaraan</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
  <?php if($latest): foreach($latest as $c): ?><tr><td class="fw-semibold"><?= e($c['brand'].' '.$c['model']) ?><div class="small text-secondary fw-normal"><?= e($c['year'] ?: 'Tahun belum diisi') ?></div></td><td><?= e(rupiah($c['price'])) ?></td><td><span class="badge rounded-pill text-bg-<?= $c['status']==='Tersedia'?'success':($c['status']==='Terjual'?'primary':'secondary') ?>"><?= e($c['status']) ?></span></td><td><a class="btn btn-sm btn-outline-primary rounded-pill" href="<?= BASE_URL ?>admin/car_form.php?id=<?= (int)$c['id'] ?>">Edit</a></td></tr><?php endforeach; else: ?><tr><td colspan="4" class="text-center text-secondary py-4">Belum ada data mobil.</td></tr><?php endif; ?>
  </tbody></table></div>
 </section>
 <div class="small text-secondary mt-3"><i class="fa-solid fa-circle-info me-1"></i>Dashboard membaca data dari database MySQL <strong>keisha_mobil</strong>. Jangan mengimpor ulang database jika website sudah memiliki data.</div>
</main>
<?php require dirname(__DIR__).'/includes/footer.php'; ?>
