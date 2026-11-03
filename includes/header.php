<?php
$storeName = setting($pdo, 'store_name', 'Keisha Mobil');
$pageTitle = $pageTitle ?? $storeName;
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e(setting($pdo, 'tagline', 'Marketplace mobil pilihan')) ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
<div class="container py-2">
<a class="navbar-brand fw-bold d-flex align-items-center" href="<?= BASE_URL ?>">
<img src="<?= BASE_URL ?>assets/img/logo.png"
     alt="Logo Keisha Mobil"
     style="width: 58px !important; height: 58px !important; object-fit: contain; flex-shrink: 0;"
     class="me-2">
<span><?= e($storeName) ?><small class="d-block text-secondary fw-normal small"><?= e(setting($pdo, 'tagline', 'Marketplace Mobil Pilihan Anda')) ?></small></span></a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-label="Buka navigasi"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="navMain"><ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>#mobil">Katalog Mobil</a></li>
<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>#tentang">Tentang</a></li>
<li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>#kontak">Kontak</a></li>
<li class="nav-item"><a class="btn btn-success rounded-pill px-4" target="_blank" rel="noopener" href="<?= e(wa_url($pdo, 'Halo ' . $storeName . ', saya ingin bertanya tentang mobil.')) ?>">
<i class="fa-brands fa-whatsapp me-2"></i>WhatsApp</a></li>
</ul></div></div></nav>
