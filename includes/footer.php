
<?php
$footerStoreName = setting($pdo, 'store_name', 'Keisha Mobil');
$footerAddress = setting($pdo, 'address', 'Jl. Dr. Mr. Mohd Hasan, Batoh, Kec. Lueng Bata, Kota Banda Aceh, Aceh, 23245');
$footerWhatsapp = wa_url($pdo);

// Ganti dengan username Instagram Keisha Mobil
$instagramUrl = 'https://www.instagram.com/mobil_bekas.aceh/';
?>

<style>
.km-footer {
    background: #10243a;
    color: #dce4ed;
    padding: 45px 0 18px;
    margin-top: 35px;
}

.km-footer .footer-brand {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.km-footer h5 {
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 18px;
}

.km-footer p {
    color: #b9c5d2;
    font-size: 13px;
    line-height: 1.8;
}

.km-footer .footer-address {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: #dce4ed;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.8;
    margin-top: 15px;
}

.km-footer .footer-address i {
    color: #c49a58;
    font-size: 17px;
    margin-top: 4px;
}

.km-footer .footer-link {
    display: block;
    color: #c5cfda;
    text-decoration: none;
    font-size: 13px;
    margin-bottom: 11px;
    transition: .2s;
}

.km-footer .footer-link:hover {
    color: #c49a58;
    padding-left: 4px;
}

/* Tombol WhatsApp */
.km-footer .footer-wa {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    background: #25D366;
    color: #ffffff;
    padding: 11px 17px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    margin: 5px 5px 0 0;
    transition: .2s;
}

.km-footer .footer-wa:hover {
    background: #1da851;
    color: #ffffff;
    transform: translateY(-2px);
}

/* Tombol Instagram */
.km-footer .footer-instagram {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    background: linear-gradient(
        45deg,
        #feda75,
        #fa7e1e,
        #d62976,
        #962fbf,
        #4f5bd5
    );
    color: #ffffff;
    padding: 11px 17px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 700;
    margin: 5px 5px 0 0;
    transition: .2s;
}

.km-footer .footer-instagram:hover {
    filter: brightness(1.1);
    color: #ffffff;
    transform: translateY(-2px);
}

.km-footer .footer-divider {
    border-color: rgba(255,255,255,.13);
    margin: 28px 0 18px;
}

.km-footer .developer-label {
    color: #aebbc9;
    font-size: 10px;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 5px;
}

.km-footer .developer-name {
    color: #d8b477;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 1.2px;
    line-height: 1.5;
    margin: 0;
}

.km-footer .copyright {
    color: #91a0b0;
    font-size: 11px;
    margin-top: 12px;
}

@media (max-width: 767px) {
    .km-footer {
        padding: 35px 0 15px;
    }

    .km-footer .footer-brand {
        font-size: 20px;
    }

    .km-footer .developer-name {
        font-size: 14px;
        letter-spacing: 1px;
    }

    .km-footer .footer-wa,
    .km-footer .footer-instagram {
        padding: 10px 13px;
    }
}
</style>

<footer class="km-footer">
    <div class="container">
        <div class="row g-4">

            <!-- Identitas Showroom -->
            <div class="col-lg-5 col-md-6">
                <div class="footer-brand">
                    <?= htmlspecialchars($footerStoreName) ?>
                </div>

                <p>
                    Marketplace mobil pilihan berkualitas.
                    Temukan kendaraan terbaik untuk menemani
                    perjalanan baru Anda bersama Keisha Mobil.
                </p>

                <?php if (!empty($footerAddress)): ?>
                    <div class="footer-address">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>
                            <?= htmlspecialchars($footerAddress) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Navigasi -->
            <div class="col-lg-3 col-md-6">
                <h5>Menu</h5>

                <a class="footer-link" href="<?= BASE_URL ?>#mobil">
                    Katalog Mobil
                </a>

                <a class="footer-link" href="<?= BASE_URL ?>#tentang">
                    Tentang Kami
                </a>

                <a class="footer-link" href="<?= BASE_URL ?>#testimoni">
                    Testimoni Pelanggan
                </a>

                <a class="footer-link" href="<?= BASE_URL ?>#kontak">
                    Hubungi Kami
                </a>
            </div>

            <!-- Kontak -->
            <div class="col-lg-4 col-md-6">
                <h5>Hubungi Kami</h5>

                <p>
                    Ikuti media sosial kami dan hubungi
                    tim Keisha Mobil untuk informasi lebih lanjut.
                </p>

                <a href="<?= htmlspecialchars($footerWhatsapp) ?>"
                   target="_blank"
                   rel="noopener"
                   class="footer-wa">
                    <i class="fab fa-whatsapp"></i>
                    WhatsApp
                </a>

                <a href="<?= htmlspecialchars($instagramUrl) ?>"
                   target="_blank"
                   rel="noopener"
                   class="footer-instagram">
                    <i class="fab fa-instagram"></i>
                    Instagram
                </a>
            </div>

        </div>

        <hr class="footer-divider">

        <!-- Developer -->
        <div class="text-center">
            <div class="developer-label">
                Designed &amp; Developed By
            </div>

            <p class="developer-name">
                TEUKU MUHAMMAD AMJAD KANA
            </p>

            <div class="copyright">
                &copy; <?= date('Y') ?>
                <?= htmlspecialchars($footerStoreName) ?>.
                All Rights Reserved.
            </div>
        </div>
    </div>
</footer>
