CREATE DATABASE IF NOT EXISTS keisha_mobil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE keisha_mobil;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS settings (
  setting_key VARCHAR(80) PRIMARY KEY,
  setting_value TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cars (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  brand VARCHAR(100) NOT NULL,
  model VARCHAR(150) NOT NULL,
  slug VARCHAR(240) NOT NULL,
  price BIGINT UNSIGNED DEFAULT 0,
  year SMALLINT UNSIGNED DEFAULT NULL,
  transmission ENUM('Automatic','Manual','CVT','Lainnya') DEFAULT 'Automatic',
  fuel ENUM('Bensin','Diesel','Hybrid','Listrik','Lainnya') DEFAULT 'Bensin',
  seats TINYINT UNSIGNED DEFAULT 5,
  mileage INT UNSIGNED DEFAULT 0,
  color VARCHAR(80) DEFAULT '',
  description TEXT,
  image VARCHAR(255) DEFAULT NULL,
  status ENUM('Tersedia','Terjual','Disembunyikan') NOT NULL DEFAULT 'Tersedia',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_brand_model (brand, model)
) ENGINE=InnoDB;

INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('store_name', 'Keisha Mobil'),
('tagline', 'Marketplace Mobil Pilihan Anda'),
('whatsapp', '6281234567890'),
('address', 'Masukkan alamat showroom Keisha Mobil'),
('hours', 'Senin - Sabtu, 09.00 - 17.00'),
('about', 'Keisha Mobil membantu Anda menemukan kendaraan yang sesuai kebutuhan. Hubungi kami untuk informasi stok, harga, dan jadwal melihat mobil.');

-- Akun admin default: admin / KeishaMobil!2026
-- Hash ini dibuat untuk password KeishaMobil!2026.
INSERT IGNORE INTO admins (username, password_hash)
VALUES ('admin', '$2y$12$VVqOYu2MMjr3/w3Mo/pUreYvMjeunpEN2DtI05DHZkYJL68LJIvEm');

-- Jika hash default di atas tidak cocok di versi PHP Anda, buka /setup_admin.php
-- untuk membuat ulang akun admin dengan hash password yang valid, lalu hapus file setup_admin.php.

INSERT INTO cars (brand, model, slug, price, year, transmission, fuel, seats, mileage, color, description, status)
SELECT 'Toyota', 'Avanza', 'toyota-avanza', 185000000, 2021, 'Automatic', 'Bensin', 7, 42000, 'Putih', 'Contoh data mobil. Silakan ganti dengan detail kendaraan asli.', 'Tersedia'
WHERE NOT EXISTS (SELECT 1 FROM cars WHERE slug='toyota-avanza');
INSERT INTO cars (brand, model, slug, price, year, transmission, fuel, seats, mileage, color, description, status)
SELECT 'Honda', 'Brio', 'honda-brio', 165000000, 2022, 'Automatic', 'Bensin', 5, 25000, 'Merah', 'Contoh data mobil. Silakan ganti dengan detail kendaraan asli.', 'Tersedia'
WHERE NOT EXISTS (SELECT 1 FROM cars WHERE slug='honda-brio');
INSERT INTO cars (brand, model, slug, price, year, transmission, fuel, seats, mileage, color, description, status)
SELECT 'Mitsubishi', 'Pajero Sport', 'mitsubishi-pajero-sport', 425000000, 2020, 'Automatic', 'Diesel', 7, 53000, 'Hitam', 'Contoh data mobil. Silakan ganti dengan detail kendaraan asli.', 'Tersedia'
WHERE NOT EXISTS (SELECT 1 FROM cars WHERE slug='mitsubishi-pajero-sport');
