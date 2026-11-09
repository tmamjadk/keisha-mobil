<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = setting($pdo, 'store_name', 'Keisha Mobil') . ' | Marketplace Mobil';
$q = trim($_GET['q'] ?? '');
$sql = "SELECT * FROM cars WHERE status = 'Tersedia'";
$params = [];
if ($q !== '') {
    $sql .= " AND (brand LIKE ? OR model LIKE ? OR description LIKE ?)";
    $term = '%' . $q . '%'; $params = [$term, $term, $term];
}
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params); $cars = $stmt->fetchAll();
$testimonials = $pdo->query("
    SELECT * FROM testimonials
    WHERE status = 'Tampil'
    ORDER BY created_at DESC
")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<header class="hero">
    <div class="container">

        <span class="badge rounded-pill text-bg-light mb-3">
            <i class="fa-solid fa-circle-check text-success me-1"></i>
            <?= e(setting($pdo, 'hero_badge', 'Temukan kendaraan terbaik pilihan Anda')) ?>
        </span>

        <h1>
            <?= nl2br(e(setting($pdo, 'hero_title', 'Perjalanan baru dimulai dari'))) ?>
            <span><?= e(setting($pdo, 'store_name', 'Keisha Mobil')) ?>.</span>
        </h1>

        <p>
            <?= e(setting($pdo, 'hero_description', 'Temukan mobil impian Anda dengan pilihan kendaraan berkualitas.')) ?>
        </p>

        <div class="d-flex flex-wrap gap-3 mt-4">

<a href="#mobil" class="btn btn-light rounded-pill px-4 py-3 fw-bold">
    <?= e(setting($pdo, 'hero_button', 'Lihat Katalog')) ?>
    <i class="fa-solid fa-arrow-right ms-2"></i>
</a>

<a href="https://www.instagram.com/mobil_bekas.aceh/"
   target="_blank"
   rel="noopener"
   class="btn rounded-pill px-4 py-3 text-white"
   style="background:linear-gradient(45deg,#feda75,#fa7e1e,#d62976,#962fbf,#4f5bd5);">
    <i class="fa-brands fa-instagram me-2"></i>
    Instagram
</a>

<a href="<?= e(wa_url($pdo, 'Halo, saya ingin konsultasi mobil.')) ?>"
   target="_blank"
   rel="noopener"
   class="btn btn-success rounded-pill px-4 py-3">
    <i class="fa-brands fa-whatsapp me-2"></i>
    Konsultasi WhatsApp
</a>


</div>
    </div>
</header>

<main>
<section class="container"><form class="search-box" method="get" action="<?= BASE_URL ?>"><div class="row g-3 align-items-end"><div class="col-md-9"><label class="form-label fw-bold" for="q">Cari mobil</label><input class="form-control form-control-lg" id="q" name="q" value="<?= e($q) ?>" placeholder="Cari merek, model, atau deskripsi..."></div><div class="col-md-3"><button class="btn btn-primary btn-lg w-100"><i class="fa-solid fa-magnifying-glass me-2"></i>Cari</button></div></div></form></section>
<section class="container py-5" id="mobil"><div class="text-center mb-4"><div class="kicker mb-2">Katalog kendaraan</div><h2 class="section-title display-6">Mobil pilihan untuk Anda</h2><p class="text-secondary">Tanyakan ketersediaan, kondisi, foto tambahan, dan harga terbaru kepada tim kami.</p></div>
<div class="row g-4" data-car-list>
<?php foreach($cars as $car): $carName = $car['brand'].' '.$car['model']; $message = 'Halo '.setting($pdo,'store_name','Keisha Mobil').', saya tertarik dengan '.$carName.'. Mohon info detail dan ketersediaannya.'; ?>
<div class="col-md-6 col-lg-4" data-car-item data-search="<?= e($carName.' '.$car['year'].' '.$car['fuel'].' '.$car['transmission']) ?>"><article class="car-card"><a href="<?= BASE_URL ?>detail.php?id=<?= (int)$car['id'] ?>" class="text-decoration-none"><div class="car-photo"><?php if($car['image'] && is_file(__DIR__.'/uploads/'.$car['image'])): ?><img src="<?= BASE_URL ?>uploads/<?= e($car['image']) ?>" alt="<?= e($carName) ?>"><?php else: ?><i class="fa-solid fa-car-side placeholder"></i><?php endif; ?></div></a><div class="card-body"><span class="pill"><?= e($car['status']) ?></span><h3 class="h5 car-title mt-3"><?= e($carName) ?></h3><div class="car-price mb-3"><?= e(rupiah($car['price'])) ?></div><div class="row g-2 car-spec mb-4"><div class="col-6"><i class="fa-regular fa-calendar"></i><?= e($car['year'] ?: 'Tahun -') ?></div><div class="col-6"><i class="fa-solid fa-gears"></i><?= e($car['transmission']) ?></div><div class="col-6"><i class="fa-solid fa-users"></i><?= (int)$car['seats'] ?> penumpang</div><div class="col-6"><i class="fa-solid fa-gas-pump"></i><?= e($car['fuel']) ?></div><div class="col-6"><i class="fa-solid fa-road"></i><?= number_format((int)$car['mileage'],0,',','.') ?> km</div><div class="col-6"><i class="fa-solid fa-palette"></i><?= e($car['color'] ?: '-') ?></div></div><div class="d-grid gap-2"><a href="<?= BASE_URL ?>detail.php?id=<?= (int)$car['id'] ?>" class="btn btn-outline-primary rounded-pill">Detail Mobil</a><a href="<?= e(wa_url($pdo,$message)) ?>" target="_blank" rel="noopener" class="btn btn-success rounded-pill"><i class="fa-brands fa-whatsapp me-2"></i>Tanya via WhatsApp</a></div></div></article></div>
<?php endforeach; ?>
</div>
<?php if(!$cars): ?><div class="text-center py-5"><i class="fa-solid fa-car-side fs-1 text-secondary"></i><h3 class="h5 mt-3">Belum ada mobil yang cocok</h3><p class="text-secondary">Coba kata kunci lain atau hubungi kami.</p></div><?php endif; ?>
</section>

<style>
    .testimonial-section {
        padding-top: 65px;
        padding-bottom: 75px;
    }

    .testimonial-wrapper {
        position: relative;
        padding: 0 58px;
    }

    .testimonial-track {
        display: flex;
        gap: 24px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding: 8px 2px 20px;
    }

    .testimonial-track::-webkit-scrollbar {
        display: none;
    }

    .testimonial-slide {
        flex: 0 0 calc(33.333% - 16px);
        min-width: 0;
        scroll-snap-align: start;
    }

    .testimonial-card {
        height: 100%;
        background: #fff;
        border: 1px solid #edf0f3;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(16,36,58,.07);
        transition: transform .3s ease, box-shadow .3s ease;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 35px rgba(16,36,58,.12);
    }

    .testimonial-photo {
        width: 100%;
        height: 230px;
        object-fit: contain;
        object-position: center;
        display: block;
        background: #f8f9fb;
    }

    .testimonial-info {
        padding: 24px;
    }

    .testimonial-name {
        color: #10243a;
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .testimonial-stars {
        color: #c49a58;
        font-size: 17px;
        letter-spacing: 2px;
        margin-bottom: 14px;
    }

    .testimonial-review {
        color: #687382;
        font-size: 14px;
        line-height: 1.8;
        margin: 0;
    }

    .testimonial-controls {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        transform: translateY(-50%);
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        z-index: 5;
    }

    .testimonial-arrow {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        border: 0;
        background: #10243a;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        pointer-events: auto;
        box-shadow: 0 5px 18px rgba(16,36,58,.22);
        transition: all .25s ease;
    }

    .testimonial-arrow:hover {
        background: #c49a58;
        transform: scale(1.1);
    }

    .testimonial-empty {
        text-align: center;
        color: #687382;
        padding: 30px;
    }

    @media (max-width: 991px) {
        .testimonial-slide {
            flex: 0 0 calc(50% - 12px);
        }
    }

    @media (max-width: 576px) {
        .testimonial-wrapper {
            padding: 0 38px;
        }

        .testimonial-slide {
            flex: 0 0 100%;
        }

        .testimonial-photo {
            height: 210px;
        }

        .testimonial-arrow {
            width: 36px;
            height: 36px;
            font-size: 13px;
        }

        .testimonial-info {
            padding: 19px;
        }
    }
</style>

<section class="container testimonial-section" id="testimoni">

<div class="text-center mb-5">
    <h2 class="section-title display-6">
        Testimoni Pelanggan
    </h2>
</div>

    <?php if (!empty($testimonials)): ?>

        <div class="testimonial-wrapper">

            <div class="testimonial-track" id="testimonialTrack">

                <?php foreach ($testimonials as $t): ?>

                    <?php
                        $photo = trim((string)($t['photo'] ?? ''));
                        $photoName = basename(str_replace('\\', '/', $photo));
                        $photoUrl = '';

                        if ($photoName !== '') {
                            foreach ([
                                'uploads/testimonials/' . $photoName,
                                'uploads/' . $photoName
                            ] as $candidate) {
                                if (is_file(__DIR__ . '/' . $candidate)) {
                                    $photoUrl = BASE_URL . $candidate;
                                    break;
                                }
                            }
                        }

                        $rating = max(0, min(5, (int)($t['rating'] ?? 5)));
                    ?>

                    <div class="testimonial-slide">

                        <div class="testimonial-card">

                            <?php if ($photoUrl !== ''): ?>

                                <img
                                    src="<?= e($photoUrl) ?>"
                                    alt="<?= e($t['customer_name']) ?>"
                                    class="testimonial-photo"
                                >

                            <?php else: ?>

                                <div class="d-flex align-items-center justify-content-center"
                                     style="height:230px;background:#f4f6f8;">
                                    <i class="fa-solid fa-user fs-1 text-secondary"></i>
                                </div>

                            <?php endif; ?>

                            <div class="testimonial-info">

                                <h3 class="testimonial-name">
                                    <?= e($t['customer_name']) ?>
                                </h3>

                                <div class="testimonial-stars">
                                    <?= str_repeat('★', $rating) ?>
                                    <span class="text-secondary small">
                                        <?= $rating ?>/5
                                    </span>
                                </div>

                                <p class="testimonial-review">
                                    "<?= nl2br(e($t['review'])) ?>"
                                </p>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <div class="testimonial-controls">

                <button
                    type="button"
                    class="testimonial-arrow"
                    onclick="moveTestimonial(-1)"
                    aria-label="Testimoni sebelumnya"
                >
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <button
                    type="button"
                    class="testimonial-arrow"
                    onclick="moveTestimonial(1)"
                    aria-label="Testimoni berikutnya"
                >
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

            </div>

        </div>

    <?php else: ?>

        <div class="testimonial-empty">
            Testimoni pelanggan akan segera hadir.
        </div>

    <?php endif; ?>

</section>

<script>
function moveTestimonial(direction) {
    const track = document.getElementById('testimonialTrack');

    if (!track) return;

    const card = track.querySelector('.testimonial-slide');

    if (!card) return;

    const gap = 24;
    const distance = card.getBoundingClientRect().width + gap;

    track.scrollBy({
        left: distance * direction,
        behavior: 'smooth'
    });
}
</script>

<section class="py-5" id="tentang" style="background:var(--pale)"><div class="container"><div class="row align-items-center g-4"><div class="col-lg-5"><div class="kicker mb-2">Tentang kami</div><h2 class="section-title display-6">Mencari mobil jadi lebih mudah.</h2><p class="text-secondary"><?= nl2br(e(setting($pdo,'about','Keisha Mobil membantu Anda menemukan kendaraan.'))) ?></p><a class="btn btn-primary rounded-pill px-4" href="<?= e(wa_url($pdo,'Halo, saya ingin bertanya tentang Keisha Mobil.')) ?>" target="_blank" rel="noopener">Hubungi tim kami</a></div><div class="col-lg-7"><div class="row g-3"><div class="col-sm-6"><div class="benefit"><div class="benefit-icon"><i class="fa-solid fa-car"></i></div><h3 class="h5 fw-bold">Pilihan kendaraan</h3><p class="text-secondary mb-0">Lihat katalog mobil yang tersedia.</p></div></div><div class="col-sm-6"><div class="benefit"><div class="benefit-icon"><i class="fa-solid fa-comments"></i></div><h3 class="h5 fw-bold">Kontak langsung</h3><p class="text-secondary mb-0">Diskusikan kebutuhan Anda melalui WhatsApp.</p></div></div><div class="col-sm-6"><div class="benefit"><div class="benefit-icon"><i class="fa-solid fa-clipboard-check"></i></div><h3 class="h5 fw-bold">Periksa detail</h3><p class="text-secondary mb-0">Konfirmasi kondisi dan dokumen sebelum transaksi.</p></div></div><div class="col-sm-6"><div class="benefit"><div class="benefit-icon"><i class="fa-solid fa-handshake"></i></div><h3 class="h5 fw-bold">Konsultasi</h3><p class="text-secondary mb-0">Tanyakan harga dan jadwal melihat mobil.</p></div></div></div></div></div></div></section>





<section class="container py-5" id="kontak"><div class="contact-panel"><div class="row g-4 align-items-center"><div class="col-lg-7"><div class="kicker mb-2" style="color:var(--gold)">Kontak & lokasi</div><h2 class="display-6 fw-bold">Yuk, temukan mobil Anda.</h2><p>Hubungi kami untuk informasi stok, harga terbaru, dan jadwal kunjungan.</p><p><i class="fa-brands fa-whatsapp me-2 text-success"></i><strong>WhatsApp:</strong> <?= e(setting($pdo,'whatsapp','6281234567890')) ?></p><p><i class="fa-solid fa-location-dot me-2" style="color:var(--gold)"></i><strong>Alamat:</strong> <?= e(setting($pdo,'address','Alamat showroom belum diisi')) ?></p><p><i class="fa-regular fa-clock me-2" style="color:var(--gold)"></i><strong>Jam buka:</strong> <?= e(setting($pdo,'hours','Jam operasional belum diisi')) ?></p></div><div class="col-lg-5"><div class="bg-white text-dark rounded-4 p-4"><h3 class="h5 fw-bold">Ada yang ingin ditanyakan?</h3><p class="text-secondary">Mulai percakapan langsung dengan tim kami.</p><a href="<?= e(wa_url($pdo,'Halo '.setting($pdo,'store_name','Keisha Mobil').', saya ingin bertanya.')) ?>" target="_blank" rel="noopener" class="btn btn-success rounded-pill w-100 py-3"><i class="fa-brands fa-whatsapp me-2"></i>Chat WhatsApp</a></div></div></div></div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
