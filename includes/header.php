
<?php
$storeName = setting($pdo, 'store_name', 'Keisha Mobil');
$pageTitle = $pageTitle ?? $storeName;

$instagramUrl = 'https://www.instagram.com/mobil_bekas.aceh/';
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e(setting($pdo, 'tagline', 'Marketplace mobil pilihan')) ?>">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>assets/style.css" rel="stylesheet">

<style>
/* HEADER HITAM */
.keisha-navbar {
    background: #111111 !important;
    border-bottom: 1px solid rgba(255,255,255,.10);
}

.keisha-navbar .navbar-brand,
.keisha-navbar .navbar-brand span {
    color: #ffffff !important;
}

.keisha-navbar .navbar-brand small {
    color: #b8b8b8 !important;
}

.keisha-navbar .nav-link {
    color: #eeeeee !important;
    font-weight: 500;
    transition: .2s;
}

.keisha-navbar .nav-link:hover {
    color: #c49a58 !important;
}

.keisha-navbar .navbar-toggler {
    border-color: rgba(255,255,255,.4);
}

.keisha-navbar .navbar-toggler-icon {
    filter: invert(1);
}

/* INSTAGRAM */
.btn-instagram {
    background: linear-gradient(
        45deg,
        #feda75,
        #fa7e1e,
        #d62976,
        #962fbf,
        #4f5bd5
    );
    color: #fff !important;
    border: none;
    transition: .2s;
}

.btn-instagram:hover {
    filter: brightness(1.1);
    color: #fff !important;
}

/* WHATSAPP */
.btn-whatsapp-header {
    background: #25D366;
    color: #fff !important;
    border: none;
    transition: .2s;
}

.btn-whatsapp-header:hover {
    background: #1da851;
    color: #fff !important;
}

@media (max-width: 991px) {
    .keisha-navbar .navbar-nav {
        padding-top: 12px;
        align-items: flex-start !important;
    }

    .keisha-navbar .nav-item {
        margin-top: 6px;
    }

    .keisha-navbar .nav-link {
        padding-left: 4px;
    }
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top shadow-sm keisha-navbar">
<div class="container py-2">

<a class="navbar-brand fw-bold d-flex align-items-center" href="<?= BASE_URL ?>">
    <img src="<?= BASE_URL ?>assets/img/logo.png"
         alt="Logo Keisha Mobil"
         style="width:68px !important;height:58px !important;object-fit:contain;flex-shrink:0;"
         class="me-2">

    <span>
        <?= e($storeName) ?>
        <small class="d-block fw-normal small">
            <?= e(setting($pdo, 'tagline', 'Marketplace Mobil Pilihan Anda')) ?>
        </small>
    </span>
</a>

<button class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navMain"
        aria-label="Buka navigasi">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navMain">
<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>#mobil">Katalog Mobil</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>#tentang">Tentang</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= BASE_URL ?>#kontak">Kontak</a>
    </li>

    <li class="nav-item">
        <a class="btn btn-instagram rounded-pill px-4"
           href="<?= e($instagramUrl) ?>"
           target="_blank" rel="noopener">
            <i class="fa-brands fa-instagram me-2"></i>
            Instagram
        </a>
    </li>

    <li class="nav-item">
        <a class="btn btn-whatsapp-header rounded-pill px-4"
           target="_blank" rel="noopener"
           href="<?= e(wa_url($pdo, 'Halo ' . $storeName . ', saya ingin bertanya tentang mobil.')) ?>">
            <i class="fa-brands fa-whatsapp me-2"></i>
            WhatsApp
        </a>
    </li>

</ul>
</div>
</div>
</nav>
