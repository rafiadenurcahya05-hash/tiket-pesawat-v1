-- =============================================
-- Database: db_wisata
-- Versi: 2.0 (dengan Role Admin & User + BPS API)
-- =============================================

CREATE DATABASE IF NOT EXISTS db_wisata DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_wisata;

-- =============================================
-- Tabel: users (dengan kolom role)
-- =============================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(100) NOT NULL,
  `username`   VARCHAR(50)  DEFAULT NULL,
  `email`      VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `role`       ENUM('admin','user') NOT NULL DEFAULT 'user',
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Data Default: Admin & User
-- Password admin : admin123
-- Password user  : user123
-- =============================================
INSERT INTO `users` (`nama`, `username`, `email`, `password`, `role`) VALUES
('Administrator', 'admin', 'admin@ddeltiket.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso',  'budi',  'budi@email.com',       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');
-- Catatan: password di atas adalah hash dari 'password' (default Laravel hash)
-- Ganti dengan hash asli menggunakan: password_hash('admin123', PASSWORD_DEFAULT)

-- =============================================
-- Tabel: destinasi
-- =============================================
DROP TABLE IF EXISTS `destinasi`;
CREATE TABLE `destinasi` (
  `id`          INT(11)        NOT NULL AUTO_INCREMENT,
  `nama`        VARCHAR(200)   NOT NULL,
  `lokasi`      VARCHAR(200)   NOT NULL,
  `provinsi`    VARCHAR(100)   DEFAULT NULL,
  `deskripsi`   TEXT           DEFAULT NULL,
  `harga`       DECIMAL(12,0)  NOT NULL DEFAULT 0,
  `rating`      DECIMAL(3,1)   NOT NULL DEFAULT 0.0,
  `imgUrl`      VARCHAR(500)   DEFAULT NULL,
  `bps_id`      VARCHAR(50)    DEFAULT NULL COMMENT 'ID dari BPS API',
  `kategori`    VARCHAR(100)   DEFAULT NULL,
  `created_at`  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_provinsi` (`provinsi`),
  KEY `idx_bps_id` (`bps_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- Data Sample Destinasi
-- =============================================
INSERT INTO `destinasi` (`nama`, `lokasi`, `provinsi`, `deskripsi`, `harga`, `rating`, `imgUrl`, `kategori`) VALUES
('Candi Borobudur',       'Magelang, Jawa Tengah',     'Jawa Tengah',    'Candi Buddha terbesar di dunia, warisan UNESCO yang megah.',           50000,  4.9, 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Borobudur-Nothwest-view.jpg/1280px-Borobudur-Nothwest-view.jpg', 'Budaya'),
('Raja Ampat',            'Papua Barat',                'Papua Barat',    'Surga bawah laut dengan ribuan pulau dan keanekaragaman hayati laut.', 250000, 5.0, 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Raja_Ampat_Islands.jpg/1280px-Raja_Ampat_Islands.jpg',          'Bahari'),
('Gunung Bromo',          'Probolinggo, Jawa Timur',   'Jawa Timur',     'Gunung berapi aktif dengan pemandangan sunrise yang spektakuler.',     35000,  4.8, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Bromo_tengger_semeru_from_penanjakan.jpg/1280px-Bromo_tengger_semeru_from_penanjakan.jpg', 'Alam'),
('Pantai Kuta Lombok',    'Lombok, NTB',                'NTB',            'Pantai eksotis dengan pasir putih dan ombak yang sempurna untuk surfing.', 20000, 4.7, 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1e/Kuta_Beach%2C_Lombok.JPG/1280px-Kuta_Beach%2C_Lombok.JPG', 'Bahari'),
('Museum Nasional',       'Jakarta Pusat, DKI Jakarta', 'DKI Jakarta',    'Museum terbesar di Indonesia dengan koleksi artefak budaya nusantara.', 15000, 4.5, 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Museum_Nasional_Indonesia.JPG/1280px-Museum_Nasional_Indonesia.JPG', 'Budaya'),
('Danau Toba',            'Sumatera Utara',             'Sumatera Utara', 'Danau vulkanik terbesar di dunia dengan Pulau Samosir di tengahnya.', 30000, 4.8, 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/Lake_Toba%2C_North_Sumatra%2C_Indonesia.jpg/1280px-Lake_Toba%2C_North_Sumatra%2C_Indonesia.jpg', 'Alam');

-- =============================================
-- Selesai
-- =============================================
