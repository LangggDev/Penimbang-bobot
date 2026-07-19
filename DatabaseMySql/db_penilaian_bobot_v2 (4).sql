-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2026 at 07:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penilaian_bobot_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembayaran_barang`
--

CREATE TABLE `detail_pembayaran_barang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pembayaran_id` bigint(20) UNSIGNED NOT NULL,
  `detail_transaksi_barang_id` bigint(20) UNSIGNED NOT NULL,
  `fuzzy_hasil_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nama_barang_snapshot` varchar(255) NOT NULL,
  `berat_bersih` decimal(12,2) NOT NULL DEFAULT 0.00,
  `persentase_potongan` decimal(8,2) NOT NULL DEFAULT 0.00,
  `potongan_berat` decimal(12,2) NOT NULL DEFAULT 0.00,
  `berat_layak` decimal(12,2) NOT NULL DEFAULT 0.00,
  `harga_per_kg` decimal(15,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `urutan` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_pembayaran_barang`
--

INSERT INTO `detail_pembayaran_barang` (`id`, `pembayaran_id`, `detail_transaksi_barang_id`, `fuzzy_hasil_id`, `nama_barang_snapshot`, `berat_bersih`, `persentase_potongan`, `potongan_berat`, `berat_layak`, `harga_per_kg`, `subtotal`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 6, 'Box', 619.98, 20.73, 128.52, 491.46, 2200.00, 1081212.00, 1, '2026-05-20 05:13:27', '2026-05-20 05:13:27'),
(2, 2, 7, 1, 'Box', 700.00, 35.00, 245.00, 455.00, 2200.00, 1001000.00, 1, '2026-05-20 06:18:59', '2026-05-20 06:18:59'),
(3, 2, 8, 2, 'Duplex', 120.00, 35.00, 42.00, 78.00, 1100.00, 85800.00, 2, '2026-05-20 06:18:59', '2026-05-20 06:18:59'),
(4, 2, 9, 3, 'Swl', 220.00, 17.00, 37.40, 182.60, 2500.00, 456500.00, 3, '2026-05-20 06:18:59', '2026-05-20 06:18:59'),
(5, 3, 10, 7, 'Box', 650.00, 30.13, 195.85, 454.15, 2250.00, 1021837.50, 1, '2026-05-21 01:12:13', '2026-05-21 01:12:13'),
(6, 3, 11, 8, 'Swl', 200.00, 35.00, 70.00, 130.00, 2500.00, 325000.00, 2, '2026-05-21 01:12:13', '2026-05-21 01:12:13'),
(7, 4, 17, NULL, 'Box', 30.00, 0.00, 0.00, 30.00, 2000.00, 60000.00, 1, '2026-05-21 20:20:44', '2026-05-21 20:20:44'),
(8, 5, 2, 4, 'Box', 780.00, 27.66, 215.75, 564.25, 2200.00, 1241350.00, 1, '2026-05-21 20:39:54', '2026-05-21 20:39:54'),
(9, 6, 16, NULL, 'Buku', 35.00, 0.00, 0.00, 35.00, 1800.00, 63000.00, 1, '2026-05-28 02:00:31', '2026-05-28 02:00:31'),
(10, 7, 15, NULL, 'Box', 65.00, 0.00, 0.00, 65.00, 2000.00, 130000.00, 1, '2026-05-28 02:00:47', '2026-05-28 02:00:47'),
(11, 8, 14, NULL, 'Box', 70.00, 0.00, 0.00, 70.00, 2000.00, 140000.00, 1, '2026-05-28 02:01:06', '2026-05-28 02:01:06'),
(12, 9, 13, NULL, 'Box', 25.00, 0.00, 0.00, 25.00, 2000.00, 50000.00, 1, '2026-05-28 02:01:15', '2026-05-28 02:01:15'),
(13, 10, 1, 5, 'Box', 720.00, 22.75, 163.80, 556.20, 2000.00, 1112400.00, 1, '2026-05-28 02:01:27', '2026-05-28 02:01:27'),
(14, 11, 19, 11, 'Box', 700.00, 17.00, 119.00, 581.00, 2200.00, 1278200.00, 1, '2026-06-03 02:58:28', '2026-06-03 02:58:28'),
(15, 11, 20, 10, 'Duplex', 350.00, 35.00, 122.50, 227.50, 1100.00, 250250.00, 2, '2026-06-03 02:58:28', '2026-06-03 02:58:28'),
(16, 12, 18, 9, 'Box', 800.00, 20.11, 160.88, 639.12, 2200.00, 1406064.00, 1, '2026-06-03 02:59:09', '2026-06-03 02:59:09'),
(17, 13, 21, 13, 'Box', 400.00, 20.11, 80.44, 319.56, 2200.00, 703032.00, 1, '2026-06-05 06:21:58', '2026-06-05 06:21:58'),
(18, 13, 22, 12, 'Duplex', 250.00, 20.11, 50.28, 199.72, 1100.00, 219692.00, 2, '2026-06-05 06:21:58', '2026-06-05 06:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi_barang`
--

CREATE TABLE `detail_transaksi_barang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaksi_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_kertas_bekas_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan_barang` varchar(255) DEFAULT NULL,
  `total_berat_kotor` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_tara` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_berat_bersih` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status_qc` enum('belum_dinilai','sudah_dinilai','revisi') NOT NULL DEFAULT 'belum_dinilai',
  `urutan` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_transaksi_barang`
--

INSERT INTO `detail_transaksi_barang` (`id`, `transaksi_id`, `jenis_kertas_bekas_id`, `keterangan_barang`, `total_berat_kotor`, `total_tara`, `total_berat_bersih`, `status_qc`, `urutan`, `created_at`, `updated_at`) VALUES
(1, 10, 1, NULL, 1650.00, 930.00, 720.00, 'sudah_dinilai', 1, '2026-05-19 01:09:44', '2026-05-19 23:31:01'),
(2, 11, 1, NULL, 1720.00, 940.00, 780.00, 'sudah_dinilai', 1, '2026-05-19 01:38:49', '2026-05-19 23:30:39'),
(3, 12, 1, NULL, 2300.00, 1100.00, 1200.00, 'sudah_dinilai', 1, '2026-05-19 01:39:35', '2026-05-19 22:45:42'),
(4, 13, 1, NULL, 1430.02, 930.02, 500.00, 'sudah_dinilai', 1, '2026-05-19 03:52:02', '2026-05-19 20:57:10'),
(5, 13, 2, NULL, 1630.00, 1430.02, 199.98, 'sudah_dinilai', 2, '2026-05-19 03:52:02', '2026-05-19 20:56:39'),
(6, 13, 3, NULL, 1780.00, 1630.00, 150.00, 'sudah_dinilai', 3, '2026-05-19 03:52:03', '2026-05-19 20:56:21'),
(7, 14, 1, NULL, 1660.00, 960.00, 700.00, 'sudah_dinilai', 1, '2026-05-19 22:57:38', '2026-05-19 23:30:11'),
(8, 14, 2, NULL, 2000.00, 1880.00, 120.00, 'sudah_dinilai', 2, '2026-05-19 22:57:38', '2026-05-19 23:29:35'),
(9, 14, 3, NULL, 1880.00, 1660.00, 220.00, 'sudah_dinilai', 3, '2026-05-19 22:57:38', '2026-05-19 23:29:50'),
(10, 15, 1, NULL, 1800.00, 1150.00, 650.00, 'sudah_dinilai', 1, '2026-05-20 01:13:15', '2026-05-21 01:09:34'),
(11, 15, 3, NULL, 1150.00, 950.00, 200.00, 'sudah_dinilai', 2, '2026-05-20 01:13:15', '2026-05-21 01:09:43'),
(12, 16, 1, NULL, 1599.98, 980.00, 619.98, 'sudah_dinilai', 1, '2026-05-20 01:13:33', '2026-05-20 01:15:05'),
(13, 17, 1, NULL, 50.00, 25.00, 25.00, 'belum_dinilai', 1, '2026-05-21 01:38:42', '2026-05-28 01:59:25'),
(14, 18, 1, NULL, 100.00, 30.00, 70.00, 'belum_dinilai', 1, '2026-05-21 01:39:27', '2026-05-28 01:59:03'),
(15, 19, 1, NULL, 90.00, 25.00, 65.00, 'belum_dinilai', 1, '2026-05-21 01:49:56', '2026-05-28 01:58:42'),
(16, 20, 5, NULL, 79.98, 44.98, 35.00, 'belum_dinilai', 1, '2026-05-21 02:05:14', '2026-05-28 01:49:49'),
(17, 21, 1, NULL, 95.00, 65.00, 30.00, 'belum_dinilai', 1, '2026-05-21 08:30:34', '2026-05-21 20:18:35'),
(18, 22, 1, NULL, 1600.00, 800.00, 800.00, 'sudah_dinilai', 1, '2026-06-03 01:48:46', '2026-06-03 02:39:03'),
(19, 23, 1, NULL, 1950.00, 1250.00, 700.00, 'sudah_dinilai', 1, '2026-06-03 01:49:17', '2026-06-03 02:39:22'),
(20, 23, 2, NULL, 1250.00, 900.00, 350.00, 'sudah_dinilai', 2, '2026-06-03 01:49:17', '2026-06-03 02:39:15'),
(21, 24, 1, NULL, 1600.00, 1200.00, 400.00, 'sudah_dinilai', 1, '2026-06-05 06:18:29', '2026-06-05 06:20:51'),
(22, 24, 2, NULL, 1200.00, 950.00, 250.00, 'sudah_dinilai', 2, '2026-06-05 06:18:29', '2026-06-05 06:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `fuzzy_hasil`
--

CREATE TABLE `fuzzy_hasil` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `qc_penilaian_id` bigint(20) UNSIGNED NOT NULL,
  `detail_transaksi_barang_id` bigint(20) UNSIGNED NOT NULL,
  `nilai_bobot_ketidaklayakan` decimal(8,2) NOT NULL DEFAULT 0.00,
  `persentase_potongan` decimal(8,2) NOT NULL DEFAULT 0.00,
  `potongan_berat` decimal(12,2) NOT NULL DEFAULT 0.00,
  `berat_layak` decimal(12,2) NOT NULL DEFAULT 0.00,
  `detail_perhitungan` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuzzy_hasil`
--

INSERT INTO `fuzzy_hasil` (`id`, `qc_penilaian_id`, `detail_transaksi_barang_id`, `nilai_bobot_ketidaklayakan`, `persentase_potongan`, `potongan_berat`, `berat_layak`, `detail_perhitungan`, `created_at`, `updated_at`) VALUES
(1, 6, 7, 35.00, 35.00, 245.00, 455.00, '{\n    \"input\": {\n        \"jenis_kendaraan\": 2,\n        \"berat_kotor\": 2000,\n        \"berat_bersih\": 700,\n        \"kualitas_kertas\": 4\n    },\n    \"total_alpha\": 1,\n    \"total_alpha_z\": 35,\n    \"rules\": [\n        {\n            \"kode_rule\": \"R50\",\n            \"jenis_kendaraan\": \"K2\",\n            \"berat_kotor\": \"Berat\",\n            \"berat_bersih\": \"Sedang\",\n            \"kualitas_kertas\": \"Sedang\",\n            \"bobot_ketidaklayakan\": \"Tinggi\",\n            \"mu_jenis_kendaraan\": 1,\n            \"mu_berat_kotor\": 1,\n            \"mu_berat_bersih\": 1,\n            \"mu_kualitas_kertas\": 1,\n            \"alpha\": 1,\n            \"z\": 35,\n            \"alpha_z\": 35\n        }\n    ]\n}', '2026-05-19 23:30:14', '2026-05-19 23:30:14'),
(2, 7, 8, 35.00, 35.00, 42.00, 78.00, '{\n    \"input\": {\n        \"jenis_kendaraan\": 2,\n        \"berat_kotor\": 2000,\n        \"berat_bersih\": 120,\n        \"kualitas_kertas\": 5\n    },\n    \"total_alpha\": 1,\n    \"total_alpha_z\": 35,\n    \"rules\": [\n        {\n            \"kode_rule\": \"R47\",\n            \"jenis_kendaraan\": \"K2\",\n            \"berat_kotor\": \"Berat\",\n            \"berat_bersih\": \"Ringan\",\n            \"kualitas_kertas\": \"Sedang\",\n            \"bobot_ketidaklayakan\": \"Tinggi\",\n            \"mu_jenis_kendaraan\": 1,\n            \"mu_berat_kotor\": 1,\n            \"mu_berat_bersih\": 1,\n            \"mu_kualitas_kertas\": 1,\n            \"alpha\": 1,\n            \"z\": 35,\n            \"alpha_z\": 35\n        }\n    ]\n}', '2026-05-19 23:30:14', '2026-05-19 23:30:14'),
(3, 8, 9, 17.00, 17.00, 37.40, 182.60, '{\n    \"input\": {\n        \"jenis_kendaraan\": 2,\n        \"berat_kotor\": 2000,\n        \"berat_bersih\": 220,\n        \"kualitas_kertas\": 8\n    },\n    \"total_alpha\": 1,\n    \"total_alpha_z\": 17,\n    \"rules\": [\n        {\n            \"kode_rule\": \"R46\",\n            \"jenis_kendaraan\": \"K2\",\n            \"berat_kotor\": \"Berat\",\n            \"berat_bersih\": \"Ringan\",\n            \"kualitas_kertas\": \"Baik\",\n            \"bobot_ketidaklayakan\": \"Sedang\",\n            \"mu_jenis_kendaraan\": 1,\n            \"mu_berat_kotor\": 1,\n            \"mu_berat_bersih\": 1,\n            \"mu_kualitas_kertas\": 1,\n            \"alpha\": 1,\n            \"z\": 17,\n            \"alpha_z\": 17\n        }\n    ]\n}', '2026-05-19 23:30:14', '2026-05-19 23:30:14'),
(4, 9, 2, 27.66, 27.66, 215.75, 564.25, '{\n    \"input\": {\n        \"jenis_kendaraan\": 2,\n        \"berat_kotor\": 1720,\n        \"berat_bersih\": 780,\n        \"kualitas_kertas\": 6\n    },\n    \"total_alpha\": 1,\n    \"total_alpha_z\": 27.6578,\n    \"rules\": [\n        {\n            \"kode_rule\": \"R41\",\n            \"jenis_kendaraan\": \"K2\",\n            \"berat_kotor\": \"Sedang\",\n            \"berat_bersih\": \"Sedang\",\n            \"kualitas_kertas\": \"Sedang\",\n            \"bobot_ketidaklayakan\": \"Sedang\",\n            \"mu_jenis_kendaraan\": 1,\n            \"mu_berat_kotor\": 0.2667,\n            \"mu_berat_bersih\": 1,\n            \"mu_kualitas_kertas\": 1,\n            \"alpha\": 0.2667,\n            \"z\": 17,\n            \"alpha_z\": 4.5333\n        },\n        {\n            \"kode_rule\": \"R50\",\n            \"jenis_kendaraan\": \"K2\",\n            \"berat_kotor\": \"Berat\",\n            \"berat_bersih\": \"Sedang\",\n            \"kualitas_kertas\": \"Sedang\",\n            \"bobot_ketidaklayakan\": \"Tinggi\",\n            \"mu_jenis_kendaraan\": 1,\n            \"mu_berat_kotor\": 0.7333,\n            \"mu_berat_bersih\": 1,\n            \"mu_kualitas_kertas\": 1,\n            \"alpha\": 0.7333,\n            \"z\": 31.5333,\n            \"alpha_z\": 23.1244\n        }\n    ]\n}', '2026-05-19 23:30:43', '2026-05-19 23:30:43'),
(5, 1, 1, 22.75, 22.75, 163.80, 556.20, '{\n    \"input\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai\": 2\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai\": 1650,\n            \"satuan\": \"kg\"\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai\": 720,\n            \"satuan\": \"kg\"\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai\": 6\n        }\n    },\n    \"fuzzifikasi\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai_crisp\": 2,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"K1\",\n                    \"nama_himpunan\": \"K1\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 1,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 1,\n                    \"nilai_d\": 1,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 1, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"K2\",\n                    \"nama_himpunan\": \"K2\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 2,\n                    \"domain_max\": 2,\n                    \"nilai_a\": 2,\n                    \"nilai_b\": 2,\n                    \"nilai_c\": 2,\n                    \"nilai_d\": 2,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 2, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"K3\",\n                    \"nama_himpunan\": \"K3\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 3,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 3,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 3,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 3, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai_crisp\": 1650,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 1000,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 800,\n                    \"nilai_d\": 1000,\n                    \"rumus\": \"Karena x >= 1000, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 800,\n                    \"domain_max\": 1800,\n                    \"nilai_a\": 800,\n                    \"nilai_b\": 1000,\n                    \"nilai_c\": 1500,\n                    \"nilai_d\": 1800,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1800 - 1650) \\/ (1800 - 1500) = 0.5.\",\n                    \"nilai_mu\": 0.5\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1500,\n                    \"domain_max\": 2000,\n                    \"nilai_a\": 1500,\n                    \"nilai_b\": 1800,\n                    \"nilai_c\": 2000,\n                    \"nilai_d\": 2000,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1650 - 1500) \\/ (1800 - 1500) = 0.5.\",\n                    \"nilai_mu\": 0.5\n                }\n            ]\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai_crisp\": 720,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 700,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 500,\n                    \"nilai_d\": 700,\n                    \"rumus\": \"Karena x >= 700, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 500,\n                    \"domain_max\": 1200,\n                    \"nilai_a\": 500,\n                    \"nilai_b\": 700,\n                    \"nilai_c\": 1000,\n                    \"nilai_d\": 1200,\n                    \"rumus\": \"Karena 700 <= x <= 1000, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1100,\n                    \"domain_max\": 2200,\n                    \"nilai_a\": 1100,\n                    \"nilai_b\": 1200,\n                    \"nilai_c\": 2200,\n                    \"nilai_d\": 2200,\n                    \"rumus\": \"Karena x <= 1100, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai_crisp\": 6,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"baik\",\n                    \"nama_himpunan\": \"Baik\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 7,\n                    \"domain_max\": 10,\n                    \"nilai_a\": 7,\n                    \"nilai_b\": 8,\n                    \"nilai_c\": 10,\n                    \"nilai_d\": 10,\n                    \"rumus\": \"Karena x <= 7, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 8,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 4,\n                    \"nilai_c\": 7,\n                    \"nilai_d\": 8,\n                    \"rumus\": \"Karena 4 <= x <= 7, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"buruk\",\n                    \"nama_himpunan\": \"Buruk\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 4,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 4,\n                    \"rumus\": \"Karena x >= 4, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        }\n    },\n    \"inferensi\": {\n        \"metode\": \"Tsukamoto\",\n        \"operator\": \"AND\",\n        \"rumus_alpha\": \"\\u03b1-predikat = min(\\u03bc1, \\u03bc2, \\u03bc3, \\u03bc4)\",\n        \"jumlah_rule_aktif\": 2,\n        \"rules\": [\n            {\n                \"kode_rule\": \"R41\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Sedang AND Berat Bersih Sedang AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Sedang\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Sedang\",\n                    \"berat_bersih\": \"Sedang\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 10,\n                    \"domain_max\": 24,\n                    \"nilai_a\": 10,\n                    \"nilai_b\": 12,\n                    \"nilai_c\": 22,\n                    \"nilai_d\": 24\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.5,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.5, 1, 1) = 0.5\",\n                    \"nilai\": 0.5\n                },\n                \"z\": {\n                    \"rumus\": \"Trapesium: z1 = 10 + (0.5 \\u00d7 (12 - 10)) = 11, z2 = 24 - (0.5 \\u00d7 (24 - 22)) = 23, z = (z1 + z2) \\/ 2 = 17\",\n                    \"nilai\": 17\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.5 \\u00d7 17 = 8.5\",\n                    \"nilai\": 8.5\n                }\n            },\n            {\n                \"kode_rule\": \"R50\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Sedang AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Sedang\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.5,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.5, 1, 1) = 0.5\",\n                    \"nilai\": 0.5\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (0.5 \\u00d7 (35 - 22)) = 28.5\",\n                    \"nilai\": 28.5\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.5 \\u00d7 28.5 = 14.25\",\n                    \"nilai\": 14.25\n                }\n            }\n        ]\n    },\n    \"defuzzifikasi\": {\n        \"rumus\": \"Z = \\u03a3(\\u03b1i \\u00d7 zi) \\/ \\u03a3\\u03b1i\",\n        \"total_alpha\": 1,\n        \"total_alpha_z\": 22.75,\n        \"perhitungan\": \"Z = 22.75 \\/ 1 = 22.75\",\n        \"hasil\": 22.75\n    },\n    \"hasil_akhir\": {\n        \"nilai_bobot_ketidaklayakan\": 22.75,\n        \"persentase_potongan\": 22.75,\n        \"potongan_berat\": {\n            \"rumus\": \"Potongan berat = berat bersih \\u00d7 persentase potongan \\/ 100\",\n            \"perhitungan\": \"720 \\u00d7 22.75 \\/ 100 = 163.8\",\n            \"nilai\": 163.8,\n            \"satuan\": \"kg\"\n        },\n        \"berat_layak\": {\n            \"rumus\": \"Berat layak = berat bersih - potongan berat\",\n            \"perhitungan\": \"720 - 163.8 = 556.2\",\n            \"nilai\": 556.2,\n            \"satuan\": \"kg\"\n        }\n    }\n}', '2026-05-20 01:04:46', '2026-05-20 01:04:46'),
(6, 10, 12, 20.73, 20.73, 128.52, 491.46, '{\n    \"input\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai\": 2\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai\": 1599.98,\n            \"satuan\": \"kg\"\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai\": 619.98,\n            \"satuan\": \"kg\"\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai\": 6\n        }\n    },\n    \"fuzzifikasi\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai_crisp\": 2,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"K1\",\n                    \"nama_himpunan\": \"K1\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 1,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 1,\n                    \"nilai_d\": 1,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 1, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"K2\",\n                    \"nama_himpunan\": \"K2\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 2,\n                    \"domain_max\": 2,\n                    \"nilai_a\": 2,\n                    \"nilai_b\": 2,\n                    \"nilai_c\": 2,\n                    \"nilai_d\": 2,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 2, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"K3\",\n                    \"nama_himpunan\": \"K3\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 3,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 3,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 3,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 3, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai_crisp\": 1599.98,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 1000,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 800,\n                    \"nilai_d\": 1000,\n                    \"rumus\": \"Karena x >= 1000, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 800,\n                    \"domain_max\": 1800,\n                    \"nilai_a\": 800,\n                    \"nilai_b\": 1000,\n                    \"nilai_c\": 1500,\n                    \"nilai_d\": 1800,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1800 - 1599.98) \\/ (1800 - 1500) = 0.6667.\",\n                    \"nilai_mu\": 0.6667\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1500,\n                    \"domain_max\": 2000,\n                    \"nilai_a\": 1500,\n                    \"nilai_b\": 1800,\n                    \"nilai_c\": 2000,\n                    \"nilai_d\": 2000,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1599.98 - 1500) \\/ (1800 - 1500) = 0.3333.\",\n                    \"nilai_mu\": 0.3333\n                }\n            ]\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai_crisp\": 619.98,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 700,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 500,\n                    \"nilai_d\": 700,\n                    \"rumus\": \"Karena 500 < x < 700, maka \\u03bc(x) = (700 - 619.98) \\/ (700 - 500) = 0.4001.\",\n                    \"nilai_mu\": 0.4001\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 500,\n                    \"domain_max\": 1200,\n                    \"nilai_a\": 500,\n                    \"nilai_b\": 700,\n                    \"nilai_c\": 1000,\n                    \"nilai_d\": 1200,\n                    \"rumus\": \"Karena 500 < x < 700, maka \\u03bc(x) = (619.98 - 500) \\/ (700 - 500) = 0.5999.\",\n                    \"nilai_mu\": 0.5999\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1100,\n                    \"domain_max\": 2200,\n                    \"nilai_a\": 1100,\n                    \"nilai_b\": 1200,\n                    \"nilai_c\": 2200,\n                    \"nilai_d\": 2200,\n                    \"rumus\": \"Karena x <= 1100, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai_crisp\": 6,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"baik\",\n                    \"nama_himpunan\": \"Baik\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 7,\n                    \"domain_max\": 10,\n                    \"nilai_a\": 7,\n                    \"nilai_b\": 8,\n                    \"nilai_c\": 10,\n                    \"nilai_d\": 10,\n                    \"rumus\": \"Karena x <= 7, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 8,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 4,\n                    \"nilai_c\": 7,\n                    \"nilai_d\": 8,\n                    \"rumus\": \"Karena 4 <= x <= 7, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"buruk\",\n                    \"nama_himpunan\": \"Buruk\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 4,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 4,\n                    \"rumus\": \"Karena x >= 4, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        }\n    },\n    \"inferensi\": {\n        \"metode\": \"Tsukamoto\",\n        \"operator\": \"AND\",\n        \"rumus_alpha\": \"\\u03b1-predikat = min(\\u03bc1, \\u03bc2, \\u03bc3, \\u03bc4)\",\n        \"jumlah_rule_aktif\": 4,\n        \"rules\": [\n            {\n                \"kode_rule\": \"R38\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Sedang AND Berat Bersih Ringan AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Sedang\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Sedang\",\n                    \"berat_bersih\": \"Ringan\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 10,\n                    \"domain_max\": 24,\n                    \"nilai_a\": 10,\n                    \"nilai_b\": 12,\n                    \"nilai_c\": 22,\n                    \"nilai_d\": 24\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.6667,\n                    \"berat_bersih\": 0.4001,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.6667, 0.4001, 1) = 0.4001\",\n                    \"nilai\": 0.4001\n                },\n                \"z\": {\n                    \"rumus\": \"Trapesium: z1 = 10 + (0.4001 \\u00d7 (12 - 10)) = 10.8002, z2 = 24 - (0.4001 \\u00d7 (24 - 22)) = 23.1998, z = (z1 + z2) \\/ 2 = 17\",\n                    \"nilai\": 17\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.4001 \\u00d7 17 = 6.8017\",\n                    \"nilai\": 6.8017\n                }\n            },\n            {\n                \"kode_rule\": \"R41\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Sedang AND Berat Bersih Sedang AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Sedang\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Sedang\",\n                    \"berat_bersih\": \"Sedang\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 10,\n                    \"domain_max\": 24,\n                    \"nilai_a\": 10,\n                    \"nilai_b\": 12,\n                    \"nilai_c\": 22,\n                    \"nilai_d\": 24\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.6667,\n                    \"berat_bersih\": 0.5999,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.6667, 0.5999, 1) = 0.5999\",\n                    \"nilai\": 0.5999\n                },\n                \"z\": {\n                    \"rumus\": \"Trapesium: z1 = 10 + (0.5999 \\u00d7 (12 - 10)) = 11.1998, z2 = 24 - (0.5999 \\u00d7 (24 - 22)) = 22.8002, z = (z1 + z2) \\/ 2 = 17\",\n                    \"nilai\": 17\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.5999 \\u00d7 17 = 10.1983\",\n                    \"nilai\": 10.1983\n                }\n            },\n            {\n                \"kode_rule\": \"R47\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Ringan AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Ringan\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.3333,\n                    \"berat_bersih\": 0.4001,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.3333, 0.4001, 1) = 0.3333\",\n                    \"nilai\": 0.3333\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (0.33326666666667 \\u00d7 (35 - 22)) = 26.3325\",\n                    \"nilai\": 26.3325\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.3333 \\u00d7 26.3325 = 8.7757\",\n                    \"nilai\": 8.7757\n                }\n            },\n            {\n                \"kode_rule\": \"R50\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Sedang AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Sedang\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.3333,\n                    \"berat_bersih\": 0.5999,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.3333, 0.5999, 1) = 0.3333\",\n                    \"nilai\": 0.3333\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (0.33326666666667 \\u00d7 (35 - 22)) = 26.3325\",\n                    \"nilai\": 26.3325\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.3333 \\u00d7 26.3325 = 8.7757\",\n                    \"nilai\": 8.7757\n                }\n            }\n        ]\n    },\n    \"defuzzifikasi\": {\n        \"rumus\": \"Z = \\u03a3(\\u03b1i \\u00d7 zi) \\/ \\u03a3\\u03b1i\",\n        \"total_alpha\": 1.6665,\n        \"total_alpha_z\": 34.5515,\n        \"perhitungan\": \"Z = 34.5515 \\/ 1.6665 = 20.73\",\n        \"hasil\": 20.73\n    },\n    \"hasil_akhir\": {\n        \"nilai_bobot_ketidaklayakan\": 20.73,\n        \"persentase_potongan\": 20.73,\n        \"potongan_berat\": {\n            \"rumus\": \"Potongan berat = berat bersih \\u00d7 persentase potongan \\/ 100\",\n            \"perhitungan\": \"619.98 \\u00d7 20.73 \\/ 100 = 128.52\",\n            \"nilai\": 128.52,\n            \"satuan\": \"kg\"\n        },\n        \"berat_layak\": {\n            \"rumus\": \"Berat layak = berat bersih - potongan berat\",\n            \"perhitungan\": \"619.98 - 128.52 = 491.46\",\n            \"nilai\": 491.46,\n            \"satuan\": \"kg\"\n        }\n    }\n}', '2026-05-20 01:15:08', '2026-05-20 01:15:08'),
(7, 12, 10, 30.13, 30.13, 195.85, 454.15, '{\n    \"input\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai\": 2\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai\": 1800,\n            \"satuan\": \"kg\"\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai\": 650,\n            \"satuan\": \"kg\"\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai\": 5\n        }\n    },\n    \"fuzzifikasi\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai_crisp\": 2,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"K1\",\n                    \"nama_himpunan\": \"K1\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 1,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 1,\n                    \"nilai_d\": 1,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 1, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"K2\",\n                    \"nama_himpunan\": \"K2\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 2,\n                    \"domain_max\": 2,\n                    \"nilai_a\": 2,\n                    \"nilai_b\": 2,\n                    \"nilai_c\": 2,\n                    \"nilai_d\": 2,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 2, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"K3\",\n                    \"nama_himpunan\": \"K3\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 3,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 3,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 3,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 3, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai_crisp\": 1800,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 1000,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 800,\n                    \"nilai_d\": 1000,\n                    \"rumus\": \"Karena x >= 1000, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 800,\n                    \"domain_max\": 1800,\n                    \"nilai_a\": 800,\n                    \"nilai_b\": 1000,\n                    \"nilai_c\": 1500,\n                    \"nilai_d\": 1800,\n                    \"rumus\": \"Karena x <= 800 atau x >= 1800, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1500,\n                    \"domain_max\": 2000,\n                    \"nilai_a\": 1500,\n                    \"nilai_b\": 1800,\n                    \"nilai_c\": 2000,\n                    \"nilai_d\": 2000,\n                    \"rumus\": \"Karena x >= 1800, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                }\n            ]\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai_crisp\": 650,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 700,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 500,\n                    \"nilai_d\": 700,\n                    \"rumus\": \"Karena 500 < x < 700, maka \\u03bc(x) = (700 - 650) \\/ (700 - 500) = 0.25.\",\n                    \"nilai_mu\": 0.25\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 500,\n                    \"domain_max\": 1200,\n                    \"nilai_a\": 500,\n                    \"nilai_b\": 700,\n                    \"nilai_c\": 1000,\n                    \"nilai_d\": 1200,\n                    \"rumus\": \"Karena 500 < x < 700, maka \\u03bc(x) = (650 - 500) \\/ (700 - 500) = 0.75.\",\n                    \"nilai_mu\": 0.75\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1100,\n                    \"domain_max\": 2200,\n                    \"nilai_a\": 1100,\n                    \"nilai_b\": 1200,\n                    \"nilai_c\": 2200,\n                    \"nilai_d\": 2200,\n                    \"rumus\": \"Karena x <= 1100, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai_crisp\": 5,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"baik\",\n                    \"nama_himpunan\": \"Baik\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 7,\n                    \"domain_max\": 10,\n                    \"nilai_a\": 7,\n                    \"nilai_b\": 8,\n                    \"nilai_c\": 10,\n                    \"nilai_d\": 10,\n                    \"rumus\": \"Karena x <= 7, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 8,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 4,\n                    \"nilai_c\": 7,\n                    \"nilai_d\": 8,\n                    \"rumus\": \"Karena 4 <= x <= 7, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"buruk\",\n                    \"nama_himpunan\": \"Buruk\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 4,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 4,\n                    \"rumus\": \"Karena x >= 4, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        }\n    },\n    \"inferensi\": {\n        \"metode\": \"Tsukamoto\",\n        \"operator\": \"AND\",\n        \"rumus_alpha\": \"\\u03b1-predikat = min(\\u03bc1, \\u03bc2, \\u03bc3, \\u03bc4)\",\n        \"jumlah_rule_aktif\": 2,\n        \"rules\": [\n            {\n                \"kode_rule\": \"R47\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Ringan AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Ringan\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 1,\n                    \"berat_bersih\": 0.25,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 1, 0.25, 1) = 0.25\",\n                    \"nilai\": 0.25\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (0.25 \\u00d7 (35 - 22)) = 25.25\",\n                    \"nilai\": 25.25\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.25 \\u00d7 25.25 = 6.3125\",\n                    \"nilai\": 6.3125\n                }\n            },\n            {\n                \"kode_rule\": \"R50\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Sedang AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Sedang\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 1,\n                    \"berat_bersih\": 0.75,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 1, 0.75, 1) = 0.75\",\n                    \"nilai\": 0.75\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (0.75 \\u00d7 (35 - 22)) = 31.75\",\n                    \"nilai\": 31.75\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.75 \\u00d7 31.75 = 23.8125\",\n                    \"nilai\": 23.8125\n                }\n            }\n        ]\n    },\n    \"defuzzifikasi\": {\n        \"rumus\": \"Z = \\u03a3(\\u03b1i \\u00d7 zi) \\/ \\u03a3\\u03b1i\",\n        \"total_alpha\": 1,\n        \"total_alpha_z\": 30.125,\n        \"perhitungan\": \"Z = 30.125 \\/ 1 = 30.13\",\n        \"hasil\": 30.13\n    },\n    \"hasil_akhir\": {\n        \"nilai_bobot_ketidaklayakan\": 30.13,\n        \"persentase_potongan\": 30.13,\n        \"potongan_berat\": {\n            \"rumus\": \"Potongan berat = berat bersih \\u00d7 persentase potongan \\/ 100\",\n            \"perhitungan\": \"650 \\u00d7 30.13 \\/ 100 = 195.85\",\n            \"nilai\": 195.85,\n            \"satuan\": \"kg\"\n        },\n        \"berat_layak\": {\n            \"rumus\": \"Berat layak = berat bersih - potongan berat\",\n            \"perhitungan\": \"650 - 195.85 = 454.15\",\n            \"nilai\": 454.15,\n            \"satuan\": \"kg\"\n        }\n    }\n}', '2026-05-21 01:09:51', '2026-05-21 01:09:51');
INSERT INTO `fuzzy_hasil` (`id`, `qc_penilaian_id`, `detail_transaksi_barang_id`, `nilai_bobot_ketidaklayakan`, `persentase_potongan`, `potongan_berat`, `berat_layak`, `detail_perhitungan`, `created_at`, `updated_at`) VALUES
(8, 11, 11, 35.00, 35.00, 70.00, 130.00, '{\n    \"input\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai\": 2\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai\": 1800,\n            \"satuan\": \"kg\"\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai\": 200,\n            \"satuan\": \"kg\"\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai\": 7\n        }\n    },\n    \"fuzzifikasi\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai_crisp\": 2,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"K1\",\n                    \"nama_himpunan\": \"K1\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 1,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 1,\n                    \"nilai_d\": 1,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 1, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"K2\",\n                    \"nama_himpunan\": \"K2\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 2,\n                    \"domain_max\": 2,\n                    \"nilai_a\": 2,\n                    \"nilai_b\": 2,\n                    \"nilai_c\": 2,\n                    \"nilai_d\": 2,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 2, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"K3\",\n                    \"nama_himpunan\": \"K3\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 3,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 3,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 3,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 3, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai_crisp\": 1800,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 1000,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 800,\n                    \"nilai_d\": 1000,\n                    \"rumus\": \"Karena x >= 1000, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 800,\n                    \"domain_max\": 1800,\n                    \"nilai_a\": 800,\n                    \"nilai_b\": 1000,\n                    \"nilai_c\": 1500,\n                    \"nilai_d\": 1800,\n                    \"rumus\": \"Karena x <= 800 atau x >= 1800, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1500,\n                    \"domain_max\": 2000,\n                    \"nilai_a\": 1500,\n                    \"nilai_b\": 1800,\n                    \"nilai_c\": 2000,\n                    \"nilai_d\": 2000,\n                    \"rumus\": \"Karena x >= 1800, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                }\n            ]\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai_crisp\": 200,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 700,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 500,\n                    \"nilai_d\": 700,\n                    \"rumus\": \"Karena x <= 500, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 500,\n                    \"domain_max\": 1200,\n                    \"nilai_a\": 500,\n                    \"nilai_b\": 700,\n                    \"nilai_c\": 1000,\n                    \"nilai_d\": 1200,\n                    \"rumus\": \"Karena x <= 500 atau x >= 1200, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1100,\n                    \"domain_max\": 2200,\n                    \"nilai_a\": 1100,\n                    \"nilai_b\": 1200,\n                    \"nilai_c\": 2200,\n                    \"nilai_d\": 2200,\n                    \"rumus\": \"Karena x <= 1100, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai_crisp\": 7,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"baik\",\n                    \"nama_himpunan\": \"Baik\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 7,\n                    \"domain_max\": 10,\n                    \"nilai_a\": 7,\n                    \"nilai_b\": 8,\n                    \"nilai_c\": 10,\n                    \"nilai_d\": 10,\n                    \"rumus\": \"Karena x <= 7, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 8,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 4,\n                    \"nilai_c\": 7,\n                    \"nilai_d\": 8,\n                    \"rumus\": \"Karena 4 <= x <= 7, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"buruk\",\n                    \"nama_himpunan\": \"Buruk\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 4,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 4,\n                    \"rumus\": \"Karena x >= 4, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        }\n    },\n    \"inferensi\": {\n        \"metode\": \"Tsukamoto\",\n        \"operator\": \"AND\",\n        \"rumus_alpha\": \"\\u03b1-predikat = min(\\u03bc1, \\u03bc2, \\u03bc3, \\u03bc4)\",\n        \"jumlah_rule_aktif\": 1,\n        \"rules\": [\n            {\n                \"kode_rule\": \"R47\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Ringan AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Ringan\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 1,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 1, 1, 1) = 1\",\n                    \"nilai\": 1\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (1 \\u00d7 (35 - 22)) = 35\",\n                    \"nilai\": 35\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"1 \\u00d7 35 = 35\",\n                    \"nilai\": 35\n                }\n            }\n        ]\n    },\n    \"defuzzifikasi\": {\n        \"rumus\": \"Z = \\u03a3(\\u03b1i \\u00d7 zi) \\/ \\u03a3\\u03b1i\",\n        \"total_alpha\": 1,\n        \"total_alpha_z\": 35,\n        \"perhitungan\": \"Z = 35 \\/ 1 = 35\",\n        \"hasil\": 35\n    },\n    \"hasil_akhir\": {\n        \"nilai_bobot_ketidaklayakan\": 35,\n        \"persentase_potongan\": 35,\n        \"potongan_berat\": {\n            \"rumus\": \"Potongan berat = berat bersih \\u00d7 persentase potongan \\/ 100\",\n            \"perhitungan\": \"200 \\u00d7 35 \\/ 100 = 70\",\n            \"nilai\": 70,\n            \"satuan\": \"kg\"\n        },\n        \"berat_layak\": {\n            \"rumus\": \"Berat layak = berat bersih - potongan berat\",\n            \"perhitungan\": \"200 - 70 = 130\",\n            \"nilai\": 130,\n            \"satuan\": \"kg\"\n        }\n    }\n}', '2026-05-21 01:09:51', '2026-05-21 01:09:51'),
(9, 13, 18, 20.11, 20.11, 160.88, 639.12, '{\n    \"input\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai\": 2\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai\": 1600,\n            \"satuan\": \"kg\"\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai\": 800,\n            \"satuan\": \"kg\"\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai\": 6\n        }\n    },\n    \"fuzzifikasi\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai_crisp\": 2,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"K1\",\n                    \"nama_himpunan\": \"K1\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 1,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 1,\n                    \"nilai_d\": 1,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 1, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"K2\",\n                    \"nama_himpunan\": \"K2\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 2,\n                    \"domain_max\": 2,\n                    \"nilai_a\": 2,\n                    \"nilai_b\": 2,\n                    \"nilai_c\": 2,\n                    \"nilai_d\": 2,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 2, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"K3\",\n                    \"nama_himpunan\": \"K3\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 3,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 3,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 3,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 3, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai_crisp\": 1600,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 1000,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 800,\n                    \"nilai_d\": 1000,\n                    \"rumus\": \"Karena x >= 1000, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 800,\n                    \"domain_max\": 1800,\n                    \"nilai_a\": 800,\n                    \"nilai_b\": 1000,\n                    \"nilai_c\": 1500,\n                    \"nilai_d\": 1800,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1800 - 1600) \\/ (1800 - 1500) = 0.6667.\",\n                    \"nilai_mu\": 0.6667\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1500,\n                    \"domain_max\": 2000,\n                    \"nilai_a\": 1500,\n                    \"nilai_b\": 1800,\n                    \"nilai_c\": 2000,\n                    \"nilai_d\": 2000,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1600 - 1500) \\/ (1800 - 1500) = 0.3333.\",\n                    \"nilai_mu\": 0.3333\n                }\n            ]\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai_crisp\": 800,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 700,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 500,\n                    \"nilai_d\": 700,\n                    \"rumus\": \"Karena x >= 700, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 500,\n                    \"domain_max\": 1200,\n                    \"nilai_a\": 500,\n                    \"nilai_b\": 700,\n                    \"nilai_c\": 1000,\n                    \"nilai_d\": 1200,\n                    \"rumus\": \"Karena 700 <= x <= 1000, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1100,\n                    \"domain_max\": 2200,\n                    \"nilai_a\": 1100,\n                    \"nilai_b\": 1200,\n                    \"nilai_c\": 2200,\n                    \"nilai_d\": 2200,\n                    \"rumus\": \"Karena x <= 1100, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai_crisp\": 6,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"baik\",\n                    \"nama_himpunan\": \"Baik\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 7,\n                    \"domain_max\": 10,\n                    \"nilai_a\": 7,\n                    \"nilai_b\": 8,\n                    \"nilai_c\": 10,\n                    \"nilai_d\": 10,\n                    \"rumus\": \"Karena x <= 7, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 8,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 4,\n                    \"nilai_c\": 7,\n                    \"nilai_d\": 8,\n                    \"rumus\": \"Karena 4 <= x <= 7, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"buruk\",\n                    \"nama_himpunan\": \"Buruk\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 4,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 4,\n                    \"rumus\": \"Karena x >= 4, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        }\n    },\n    \"inferensi\": {\n        \"metode\": \"Tsukamoto\",\n        \"operator\": \"AND\",\n        \"rumus_alpha\": \"\\u03b1-predikat = min(\\u03bc1, \\u03bc2, \\u03bc3, \\u03bc4)\",\n        \"jumlah_rule_aktif\": 2,\n        \"rules\": [\n            {\n                \"kode_rule\": \"R41\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Sedang AND Berat Bersih Sedang AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Sedang\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Sedang\",\n                    \"berat_bersih\": \"Sedang\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 10,\n                    \"domain_max\": 24,\n                    \"nilai_a\": 10,\n                    \"nilai_b\": 12,\n                    \"nilai_c\": 22,\n                    \"nilai_d\": 24\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.6667,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.6667, 1, 1) = 0.6667\",\n                    \"nilai\": 0.6667\n                },\n                \"z\": {\n                    \"rumus\": \"Trapesium: z1 = 10 + (0.66666666666667 \\u00d7 (12 - 10)) = 11.3333, z2 = 24 - (0.66666666666667 \\u00d7 (24 - 22)) = 22.6667, z = (z1 + z2) \\/ 2 = 17\",\n                    \"nilai\": 17\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.6667 \\u00d7 17 = 11.3333\",\n                    \"nilai\": 11.3333\n                }\n            },\n            {\n                \"kode_rule\": \"R50\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Sedang AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Sedang\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.3333,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.3333, 1, 1) = 0.3333\",\n                    \"nilai\": 0.3333\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (0.33333333333333 \\u00d7 (35 - 22)) = 26.3333\",\n                    \"nilai\": 26.3333\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.3333 \\u00d7 26.3333 = 8.7778\",\n                    \"nilai\": 8.7778\n                }\n            }\n        ]\n    },\n    \"defuzzifikasi\": {\n        \"rumus\": \"Z = \\u03a3(\\u03b1i \\u00d7 zi) \\/ \\u03a3\\u03b1i\",\n        \"total_alpha\": 1,\n        \"total_alpha_z\": 20.1111,\n        \"perhitungan\": \"Z = 20.1111 \\/ 1 = 20.11\",\n        \"hasil\": 20.11\n    },\n    \"hasil_akhir\": {\n        \"nilai_bobot_ketidaklayakan\": 20.11,\n        \"persentase_potongan\": 20.11,\n        \"potongan_berat\": {\n            \"rumus\": \"Potongan berat = berat bersih \\u00d7 persentase potongan \\/ 100\",\n            \"perhitungan\": \"800 \\u00d7 20.11 \\/ 100 = 160.88\",\n            \"nilai\": 160.88,\n            \"satuan\": \"kg\"\n        },\n        \"berat_layak\": {\n            \"rumus\": \"Berat layak = berat bersih - potongan berat\",\n            \"perhitungan\": \"800 - 160.88 = 639.12\",\n            \"nilai\": 639.12,\n            \"satuan\": \"kg\"\n        }\n    }\n}', '2026-06-03 02:39:03', '2026-06-03 02:39:03'),
(10, 14, 20, 35.00, 35.00, 122.50, 227.50, '{\n    \"input\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai\": 2\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai\": 1950,\n            \"satuan\": \"kg\"\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai\": 350,\n            \"satuan\": \"kg\"\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai\": 7\n        }\n    },\n    \"fuzzifikasi\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai_crisp\": 2,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"K1\",\n                    \"nama_himpunan\": \"K1\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 1,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 1,\n                    \"nilai_d\": 1,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 1, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"K2\",\n                    \"nama_himpunan\": \"K2\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 2,\n                    \"domain_max\": 2,\n                    \"nilai_a\": 2,\n                    \"nilai_b\": 2,\n                    \"nilai_c\": 2,\n                    \"nilai_d\": 2,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 2, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"K3\",\n                    \"nama_himpunan\": \"K3\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 3,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 3,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 3,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 3, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai_crisp\": 1950,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 1000,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 800,\n                    \"nilai_d\": 1000,\n                    \"rumus\": \"Karena x >= 1000, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 800,\n                    \"domain_max\": 1800,\n                    \"nilai_a\": 800,\n                    \"nilai_b\": 1000,\n                    \"nilai_c\": 1500,\n                    \"nilai_d\": 1800,\n                    \"rumus\": \"Karena x <= 800 atau x >= 1800, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1500,\n                    \"domain_max\": 2000,\n                    \"nilai_a\": 1500,\n                    \"nilai_b\": 1800,\n                    \"nilai_c\": 2000,\n                    \"nilai_d\": 2000,\n                    \"rumus\": \"Karena x >= 1800, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                }\n            ]\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai_crisp\": 350,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 700,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 500,\n                    \"nilai_d\": 700,\n                    \"rumus\": \"Karena x <= 500, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 500,\n                    \"domain_max\": 1200,\n                    \"nilai_a\": 500,\n                    \"nilai_b\": 700,\n                    \"nilai_c\": 1000,\n                    \"nilai_d\": 1200,\n                    \"rumus\": \"Karena x <= 500 atau x >= 1200, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1100,\n                    \"domain_max\": 2200,\n                    \"nilai_a\": 1100,\n                    \"nilai_b\": 1200,\n                    \"nilai_c\": 2200,\n                    \"nilai_d\": 2200,\n                    \"rumus\": \"Karena x <= 1100, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai_crisp\": 7,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"baik\",\n                    \"nama_himpunan\": \"Baik\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 7,\n                    \"domain_max\": 10,\n                    \"nilai_a\": 7,\n                    \"nilai_b\": 8,\n                    \"nilai_c\": 10,\n                    \"nilai_d\": 10,\n                    \"rumus\": \"Karena x <= 7, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 8,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 4,\n                    \"nilai_c\": 7,\n                    \"nilai_d\": 8,\n                    \"rumus\": \"Karena 4 <= x <= 7, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"buruk\",\n                    \"nama_himpunan\": \"Buruk\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 4,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 4,\n                    \"rumus\": \"Karena x >= 4, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        }\n    },\n    \"inferensi\": {\n        \"metode\": \"Tsukamoto\",\n        \"operator\": \"AND\",\n        \"rumus_alpha\": \"\\u03b1-predikat = min(\\u03bc1, \\u03bc2, \\u03bc3, \\u03bc4)\",\n        \"jumlah_rule_aktif\": 1,\n        \"rules\": [\n            {\n                \"kode_rule\": \"R47\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Ringan AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Ringan\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 1,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 1, 1, 1) = 1\",\n                    \"nilai\": 1\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (1 \\u00d7 (35 - 22)) = 35\",\n                    \"nilai\": 35\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"1 \\u00d7 35 = 35\",\n                    \"nilai\": 35\n                }\n            }\n        ]\n    },\n    \"defuzzifikasi\": {\n        \"rumus\": \"Z = \\u03a3(\\u03b1i \\u00d7 zi) \\/ \\u03a3\\u03b1i\",\n        \"total_alpha\": 1,\n        \"total_alpha_z\": 35,\n        \"perhitungan\": \"Z = 35 \\/ 1 = 35\",\n        \"hasil\": 35\n    },\n    \"hasil_akhir\": {\n        \"nilai_bobot_ketidaklayakan\": 35,\n        \"persentase_potongan\": 35,\n        \"potongan_berat\": {\n            \"rumus\": \"Potongan berat = berat bersih \\u00d7 persentase potongan \\/ 100\",\n            \"perhitungan\": \"350 \\u00d7 35 \\/ 100 = 122.5\",\n            \"nilai\": 122.5,\n            \"satuan\": \"kg\"\n        },\n        \"berat_layak\": {\n            \"rumus\": \"Berat layak = berat bersih - potongan berat\",\n            \"perhitungan\": \"350 - 122.5 = 227.5\",\n            \"nilai\": 227.5,\n            \"satuan\": \"kg\"\n        }\n    }\n}', '2026-06-03 02:39:15', '2026-06-03 02:39:15'),
(11, 15, 19, 17.00, 17.00, 119.00, 581.00, '{\n    \"input\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai\": 2\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai\": 1950,\n            \"satuan\": \"kg\"\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai\": 700,\n            \"satuan\": \"kg\"\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai\": 8\n        }\n    },\n    \"fuzzifikasi\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai_crisp\": 2,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"K1\",\n                    \"nama_himpunan\": \"K1\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 1,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 1,\n                    \"nilai_d\": 1,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 1, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"K2\",\n                    \"nama_himpunan\": \"K2\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 2,\n                    \"domain_max\": 2,\n                    \"nilai_a\": 2,\n                    \"nilai_b\": 2,\n                    \"nilai_c\": 2,\n                    \"nilai_d\": 2,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 2, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"K3\",\n                    \"nama_himpunan\": \"K3\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 3,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 3,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 3,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 3, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai_crisp\": 1950,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 1000,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 800,\n                    \"nilai_d\": 1000,\n                    \"rumus\": \"Karena x >= 1000, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 800,\n                    \"domain_max\": 1800,\n                    \"nilai_a\": 800,\n                    \"nilai_b\": 1000,\n                    \"nilai_c\": 1500,\n                    \"nilai_d\": 1800,\n                    \"rumus\": \"Karena x <= 800 atau x >= 1800, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1500,\n                    \"domain_max\": 2000,\n                    \"nilai_a\": 1500,\n                    \"nilai_b\": 1800,\n                    \"nilai_c\": 2000,\n                    \"nilai_d\": 2000,\n                    \"rumus\": \"Karena x >= 1800, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                }\n            ]\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai_crisp\": 700,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 700,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 500,\n                    \"nilai_d\": 700,\n                    \"rumus\": \"Karena x >= 700, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 500,\n                    \"domain_max\": 1200,\n                    \"nilai_a\": 500,\n                    \"nilai_b\": 700,\n                    \"nilai_c\": 1000,\n                    \"nilai_d\": 1200,\n                    \"rumus\": \"Karena 700 <= x <= 1000, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1100,\n                    \"domain_max\": 2200,\n                    \"nilai_a\": 1100,\n                    \"nilai_b\": 1200,\n                    \"nilai_c\": 2200,\n                    \"nilai_d\": 2200,\n                    \"rumus\": \"Karena x <= 1100, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai_crisp\": 8,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"baik\",\n                    \"nama_himpunan\": \"Baik\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 7,\n                    \"domain_max\": 10,\n                    \"nilai_a\": 7,\n                    \"nilai_b\": 8,\n                    \"nilai_c\": 10,\n                    \"nilai_d\": 10,\n                    \"rumus\": \"Karena x >= 8, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 8,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 4,\n                    \"nilai_c\": 7,\n                    \"nilai_d\": 8,\n                    \"rumus\": \"Karena x <= 3 atau x >= 8, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"buruk\",\n                    \"nama_himpunan\": \"Buruk\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 4,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 4,\n                    \"rumus\": \"Karena x >= 4, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        }\n    },\n    \"inferensi\": {\n        \"metode\": \"Tsukamoto\",\n        \"operator\": \"AND\",\n        \"rumus_alpha\": \"\\u03b1-predikat = min(\\u03bc1, \\u03bc2, \\u03bc3, \\u03bc4)\",\n        \"jumlah_rule_aktif\": 1,\n        \"rules\": [\n            {\n                \"kode_rule\": \"R49\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Sedang AND Kualitas Kertas Baik THEN Bobot Ketidaklayakan Sedang\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Sedang\",\n                    \"kualitas_kertas\": \"Baik\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 10,\n                    \"domain_max\": 24,\n                    \"nilai_a\": 10,\n                    \"nilai_b\": 12,\n                    \"nilai_c\": 22,\n                    \"nilai_d\": 24\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 1,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 1, 1, 1) = 1\",\n                    \"nilai\": 1\n                },\n                \"z\": {\n                    \"rumus\": \"Trapesium: z1 = 10 + (1 \\u00d7 (12 - 10)) = 12, z2 = 24 - (1 \\u00d7 (24 - 22)) = 22, z = (z1 + z2) \\/ 2 = 17\",\n                    \"nilai\": 17\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"1 \\u00d7 17 = 17\",\n                    \"nilai\": 17\n                }\n            }\n        ]\n    },\n    \"defuzzifikasi\": {\n        \"rumus\": \"Z = \\u03a3(\\u03b1i \\u00d7 zi) \\/ \\u03a3\\u03b1i\",\n        \"total_alpha\": 1,\n        \"total_alpha_z\": 17,\n        \"perhitungan\": \"Z = 17 \\/ 1 = 17\",\n        \"hasil\": 17\n    },\n    \"hasil_akhir\": {\n        \"nilai_bobot_ketidaklayakan\": 17,\n        \"persentase_potongan\": 17,\n        \"potongan_berat\": {\n            \"rumus\": \"Potongan berat = berat bersih \\u00d7 persentase potongan \\/ 100\",\n            \"perhitungan\": \"700 \\u00d7 17 \\/ 100 = 119\",\n            \"nilai\": 119,\n            \"satuan\": \"kg\"\n        },\n        \"berat_layak\": {\n            \"rumus\": \"Berat layak = berat bersih - potongan berat\",\n            \"perhitungan\": \"700 - 119 = 581\",\n            \"nilai\": 581,\n            \"satuan\": \"kg\"\n        }\n    }\n}', '2026-06-03 02:39:22', '2026-06-03 02:39:22');
INSERT INTO `fuzzy_hasil` (`id`, `qc_penilaian_id`, `detail_transaksi_barang_id`, `nilai_bobot_ketidaklayakan`, `persentase_potongan`, `potongan_berat`, `berat_layak`, `detail_perhitungan`, `created_at`, `updated_at`) VALUES
(12, 16, 22, 20.11, 20.11, 50.28, 199.72, '{\n    \"input\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai\": 2\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai\": 1600,\n            \"satuan\": \"kg\"\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai\": 250,\n            \"satuan\": \"kg\"\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai\": 7\n        }\n    },\n    \"fuzzifikasi\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai_crisp\": 2,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"K1\",\n                    \"nama_himpunan\": \"K1\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 1,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 1,\n                    \"nilai_d\": 1,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 1, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"K2\",\n                    \"nama_himpunan\": \"K2\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 2,\n                    \"domain_max\": 2,\n                    \"nilai_a\": 2,\n                    \"nilai_b\": 2,\n                    \"nilai_c\": 2,\n                    \"nilai_d\": 2,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 2, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"K3\",\n                    \"nama_himpunan\": \"K3\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 3,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 3,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 3,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 3, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai_crisp\": 1600,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 1000,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 800,\n                    \"nilai_d\": 1000,\n                    \"rumus\": \"Karena x >= 1000, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 800,\n                    \"domain_max\": 1800,\n                    \"nilai_a\": 800,\n                    \"nilai_b\": 1000,\n                    \"nilai_c\": 1500,\n                    \"nilai_d\": 1800,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1800 - 1600) \\/ (1800 - 1500) = 0.6667.\",\n                    \"nilai_mu\": 0.6667\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1500,\n                    \"domain_max\": 2000,\n                    \"nilai_a\": 1500,\n                    \"nilai_b\": 1800,\n                    \"nilai_c\": 2000,\n                    \"nilai_d\": 2000,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1600 - 1500) \\/ (1800 - 1500) = 0.3333.\",\n                    \"nilai_mu\": 0.3333\n                }\n            ]\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai_crisp\": 250,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 700,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 500,\n                    \"nilai_d\": 700,\n                    \"rumus\": \"Karena x <= 500, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 500,\n                    \"domain_max\": 1200,\n                    \"nilai_a\": 500,\n                    \"nilai_b\": 700,\n                    \"nilai_c\": 1000,\n                    \"nilai_d\": 1200,\n                    \"rumus\": \"Karena x <= 500 atau x >= 1200, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1100,\n                    \"domain_max\": 2200,\n                    \"nilai_a\": 1100,\n                    \"nilai_b\": 1200,\n                    \"nilai_c\": 2200,\n                    \"nilai_d\": 2200,\n                    \"rumus\": \"Karena x <= 1100, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai_crisp\": 7,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"baik\",\n                    \"nama_himpunan\": \"Baik\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 7,\n                    \"domain_max\": 10,\n                    \"nilai_a\": 7,\n                    \"nilai_b\": 8,\n                    \"nilai_c\": 10,\n                    \"nilai_d\": 10,\n                    \"rumus\": \"Karena x <= 7, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 8,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 4,\n                    \"nilai_c\": 7,\n                    \"nilai_d\": 8,\n                    \"rumus\": \"Karena 4 <= x <= 7, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"buruk\",\n                    \"nama_himpunan\": \"Buruk\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 4,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 4,\n                    \"rumus\": \"Karena x >= 4, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        }\n    },\n    \"inferensi\": {\n        \"metode\": \"Tsukamoto\",\n        \"operator\": \"AND\",\n        \"rumus_alpha\": \"\\u03b1-predikat = min(\\u03bc1, \\u03bc2, \\u03bc3, \\u03bc4)\",\n        \"jumlah_rule_aktif\": 2,\n        \"rules\": [\n            {\n                \"kode_rule\": \"R38\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Sedang AND Berat Bersih Ringan AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Sedang\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Sedang\",\n                    \"berat_bersih\": \"Ringan\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 10,\n                    \"domain_max\": 24,\n                    \"nilai_a\": 10,\n                    \"nilai_b\": 12,\n                    \"nilai_c\": 22,\n                    \"nilai_d\": 24\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.6667,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.6667, 1, 1) = 0.6667\",\n                    \"nilai\": 0.6667\n                },\n                \"z\": {\n                    \"rumus\": \"Trapesium: z1 = 10 + (0.66666666666667 \\u00d7 (12 - 10)) = 11.3333, z2 = 24 - (0.66666666666667 \\u00d7 (24 - 22)) = 22.6667, z = (z1 + z2) \\/ 2 = 17\",\n                    \"nilai\": 17\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.6667 \\u00d7 17 = 11.3333\",\n                    \"nilai\": 11.3333\n                }\n            },\n            {\n                \"kode_rule\": \"R47\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Ringan AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Ringan\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.3333,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.3333, 1, 1) = 0.3333\",\n                    \"nilai\": 0.3333\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (0.33333333333333 \\u00d7 (35 - 22)) = 26.3333\",\n                    \"nilai\": 26.3333\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.3333 \\u00d7 26.3333 = 8.7778\",\n                    \"nilai\": 8.7778\n                }\n            }\n        ]\n    },\n    \"defuzzifikasi\": {\n        \"rumus\": \"Z = \\u03a3(\\u03b1i \\u00d7 zi) \\/ \\u03a3\\u03b1i\",\n        \"total_alpha\": 1,\n        \"total_alpha_z\": 20.1111,\n        \"perhitungan\": \"Z = 20.1111 \\/ 1 = 20.11\",\n        \"hasil\": 20.11\n    },\n    \"hasil_akhir\": {\n        \"nilai_bobot_ketidaklayakan\": 20.11,\n        \"persentase_potongan\": 20.11,\n        \"potongan_berat\": {\n            \"rumus\": \"Potongan berat = berat bersih \\u00d7 persentase potongan \\/ 100\",\n            \"perhitungan\": \"250 \\u00d7 20.11 \\/ 100 = 50.28\",\n            \"nilai\": 50.28,\n            \"satuan\": \"kg\"\n        },\n        \"berat_layak\": {\n            \"rumus\": \"Berat layak = berat bersih - potongan berat\",\n            \"perhitungan\": \"250 - 50.28 = 199.72\",\n            \"nilai\": 199.72,\n            \"satuan\": \"kg\"\n        }\n    }\n}', '2026-06-05 06:20:43', '2026-06-05 06:20:43'),
(13, 17, 21, 20.11, 20.11, 80.44, 319.56, '{\n    \"input\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai\": 2\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai\": 1600,\n            \"satuan\": \"kg\"\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai\": 400,\n            \"satuan\": \"kg\"\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai\": 6\n        }\n    },\n    \"fuzzifikasi\": {\n        \"jenis_kendaraan\": {\n            \"label\": \"Jenis Kendaraan\",\n            \"nilai_crisp\": 2,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"K1\",\n                    \"nama_himpunan\": \"K1\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 1,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 1,\n                    \"nilai_d\": 1,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 1, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"K2\",\n                    \"nama_himpunan\": \"K2\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 2,\n                    \"domain_max\": 2,\n                    \"nilai_a\": 2,\n                    \"nilai_b\": 2,\n                    \"nilai_c\": 2,\n                    \"nilai_d\": 2,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 2, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"K3\",\n                    \"nama_himpunan\": \"K3\",\n                    \"tipe_fungsi\": \"singleton\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 3,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 3,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 3,\n                    \"rumus\": \"Singleton: \\u03bc(x) = 1 jika x = 3, selain itu 0. Nilai x = 2.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"berat_kotor\": {\n            \"label\": \"Berat Kotor\",\n            \"nilai_crisp\": 1600,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 1000,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 800,\n                    \"nilai_d\": 1000,\n                    \"rumus\": \"Karena x >= 1000, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 800,\n                    \"domain_max\": 1800,\n                    \"nilai_a\": 800,\n                    \"nilai_b\": 1000,\n                    \"nilai_c\": 1500,\n                    \"nilai_d\": 1800,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1800 - 1600) \\/ (1800 - 1500) = 0.6667.\",\n                    \"nilai_mu\": 0.6667\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1500,\n                    \"domain_max\": 2000,\n                    \"nilai_a\": 1500,\n                    \"nilai_b\": 1800,\n                    \"nilai_c\": 2000,\n                    \"nilai_d\": 2000,\n                    \"rumus\": \"Karena 1500 < x < 1800, maka \\u03bc(x) = (1600 - 1500) \\/ (1800 - 1500) = 0.3333.\",\n                    \"nilai_mu\": 0.3333\n                }\n            ]\n        },\n        \"berat_bersih\": {\n            \"label\": \"Berat Bersih\",\n            \"nilai_crisp\": 400,\n            \"satuan\": \"kg\",\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"ringan\",\n                    \"nama_himpunan\": \"Ringan\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 100,\n                    \"domain_max\": 700,\n                    \"nilai_a\": 100,\n                    \"nilai_b\": 100,\n                    \"nilai_c\": 500,\n                    \"nilai_d\": 700,\n                    \"rumus\": \"Karena x <= 500, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 500,\n                    \"domain_max\": 1200,\n                    \"nilai_a\": 500,\n                    \"nilai_b\": 700,\n                    \"nilai_c\": 1000,\n                    \"nilai_d\": 1200,\n                    \"rumus\": \"Karena x <= 500 atau x >= 1200, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"berat\",\n                    \"nama_himpunan\": \"Berat\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 1100,\n                    \"domain_max\": 2200,\n                    \"nilai_a\": 1100,\n                    \"nilai_b\": 1200,\n                    \"nilai_c\": 2200,\n                    \"nilai_d\": 2200,\n                    \"rumus\": \"Karena x <= 1100, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        },\n        \"kualitas_kertas\": {\n            \"label\": \"Kualitas Kertas\",\n            \"nilai_crisp\": 6,\n            \"himpunan\": [\n                {\n                    \"kode_himpunan\": \"baik\",\n                    \"nama_himpunan\": \"Baik\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 7,\n                    \"domain_max\": 10,\n                    \"nilai_a\": 7,\n                    \"nilai_b\": 8,\n                    \"nilai_c\": 10,\n                    \"nilai_d\": 10,\n                    \"rumus\": \"Karena x <= 7, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                },\n                {\n                    \"kode_himpunan\": \"sedang\",\n                    \"nama_himpunan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 3,\n                    \"domain_max\": 8,\n                    \"nilai_a\": 3,\n                    \"nilai_b\": 4,\n                    \"nilai_c\": 7,\n                    \"nilai_d\": 8,\n                    \"rumus\": \"Karena 4 <= x <= 7, maka \\u03bc(x) = 1.\",\n                    \"nilai_mu\": 1\n                },\n                {\n                    \"kode_himpunan\": \"buruk\",\n                    \"nama_himpunan\": \"Buruk\",\n                    \"tipe_fungsi\": \"linear_turun\",\n                    \"domain_min\": 1,\n                    \"domain_max\": 4,\n                    \"nilai_a\": 1,\n                    \"nilai_b\": 1,\n                    \"nilai_c\": 3,\n                    \"nilai_d\": 4,\n                    \"rumus\": \"Karena x >= 4, maka \\u03bc(x) = 0.\",\n                    \"nilai_mu\": 0\n                }\n            ]\n        }\n    },\n    \"inferensi\": {\n        \"metode\": \"Tsukamoto\",\n        \"operator\": \"AND\",\n        \"rumus_alpha\": \"\\u03b1-predikat = min(\\u03bc1, \\u03bc2, \\u03bc3, \\u03bc4)\",\n        \"jumlah_rule_aktif\": 2,\n        \"rules\": [\n            {\n                \"kode_rule\": \"R38\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Sedang AND Berat Bersih Ringan AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Sedang\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Sedang\",\n                    \"berat_bersih\": \"Ringan\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Sedang\",\n                    \"tipe_fungsi\": \"trapesium\",\n                    \"domain_min\": 10,\n                    \"domain_max\": 24,\n                    \"nilai_a\": 10,\n                    \"nilai_b\": 12,\n                    \"nilai_c\": 22,\n                    \"nilai_d\": 24\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.6667,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.6667, 1, 1) = 0.6667\",\n                    \"nilai\": 0.6667\n                },\n                \"z\": {\n                    \"rumus\": \"Trapesium: z1 = 10 + (0.66666666666667 \\u00d7 (12 - 10)) = 11.3333, z2 = 24 - (0.66666666666667 \\u00d7 (24 - 22)) = 22.6667, z = (z1 + z2) \\/ 2 = 17\",\n                    \"nilai\": 17\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.6667 \\u00d7 17 = 11.3333\",\n                    \"nilai\": 11.3333\n                }\n            },\n            {\n                \"kode_rule\": \"R47\",\n                \"rule_text\": \"IF Jenis Kendaraan K2 AND Berat Kotor Berat AND Berat Bersih Ringan AND Kualitas Kertas Sedang THEN Bobot Ketidaklayakan Tinggi\",\n                \"premis\": {\n                    \"jenis_kendaraan\": \"K2\",\n                    \"berat_kotor\": \"Berat\",\n                    \"berat_bersih\": \"Ringan\",\n                    \"kualitas_kertas\": \"Sedang\"\n                },\n                \"output\": {\n                    \"bobot_ketidaklayakan\": \"Tinggi\",\n                    \"tipe_fungsi\": \"linear_naik\",\n                    \"domain_min\": 22,\n                    \"domain_max\": 35,\n                    \"nilai_a\": 22,\n                    \"nilai_b\": 24,\n                    \"nilai_c\": 35,\n                    \"nilai_d\": 35\n                },\n                \"membership\": {\n                    \"jenis_kendaraan\": 1,\n                    \"berat_kotor\": 0.3333,\n                    \"berat_bersih\": 1,\n                    \"kualitas_kertas\": 1\n                },\n                \"alpha\": {\n                    \"rumus\": \"\\u03b1 = min(\\u03bc jenis kendaraan, \\u03bc berat kotor, \\u03bc berat bersih, \\u03bc kualitas kertas)\",\n                    \"perhitungan\": \"\\u03b1 = min(1, 0.3333, 1, 1) = 0.3333\",\n                    \"nilai\": 0.3333\n                },\n                \"z\": {\n                    \"rumus\": \"Linear naik: z = 22 + (0.33333333333333 \\u00d7 (35 - 22)) = 26.3333\",\n                    \"nilai\": 26.3333\n                },\n                \"alpha_z\": {\n                    \"rumus\": \"\\u03b1z = \\u03b1 \\u00d7 z\",\n                    \"perhitungan\": \"0.3333 \\u00d7 26.3333 = 8.7778\",\n                    \"nilai\": 8.7778\n                }\n            }\n        ]\n    },\n    \"defuzzifikasi\": {\n        \"rumus\": \"Z = \\u03a3(\\u03b1i \\u00d7 zi) \\/ \\u03a3\\u03b1i\",\n        \"total_alpha\": 1,\n        \"total_alpha_z\": 20.1111,\n        \"perhitungan\": \"Z = 20.1111 \\/ 1 = 20.11\",\n        \"hasil\": 20.11\n    },\n    \"hasil_akhir\": {\n        \"nilai_bobot_ketidaklayakan\": 20.11,\n        \"persentase_potongan\": 20.11,\n        \"potongan_berat\": {\n            \"rumus\": \"Potongan berat = berat bersih \\u00d7 persentase potongan \\/ 100\",\n            \"perhitungan\": \"400 \\u00d7 20.11 \\/ 100 = 80.44\",\n            \"nilai\": 80.44,\n            \"satuan\": \"kg\"\n        },\n        \"berat_layak\": {\n            \"rumus\": \"Berat layak = berat bersih - potongan berat\",\n            \"perhitungan\": \"400 - 80.44 = 319.56\",\n            \"nilai\": 319.56,\n            \"satuan\": \"kg\"\n        }\n    }\n}', '2026-06-05 06:20:51', '2026-06-05 06:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `fuzzy_himpunan`
--

CREATE TABLE `fuzzy_himpunan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fuzzy_variabel_id` bigint(20) UNSIGNED NOT NULL,
  `kode_himpunan` varchar(50) NOT NULL,
  `nama_himpunan` varchar(100) NOT NULL,
  `tipe_fungsi` enum('singleton','linear_turun','linear_naik','segitiga','trapesium') NOT NULL,
  `domain_min` decimal(12,2) NOT NULL,
  `domain_max` decimal(12,2) NOT NULL,
  `nilai_a` decimal(12,2) DEFAULT NULL,
  `nilai_b` decimal(12,2) DEFAULT NULL,
  `nilai_c` decimal(12,2) DEFAULT NULL,
  `nilai_d` decimal(12,2) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuzzy_himpunan`
--

INSERT INTO `fuzzy_himpunan` (`id`, `fuzzy_variabel_id`, `kode_himpunan`, `nama_himpunan`, `tipe_fungsi`, `domain_min`, `domain_max`, `nilai_a`, `nilai_b`, `nilai_c`, `nilai_d`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 'K1', 'K1', 'singleton', 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 'Tipe kendaraan 1: motor, gerobak, motor trike', '2026-05-13 07:59:53', '2026-05-13 07:59:53'),
(2, 1, 'K2', 'K2', 'singleton', 2.00, 2.00, 2.00, 2.00, 2.00, 2.00, 'Tipe kendaraan 2: mobil pickup', '2026-05-13 07:59:53', '2026-05-13 07:59:53'),
(3, 1, 'K3', 'K3', 'singleton', 3.00, 3.00, 3.00, 3.00, 3.00, 3.00, 'Tipe kendaraan 3: colt diesel, tronton, truck', '2026-05-13 07:59:53', '2026-05-13 07:59:53'),
(4, 2, 'ringan', 'Ringan', 'linear_turun', 100.00, 1000.00, 100.00, 100.00, 800.00, 1000.00, 'Berat kotor ringan: 100 - 1000 kg', '2026-05-13 08:00:23', '2026-05-13 08:00:23'),
(5, 2, 'sedang', 'Sedang', 'trapesium', 800.00, 1800.00, 800.00, 1000.00, 1500.00, 1800.00, 'Berat kotor sedang: 800 - 1800 kg', '2026-05-13 08:00:23', '2026-05-13 08:00:23'),
(6, 2, 'berat', 'Berat', 'linear_naik', 1500.00, 2000.00, 1500.00, 1800.00, 2000.00, 2000.00, 'Berat kotor berat: 1500 - 2000 kg+', '2026-05-13 08:00:23', '2026-05-13 08:00:23'),
(7, 3, 'ringan', 'Ringan', 'linear_turun', 100.00, 700.00, 100.00, 100.00, 500.00, 700.00, 'Berat bersih ringan: 100 - 700 kg', '2026-05-13 08:01:06', '2026-05-13 08:01:06'),
(8, 3, 'sedang', 'Sedang', 'trapesium', 500.00, 1200.00, 500.00, 700.00, 1000.00, 1200.00, 'Berat bersih sedang: 500 - 1200 kg', '2026-05-13 08:01:06', '2026-05-13 08:01:06'),
(9, 3, 'berat', 'Berat', 'linear_naik', 1100.00, 2200.00, 1100.00, 1200.00, 2200.00, 2200.00, 'Berat bersih berat: 1100 - 2200 kg+', '2026-05-13 08:01:06', '2026-05-13 08:01:06'),
(10, 4, 'baik', 'Baik', 'linear_naik', 7.00, 10.00, 7.00, 8.00, 10.00, 10.00, 'Kualitas baik: 7 - 10', '2026-05-13 08:01:21', '2026-05-13 08:01:21'),
(11, 4, 'sedang', 'Sedang', 'trapesium', 3.00, 8.00, 3.00, 4.00, 7.00, 8.00, 'Kualitas sedang: 3 - 8', '2026-05-13 08:01:21', '2026-05-13 08:01:21'),
(12, 4, 'buruk', 'Buruk', 'linear_turun', 1.00, 4.00, 1.00, 1.00, 3.00, 4.00, 'Kualitas buruk: 1 - 4', '2026-05-13 08:01:21', '2026-05-13 08:01:21'),
(14, 5, 'rendah', 'Rendah', 'linear_turun', 2.00, 12.00, 2.00, 2.00, 10.00, 12.00, 'Bobot ketidaklayakan rendah: 2% - 12%', '2026-05-13 08:02:35', '2026-05-13 08:02:35'),
(15, 5, 'sedang', 'Sedang', 'trapesium', 10.00, 24.00, 10.00, 12.00, 22.00, 24.00, 'Bobot ketidaklayakan sedang: 10% - 24%', '2026-05-13 08:02:35', '2026-05-13 08:02:35'),
(16, 5, 'tinggi', 'Tinggi', 'linear_naik', 22.00, 35.00, 22.00, 24.00, 35.00, 35.00, 'Bobot ketidaklayakan tinggi: 22% - 35%', '2026-05-13 08:02:35', '2026-05-13 08:02:35');

-- --------------------------------------------------------

--
-- Table structure for table `fuzzy_rule`
--

CREATE TABLE `fuzzy_rule` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_rule` varchar(50) NOT NULL,
  `jenis_kendaraan` enum('K1','K2','K3') NOT NULL,
  `berat_kotor` enum('Ringan','Sedang','Berat') NOT NULL,
  `berat_bersih` enum('Ringan','Sedang','Berat') NOT NULL,
  `kualitas_kertas` enum('Baik','Sedang','Buruk') NOT NULL,
  `bobot_ketidaklayakan` enum('Rendah','Sedang','Tinggi') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuzzy_rule`
--

INSERT INTO `fuzzy_rule` (`id`, `kode_rule`, `jenis_kendaraan`, `berat_kotor`, `berat_bersih`, `kualitas_kertas`, `bobot_ketidaklayakan`, `status`, `created_at`, `updated_at`) VALUES
(1, 'R1', 'K1', 'Ringan', 'Ringan', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(2, 'R2', 'K1', 'Ringan', 'Ringan', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(3, 'R3', 'K1', 'Ringan', 'Ringan', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(4, 'R4', 'K1', 'Ringan', 'Sedang', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(5, 'R5', 'K1', 'Ringan', 'Sedang', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(6, 'R6', 'K1', 'Ringan', 'Sedang', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(7, 'R7', 'K1', 'Ringan', 'Berat', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(8, 'R8', 'K1', 'Ringan', 'Berat', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(9, 'R9', 'K1', 'Ringan', 'Berat', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(10, 'R10', 'K1', 'Sedang', 'Ringan', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(11, 'R11', 'K1', 'Sedang', 'Ringan', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(12, 'R12', 'K1', 'Sedang', 'Ringan', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(13, 'R13', 'K1', 'Sedang', 'Sedang', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(14, 'R14', 'K1', 'Sedang', 'Sedang', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(15, 'R15', 'K1', 'Sedang', 'Sedang', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(16, 'R16', 'K1', 'Sedang', 'Berat', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(17, 'R17', 'K1', 'Sedang', 'Berat', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(18, 'R18', 'K1', 'Sedang', 'Berat', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(19, 'R19', 'K1', 'Berat', 'Ringan', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(20, 'R20', 'K1', 'Berat', 'Ringan', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(21, 'R21', 'K1', 'Berat', 'Ringan', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(22, 'R22', 'K1', 'Berat', 'Sedang', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(23, 'R23', 'K1', 'Berat', 'Sedang', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(24, 'R24', 'K1', 'Berat', 'Sedang', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(25, 'R25', 'K1', 'Berat', 'Berat', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(26, 'R26', 'K1', 'Berat', 'Berat', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(27, 'R27', 'K1', 'Berat', 'Berat', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(28, 'R28', 'K2', 'Ringan', 'Ringan', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(29, 'R29', 'K2', 'Ringan', 'Ringan', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(30, 'R30', 'K2', 'Ringan', 'Ringan', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(31, 'R31', 'K2', 'Ringan', 'Sedang', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(32, 'R32', 'K2', 'Ringan', 'Sedang', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(33, 'R33', 'K2', 'Ringan', 'Sedang', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(34, 'R34', 'K2', 'Ringan', 'Berat', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(35, 'R35', 'K2', 'Ringan', 'Berat', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(36, 'R36', 'K2', 'Ringan', 'Berat', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(37, 'R37', 'K2', 'Sedang', 'Ringan', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(38, 'R38', 'K2', 'Sedang', 'Ringan', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(39, 'R39', 'K2', 'Sedang', 'Ringan', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(40, 'R40', 'K2', 'Sedang', 'Sedang', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(41, 'R41', 'K2', 'Sedang', 'Sedang', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(42, 'R42', 'K2', 'Sedang', 'Sedang', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(43, 'R43', 'K2', 'Sedang', 'Berat', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(44, 'R44', 'K2', 'Sedang', 'Berat', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(45, 'R45', 'K2', 'Sedang', 'Berat', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(46, 'R46', 'K2', 'Berat', 'Ringan', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(47, 'R47', 'K2', 'Berat', 'Ringan', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(48, 'R48', 'K2', 'Berat', 'Ringan', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(49, 'R49', 'K2', 'Berat', 'Sedang', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(50, 'R50', 'K2', 'Berat', 'Sedang', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(51, 'R51', 'K2', 'Berat', 'Sedang', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(52, 'R52', 'K2', 'Berat', 'Berat', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(53, 'R53', 'K2', 'Berat', 'Berat', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(54, 'R54', 'K2', 'Berat', 'Berat', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(55, 'R55', 'K3', 'Ringan', 'Ringan', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(56, 'R56', 'K3', 'Ringan', 'Ringan', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(57, 'R57', 'K3', 'Ringan', 'Ringan', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(58, 'R58', 'K3', 'Ringan', 'Sedang', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(59, 'R59', 'K3', 'Ringan', 'Sedang', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(60, 'R60', 'K3', 'Ringan', 'Sedang', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(61, 'R61', 'K3', 'Ringan', 'Berat', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(62, 'R62', 'K3', 'Ringan', 'Berat', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(63, 'R63', 'K3', 'Ringan', 'Berat', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(64, 'R64', 'K3', 'Sedang', 'Ringan', 'Baik', 'Rendah', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(65, 'R65', 'K3', 'Sedang', 'Ringan', 'Sedang', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(66, 'R66', 'K3', 'Sedang', 'Ringan', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(67, 'R67', 'K3', 'Sedang', 'Sedang', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(68, 'R68', 'K3', 'Sedang', 'Sedang', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(69, 'R69', 'K3', 'Sedang', 'Sedang', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(70, 'R70', 'K3', 'Sedang', 'Berat', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(71, 'R71', 'K3', 'Sedang', 'Berat', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(72, 'R72', 'K3', 'Sedang', 'Berat', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(73, 'R73', 'K3', 'Berat', 'Ringan', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(74, 'R74', 'K3', 'Berat', 'Ringan', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(75, 'R75', 'K3', 'Berat', 'Ringan', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(76, 'R76', 'K3', 'Berat', 'Sedang', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(77, 'R77', 'K3', 'Berat', 'Sedang', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(78, 'R78', 'K3', 'Berat', 'Sedang', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(79, 'R79', 'K3', 'Berat', 'Berat', 'Baik', 'Sedang', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(80, 'R80', 'K3', 'Berat', 'Berat', 'Sedang', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00'),
(81, 'R81', 'K3', 'Berat', 'Berat', 'Buruk', 'Tinggi', 'aktif', '2026-05-18 03:57:00', '2026-05-18 03:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `fuzzy_variabel`
--

CREATE TABLE `fuzzy_variabel` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_variabel` varchar(50) NOT NULL,
  `nama_variabel` varchar(100) NOT NULL,
  `tipe_variabel` enum('input','output') NOT NULL,
  `satuan` varchar(30) DEFAULT NULL,
  `min_value` decimal(12,2) DEFAULT NULL,
  `max_value` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fuzzy_variabel`
--

INSERT INTO `fuzzy_variabel` (`id`, `kode_variabel`, `nama_variabel`, `tipe_variabel`, `satuan`, `min_value`, `max_value`, `created_at`, `updated_at`) VALUES
(1, 'jenis_kendaraan', 'Jenis Kendaraan', 'input', NULL, 1.00, 3.00, '2026-05-13 07:57:30', '2026-05-13 07:57:30'),
(2, 'berat_kotor', 'Berat Kotor', 'input', 'kg', 100.00, 2000.00, '2026-05-13 07:57:30', '2026-05-13 07:57:30'),
(3, 'berat_bersih', 'Berat Bersih', 'input', 'kg', 100.00, 2200.00, '2026-05-13 07:57:30', '2026-05-13 07:57:30'),
(4, 'kualitas_kertas', 'Kualitas Kertas', 'input', 'nilai', 1.00, 10.00, '2026-05-13 07:57:30', '2026-05-13 07:57:30'),
(5, 'bobot_ketidaklayakan', 'Bobot Ketidaklayakan', 'output', '%', 2.00, 35.00, '2026-05-13 07:57:30', '2026-05-13 07:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `hutang_pelanggan`
--

CREATE TABLE `hutang_pelanggan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_hutang` varchar(50) NOT NULL,
  `pelanggan_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_hutang` datetime NOT NULL,
  `total_hutang` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_terbayar` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sisa_hutang` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('belum_lunas','lunas','dibatalkan') NOT NULL DEFAULT 'belum_lunas',
  `keterangan` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hutang_pelanggan`
--

INSERT INTO `hutang_pelanggan` (`id`, `kode_hutang`, `pelanggan_id`, `tanggal_hutang`, `total_hutang`, `total_terbayar`, `sisa_hutang`, `status`, `keterangan`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 'KSB-20260520-0002', 7, '2026-05-20 00:00:00', 10000000.00, 0.00, 10000000.00, 'belum_lunas', NULL, 3, '2026-05-20 06:06:23', '2026-05-20 06:06:42'),
(4, 'KSB-20260520-0003', 2, '2026-05-20 00:00:00', 5000000.00, 300000.00, 4700000.00, 'belum_lunas', NULL, 3, '2026-05-20 06:23:02', '2026-05-21 20:39:54'),
(5, 'KSB-20260521-0001', 1, '2026-05-21 00:00:00', 10000000.00, 0.00, 10000000.00, 'belum_lunas', NULL, 3, '2026-05-21 01:11:08', '2026-05-21 01:11:08'),
(6, 'KSB-20260521-0002', 8, '2026-05-21 00:00:00', 10000000.00, 500000.00, 9500000.00, 'belum_lunas', NULL, 3, '2026-05-21 01:11:24', '2026-05-21 01:12:13');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_kendaraan`
--

CREATE TABLE `jenis_kendaraan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kendaraan` varchar(255) NOT NULL,
  `kategori_kendaraan` enum('K1','K2','K3') DEFAULT NULL,
  `nilai_parameter` tinyint(3) UNSIGNED DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_kendaraan`
--

INSERT INTO `jenis_kendaraan` (`id`, `nama_kendaraan`, `kategori_kendaraan`, `nilai_parameter`, `keterangan`, `status`, `created_at`, `updated_at`) VALUES
(1, 'K1', 'K1', 1, 'Motor, gerobak, motor trike', 'aktif', '2026-05-13 07:26:36', '2026-05-13 07:26:36'),
(2, 'K2', 'K2', 2, 'Mobil pickup', 'aktif', '2026-05-13 07:26:36', '2026-05-13 07:26:36'),
(3, 'K3', 'K3', 3, 'Colt diesel, tronton, truck', 'aktif', '2026-05-13 07:26:36', '2026-05-13 07:26:36');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_kertas_bekas`
--

CREATE TABLE `jenis_kertas_bekas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_kertas_bekas`
--

INSERT INTO `jenis_kertas_bekas` (`id`, `kode_barang`, `nama_barang`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BOX', 'Box', 'aktif', '2026-05-19 07:34:41', '2026-05-19 07:34:41'),
(2, 'DUPLEX', 'Duplex', 'aktif', '2026-05-19 07:34:41', '2026-05-19 07:34:41'),
(3, 'SWL', 'Swl', 'aktif', '2026-05-19 07:34:41', '2026-05-19 07:34:41'),
(4, 'KORAN', 'Koran', 'aktif', '2026-05-19 07:34:41', '2026-05-19 07:34:41'),
(5, 'BUKU', 'Buku', 'aktif', '2026-05-19 07:34:41', '2026-05-19 07:34:41'),
(6, 'CORE', 'Core', 'aktif', '2026-05-19 07:34:41', '2026-05-19 07:34:41');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_pelanggan` varchar(50) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `no_hp` varchar(30) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `kode_pelanggan`, `nama_pelanggan`, `no_hp`, `alamat`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PLG-20260519-001', 'asep', '08963272946', 'Bekasi utara', 'aktif', '2026-05-19 00:07:09', '2026-05-19 00:07:09'),
(2, 'PLG-20260519-002', 'aan', '089827198371', 'jakarta timur', 'nonaktif', '2026-05-19 00:11:14', '2026-05-28 01:48:12'),
(4, 'PLG-20260519-003', 'yuyun', '082134627192', 'cikarang barat', 'aktif', '2026-05-19 00:47:43', '2026-05-19 00:47:43'),
(5, 'PLG-20260519-004', 'jokow', '089817263512', 'jakarta barat', 'nonaktif', '2026-05-19 00:48:19', '2026-05-28 01:48:05'),
(6, 'PLG-20260519-005', 'aska', '087827465152', 'bekasi barat', 'aktif', '2026-05-19 00:48:47', '2026-05-19 00:48:47'),
(7, 'PLG-20260520-001', 'edi', '0989783623', 'bekasi', 'aktif', '2026-05-19 22:56:42', '2026-05-19 22:56:42'),
(8, 'PLG-20260520-002', 'agoy', '0898362453', 'Jakarta selatan', 'nonaktif', '2026-05-20 01:12:42', '2026-05-28 01:48:08'),
(9, 'PLG-20260521-001', 'joorge', '0898236513', 'Bekasi utara', 'aktif', '2026-05-21 01:38:13', '2026-05-21 01:38:13'),
(10, 'PLG-20260521-002', 'edi', '0898653527', 'bekasi utara', 'aktif', '2026-05-21 01:49:33', '2026-05-21 01:49:33'),
(11, 'PLG-20260521-003', 'edi', '089821726312', NULL, 'aktif', '2026-05-21 02:04:38', '2026-05-21 02:04:38'),
(12, 'PLG-20260603-001', 'edi', '089182715122', 'Bekasi timur', 'aktif', '2026-06-03 01:48:17', '2026-06-03 01:48:17'),
(13, 'PLG-20260605-001', 'edi', '0876898797', 'Bekasi utara', 'aktif', '2026-06-05 06:18:01', '2026-06-05 06:18:01');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_pembayaran` varchar(50) NOT NULL,
  `transaksi_id` bigint(20) UNSIGNED NOT NULL,
  `pelanggan_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_bayar` datetime NOT NULL,
  `total_berat_bersih` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_potongan_berat` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_berat_layak` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_transaksi` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sisa_hutang_sebelum` decimal(15,2) NOT NULL DEFAULT 0.00,
  `potongan_kasbon` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_dibayar_ke_pelanggan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `sisa_hutang_setelah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `metode_pembayaran` enum('tunai','transfer') NOT NULL DEFAULT 'tunai',
  `status_pembayaran` enum('draft','dibayar','dibatalkan') NOT NULL DEFAULT 'dibayar',
  `kasir_id` bigint(20) UNSIGNED NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `kode_pembayaran`, `transaksi_id`, `pelanggan_id`, `tanggal_bayar`, `total_berat_bersih`, `total_potongan_berat`, `total_berat_layak`, `total_transaksi`, `sisa_hutang_sebelum`, `potongan_kasbon`, `total_dibayar_ke_pelanggan`, `sisa_hutang_setelah`, `metode_pembayaran`, `status_pembayaran`, `kasir_id`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'PAY-20260520-0001', 16, 5, '2026-05-20 12:13:27', 619.98, 128.52, 491.46, 1081212.00, 0.00, 0.00, 1081212.00, 0.00, 'tunai', 'dibayar', 3, NULL, '2026-05-20 05:13:27', '2026-05-20 05:13:27'),
(2, 'PAY-20260520-0002', 14, 7, '2026-05-20 13:18:59', 1040.00, 324.40, 715.60, 1543300.00, 0.00, 0.00, 1543300.00, 0.00, 'tunai', 'dibayar', 3, NULL, '2026-05-20 06:18:59', '2026-05-20 06:18:59'),
(3, 'PAY-20260521-0001', 15, 8, '2026-05-21 08:12:13', 850.00, 265.85, 584.15, 1346837.50, 10000000.00, 500000.00, 846837.50, 9500000.00, 'tunai', 'dibayar', 3, NULL, '2026-05-21 01:12:13', '2026-05-21 01:12:13'),
(4, 'PAY-20260522-0001', 21, 8, '2026-05-22 03:20:44', 30.00, 0.00, 30.00, 60000.00, 9500000.00, 0.00, 60000.00, 9500000.00, 'tunai', 'dibayar', 3, NULL, '2026-05-21 20:20:44', '2026-05-21 20:20:44'),
(5, 'PAY-20260522-0002', 11, 2, '2026-05-22 03:39:54', 780.00, 215.75, 564.25, 1241350.00, 5000000.00, 300000.00, 941350.00, 4700000.00, 'tunai', 'dibayar', 3, NULL, '2026-05-21 20:39:54', '2026-05-21 20:39:54'),
(6, 'PAY-20260528-0001', 20, 11, '2026-05-28 09:00:31', 35.00, 0.00, 35.00, 63000.00, 0.00, 0.00, 63000.00, 0.00, 'tunai', 'dibayar', 3, NULL, '2026-05-28 02:00:31', '2026-05-28 02:00:31'),
(7, 'PAY-20260528-0002', 19, 10, '2026-05-28 09:00:47', 65.00, 0.00, 65.00, 130000.00, 0.00, 0.00, 130000.00, 0.00, 'tunai', 'dibayar', 3, NULL, '2026-05-28 02:00:47', '2026-05-28 02:00:47'),
(8, 'PAY-20260528-0003', 18, 7, '2026-05-28 09:01:06', 70.00, 0.00, 70.00, 140000.00, 10000000.00, 0.00, 140000.00, 10000000.00, 'tunai', 'dibayar', 3, NULL, '2026-05-28 02:01:06', '2026-05-28 02:01:06'),
(9, 'PAY-20260528-0004', 17, 9, '2026-05-28 09:01:15', 25.00, 0.00, 25.00, 50000.00, 0.00, 0.00, 50000.00, 0.00, 'tunai', 'dibayar', 3, NULL, '2026-05-28 02:01:15', '2026-05-28 02:01:15'),
(10, 'PAY-20260528-0005', 10, 1, '2026-05-28 09:01:27', 720.00, 163.80, 556.20, 1112400.00, 10000000.00, 0.00, 1112400.00, 10000000.00, 'tunai', 'dibayar', 3, NULL, '2026-05-28 02:01:27', '2026-05-28 02:01:27'),
(11, 'PAY-20260603-0001', 23, 9, '2026-06-03 09:58:28', 1050.00, 241.50, 808.50, 1528450.00, 0.00, 0.00, 1528450.00, 0.00, 'tunai', 'dibayar', 3, NULL, '2026-06-03 02:58:28', '2026-06-03 02:58:28'),
(12, 'PAY-20260603-0002', 22, 12, '2026-06-03 09:59:09', 800.00, 160.88, 639.12, 1406064.00, 0.00, 0.00, 1406064.00, 0.00, 'tunai', 'dibayar', 3, NULL, '2026-06-03 02:59:09', '2026-06-03 02:59:09'),
(13, 'PAY-20260605-0001', 24, 13, '2026-06-05 13:21:58', 650.00, 130.72, 519.28, 922724.00, 0.00, 0.00, 922724.00, 0.00, 'tunai', 'dibayar', 3, NULL, '2026-06-05 06:21:58', '2026-06-05 06:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_hutang`
--

CREATE TABLE `pembayaran_hutang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_pembayaran_hutang` varchar(50) NOT NULL,
  `hutang_pelanggan_id` bigint(20) UNSIGNED NOT NULL,
  `pembayaran_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nomor_potongan` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `nominal_bayar` decimal(15,2) NOT NULL DEFAULT 0.00,
  `jenis_pembayaran` enum('potongan_transaksi','bayar_tunai','koreksi') NOT NULL DEFAULT 'potongan_transaksi',
  `tanggal_bayar` datetime NOT NULL,
  `kasir_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran_hutang`
--

INSERT INTO `pembayaran_hutang` (`id`, `kode_pembayaran_hutang`, `hutang_pelanggan_id`, `pembayaran_id`, `nomor_potongan`, `nominal_bayar`, `jenis_pembayaran`, `tanggal_bayar`, `kasir_id`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'PH-20260521-0001', 6, 3, 1, 500000.00, 'potongan_transaksi', '2026-05-21 08:12:13', 3, 'Potongan kasbon dari pembayaran PAY-20260521-0001', '2026-05-21 01:12:13', '2026-05-21 01:12:13'),
(2, 'PH-20260522-0001', 4, 5, 1, 300000.00, 'potongan_transaksi', '2026-05-22 03:39:54', 3, 'Potongan kasbon dari pembayaran PAY-20260522-0002', '2026-05-21 20:39:54', '2026-05-21 20:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `qc_penilaian`
--

CREATE TABLE `qc_penilaian` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detail_transaksi_barang_id` bigint(20) UNSIGNED NOT NULL,
  `qc_user_id` bigint(20) UNSIGNED NOT NULL,
  `nilai_jenis_kendaraan` tinyint(3) UNSIGNED NOT NULL,
  `nilai_berat_kotor` decimal(12,2) NOT NULL DEFAULT 0.00,
  `nilai_berat_bersih` decimal(12,2) NOT NULL DEFAULT 0.00,
  `nilai_kualitas_kertas` decimal(5,2) NOT NULL DEFAULT 0.00,
  `catatan` text DEFAULT NULL,
  `waktu_qc` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qc_penilaian`
--

INSERT INTO `qc_penilaian` (`id`, `detail_transaksi_barang_id`, `qc_user_id`, `nilai_jenis_kendaraan`, `nilai_berat_kotor`, `nilai_berat_bersih`, `nilai_kualitas_kertas`, `catatan`, `waktu_qc`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 2, 1650.00, 720.00, 6.00, NULL, '2026-05-19 08:40:14', '2026-05-19 01:40:14', '2026-05-19 23:31:01'),
(2, 3, 2, 3, 2300.00, 1200.00, 4.00, 'agak basah , harga 2200', '2026-05-19 09:22:48', '2026-05-19 01:46:21', '2026-05-19 22:45:42'),
(3, 5, 2, 2, 1780.00, 199.98, 3.00, 'basah, harga 1000', '2026-05-20 01:57:58', '2026-05-19 18:57:58', '2026-05-19 20:56:39'),
(4, 4, 2, 2, 1780.00, 500.00, 5.00, 'agak basah, harga 2200', '2026-05-20 01:58:34', '2026-05-19 18:58:34', '2026-05-19 20:57:10'),
(5, 6, 2, 2, 1780.00, 150.00, 6.00, 'harga 2400', '2026-05-20 01:59:07', '2026-05-19 18:59:07', '2026-05-19 20:56:21'),
(6, 7, 2, 2, 2000.00, 700.00, 4.00, NULL, '2026-05-20 05:58:13', '2026-05-19 22:58:13', '2026-05-19 23:30:11'),
(7, 8, 2, 2, 2000.00, 120.00, 5.00, NULL, '2026-05-20 05:58:31', '2026-05-19 22:58:31', '2026-05-19 23:29:35'),
(8, 9, 2, 2, 2000.00, 220.00, 8.00, NULL, '2026-05-20 05:58:37', '2026-05-19 22:58:37', '2026-05-19 23:29:50'),
(9, 2, 2, 2, 1720.00, 780.00, 6.00, NULL, '2026-05-20 06:24:02', '2026-05-19 23:24:02', '2026-05-19 23:30:39'),
(10, 12, 2, 2, 1599.98, 619.98, 6.00, NULL, '2026-05-20 08:13:57', '2026-05-20 01:13:57', '2026-05-20 01:15:05'),
(11, 11, 2, 2, 1800.00, 200.00, 7.00, NULL, '2026-05-21 08:08:31', '2026-05-21 01:08:31', '2026-05-21 01:09:43'),
(12, 10, 2, 2, 1800.00, 650.00, 5.00, NULL, '2026-05-21 08:08:36', '2026-05-21 01:08:36', '2026-05-21 01:09:34'),
(13, 18, 2, 2, 1600.00, 800.00, 6.00, NULL, '2026-06-03 09:39:03', '2026-06-03 02:39:03', '2026-06-03 02:39:03'),
(14, 20, 2, 2, 1950.00, 350.00, 7.00, NULL, '2026-06-03 09:39:15', '2026-06-03 02:39:15', '2026-06-03 02:39:15'),
(15, 19, 2, 2, 1950.00, 700.00, 8.00, NULL, '2026-06-03 09:39:22', '2026-06-03 02:39:22', '2026-06-03 02:39:22'),
(16, 22, 2, 2, 1600.00, 250.00, 7.00, NULL, '2026-06-05 13:20:43', '2026-06-05 06:20:43', '2026-06-05 06:20:43'),
(17, 21, 2, 2, 1600.00, 400.00, 6.00, NULL, '2026-06-05 13:20:51', '2026-06-05 06:20:51', '2026-06-05 06:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_penimbangan_barang`
--

CREATE TABLE `riwayat_penimbangan_barang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detail_transaksi_barang_id` bigint(20) UNSIGNED NOT NULL,
  `urutan_timbang` int(10) UNSIGNED NOT NULL,
  `berat_kotor` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tara` decimal(12,2) NOT NULL DEFAULT 0.00,
  `berat_bersih` decimal(12,2) NOT NULL DEFAULT 0.00,
  `waktu_timbang` datetime NOT NULL,
  `petugas_timbang_id` bigint(20) UNSIGNED NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riwayat_penimbangan_barang`
--

INSERT INTO `riwayat_penimbangan_barang` (`id`, `detail_transaksi_barang_id`, `urutan_timbang`, `berat_kotor`, `tara`, `berat_bersih`, `waktu_timbang`, `petugas_timbang_id`, `catatan`, `created_at`, `updated_at`) VALUES
(2, 6, 1, 1780.00, 1630.00, 150.00, '2026-05-20 03:56:21', 1, NULL, '2026-05-19 20:56:21', '2026-05-19 20:56:21'),
(3, 5, 2, 1630.00, 1430.02, 199.98, '2026-05-20 03:56:39', 1, NULL, '2026-05-19 20:56:39', '2026-05-19 20:56:39'),
(4, 4, 3, 1430.02, 930.02, 500.00, '2026-05-20 03:57:10', 1, NULL, '2026-05-19 20:57:10', '2026-05-19 20:57:10'),
(5, 3, 1, 2300.00, 1100.00, 1200.00, '2026-05-20 05:45:42', 1, NULL, '2026-05-19 22:45:42', '2026-05-19 22:45:42'),
(6, 8, 1, 2000.00, 1880.00, 120.00, '2026-05-20 06:29:35', 1, NULL, '2026-05-19 23:29:35', '2026-05-19 23:29:35'),
(7, 9, 2, 1880.00, 1660.00, 220.00, '2026-05-20 06:29:50', 1, NULL, '2026-05-19 23:29:50', '2026-05-19 23:29:50'),
(8, 7, 3, 1660.00, 960.00, 700.00, '2026-05-20 06:30:11', 1, NULL, '2026-05-19 23:30:11', '2026-05-19 23:30:11'),
(9, 2, 1, 1720.00, 940.00, 780.00, '2026-05-20 06:30:39', 1, NULL, '2026-05-19 23:30:39', '2026-05-19 23:30:39'),
(10, 1, 1, 1650.00, 930.00, 720.00, '2026-05-20 06:31:01', 1, NULL, '2026-05-19 23:31:01', '2026-05-19 23:31:01'),
(11, 12, 1, 1599.98, 980.00, 619.98, '2026-05-20 08:15:05', 1, NULL, '2026-05-20 01:15:05', '2026-05-20 01:15:05'),
(12, 10, 1, 1800.00, 1150.00, 650.00, '2026-05-21 08:09:34', 1, NULL, '2026-05-21 01:09:34', '2026-05-21 01:09:34'),
(13, 11, 2, 1150.00, 950.00, 200.00, '2026-05-21 08:09:43', 1, NULL, '2026-05-21 01:09:43', '2026-05-21 01:09:43'),
(14, 17, 1, 95.00, 65.00, 30.00, '2026-05-22 03:18:35', 1, NULL, '2026-05-21 20:18:35', '2026-05-21 20:18:35'),
(15, 16, 1, 79.98, 44.98, 35.00, '2026-05-28 08:49:49', 1, NULL, '2026-05-28 01:49:49', '2026-05-28 01:49:49'),
(16, 15, 1, 90.00, 25.00, 65.00, '2026-05-28 08:58:42', 1, NULL, '2026-05-28 01:58:42', '2026-05-28 01:58:42'),
(17, 14, 1, 100.00, 30.00, 70.00, '2026-05-28 08:59:03', 1, NULL, '2026-05-28 01:59:03', '2026-05-28 01:59:03'),
(18, 13, 1, 50.00, 25.00, 25.00, '2026-05-28 08:59:25', 1, NULL, '2026-05-28 01:59:25', '2026-05-28 01:59:25'),
(21, 19, 1, 1950.00, 1250.00, 700.00, '2026-06-03 09:11:23', 1, NULL, '2026-06-03 02:11:23', '2026-06-03 02:11:23'),
(22, 20, 2, 1250.00, 900.00, 350.00, '2026-06-03 09:11:46', 1, NULL, '2026-06-03 02:11:46', '2026-06-03 02:11:46'),
(23, 18, 1, 1600.00, 800.00, 800.00, '2026-06-03 09:38:29', 1, NULL, '2026-06-03 02:38:29', '2026-06-03 02:38:29'),
(24, 21, 1, 1600.00, 1200.00, 400.00, '2026-06-05 13:19:02', 1, NULL, '2026-06-05 06:19:02', '2026-06-05 06:19:02'),
(25, 22, 2, 1200.00, 950.00, 250.00, '2026-06-05 13:19:14', 1, NULL, '2026-06-05 06:19:14', '2026-06-05 06:19:14');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_penimbangan`
--

CREATE TABLE `transaksi_penimbangan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `pelanggan_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_kendaraan_id` bigint(20) UNSIGNED NOT NULL,
  `plat_kendaraan` varchar(30) DEFAULT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `berat_timbang_pertama` decimal(12,2) NOT NULL DEFAULT 0.00,
  `berat_timbang_kedua` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft_penimbangan','menunggu_qc','proses_qc','menunggu_pembayaran','selesai','dibatalkan') NOT NULL DEFAULT 'draft_penimbangan',
  `petugas_timbang_id` bigint(20) UNSIGNED NOT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi_penimbangan`
--

INSERT INTO `transaksi_penimbangan` (`id`, `kode_transaksi`, `pelanggan_id`, `jenis_kendaraan_id`, `plat_kendaraan`, `tanggal_transaksi`, `berat_timbang_pertama`, `berat_timbang_kedua`, `status`, `petugas_timbang_id`, `catatan`, `created_at`, `updated_at`) VALUES
(10, 'TRX-20260519-0001', 1, 2, 'B 1726 FOP', '2026-05-19 14:49:00', 1650.00, 930.00, 'selesai', 1, NULL, '2026-05-19 01:09:44', '2026-05-28 02:01:27'),
(11, 'TRX-20260519-0002', 2, 2, 'B 1456 FLP', '2026-05-19 15:38:00', 1720.00, 940.00, 'selesai', 1, NULL, '2026-05-19 01:38:49', '2026-05-21 20:39:54'),
(12, 'TRX-20260519-0003', 4, 3, 'B 1325 FIK', '2026-05-19 15:39:00', 2300.00, 1100.00, 'menunggu_pembayaran', 1, NULL, '2026-05-19 01:39:35', '2026-05-19 22:45:46'),
(13, 'TRX-20260519-0004', 6, 2, 'B 1756 BIK', '2026-05-19 10:51:00', 1780.00, 930.02, 'menunggu_pembayaran', 1, NULL, '2026-05-19 03:52:02', '2026-05-19 20:57:22'),
(14, 'TRX-20260520-0001', 7, 2, 'B 1787 FOP', '2026-05-20 12:56:00', 2000.00, 960.00, 'selesai', 1, NULL, '2026-05-19 22:57:38', '2026-05-20 06:18:59'),
(15, 'TRX-20260520-0002', 8, 2, 'B 1726 FTY', '2026-05-20 08:12:00', 1800.00, 950.00, 'selesai', 1, NULL, '2026-05-20 01:13:15', '2026-05-21 01:12:13'),
(16, 'TRX-20260520-0003', 5, 2, NULL, '2026-05-20 08:13:00', 1599.98, 980.00, 'selesai', 1, NULL, '2026-05-20 01:13:33', '2026-05-20 05:13:27'),
(17, 'TRX-20260521-0001', 9, 1, 'B 1987 KRT', '2026-05-21 08:38:00', 50.00, 25.00, 'selesai', 1, NULL, '2026-05-21 01:38:42', '2026-05-28 02:01:15'),
(18, 'TRX-20260521-0002', 7, 1, 'B 1456 FGP', '2026-05-21 08:38:00', 100.00, 30.00, 'selesai', 1, NULL, '2026-05-21 01:39:27', '2026-05-28 02:01:06'),
(19, 'TRX-20260521-0003', 10, 1, 'B 1726 FTY', '2026-05-21 08:49:00', 90.00, 25.00, 'selesai', 1, NULL, '2026-05-21 01:49:56', '2026-05-28 02:00:47'),
(20, 'TRX-20260521-0004', 11, 1, 'B 1762 KPK', '2026-05-21 09:04:00', 79.98, 44.98, 'selesai', 1, NULL, '2026-05-21 02:05:14', '2026-05-28 02:00:31'),
(21, 'TRX-20260521-0005', 8, 1, 'B 1768 POK', '2026-05-21 15:30:00', 95.00, 0.00, 'selesai', 1, NULL, '2026-05-21 08:30:34', '2026-05-21 20:20:44'),
(22, 'TRX-20260603-0001', 12, 2, 'B 1726 FTY', '2026-06-03 08:48:00', 1600.00, 800.00, 'selesai', 1, NULL, '2026-06-03 01:48:46', '2026-06-03 02:59:09'),
(23, 'TRX-20260603-0002', 9, 2, NULL, '2026-06-03 08:48:00', 1950.00, 900.00, 'selesai', 1, NULL, '2026-06-03 01:49:17', '2026-06-03 02:58:28'),
(24, 'TRX-20260605-0001', 13, 2, 'B 1726 FTY', '2026-06-05 13:18:00', 1600.00, 950.00, 'selesai', 1, NULL, '2026-06-05 06:18:29', '2026-06-05 06:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('qc','penimbang','kasir') NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `role`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Penimbang', '$2y$12$P3RTTwDzLB21PDBDlUKzw.Qiql4OzgGGs/XlJgUdCqzl/zZzLdpPi', 'penimbang', 'aktif', NULL, NULL, '2026-05-18 05:18:02'),
(2, 'qc', '$2y$12$EoOzTxH3efPoLawZfZJoDO8uiEn0LDB.j5Fa3fhOg7iR/oY0NVF0K', 'qc', 'aktif', NULL, NULL, '2026-05-18 05:18:02'),
(3, 'Kasir', '$2y$12$BRdCX1WQkjehaVNt7pDCRu6a6XNLFo59W7HMqRCyDYDRxqOYS5w8O', 'kasir', 'aktif', NULL, NULL, '2026-05-18 05:18:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_pembayaran_barang`
--
ALTER TABLE `detail_pembayaran_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detail_pembayaran` (`pembayaran_id`),
  ADD KEY `fk_detail_pembayaran_barang` (`detail_transaksi_barang_id`),
  ADD KEY `fk_detail_pembayaran_fuzzy` (`fuzzy_hasil_id`);

--
-- Indexes for table `detail_transaksi_barang`
--
ALTER TABLE `detail_transaksi_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detail_transaksi` (`transaksi_id`),
  ADD KEY `fk_detail_jenis_kertas_bekas` (`jenis_kertas_bekas_id`);

--
-- Indexes for table `fuzzy_hasil`
--
ALTER TABLE `fuzzy_hasil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_hasil_qc` (`qc_penilaian_id`),
  ADD KEY `fk_hasil_detail_barang` (`detail_transaksi_barang_id`);

--
-- Indexes for table `fuzzy_himpunan`
--
ALTER TABLE `fuzzy_himpunan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_variabel_himpunan` (`fuzzy_variabel_id`,`kode_himpunan`);

--
-- Indexes for table `fuzzy_rule`
--
ALTER TABLE `fuzzy_rule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_rule` (`kode_rule`);

--
-- Indexes for table `fuzzy_variabel`
--
ALTER TABLE `fuzzy_variabel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_variabel` (`kode_variabel`);

--
-- Indexes for table `hutang_pelanggan`
--
ALTER TABLE `hutang_pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_hutang` (`kode_hutang`),
  ADD KEY `fk_hutang_pelanggan` (`pelanggan_id`),
  ADD KEY `fk_hutang_created_by` (`created_by`);

--
-- Indexes for table `jenis_kendaraan`
--
ALTER TABLE `jenis_kendaraan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_kertas_bekas`
--
ALTER TABLE `jenis_kertas_bekas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pelanggan` (`kode_pelanggan`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pembayaran` (`kode_pembayaran`),
  ADD UNIQUE KEY `unique_pembayaran_transaksi` (`transaksi_id`),
  ADD KEY `fk_pembayaran_pelanggan` (`pelanggan_id`),
  ADD KEY `fk_pembayaran_kasir` (`kasir_id`);

--
-- Indexes for table `pembayaran_hutang`
--
ALTER TABLE `pembayaran_hutang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pembayaran_hutang` (`kode_pembayaran_hutang`),
  ADD KEY `fk_pembayaran_hutang_hutang` (`hutang_pelanggan_id`),
  ADD KEY `fk_pembayaran_hutang_pembayaran` (`pembayaran_id`),
  ADD KEY `fk_pembayaran_hutang_kasir` (`kasir_id`);

--
-- Indexes for table `qc_penilaian`
--
ALTER TABLE `qc_penilaian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_qc_detail_barang` (`detail_transaksi_barang_id`),
  ADD KEY `fk_qc_user` (`qc_user_id`);

--
-- Indexes for table `riwayat_penimbangan_barang`
--
ALTER TABLE `riwayat_penimbangan_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_riwayat_detail_barang` (`detail_transaksi_barang_id`),
  ADD KEY `fk_riwayat_petugas_timbang` (`petugas_timbang_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transaksi_penimbangan`
--
ALTER TABLE `transaksi_penimbangan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `fk_transaksi_pelanggan` (`pelanggan_id`),
  ADD KEY `fk_transaksi_jenis_kendaraan` (`jenis_kendaraan_id`),
  ADD KEY `fk_transaksi_petugas_timbang` (`petugas_timbang_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pembayaran_barang`
--
ALTER TABLE `detail_pembayaran_barang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `detail_transaksi_barang`
--
ALTER TABLE `detail_transaksi_barang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `fuzzy_hasil`
--
ALTER TABLE `fuzzy_hasil`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `fuzzy_himpunan`
--
ALTER TABLE `fuzzy_himpunan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `fuzzy_rule`
--
ALTER TABLE `fuzzy_rule`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `fuzzy_variabel`
--
ALTER TABLE `fuzzy_variabel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hutang_pelanggan`
--
ALTER TABLE `hutang_pelanggan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jenis_kendaraan`
--
ALTER TABLE `jenis_kendaraan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jenis_kertas_bekas`
--
ALTER TABLE `jenis_kertas_bekas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pembayaran_hutang`
--
ALTER TABLE `pembayaran_hutang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `qc_penilaian`
--
ALTER TABLE `qc_penilaian`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `riwayat_penimbangan_barang`
--
ALTER TABLE `riwayat_penimbangan_barang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `transaksi_penimbangan`
--
ALTER TABLE `transaksi_penimbangan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pembayaran_barang`
--
ALTER TABLE `detail_pembayaran_barang`
  ADD CONSTRAINT `fk_detail_pembayaran` FOREIGN KEY (`pembayaran_id`) REFERENCES `pembayaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_pembayaran_barang` FOREIGN KEY (`detail_transaksi_barang_id`) REFERENCES `detail_transaksi_barang` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_pembayaran_fuzzy` FOREIGN KEY (`fuzzy_hasil_id`) REFERENCES `fuzzy_hasil` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `detail_transaksi_barang`
--
ALTER TABLE `detail_transaksi_barang`
  ADD CONSTRAINT `fk_detail_jenis_kertas_bekas` FOREIGN KEY (`jenis_kertas_bekas_id`) REFERENCES `jenis_kertas_bekas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi_penimbangan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fuzzy_hasil`
--
ALTER TABLE `fuzzy_hasil`
  ADD CONSTRAINT `fk_hasil_detail_barang` FOREIGN KEY (`detail_transaksi_barang_id`) REFERENCES `detail_transaksi_barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hasil_qc` FOREIGN KEY (`qc_penilaian_id`) REFERENCES `qc_penilaian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fuzzy_himpunan`
--
ALTER TABLE `fuzzy_himpunan`
  ADD CONSTRAINT `fk_himpunan_variabel` FOREIGN KEY (`fuzzy_variabel_id`) REFERENCES `fuzzy_variabel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hutang_pelanggan`
--
ALTER TABLE `hutang_pelanggan`
  ADD CONSTRAINT `fk_hutang_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hutang_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_kasir` FOREIGN KEY (`kasir_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembayaran_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembayaran_transaksi` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi_penimbangan` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran_hutang`
--
ALTER TABLE `pembayaran_hutang`
  ADD CONSTRAINT `fk_pembayaran_hutang_hutang` FOREIGN KEY (`hutang_pelanggan_id`) REFERENCES `hutang_pelanggan` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembayaran_hutang_kasir` FOREIGN KEY (`kasir_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembayaran_hutang_pembayaran` FOREIGN KEY (`pembayaran_id`) REFERENCES `pembayaran` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `qc_penilaian`
--
ALTER TABLE `qc_penilaian`
  ADD CONSTRAINT `fk_qc_detail_barang` FOREIGN KEY (`detail_transaksi_barang_id`) REFERENCES `detail_transaksi_barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qc_user` FOREIGN KEY (`qc_user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `riwayat_penimbangan_barang`
--
ALTER TABLE `riwayat_penimbangan_barang`
  ADD CONSTRAINT `fk_riwayat_detail_barang` FOREIGN KEY (`detail_transaksi_barang_id`) REFERENCES `detail_transaksi_barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_riwayat_petugas_timbang` FOREIGN KEY (`petugas_timbang_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_penimbangan`
--
ALTER TABLE `transaksi_penimbangan`
  ADD CONSTRAINT `fk_transaksi_jenis_kendaraan` FOREIGN KEY (`jenis_kendaraan_id`) REFERENCES `jenis_kendaraan` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_petugas_timbang` FOREIGN KEY (`petugas_timbang_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
