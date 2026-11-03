<footer class="footer mt-5 py-4"><div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
<div><strong><?= e(setting($pdo, 'store_name', 'Keisha Mobil')) ?></strong>
<div class="small text-white-50"><?= e(setting($pdo, 'tagline', 'Marketplace Mobil')) ?></div></div>
<div class="small text-white-50">© <?= date('Y') ?> <?= e(setting($pdo, 'store_name', 'Keisha Mobil')) ?></div>
<a class="text-white text-decoration-none" href="<?= e(wa_url($pdo)) ?>" target="_blank" rel="noopener">
<i class="fa-brands fa-whatsapp me-1"></i>Hubungi kami</a></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/app.js"></script>
</body></html>
