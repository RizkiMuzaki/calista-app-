-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 25, 2026 at 12:07 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calistaadmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `anaks`
--

CREATE TABLE `anaks` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_anak` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `limit_detik` int NOT NULL DEFAULT '3600',
  `sisa_detik` int NOT NULL DEFAULT '3600',
  `tanggal_reset` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `timer_started_at` timestamp NULL DEFAULT NULL,
  `timer_last_updated` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anaks`
--

INSERT INTO `anaks` (`id`, `user_id`, `nama_anak`, `tanggal_lahir`, `limit_detik`, `sisa_detik`, `tanggal_reset`, `is_active`, `timer_started_at`, `timer_last_updated`, `created_at`, `updated_at`) VALUES
(1, 4, 'Anara Anindya', '2026-01-18', 4999, 2176, '2026-01-24', 1, '2026-01-24 09:02:20', '2026-01-24 09:49:23', '2026-01-18 00:07:38', '2026-01-24 09:49:23'),
(2, 1, 'Pau', '2026-01-23', 1000, 540, '2026-01-23', 1, '2026-01-23 00:05:27', '2026-01-23 00:13:07', '2026-01-22 23:52:53', '2026-01-23 00:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `artikels`
--

CREATE TABLE `artikels` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `artikels`
--

INSERT INTO `artikels` (`id`, `title`, `slug`, `content`, `image`, `is_published`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Pentingnya Calistung untuk Anak Usia Dini', 'pentingnya-calistung-untuk-anak-usia-dini', '<p>Calistung (membaca, menulis, dan berhitung) merupakan fondasi penting dalam perkembangan kognitif anak usia dini. Pada fase ini, otak anak berkembang sangat pesat dan mudah menyerap berbagai stimulasi. Pengenalan calistung yang tepat akan membantu anak membangun dasar kemampuan akademik di masa depan.</p><p>Membaca menjadi pintu awal anak mengenal dunia. Melalui membaca, anak belajar mengenali huruf, bunyi, dan makna kata. Aktivitas membaca yang dilakukan secara menyenangkan, seperti melalui cerita bergambar, akan membuat anak lebih tertarik dan tidak merasa tertekan.</p><p>Menulis membantu anak melatih koordinasi motorik halus. Saat anak belajar memegang pensil dan menulis huruf, mereka tidak hanya belajar bentuk huruf, tetapi juga melatih kesabaran dan konsentrasi. Proses ini penting untuk membangun kepercayaan diri anak.</p><p>Berhitung memperkenalkan konsep logika dan pemecahan masalah. Anak mulai memahami angka, jumlah, dan perbandingan sederhana. Dengan metode bermain, berhitung bisa menjadi aktivitas yang menyenangkan dan menantang.</p><p>Calistung sebaiknya tidak diajarkan secara kaku. Anak usia dini lebih mudah belajar melalui permainan, lagu, dan aktivitas interaktif. Pendekatan ini membuat anak merasa belajar adalah sesuatu yang menyenangkan, bukan beban.</p><p>Peran orang tua dan pendidik sangat penting dalam proses ini. Dukungan, pujian, dan kesabaran akan membuat anak merasa aman untuk mencoba dan tidak takut salah. Lingkungan belajar yang positif akan mempercepat perkembangan anak.</p><p>Seiring perkembangan teknologi, pembelajaran calistung kini dapat dipadukan dengan media digital. Aplikasi interaktif mampu memberikan pengalaman belajar yang lebih variatif dan menarik bagi anak.</p><p>Dengan pendekatan yang tepat, calistung dapat menjadi aktivitas yang disukai anak. Calista belajar jadi menyenangkan bersama AI.</p>', 'articles/01KFD92Z8PH9485S5V7FSCB6CC.png', 1, 4, '2026-01-20 01:42:06', '2026-01-20 01:42:06'),
(2, 'Metode Belajar Calistung yang Menyenangkan untuk Anak', 'metode-belajar-calistung-yang-menyenangkan-untuk-anak', '<p>Belajar calistung tidak harus selalu dilakukan dengan buku dan papan tulis. Anak usia dini memiliki dunia yang penuh imajinasi, sehingga metode belajar yang menyenangkan sangat dibutuhkan agar mereka tidak mudah bosan.</p><p>Salah satu metode efektif adalah belajar sambil bermain. Permainan huruf, kartu angka, dan puzzle dapat membantu anak mengenal konsep dasar calistung tanpa merasa sedang belajar. Anak akan belajar secara alami melalui aktivitas tersebut.</p><p>Metode bercerita juga sangat efektif untuk melatih kemampuan membaca dan mendengar. Cerita dengan gambar menarik dan bahasa sederhana akan membuat anak lebih mudah memahami isi cerita dan mengenal kosakata baru.</p><p>Bernyanyi dan bergerak dapat membantu anak menghafal huruf dan angka. Lagu-lagu edukatif dengan gerakan sederhana membuat anak lebih aktif dan terlibat secara emosional dalam pembelajaran.</p><p>Menulis bisa dikenalkan melalui aktivitas menggambar dan mewarnai. Anak dapat belajar menulis huruf dengan cara menebalkan garis atau meniru bentuk huruf secara perlahan.</p><p>Dalam berhitung, anak dapat diajak menghitung benda di sekitar, seperti mainan atau buah. Cara ini membantu anak memahami bahwa angka memiliki makna dalam kehidupan sehari-hari.</p><p>Penggunaan media digital interaktif juga menjadi alternatif menarik. Aplikasi belajar dengan animasi dan suara dapat meningkatkan minat anak untuk belajar mandiri.</p><p>Dengan metode yang tepat, anak akan merasa senang dan termotivasi. Calista belajar jadi menyenangkan bersama AI.</p>', 'articles/01KFD97X3FY91A73SS2DWT39H1.png', 1, 4, '2026-01-20 01:44:48', '2026-01-20 01:44:48'),
(3, 'Peran Orang Tua dalam Mendampingi Calistung Anak', 'peran-orang-tua-dalam-mendampingi-calistung-anak', '<p>Orang tua memiliki peran utama dalam mendampingi proses belajar calistung anak usia dini. Pendampingan yang tepat akan membantu anak merasa lebih percaya diri dan nyaman saat belajar.</p><p>Lingkungan rumah yang mendukung sangat berpengaruh. Orang tua dapat menyediakan buku bacaan anak, alat tulis warna-warni, dan permainan edukatif yang merangsang minat belajar.</p><p>Konsistensi juga menjadi kunci. Meluangkan waktu secara rutin untuk menemani anak belajar akan membangun kebiasaan positif. Anak akan memahami bahwa belajar adalah bagian dari aktivitas sehari-hari.</p><p>Orang tua perlu bersikap sabar dan tidak memaksakan target. Setiap anak memiliki kecepatan belajar yang berbeda, sehingga penting untuk menghargai proses, bukan hanya hasil.</p><p>Memberikan pujian atas usaha anak akan meningkatkan motivasi belajar. Pujian sederhana dapat membuat anak merasa dihargai dan lebih semangat mencoba hal baru.</p><p>Orang tua juga dapat memanfaatkan teknologi sebagai media belajar. Dengan pengawasan yang baik, aplikasi edukatif dapat menjadi sarana belajar yang efektif.</p><p>Komunikasi antara orang tua dan anak sangat penting. Dengan berdialog, orang tua dapat memahami kesulitan anak dan membantu mencari solusi bersama.</p><p>Dengan pendampingan yang penuh kasih, proses belajar akan terasa ringan. Calista belajar jadi menyenangkan bersama AI.</p>', 'articles/01KFD9BCBB0XP9P3W95W7CRP38.png', 1, 4, '2026-01-20 01:46:42', '2026-01-20 01:46:42'),
(4, 'Tantangan dan Solusi dalam Mengajarkan Calistung', 'tantangan-dan-solusi-dalam-mengajarkan-calistung', '<p>Mengajarkan calistung pada anak usia dini tentu memiliki tantangan tersendiri. Salah satu tantangan utama adalah menjaga fokus dan minat anak yang mudah berubah.</p><p>Anak sering kali merasa bosan jika metode belajar monoton. Oleh karena itu, variasi metode sangat dibutuhkan agar anak tetap antusias mengikuti pembelajaran.</p><p>Tantangan lain adalah perbedaan kemampuan setiap anak. Beberapa anak cepat memahami, sementara yang lain membutuhkan waktu lebih lama. Hal ini memerlukan kesabaran ekstra dari pendidik dan orang tua.</p><p>Solusi dari tantangan tersebut adalah menciptakan suasana belajar yang fleksibel. Anak diberi kebebasan untuk bereksplorasi dan belajar sesuai dengan gaya mereka masing-masing.</p><p>Menggunakan media visual dan audio dapat membantu anak lebih mudah memahami materi. Gambar, animasi, dan suara membuat pembelajaran lebih hidup.</p><p>Teknologi berbasis AI dapat membantu menyesuaikan materi dengan kemampuan anak. Dengan pendekatan personal, anak dapat belajar sesuai dengan tingkat perkembangannya.</p><p>Kolaborasi antara orang tua dan pendidik juga penting untuk mengatasi hambatan belajar. Dengan komunikasi yang baik, solusi dapat ditemukan lebih cepat.</p><p>Dengan solusi yang tepat, tantangan dapat diatasi dengan baik. Calista belajar jadi menyenangkan bersama AI.</p>', 'articles/01KFD9EWE4YC092JRHZAY71FD6.png', 1, 4, '2026-01-20 01:48:36', '2026-01-20 01:48:36'),
(5, 'Calistung Berbasis AI sebagai Inovasi Pembelajaran Anak', 'calistung-berbasis-ai-sebagai-inovasi-pembelajaran-anak', '<p>Perkembangan teknologi membawa inovasi baru dalam dunia pendidikan, termasuk pembelajaran calistung untuk anak usia dini. AI hadir sebagai solusi pembelajaran yang adaptif dan interaktif.</p><p>Calistung berbasis AI memungkinkan materi disesuaikan dengan kemampuan anak. Sistem dapat mengenali kekuatan dan kelemahan anak, lalu menyesuaikan tingkat kesulitan materi.</p><p>Interaksi dengan AI membuat anak merasa seperti belajar dengan teman. Karakter virtual yang ramah dapat meningkatkan kenyamanan dan minat belajar anak.</p><p>AI juga mampu memberikan umpan balik secara langsung. Anak dapat mengetahui kesalahan dan belajar memperbaikinya tanpa merasa takut atau tertekan.</p><p>Pembelajaran berbasis AI dapat dilakukan kapan saja dan di mana saja. Fleksibilitas ini sangat membantu orang tua dalam mendampingi anak belajar di rumah.</p><p>Konten visual dan animasi yang menarik membuat anak lebih fokus. Warna cerah dan suara interaktif menciptakan pengalaman belajar yang menyenangkan.</p><p>Dengan pemanfaatan teknologi yang tepat, calistung tidak lagi terasa sulit. Anak belajar dengan cara yang sesuai dengan zamannya.</p><p>Inilah masa depan pembelajaran anak usia dini. Calista belajar jadi menyenangkan bersama AI.</p>', 'articles/01KFD9HMYTKQJCZBJSMEFY7XSM.png', 1, 4, '2026-01-20 01:50:07', '2026-01-20 01:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` bigint UNSIGNED NOT NULL,
  `module_id` bigint UNSIGNED NOT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` enum('cerita','membaca') COLLATE utf8mb4_unicode_ci DEFAULT 'cerita'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `module_id`, `cover_image`, `title`, `slug`, `order`, `is_active`, `is_premium`, `created_at`, `updated_at`, `description`, `type`) VALUES
(1, 4, 'books/01KEM1HMEDSH5M2KZ4DPXF4KYT.png', 'Makanan Tradisional', 'makanan-tradisional', 1, 1, 0, '2026-01-10 06:29:17', '2026-01-20 23:13:02', NULL, 'membaca'),
(2, 5, 'books/01KF3R3XMYN7KKTTRSSHCCPZH9.png', 'Legenda Pulo Kemaro Palembang', 'legenda-pulo-kemaro-palembang', 1, 1, 1, '2026-01-16 08:52:21', '2026-01-23 18:32:56', NULL, 'cerita');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-voice_history_4_general', 'a:20:{i:0;a:4:{s:9:\"user_text\";s:21:\"Teks tidak terdeteksi\";s:11:\"ai_response\";s:25:\"Respons AI tidak tersedia\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 13:38:10\";}i:1;a:4:{s:9:\"user_text\";s:1:\"t\";s:11:\"ai_response\";s:86:\"Halo teman! Ayo kita belajar bersama, seru lho! Apa yang ingin kamu pelajari hari ini?\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 13:39:30\";}i:2;a:4:{s:9:\"user_text\";s:14:\"enggak ada sih\";s:11:\"ai_response\";s:149:\"Tidak apa-apa, kita bisa mencari sesuatu yang menyenangkan untuk dipelajari bersama! Bagaimana kalau kita bermain \"Tebak Gambar\" atau \"Mencari Kata\"?\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 13:39:51\";}i:3;a:4:{s:9:\"user_text\";s:21:\"Teks tidak terdeteksi\";s:11:\"ai_response\";s:25:\"Respons AI tidak tersedia\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 13:43:50\";}i:4;a:4:{s:9:\"user_text\";s:13:\"tes tes tes 1\";s:11:\"ai_response\";s:105:\"Halo teman! Senang sekali kita bisa belajar bersama hari ini! Apa yang ingin kita pelajari hari ini, sih?\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 13:45:27\";}i:5;a:4:{s:9:\"user_text\";s:21:\"Teks tidak terdeteksi\";s:11:\"ai_response\";s:25:\"Respons AI tidak tersedia\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 13:54:42\";}i:6;a:4:{s:9:\"user_text\";s:21:\"Teks tidak terdeteksi\";s:11:\"ai_response\";s:25:\"Respons AI tidak tersedia\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 13:59:01\";}i:7;a:4:{s:9:\"user_text\";s:9:\"halo halo\";s:11:\"ai_response\";s:150:\"Halo teman! Senang sekali bertemu denganmu! Apakah kamu siap untuk belajar dan bermain tentang CALISTUNG sambil menjelajahi budaya Indonesia yang kaya\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:03:49\";}i:8;a:4:{s:9:\"user_text\";s:4:\"halo\";s:11:\"ai_response\";s:150:\"Halo! Senang sekali bertemu denganmu! Apakah kamu siap untuk bermain sambil belajar tentang huruf, angka, dan budaya Indonesia yang kaya? Mari kita mu\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:09:32\";}i:9;a:4:{s:9:\"user_text\";s:33:\"Halo Ceritakan saya tentang tempe\";s:11:\"ai_response\";s:149:\"Tempe adalah makanan tradisional Indonesia yang lezat! Mari kita hitung bersama, jika kita memiliki 5 potong tempe dan ibu menambahkan 2 potong lagi,\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:10:05\";}i:10;a:4:{s:9:\"user_text\";s:28:\"pempek itu asalnya dari mana\";s:11:\"ai_response\";s:149:\"Pempek adalah makanan khas dari Palembang, Sumatera Selatan! Mari kita membaca tentang pempek: P-E-M-P-E-K. Kamu bisa membacanya dengan benar! Pempek\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:11:21\";}i:11;a:4:{s:9:\"user_text\";s:8:\"tes halo\";s:11:\"ai_response\";s:150:\"Halo teman! Senang sekali bertemu denganmu! Mari kita belajar bersama tentang huruf, angka, dan cerita-cerita menarik dari budaya Indonesia, seperti w\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:29:18\";}i:12;a:4:{s:9:\"user_text\";s:38:\"Tolong ceritakan kembali tentang lupis\";s:11:\"ai_response\";s:150:\"Teman! Lupis adalah makanan tradisional Indonesia yang lezat! Mari kita hitung bersama, jika kita memiliki 5 lupis dan ibu memberikan 2 lupis lagi, be\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:37:18\";}i:13;a:4:{s:9:\"user_text\";s:12:\"Berapa 3 + 3\";s:11:\"ai_response\";s:93:\"Teman pintar! 3 + 3 = 6! Kamu bisa membeli 6 buah lupis dengan uang tabunganmu! Bagus sekali!\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:43:38\";}i:14;a:4:{s:9:\"user_text\";s:7:\"hai hai\";s:11:\"ai_response\";s:95:\"Hai! Asyik! Mari kita belajar tentang budaya Indonesia, seperti wayang kulit yang sangat keren!\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:54:24\";}i:15;a:4:{s:9:\"user_text\";s:4:\"halo\";s:11:\"ai_response\";s:100:\"Halo! Bagus! Kita bisa membahas tentang batik, yaitu seni kain Indonesia yang sangat indah dan unik!\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:54:37\";}i:16;a:4:{s:9:\"user_text\";s:21:\"Teks tidak terdeteksi\";s:11:\"ai_response\";s:25:\"Respons AI tidak tersedia\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:55:57\";}i:17;a:4:{s:9:\"user_text\";s:4:\"halo\";s:11:\"ai_response\";s:103:\"Halo! Asyik! Mari kita kenalan dengan tari tradisional Indonesia, seperti tari kecak yang sangat keren!\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 14:56:46\";}i:18;a:4:{s:9:\"user_text\";s:5:\"hello\";s:11:\"ai_response\";s:111:\"Halo! Keren! Kamu tahu bahwa wayang kulit adalah salah satu kesenian tradisional Indonesia yang sangat menarik!\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 15:00:45\";}i:19;a:4:{s:9:\"user_text\";s:34:\"kamu tahu tarian dari Jepang nggak\";s:11:\"ai_response\";s:129:\"Tentu, aku tahu! Tarian dari Jepang yang terkenal adalah Kabuki! Kamu bisa mencoba meniru gerakannya yang elegan dan cantik, lho!\";s:9:\"age_group\";s:3:\"5-7\";s:9:\"timestamp\";s:19:\"2026-01-24 15:40:37\";}}', 1769874037);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counting_items`
--

CREATE TABLE `counting_items` (
  `id` bigint UNSIGNED NOT NULL,
  `level_id` bigint UNSIGNED NOT NULL,
  `nama_objek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar_objek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_operasi` enum('tambah','kurang') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tambah',
  `nilai_kiri` int UNSIGNED NOT NULL,
  `nilai_kanan` int UNSIGNED NOT NULL,
  `hasil` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `counting_items`
--

INSERT INTO `counting_items` (`id`, `level_id`, `nama_objek`, `gambar_objek`, `jenis_operasi`, `nilai_kiri`, `nilai_kanan`, `hasil`, `created_at`, `updated_at`) VALUES
(1, 2, 'Mi Aceh', 'counting-items/images/01KE48VB5JEC9ZVHT05Q3KJJY9.png', 'tambah', 2, 3, 5, '2026-01-04 03:29:04', '2026-01-18 10:51:10'),
(2, 5, 'Lupis', 'counting-items/images/Black White Minimalist Brand Fashion Logo (3).png', 'tambah', 1, 2, 3, '2026-01-18 03:27:42', '2026-01-18 03:33:16'),
(3, 7, 'Nasi Tumpeng', 'counting-items/images/Black White Minimalist Brand Fashion Logo (4).png', 'tambah', 2, 4, 6, '2026-01-18 03:30:13', '2026-01-18 03:30:13'),
(4, 8, 'Papeda', 'counting-items/images/Black White Minimalist Brand Fashion Logo (5).png', 'tambah', 1, 1, 2, '2026-01-18 03:33:53', '2026-01-18 03:33:53'),
(5, 9, 'Pempek', 'counting-items/images/Black White Minimalist Brand Fashion Logo (7).png', 'tambah', 2, 1, 3, '2026-01-18 03:38:25', '2026-01-18 03:38:25'),
(6, 11, 'Rendang', 'counting-items/images/Black White Minimalist Brand Fashion Logo (8).png', 'tambah', 4, 4, 8, '2026-01-18 03:41:06', '2026-01-18 03:41:06'),
(7, 15, 'Gudeng', 'counting-items/images/Black White Minimalist Brand Fashion Logo (9).png', 'tambah', 3, 4, 7, '2026-01-18 03:43:16', '2026-01-18 03:43:16'),
(8, 19, 'Rawon', 'counting-items/images/Black White Minimalist Brand Fashion Logo (10).png', 'tambah', 1, 1, 2, '2026-01-18 03:47:31', '2026-01-18 03:47:31'),
(9, 20, 'Sate Lilit', 'counting-items/images/Black White Minimalist Brand Fashion Logo (11).png', 'tambah', 4, 2, 6, '2026-01-18 03:50:52', '2026-01-18 03:50:52'),
(10, 21, 'Coto Makassar', 'counting-items/images/Black White Minimalist Brand Fashion Logo (12).png', 'tambah', 1, 2, 3, '2026-01-18 03:56:35', '2026-01-18 03:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `f_a_q_s`
--

CREATE TABLE `f_a_q_s` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_game` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `nama_game`, `status`, `created_at`, `updated_at`, `foto`) VALUES
(1, 'Tunjuk Gelasnya, Sikat Hadiahnya', 1, '2026-01-22 23:29:25', '2026-01-22 23:39:26', '01KFMRP5GN7N5DCVCX6077W113.png');

-- --------------------------------------------------------

--
-- Table structure for table `hadiahs`
--

CREATE TABLE `hadiahs` (
  `id` bigint UNSIGNED NOT NULL,
  `game_id` bigint UNSIGNED NOT NULL,
  `nama_hadiah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_hadiah` enum('uang','makanan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `audio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hadiahs`
--

INSERT INTO `hadiahs` (`id`, `game_id`, `nama_hadiah`, `jenis_hadiah`, `foto`, `audio`, `created_at`, `updated_at`) VALUES
(1, 1, 'Uang 100 Ribu', 'uang', 'hadiahs/01KFMRX5XE5FX0NP40C2JZ8GPG.png', 'hadiahs/audio/01KFPXTVGMDGWTXKC6TJ1TJ285.mp3', '2026-01-22 23:33:14', '2026-01-23 19:37:50'),
(2, 1, 'Permen', 'makanan', 'hadiahs/01KFMS1GCNAACVDFPYATHGCHSZ.png', 'hadiahs/audio/01KFPXQYHBMVXVK1PBM16W5TGB.mp3', '2026-01-22 23:35:36', '2026-01-23 19:36:15'),
(3, 1, 'Basreng', 'makanan', 'hadiahs/01KFMS55AFCQ28N52M7QZ9DKHB.png', 'hadiahs/audio/01KFPXW9116E2DB9RCQ7QD88MN.mp3', '2026-01-22 23:37:36', '2026-01-23 19:38:37');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` bigint UNSIGNED NOT NULL,
  `module_id` bigint UNSIGNED NOT NULL,
  `order_number` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `module_id`, `order_number`, `title`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Level 1', '2025-12-31 01:14:15', '2025-12-31 01:14:15'),
(2, 2, 1, 'Level 1', '2026-01-04 03:28:17', '2026-01-04 03:28:17'),
(3, 1, 2, 'Level 2', '2026-01-04 04:27:15', '2026-01-04 04:27:15'),
(4, 3, 1, 'Level 1', '2026-01-04 06:35:25', '2026-01-04 06:35:25'),
(5, 2, 2, 'Level 2', '2026-01-04 19:41:57', '2026-01-04 19:41:57'),
(6, 1, 3, 'Level 3', '2026-01-17 19:18:36', '2026-01-17 19:18:36'),
(7, 2, 3, 'Level 3', '2026-01-18 03:21:32', '2026-01-18 03:21:32'),
(8, 2, 4, 'Level 4', '2026-01-18 03:21:45', '2026-01-18 03:21:45'),
(9, 2, 5, 'Level 5', '2026-01-18 03:21:55', '2026-01-18 03:21:55'),
(10, 1, 4, 'Level 4', '2026-01-18 03:22:01', '2026-01-18 03:22:01'),
(11, 2, 6, 'Level 6', '2026-01-18 03:22:07', '2026-01-18 03:22:07'),
(12, 1, 5, 'Level 5', '2026-01-18 03:22:13', '2026-01-18 03:22:13'),
(13, 1, 6, 'Level 6', '2026-01-18 03:22:19', '2026-01-18 03:22:19'),
(14, 1, 7, 'Level 7', '2026-01-18 03:22:24', '2026-01-18 03:22:24'),
(15, 2, 7, 'Level 7', '2026-01-18 03:22:29', '2026-01-18 03:22:29'),
(16, 1, 8, 'Level 8', '2026-01-18 03:22:37', '2026-01-18 03:22:37'),
(17, 1, 9, 'Level 9', '2026-01-18 03:22:43', '2026-01-18 03:22:43'),
(18, 1, 10, 'Level 10', '2026-01-18 03:22:55', '2026-01-18 03:22:55'),
(19, 2, 8, 'Level 8', '2026-01-18 03:23:04', '2026-01-18 03:23:04'),
(20, 2, 9, 'Level 9', '2026-01-18 03:23:12', '2026-01-18 03:23:12'),
(21, 2, 10, 'Level 10', '2026-01-18 03:23:21', '2026-01-18 03:23:21');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_31_071408_create_modules_table', 2),
(5, '2025_12_31_071827_create_levels_table', 2),
(6, '2025_12_31_071958_create_writing_items_table', 2),
(7, '2025_12_31_071958_create_writing_itemsss_table', 3),
(8, '2026_01_04_062418_create_counting_items_table', 4),
(9, '2026_01_04_134359_create_puzzle_items_table', 5),
(10, '2026_01_10_123923_create_books_table', 6),
(11, '2026_01_10_123933_create_page_books_table', 6),
(12, '2026_01_16_145134_create_story_pages_table', 7),
(13, '2026_01_16_145350_create_story_images_table', 7),
(14, '2026_01_16_145357_create_story_choices_table', 7),
(15, '2026_01_18_004952_create_anaks_table', 8),
(16, '2026_01_18_010950_create_progres_anaks_table', 9),
(17, '2026_01_19_220208_create_f_a_q_s_table', 10),
(18, '2026_01_19_220218_create_artikels_table', 10),
(19, '2026_01_20_072525_create_plans_table', 10),
(20, '2026_01_20_073041_create_subscriptions_table', 11),
(21, '2026_01_23_055254_create_games_table', 12),
(22, '2026_01_23_055315_create_hadiahs_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint UNSIGNED NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('level','buku') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `foto`, `name`, `type`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'modules/01KDSQCN31WZP0YW10SZXWG76W.png', 'Menulis', 'level', 'menulis-calista', '2025-12-31 01:11:33', '2026-01-10 08:41:37'),
(2, 'modules/01KE48R8FBBP3XGCAANARRRVD4.png', 'Menghitung', 'level', 'menghitung-calista', '2026-01-04 03:27:23', '2026-01-10 08:41:46'),
(3, 'modules/01KE4KFY2ZQWAVC0BX0EHFPXPD.png', 'Puzzle', 'level', 'puzzle-calista', '2026-01-04 06:35:05', '2026-01-10 08:41:55'),
(4, 'modules/01KEKYDH0GX3Q8E9SAG856TSYR.png', 'Membaca', 'buku', 'membaca-calista', '2026-01-10 05:34:37', '2026-01-10 05:34:37'),
(5, 'modules/01KF3QWQ4Z2WDYP66DDBM5BMEA.png', 'Cerita Rakyat', 'buku', 'cerita-rakyat-calista', '2026-01-16 08:48:25', '2026-01-16 08:48:25');

-- --------------------------------------------------------

--
-- Table structure for table `page_books`
--

CREATE TABLE `page_books` (
  `id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `page_number` int NOT NULL,
  `nama_benda` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suku_kata` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `audio_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `audio_kata` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `explanation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_premium` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_books`
--

INSERT INTO `page_books` (`id`, `book_id`, `page_number`, `nama_benda`, `suku_kata`, `image_path`, `audio_path`, `audio_kata`, `explanation`, `is_active`, `is_premium`, `created_at`, `updated_at`) VALUES
(3, 1, 1, 'PEMPEK', 'PEM-PEK', 'page_books/images/01KEMEG8XVBT9VMMXNM8QY36G1.png', 'page_books/audio/01KEMEG8Y0GXJ055YKF34EMYAX.mp3', 'page_books/audio_kata/01KEMEG8Y6A0NKT69QNRPP1RC4.mp3', '<p>Pempek adalah makanan khas Palembang yang terbuat dari ikan dan tepung sagu.&nbsp;</p>', 1, 0, '2026-01-10 10:15:44', '2026-01-10 10:15:44'),
(5, 1, 2, 'LUPIS', 'LU-PIS', 'page_books/images/01KEMF6THQGZPQ96FE19S0R09D.png', 'page_books/audio/01KEMF6THV5BSB7PNET1W7H7KY.mp3', 'page_books/audio_kata/01KEMF6THYH0AEK2ZGYQ3S4RXR.mp3', '<p>&nbsp;Lupis adalah makanan tradisional Indonesia yang terbuat dari beras ketan. Lupis biasanya disajikan dengan kelapa parut dan gula merah cair.&nbsp;</p>', 1, 0, '2026-01-10 10:28:03', '2026-01-10 10:28:03');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `plan_id` bigint UNSIGNED DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `merchant_ref` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `fee_merchant` decimal(12,2) NOT NULL DEFAULT '0.00',
  `fee_customer` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount_received` decimal(12,2) NOT NULL DEFAULT '0.00',
  `pay_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `checkout_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('UNPAID','PAID','EXPIRED','FAILED') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNPAID',
  `expired_time` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `plan_id`, `reference`, `merchant_ref`, `payment_method`, `payment_name`, `amount`, `fee_merchant`, `fee_customer`, `total_fee`, `amount_received`, `pay_code`, `pay_url`, `checkout_url`, `status`, `expired_time`, `created_at`, `updated_at`) VALUES
(100, 4, 1, 'DEV-T4648633179050YN1', 'PLAN-1768917091-4-1', 'BCAVA', 'BCA Virtual Account', '96500.00', '0.00', '5500.00', '5500.00', '91000.00', '752582408540679', NULL, 'https://tripay.co.id/checkout/DEV-T4648633179050YN1', 'PAID', '2026-01-21 06:51:31', '2026-01-20 06:51:32', '2026-01-20 06:51:58'),
(101, 4, 2, 'DEV-T46486331792H6HOW', 'PLAN-1768917220-4-2', 'BSIVA', 'BSI Virtual Account', '273000.00', '4250.00', '0.00', '4250.00', '268750.00', '718921057510437', NULL, 'https://tripay.co.id/checkout/DEV-T46486331792H6HOW', 'PAID', '2026-01-20 09:52:42', '2026-01-20 06:53:41', '2026-01-20 06:54:16'),
(102, 4, 2, 'DEV-T46486331802CN5GC', 'PLAN-1768917469-4-2', 'CIMBVA', 'CIMB Niaga Virtual Account', '273000.00', '4250.00', '0.00', '4250.00', '268750.00', '407463998394411', NULL, 'https://tripay.co.id/checkout/DEV-T46486331802CN5GC', 'PAID', '2026-01-21 06:57:49', '2026-01-20 06:57:49', '2026-01-20 06:58:36'),
(103, 4, 2, 'DEV-T46486331953BJNTJ', 'PLAN-1768976230-4-2', 'BCAVA', 'BCA Virtual Account', '278500.00', '0.00', '5500.00', '5500.00', '273000.00', '934146107519793', NULL, 'https://tripay.co.id/checkout/DEV-T46486331953BJNTJ', 'UNPAID', '2026-01-21 23:17:10', '2026-01-20 23:17:11', '2026-01-20 23:17:11'),
(104, 4, 1, 'DEV-T464863326986LBP8', 'PLAN-1769219057-4-1', 'BCAVA', 'BCA Virtual Account', '96500.00', '0.00', '5500.00', '5500.00', '91000.00', '968293202829236', NULL, 'https://tripay.co.id/checkout/DEV-T464863326986LBP8', 'PAID', '2026-01-24 18:44:17', '2026-01-23 18:44:17', '2026-01-23 18:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_paket` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `durasi_bulan` int NOT NULL,
  `harga_jual` decimal(10,2) DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `nama_paket`, `durasi_bulan`, `harga_jual`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, '1 Bulan', 1, '91000.00', '🎒 Paket CALISTA 1 Bulan — Belajar Calistung bersama AI\n\n📅 Durasi: 30 hari\n\n🤖 Akses AI Interaktif untuk belajar membaca, menulis, dan berhitung\n\n🔤 Materi dasar: Pengenalan huruf & angka\n\n🧠 Latihan adaptif sesuai kemampuan anak\n\n👶 Cocok untuk anak usia 5–10 tahun', NULL, NULL),
(2, '3 Bulan', 3, '273000.00', '🌟 Paket CALISTA 3 Bulan — Solusi belajar konsisten dan hemat\n\n📅 Durasi: 90 hari\n\n🤖 Akses AI Interaktif tanpa batas\n\n🔤 Materi lengkap calistung dasar\n\n📊 Evaluasi perkembangan belajar anak\n\n🎮 Pembelajaran interaktif & menyenangkan\n\n👶 Direkomendasikan untuk anak usia 5–10 tahun', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `progres_anaks`
--

CREATE TABLE `progres_anaks` (
  `id` bigint UNSIGNED NOT NULL,
  `anak_id` bigint UNSIGNED NOT NULL,
  `level_id` bigint UNSIGNED NOT NULL,
  `score` int NOT NULL DEFAULT '0',
  `bintang` int NOT NULL DEFAULT '0',
  `selesai` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `progres_anaks`
--

INSERT INTO `progres_anaks` (`id`, `anak_id`, `level_id`, `score`, `bintang`, `selesai`, `created_at`, `updated_at`) VALUES
(10, 1, 1, 100, 3, 1, '2026-01-19 09:28:01', '2026-01-19 16:09:39'),
(11, 1, 2, 0, 1, 1, '2026-01-19 14:55:42', '2026-01-23 22:55:02'),
(12, 1, 3, 93, 2, 1, '2026-01-19 16:01:26', '2026-01-19 16:01:26'),
(13, 1, 5, 10, 3, 1, '2026-01-21 07:13:16', '2026-01-21 07:13:16'),
(14, 1, 7, 10, 3, 1, '2026-01-21 20:21:42', '2026-01-21 20:21:42'),
(15, 1, 8, 10, 3, 1, '2026-01-21 22:25:18', '2026-01-21 22:25:18'),
(16, 1, 9, 10, 3, 1, '2026-01-21 22:49:46', '2026-01-21 22:49:46'),
(17, 1, 11, 10, 3, 1, '2026-01-22 02:13:36', '2026-01-22 02:13:36'),
(18, 1, 15, 10, 3, 1, '2026-01-22 16:27:22', '2026-01-22 16:27:22'),
(19, 1, 19, 10, 3, 1, '2026-01-22 16:29:47', '2026-01-22 16:29:47');

-- --------------------------------------------------------

--
-- Table structure for table `puzzle_items`
--

CREATE TABLE `puzzle_items` (
  `id` bigint UNSIGNED NOT NULL,
  `level_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grid_size` tinyint UNSIGNED NOT NULL,
  `order_number` int UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `puzzle_items`
--

INSERT INTO `puzzle_items` (`id`, `level_id`, `title`, `image`, `grid_size`, `order_number`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 4, 'Rumah Adat Bali (Parahyangan)', 'puzzle-items/01KE4MFZC2FRGKS6EJCG7ZAQBY.svg', 3, 1, 1, '2026-01-04 06:52:35', '2026-01-04 06:52:35');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9xDA83kDnJZ482zQIm2Dp1l6rHcO8lKQIDoCMxzS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiUjdSOXFjVUVMYzJrNXkzMmx1MWFyU3JXOUtWVExrcVBJNnF4M3ZRMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769299557),
('TBOCLTHR74ygr816HrFcqOtet1Xeznne2pW4opeM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWTdOWGl6cUNOazdFWHphVVQ2Y2tVWmJTaENxQXVnNVE1ZkNQZUYxTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769297819),
('ZCWz1I187dUx689adSkb59grUfBjHTdt6JPgF20F', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWGZqN2ZCNmZ4aGdjNjlxNFpEWmdXTTBEaXQxaWNSVzVzOUdJWGkzWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1769297795);

-- --------------------------------------------------------

--
-- Table structure for table `story_choices`
--

CREATE TABLE `story_choices` (
  `id` bigint UNSIGNED NOT NULL,
  `story_page_id` bigint UNSIGNED NOT NULL,
  `choice_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `next_page_number` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `story_choices`
--

INSERT INTO `story_choices` (`id`, `story_page_id`, `choice_text`, `next_page_number`, `created_at`, `updated_at`) VALUES
(1, 4, 'Melempar hadiah ke sungai dengan ikhlas', 5, '2026-01-16 09:07:24', '2026-01-16 09:07:24'),
(2, 4, 'Menyimpan hadiah dan menjelaskan dengan jujur', 6, '2026-01-16 09:07:44', '2026-01-16 09:07:44');

-- --------------------------------------------------------

--
-- Table structure for table `story_images`
--

CREATE TABLE `story_images` (
  `id` bigint UNSIGNED NOT NULL,
  `story_page_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('background','character','object') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'background',
  `order` int NOT NULL DEFAULT '1',
  `start_second` int NOT NULL DEFAULT '0',
  `end_second` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `story_images`
--

INSERT INTO `story_images` (`id`, `story_page_id`, `image_path`, `type`, `order`, `start_second`, `end_second`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'story-images/01KF3SGFEN0VTGGVXB0WTR6ADS.png', 'background', 1, 0, 6, 1, '2026-01-16 09:16:41', '2026-01-16 09:17:15'),
(2, 1, 'story-images/01KF3SW87G6GQQ1VB4PPRV1F06.png', 'character', 2, 2, 6, 1, '2026-01-16 09:23:07', '2026-01-16 09:23:07');

-- --------------------------------------------------------

--
-- Table structure for table `story_pages`
--

CREATE TABLE `story_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `book_id` bigint UNSIGNED NOT NULL,
  `page_number` int NOT NULL,
  `story_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `audio_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `story_pages`
--

INSERT INTO `story_pages` (`id`, `book_id`, `page_number`, `story_text`, `audio_path`, `question`, `duration`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Dahulu kala, di Palembang, mengalir Sungai Musi yang sangat besar. Di tepi sungai itu tinggal seorang putri Palembang bernama Putri Siti Fatimah.', 'story_audio/legenda-pulo-kemaro-palembang/1_1768640507.mp3', NULL, 6, 1, '2026-01-16 08:55:06', '2026-01-17 02:01:49'),
(2, 2, 2, 'Suatu hari, datang seorang pangeran dari negeri Tiongkok bernama Tan Bun An. Ia datang dengan perahu besar dan membawa banyak hadiah.', 'story_audio/legenda-pulo-kemaro-palembang/2_1768640533.mp3', NULL, 8, 1, '2026-01-16 08:55:45', '2026-01-17 02:02:13'),
(3, 2, 3, 'Putri Siti Fatimah dan Pangeran Tan Bun An saling menyukai. Namun, ayah sang putri ingin memastikan ketulusan hati sang pangeran.', 'story_audio/legenda-pulo-kemaro-palembang/3_1768667030.mp3', NULL, 5, 1, '2026-01-16 09:02:08', '2026-01-17 09:23:52'),
(4, 2, 4, 'Ayah Putri meminta Tan Bun An melemparkan hadiah ke Sungai Musi sebagai tanda ketulusan. Pangeran pun ragu sejenak.', 'story_audio/legenda-pulo-kemaro-palembang/4_1768640567.mp3', 'Menurut adik, apa yang sebaiknya dilakukan Tan Bun An?', 5, 1, '2026-01-16 09:02:34', '2026-01-17 02:02:47'),
(5, 2, 5, 'Tan Bun An melempar hadiah ke sungai dengan tulus. Namun ia terkejut karena hadiah itu ternyata sangat berat dan tenggelam.', 'story_audio/legenda-pulo-kemaro-palembang/5_1768640596.mp3', NULL, 6, 1, '2026-01-16 09:08:27', '2026-01-17 02:03:16'),
(6, 2, 6, 'Tan Bun An menjelaskan dengan jujur bahwa hadiah itu berharga. Kejujurannya membuat ayah Putri menghargainya.', 'story_audio/legenda-pulo-kemaro-palembang/6_1768667224.mp3', NULL, 5, 1, '2026-01-16 09:09:06', '2026-01-17 09:27:04');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_berakhir` datetime NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nonaktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `plan_id`, `tanggal_mulai`, `tanggal_berakhir`, `status`, `created_at`, `updated_at`) VALUES
(3, 4, 1, '2026-01-24 01:45:35', '2026-02-24 01:45:35', 'aktif', '2026-01-23 18:45:35', '2026-01-23 18:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'calista', 'calista@gmail.com', NULL, '$2y$12$9WGsYj9i3x/IwsQYvxQOWOyFg4x4bEiuCcXHZ/VkTvHsBAjL1da6W', NULL, '2025-12-31 01:02:03', '2025-12-31 01:02:03'),
(2, 'Azizah Nur Rahma', 'azizahnurrahma27@gmail.com', NULL, '$2y$12$UCTrjwUqKdtH4E0sTWDIKO3vnjMCKPuKZ1sopoS9NTE3nnN3ulN..', NULL, '2026-01-02 23:08:08', '2026-01-02 23:08:08'),
(3, 'Azizah Nur Rahma', 'azizahnurrahma29@gmail.com', NULL, '$2y$12$Ll7ijPmNPD1hhUdo6rp3EOqefYFu9MriNymzUvrv6QekW4HfGJX9.', NULL, '2026-01-17 18:52:20', '2026-01-17 18:52:20'),
(4, 'Azizah', 'azizahnurrahma20@gmail.com', NULL, '$2y$12$pqEHDgAhnK41ikc5GsVexeO7c.QllSaoFxBzUQM8j12QAbI2kzsTW', '1hqpT5CJPoXYJJ5JLVPVypROBw5IQL9JmDPHeh9HmPt9KLFJ6r9P1hH6Vu9O', '2026-01-17 19:07:45', '2026-01-17 19:07:45'),
(5, 'ELANORA', 'elanoraadmin@gmail.com', '2026-01-20 02:56:40', '$2y$12$ZNOzoDWGx2OFSuqsbRw9KOWw3lnhoNew3Iqwy3Wwro8LDoBcEJ0Wm', 'G152Mn406u', '2026-01-20 02:56:40', '2026-01-20 02:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `writing_items`
--

CREATE TABLE `writing_items` (
  `id` bigint UNSIGNED NOT NULL,
  `level_id` bigint UNSIGNED NOT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `audio_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('letter','word') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'word',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `writing_items`
--

INSERT INTO `writing_items` (`id`, `level_id`, `text`, `image_path`, `audio_path`, `type`, `created_at`, `updated_at`) VALUES
(1, 1, 'ANGKLUNG', 'writing-items/images/01KDSQYZBZKX7T55ACCZBEXNYN.png', 'writing-items/audio/01KDSQYZC39ZBFE3FKHGQM7QK2.mp3', 'word', '2025-12-31 01:21:33', '2025-12-31 01:21:33'),
(3, 3, 'LUPIS', 'writing-items/images/Black White Minimalist Brand Fashion Logo (3).png', NULL, 'word', '2026-01-17 21:21:33', '2026-01-17 21:21:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anaks`
--
ALTER TABLE `anaks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_anaks_user` (`user_id`);

--
-- Indexes for table `artikels`
--
ALTER TABLE `artikels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `artikels_slug_unique` (`slug`),
  ADD KEY `artikels_user_id_foreign` (`user_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `books_slug_unique` (`slug`),
  ADD KEY `books_module_id_foreign` (`module_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `counting_items`
--
ALTER TABLE `counting_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `counting_items_level_id_foreign` (`level_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `f_a_q_s`
--
ALTER TABLE `f_a_q_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hadiahs`
--
ALTER TABLE `hadiahs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hadiahs_game_id_foreign` (`game_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `levels_module_id_foreign` (`module_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modules_slug_unique` (`slug`);

--
-- Indexes for table `page_books`
--
ALTER TABLE `page_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_books_book_id_foreign` (`book_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_reference_unique` (`reference`),
  ADD KEY `payments_reference_merchant_ref_index` (`reference`,`merchant_ref`),
  ADD KEY `payments_plan_id_foreign` (`plan_id`),
  ADD KEY `fk_payments_user_id` (`user_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `progres_anaks`
--
ALTER TABLE `progres_anaks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progres_anaks_anak_id_foreign` (`anak_id`),
  ADD KEY `progres_anaks_level_id_foreign` (`level_id`);

--
-- Indexes for table `puzzle_items`
--
ALTER TABLE `puzzle_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `puzzle_items_level_id_foreign` (`level_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `story_choices`
--
ALTER TABLE `story_choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `story_choices_story_page_id_foreign` (`story_page_id`);

--
-- Indexes for table `story_images`
--
ALTER TABLE `story_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `story_images_story_page_id_foreign` (`story_page_id`);

--
-- Indexes for table `story_pages`
--
ALTER TABLE `story_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `story_pages_book_id_page_number_unique` (`book_id`,`page_number`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `subscriptions_plan_id_foreign` (`plan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `writing_items`
--
ALTER TABLE `writing_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `writing_items_level_id_foreign` (`level_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anaks`
--
ALTER TABLE `anaks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `artikels`
--
ALTER TABLE `artikels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `counting_items`
--
ALTER TABLE `counting_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `f_a_q_s`
--
ALTER TABLE `f_a_q_s`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hadiahs`
--
ALTER TABLE `hadiahs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `page_books`
--
ALTER TABLE `page_books`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `progres_anaks`
--
ALTER TABLE `progres_anaks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `puzzle_items`
--
ALTER TABLE `puzzle_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `story_choices`
--
ALTER TABLE `story_choices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `story_images`
--
ALTER TABLE `story_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `story_pages`
--
ALTER TABLE `story_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `writing_items`
--
ALTER TABLE `writing_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anaks`
--
ALTER TABLE `anaks`
  ADD CONSTRAINT `fk_anaks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `artikels`
--
ALTER TABLE `artikels`
  ADD CONSTRAINT `artikels_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `counting_items`
--
ALTER TABLE `counting_items`
  ADD CONSTRAINT `counting_items_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hadiahs`
--
ALTER TABLE `hadiahs`
  ADD CONSTRAINT `hadiahs_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `levels`
--
ALTER TABLE `levels`
  ADD CONSTRAINT `levels_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `page_books`
--
ALTER TABLE `page_books`
  ADD CONSTRAINT `page_books_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `progres_anaks`
--
ALTER TABLE `progres_anaks`
  ADD CONSTRAINT `progres_anaks_anak_id_foreign` FOREIGN KEY (`anak_id`) REFERENCES `anaks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `progres_anaks_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `puzzle_items`
--
ALTER TABLE `puzzle_items`
  ADD CONSTRAINT `puzzle_items_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `story_choices`
--
ALTER TABLE `story_choices`
  ADD CONSTRAINT `story_choices_story_page_id_foreign` FOREIGN KEY (`story_page_id`) REFERENCES `story_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `story_images`
--
ALTER TABLE `story_images`
  ADD CONSTRAINT `story_images_story_page_id_foreign` FOREIGN KEY (`story_page_id`) REFERENCES `story_pages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `story_pages`
--
ALTER TABLE `story_pages`
  ADD CONSTRAINT `story_pages_book_id_foreign` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `writing_items`
--
ALTER TABLE `writing_items`
  ADD CONSTRAINT `writing_items_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
