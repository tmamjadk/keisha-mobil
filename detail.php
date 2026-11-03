<?php
require_once __DIR__ . '/config.php'; require_once __DIR__ . '/includes/functions.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id=? AND status='Tersedia'"); $stmt->execute([$id ?: 0]); $car = $stmt->fetch();
if (!$car) { http_response_code(404); $pageTitle='Mobil tidak ditemukan'; require __DIR__.'/includes/header.php'; echo '<main class="container py-5"><h1>Mobil tidak ditemukan</h1><p>Mobil mungkin sudah terjual atau tidak tersedia.</p><a href="'.BASE_URL.'" class="btn btn-primary">Kembali ke katalog</a></main>'; require __DIR__.'/includes/footer.php'; exit; }
$name=$car['brand'].' '.$car['model']; $pageTitle=$name.' | '.setting($pdo,'store_name','Keisha Mobil');
$msg='Halo '.setting($pdo,'store_name','Keisha Mobil').', saya tertarik dengan '.$name.'. Mohon info lebih lanjut.';
require __DIR__.'/includes/header.php';
?>
<main class="container py-5"><nav aria-label="breadcrumb"><ol class="breadcrumb">
<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Beranda</a></li>
<li class="breadcrumb-item active"><?= e($name) ?></li></ol></nav>
<div class="row g-5"><div class="col-lg-7"><div class="car-photo rounded-4" style="height:clamp(260px,45vw,480px)">
<?php if($car['image'] && is_file(__DIR__.'/uploads/'.$car['image'])): ?><img src="<?= BASE_URL ?>uploads/<?= e($car['image']) ?>" alt="<?= e($name) ?>"><?php else: ?>
    <i class="fa-solid fa-car-side placeholder"></i><?php endif; ?></div></div><div class="col-lg-5"><span class="pill"><?= e($car['status']) ?></span><h1 class="section-title mt-3"><?= e($name) ?></h1><div class="car-price fs-3 mb-3"><?= e(rupiah($car['price'])) ?></div><p><?= nl2br(e($car['description'] ?: 'Hubungi kami untuk informasi lengkap.')) ?></p>
    <hr><div class="row g-3 car-spec"><div class="col-6">
        <strong>Tahun</strong><div><?= e($car['year'] ?: '-') ?></div></div><div class="col-6">
            <strong>Transmisi</strong><div><?= e($car['transmission']) ?></div></div><div class="col-6">
                <strong>Bahan bakar</strong><div><?= e($car['fuel']) ?></div></div><div class="col-6">
                    <strong>Kapasitas</strong>
                    <div><?= (int)$car['seats'] ?> penumpang</div></div>
                    <div class="col-6"><strong>Kilometer</strong>
                    <div><?= number_format((int)$car['mileage'],0,',','.') ?> km</div></div>
                    <div class="col-6"><strong>Warna</strong><div><?= e($car['color'] ?: '-') ?></div></div></div><a class="btn btn-success btn-lg w-100 rounded-pill mt-4" href="<?= e(wa_url($pdo,$msg)) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp me-2"></i>Tanya mobil ini</a><a class="btn btn-outline-secondary w-100 rounded-pill mt-2" href="<?= BASE_URL ?>">Kembali ke katalog</a></div></div></main>
<?php require __DIR__.'/includes/footer.php'; ?>
