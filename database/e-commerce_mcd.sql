-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 08, 2026 at 12:39 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uas_mcd`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 6, '2025-12-25 10:07:06', '2025-12-25 10:07:06'),
(4, 5, '2026-01-02 00:03:00', '2026-01-02 00:03:00');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Burgers', 'burgers', 'Delicious burgers made with 100% beef', NULL, 1, '2025-12-25 07:59:35', '2025-12-25 07:59:35'),
(2, 'Chicken', 'chicken', 'Crispy and tender chicken items', NULL, 2, '2025-12-25 07:59:35', '2025-12-25 07:59:35'),
(3, 'Sides', 'sides', 'Perfect sides to complement your meal', NULL, 3, '2025-12-25 07:59:35', '2025-12-25 07:59:35'),
(4, 'Drinks', 'drinks', 'Refreshing beverages', NULL, 4, '2025-12-25 07:59:35', '2025-12-25 07:59:35'),
(5, 'Desserts', 'desserts', 'Sweet treats to end your meal', NULL, 5, '2025-12-25 07:59:35', '2025-12-25 07:59:35'),
(6, 'Breakfast', 'breakfast', 'Start your day with our breakfast menu', NULL, 6, '2025-12-25 07:59:35', '2025-12-25 07:59:35');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(2, 6, 'Test User', 'user@mcd.com', 'Masukan', 'Bagus', 1, '2025-12-25 10:47:15', '2025-12-25 10:47:28'),
(3, 7, 'adit', 'raflipraditta@gmail.com', 'Masukan', 'Web nya bagus', 1, '2025-12-25 22:38:00', '2025-12-25 22:38:31'),
(4, 6, 'Test User', 'user@mcd.com', 'Masukan', 'Hai', 1, '2026-01-02 01:08:41', '2026-01-02 01:11:07');

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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `invoice_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `invoice_data`, `created_at`, `updated_at`) VALUES
(11, 'INV-20251225-M6OVOY', 15, '{\"order_number\":\"MCD-20251225-TSD6W7\",\"invoice_number\":\"INV-20251225-M6OVOY\",\"date\":\"2025-12-25 17:07:50\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jakarta, Indonesia\",\"items\":[{\"name\":\"Big Mac\",\"price\":45000,\"quantity\":1,\"subtotal\":45000,\"notes\":null}],\"total_amount\":45000,\"payment_method\":\"transfer_bank\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":\"tidak memakai sayuran\"}', '2025-12-25 10:07:50', '2025-12-25 10:07:50'),
(12, 'INV-20251225-UHUM6B', 16, '{\"order_number\":\"MCD-20251225-B3DCR2\",\"invoice_number\":\"INV-20251225-UHUM6B\",\"date\":\"2025-12-25 17:31:24\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jakarta, Indonesia\",\"items\":[{\"name\":\"Big Mac\",\"price\":45000,\"quantity\":2,\"subtotal\":90000,\"notes\":null}],\"total_amount\":90000,\"payment_method\":\"transfer_bank\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":null}', '2025-12-25 10:31:24', '2025-12-25 10:31:24'),
(13, 'INV-20251225-4RP2L6', 17, '{\"order_number\":\"MCD-20251225-GDSIUU\",\"invoice_number\":\"INV-20251225-4RP2L6\",\"date\":\"2025-12-25 17:35:08\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jl. Test UI No. 123, Jakarta\",\"items\":[{\"name\":\"Big Mac\",\"price\":45000,\"quantity\":1,\"subtotal\":45000,\"notes\":null},{\"name\":\"Quarter Pounder with Cheese\",\"price\":52000,\"quantity\":1,\"subtotal\":52000,\"notes\":null}],\"total_amount\":97000,\"payment_method\":\"cod\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":\"Test order for UI improvements\"}', '2025-12-25 10:35:08', '2025-12-25 10:35:08'),
(14, 'INV-20251225-J0M7V2', 18, '{\"order_number\":\"MCD-20251225-BF1VQQ\",\"invoice_number\":\"INV-20251225-J0M7V2\",\"date\":\"2025-12-25 17:41:09\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jl. McDonald\'s Test No. 123, Jakarta Selatan, DKI Jakarta 12345\",\"items\":[{\"name\":\"Big Mac\",\"price\":45000,\"quantity\":1,\"subtotal\":45000,\"notes\":\"Extra sauce please\"},{\"name\":\"Quarter Pounder with Cheese\",\"price\":52000,\"quantity\":1,\"subtotal\":52000,\"notes\":\"Extra sauce please\"},{\"name\":\"McDouble\",\"price\":35000,\"quantity\":1,\"subtotal\":35000,\"notes\":\"Extra sauce please\"}],\"total_amount\":132000,\"payment_method\":\"cod\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":\"Please deliver hot and fresh. Thank you!\"}', '2025-12-25 10:41:09', '2025-12-25 10:41:09'),
(15, 'INV-20251226-PEVZGA', 19, '{\"order_number\":\"MCD-20251226-F5VT1O\",\"invoice_number\":\"INV-20251226-PEVZGA\",\"date\":\"2025-12-26 05:42:21\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jakarta, Indonesia\",\"items\":[{\"name\":\"Big Mac\",\"price\":45000,\"quantity\":1,\"subtotal\":45000,\"notes\":null},{\"name\":\"Quarter Pounder with Cheese\",\"price\":52000,\"quantity\":1,\"subtotal\":52000,\"notes\":null},{\"name\":\"Beef Deluxe\",\"price\":42000,\"quantity\":1,\"subtotal\":42000,\"notes\":null},{\"name\":\"Apple Slices\",\"price\":10000,\"quantity\":1,\"subtotal\":10000,\"notes\":null}],\"total_amount\":149000,\"payment_method\":\"cod\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":null}', '2025-12-25 22:42:21', '2025-12-25 22:42:21'),
(16, 'INV-20251226-4DZARK', 20, '{\"order_number\":\"MCD-20251226-WK11XH\",\"invoice_number\":\"INV-20251226-4DZARK\",\"date\":\"2025-12-26 05:44:13\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jakarta, Indonesia\",\"items\":[{\"name\":\"French Fries Medium\",\"price\":18000,\"quantity\":1,\"subtotal\":18000,\"notes\":null},{\"name\":\"Apple Slices\",\"price\":10000,\"quantity\":1,\"subtotal\":10000,\"notes\":null}],\"total_amount\":28000,\"payment_method\":\"transfer_bank\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":null}', '2025-12-25 22:44:13', '2025-12-25 22:44:13'),
(17, 'INV-20260102-GSBXTK', 21, '{\"order_number\":\"MCD-20260102-R2PDBK\",\"invoice_number\":\"INV-20260102-GSBXTK\",\"date\":\"2026-01-02 08:09:33\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jakarta, Indonesia\",\"items\":[{\"name\":\"Ayam Goreng McD\",\"price\":28000,\"quantity\":1,\"subtotal\":28000,\"notes\":null},{\"name\":\"Chicken McNuggets 20 pcs\",\"price\":85000,\"quantity\":1,\"subtotal\":85000,\"notes\":null},{\"name\":\"Apple Slices\",\"price\":10000,\"quantity\":1,\"subtotal\":10000,\"notes\":null},{\"name\":\"Breakfast Burrito\",\"price\":35000,\"quantity\":1,\"subtotal\":35000,\"notes\":null}],\"total_amount\":158000,\"payment_method\":\"transfer_bank\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":null}', '2026-01-02 01:09:33', '2026-01-02 01:09:33'),
(18, 'INV-20260107-5P2KOG', 22, '{\"order_number\":\"MCD-20260107-V8LGFI\",\"invoice_number\":\"INV-20260107-5P2KOG\",\"date\":\"2026-01-07 03:30:04\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jakarta, Indonesia\",\"items\":[{\"name\":\"Breakfast Burrito\",\"price\":35000,\"quantity\":1,\"subtotal\":35000,\"notes\":null}],\"total_amount\":35000,\"payment_method\":\"transfer_bank\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":null}', '2026-01-06 20:30:04', '2026-01-06 20:30:04'),
(19, 'INV-20260107-BZUTZ1', 23, '{\"order_number\":\"MCD-20260107-P5BIWZ\",\"invoice_number\":\"INV-20260107-BZUTZ1\",\"date\":\"2026-01-07 04:06:23\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jakarta, Indonesia\",\"items\":[{\"name\":\"Big Mac\",\"price\":45000,\"quantity\":1,\"subtotal\":45000,\"notes\":null}],\"total_amount\":45000,\"payment_method\":\"transfer_bank\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":null}', '2026-01-06 21:06:23', '2026-01-06 21:06:23'),
(20, 'INV-20260107-VFTX0K', 24, '{\"order_number\":\"MCD-20260107-2LUCQB\",\"invoice_number\":\"INV-20260107-VFTX0K\",\"date\":\"2026-01-07 04:22:38\",\"customer\":{\"name\":\"Test User\",\"email\":\"user@mcd.com\",\"phone\":\"081234567891\",\"address\":\"Jakarta, Indonesia\"},\"delivery_address\":\"Jakarta, Indonesia\",\"items\":[{\"name\":\"Ayam Goreng McD\",\"price\":28000,\"quantity\":1,\"subtotal\":28000,\"notes\":null}],\"total_amount\":28000,\"payment_method\":\"transfer_bank\",\"payment_status\":\"pending\",\"order_status\":\"pending\",\"notes\":null}', '2026-01-06 21:22:38', '2026-01-06 21:22:38');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_01_000001_add_columns_to_users_table', 1),
(6, '2024_01_01_000002_create_categories_table', 1),
(7, '2024_01_01_000003_create_products_table', 1),
(8, '2024_01_01_000004_create_product_images_table', 1),
(9, '2024_01_01_000005_create_carts_table', 1),
(10, '2024_01_01_000006_create_cart_items_table', 1),
(11, '2024_01_01_000007_create_orders_table', 1),
(12, '2024_01_01_000008_create_order_items_table', 1),
(13, '2024_01_01_000009_create_order_status_histories_table', 1),
(14, '2024_01_01_000010_create_invoices_table', 1),
(15, '2024_01_01_000011_create_contact_messages_table', 1),
(16, '2025_12_25_162307_add_shipped_delivered_status_to_orders_table', 2),
(17, '2025_12_25_163353_update_order_status_histories_table_add_new_statuses', 3),
(18, '2025_12_25_170128_create_sessions_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `delivery_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `payment_method` enum('cod','transfer_bank') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('pending','paid','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_status` enum('pending','confirmed','processing','shipped','delivered','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `delivery_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `total_amount`, `delivery_address`, `notes`, `payment_method`, `payment_status`, `order_status`, `payment_proof`, `shipped_at`, `delivered_at`, `delivery_notes`, `created_at`, `updated_at`) VALUES
(14, 'MCD-TEST-001', 4, '100.00', 'Test Address', NULL, 'cod', 'paid', 'completed', NULL, NULL, NULL, NULL, '2025-12-15 05:00:00', '2025-12-15 05:00:00'),
(15, 'MCD-20251225-TSD6W7', 6, '45000.00', 'Jakarta, Indonesia', 'tidak memakai sayuran', 'transfer_bank', 'paid', 'completed', 'payment-proofs/i8OTBvSX4ipZTuuCJbcALYVDG3y4osZkD1qrr40Y.jpg', '2025-12-25 10:10:15', '2025-12-25 10:13:24', 'sudah sampai', '2025-12-25 10:07:50', '2025-12-25 10:53:19'),
(16, 'MCD-20251225-B3DCR2', 6, '90000.00', 'Jakarta, Indonesia', NULL, 'transfer_bank', 'paid', 'completed', 'payment-proofs/W6zoj6LabtbDTAhzTikRJTXhG0LlD7lMrN387OwJ.jpg', '2025-12-25 10:32:05', '2025-12-25 10:36:08', NULL, '2025-12-25 10:31:24', '2025-12-25 10:53:02'),
(17, 'MCD-20251225-GDSIUU', 6, '97000.00', 'Jl. Test UI No. 123, Jakarta', 'Test order for UI improvements', 'cod', 'paid', 'completed', NULL, '2025-12-25 10:35:08', '2025-12-25 22:41:42', NULL, '2025-12-25 10:35:08', '2025-12-25 22:50:54'),
(18, 'MCD-20251225-BF1VQQ', 6, '132000.00', 'Jl. McDonald\'s Test No. 123, Jakarta Selatan, DKI Jakarta 12345', 'Please deliver hot and fresh. Thank you!', 'cod', 'paid', 'completed', NULL, NULL, NULL, NULL, '2025-12-25 10:41:09', '2025-12-25 10:41:09'),
(19, 'MCD-20251226-F5VT1O', 6, '149000.00', 'Jakarta, Indonesia', NULL, 'cod', 'paid', 'completed', NULL, '2025-12-25 22:51:36', '2025-12-25 22:52:22', 'Cepat', '2025-12-25 22:42:21', '2025-12-25 22:53:05'),
(20, 'MCD-20251226-WK11XH', 6, '28000.00', 'Jakarta, Indonesia', NULL, 'transfer_bank', 'paid', 'completed', 'payment-proofs/evkCC82yG5x3oI05G6dLag9GS4BMelZpJezB4o7K.jpg', '2025-12-25 22:53:36', '2025-12-25 22:58:13', NULL, '2025-12-25 22:44:13', '2026-01-06 20:28:36'),
(21, 'MCD-20260102-R2PDBK', 6, '158000.00', 'Jakarta, Indonesia', NULL, 'transfer_bank', 'paid', 'completed', 'payment-proofs/Eot8VuAKkOv5Yby5X9AQckBdXPtzzjzCHdUL48LU.jpg', '2026-01-02 01:10:19', '2026-01-02 01:10:41', NULL, '2026-01-02 01:09:33', '2026-01-02 01:10:54'),
(22, 'MCD-20260107-V8LGFI', 6, '35000.00', 'Jakarta, Indonesia', NULL, 'transfer_bank', 'paid', 'delivered', 'payment-proofs/R8zEs4k5hSj2qt2dQiBnTEvIeOBOrdqYpDgbYr0a.jpg', '2026-01-06 20:30:49', '2026-01-06 20:31:17', NULL, '2026-01-06 20:30:04', '2026-01-06 20:31:17'),
(23, 'MCD-20260107-P5BIWZ', 6, '45000.00', 'Jakarta, Indonesia', NULL, 'transfer_bank', 'paid', 'completed', 'payment-proofs/r5Ren3r4X8eoTFElTbEnhvNkxHs7wt7nwiKksudl.jpg', '2026-01-06 21:07:59', '2026-01-06 21:08:24', NULL, '2026-01-06 21:06:23', '2026-01-06 21:08:43'),
(24, 'MCD-20260107-2LUCQB', 6, '28000.00', 'Jakarta, Indonesia', NULL, 'transfer_bank', 'paid', 'confirmed', 'payment-proofs/O3F2swlaYBPzwhbqDNySThszFueNovyOOTTxnjUY.jpg', NULL, NULL, NULL, '2026-01-06 21:22:38', '2026-01-06 21:23:36');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_price`, `quantity`, `subtotal`, `notes`, `created_at`, `updated_at`) VALUES
(21, 15, 1, 'Big Mac', '45000.00', 1, '45000.00', NULL, '2025-12-25 10:07:50', '2025-12-25 10:07:50'),
(22, 16, 1, 'Big Mac', '45000.00', 2, '90000.00', NULL, '2025-12-25 10:31:24', '2025-12-25 10:31:24'),
(23, 17, 1, 'Big Mac', '45000.00', 1, '45000.00', NULL, '2025-12-25 10:35:08', '2025-12-25 10:35:08'),
(24, 17, 2, 'Quarter Pounder with Cheese', '52000.00', 1, '52000.00', NULL, '2025-12-25 10:35:08', '2025-12-25 10:35:08'),
(25, 18, 1, 'Big Mac', '45000.00', 1, '45000.00', 'Extra sauce please', '2025-12-25 10:41:09', '2025-12-25 10:41:09'),
(26, 18, 2, 'Quarter Pounder with Cheese', '52000.00', 1, '52000.00', 'Extra sauce please', '2025-12-25 10:41:09', '2025-12-25 10:41:09'),
(27, 18, 3, 'McDouble', '35000.00', 1, '35000.00', 'Extra sauce please', '2025-12-25 10:41:09', '2025-12-25 10:41:09'),
(28, 19, 1, 'Big Mac', '45000.00', 1, '45000.00', NULL, '2025-12-25 22:42:21', '2025-12-25 22:42:21'),
(29, 19, 2, 'Quarter Pounder with Cheese', '52000.00', 1, '52000.00', NULL, '2025-12-25 22:42:21', '2025-12-25 22:42:21'),
(30, 19, 7, 'Beef Deluxe', '42000.00', 1, '42000.00', NULL, '2025-12-25 22:42:21', '2025-12-25 22:42:21'),
(31, 19, 21, 'Apple Slices', '10000.00', 1, '10000.00', NULL, '2025-12-25 22:42:21', '2025-12-25 22:42:21'),
(32, 20, 18, 'French Fries Medium', '18000.00', 1, '18000.00', NULL, '2025-12-25 22:44:13', '2025-12-25 22:44:13'),
(33, 20, 21, 'Apple Slices', '10000.00', 1, '10000.00', NULL, '2025-12-25 22:44:13', '2025-12-25 22:44:13'),
(34, 21, 9, 'Ayam Goreng McD', '28000.00', 1, '28000.00', NULL, '2026-01-02 01:09:33', '2026-01-02 01:09:33'),
(35, 21, 12, 'Chicken McNuggets 20 pcs', '85000.00', 1, '85000.00', NULL, '2026-01-02 01:09:33', '2026-01-02 01:09:33'),
(36, 21, 21, 'Apple Slices', '10000.00', 1, '10000.00', NULL, '2026-01-02 01:09:33', '2026-01-02 01:09:33'),
(37, 21, 51, 'Breakfast Burrito', '35000.00', 1, '35000.00', NULL, '2026-01-02 01:09:33', '2026-01-02 01:09:33'),
(38, 22, 51, 'Breakfast Burrito', '35000.00', 1, '35000.00', NULL, '2026-01-06 20:30:04', '2026-01-06 20:30:04'),
(39, 23, 1, 'Big Mac', '45000.00', 1, '45000.00', NULL, '2026-01-06 21:06:23', '2026-01-06 21:06:23'),
(40, 24, 9, 'Ayam Goreng McD', '28000.00', 1, '28000.00', NULL, '2026-01-06 21:22:38', '2026-01-06 21:22:38');

-- --------------------------------------------------------

--
-- Table structure for table `order_status_histories`
--

CREATE TABLE `order_status_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `status` enum('pending','confirmed','processing','shipped','delivered','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_histories`
--

INSERT INTO `order_status_histories` (`id`, `order_id`, `status`, `notes`, `created_at`) VALUES
(24, 15, 'pending', 'Order created', '2025-12-25 10:07:50'),
(25, 15, 'confirmed', 'Status changed from pending to confirmed', '2025-12-25 10:09:43'),
(26, 15, 'processing', 'Status changed from confirmed to processing', '2025-12-25 10:10:06'),
(27, 15, 'shipped', 'Status changed from processing to shipped', '2025-12-25 10:10:15'),
(28, 15, 'delivered', 'Delivery confirmed by customer: sudah sampai', '2025-12-25 10:13:24'),
(29, 16, 'pending', 'Order created', '2025-12-25 10:31:24'),
(30, 16, 'confirmed', 'Status changed from pending to confirmed', '2025-12-25 10:31:45'),
(31, 16, 'processing', 'Status changed from confirmed to processing', '2025-12-25 10:31:54'),
(32, 16, 'shipped', 'Status changed from processing to shipped', '2025-12-25 10:32:05'),
(33, 17, 'pending', 'Order created', '2025-12-25 10:35:08'),
(34, 17, 'shipped', 'Order shipped for UI testing', '2025-12-25 10:35:08'),
(35, 16, 'delivered', 'Delivery confirmed by customer: No additional notes', '2025-12-25 10:36:08'),
(36, 18, 'pending', 'Order created', '2025-12-25 10:41:09'),
(37, 17, 'delivered', 'Delivery confirmed by customer: No additional notes', '2025-12-25 10:52:08'),
(38, 17, 'completed', 'Status changed from delivered to completed', '2025-12-25 10:52:42'),
(39, 16, 'completed', 'Status changed from delivered to completed', '2025-12-25 10:53:02'),
(40, 15, 'completed', 'Status changed from delivered to completed', '2025-12-25 10:53:19'),
(41, 17, 'completed', 'Status changed from completed to completed', '2025-12-25 22:40:18'),
(42, 17, 'delivered', 'Status changed from completed to delivered', '2025-12-25 22:41:42'),
(43, 17, 'pending', 'Status changed from delivered to pending', '2025-12-25 22:41:51'),
(44, 19, 'pending', 'Order created', '2025-12-25 22:42:21'),
(45, 19, 'confirmed', 'Status changed from pending to confirmed', '2025-12-25 22:43:01'),
(46, 20, 'pending', 'Order created', '2025-12-25 22:44:13'),
(47, 17, 'completed', 'Status changed from pending to completed', '2025-12-25 22:44:50'),
(48, 19, 'processing', 'Status changed from confirmed to processing', '2025-12-25 22:51:23'),
(49, 19, 'shipped', 'Status changed from processing to shipped', '2025-12-25 22:51:36'),
(50, 19, 'delivered', 'Delivery confirmed by customer: Cepat', '2025-12-25 22:52:22'),
(51, 19, 'completed', 'Status changed from delivered to completed', '2025-12-25 22:53:05'),
(52, 20, 'confirmed', 'Status changed from pending to confirmed', '2025-12-25 22:53:20'),
(53, 20, 'processing', 'Status changed from confirmed to processing', '2025-12-25 22:53:27'),
(54, 20, 'shipped', 'Status changed from processing to shipped', '2025-12-25 22:53:36'),
(55, 20, 'delivered', 'Delivery confirmed by customer: No additional notes', '2025-12-25 22:58:13'),
(56, 21, 'pending', 'Order created', '2026-01-02 01:09:33'),
(57, 21, 'confirmed', 'Status changed from pending to confirmed', '2026-01-02 01:09:56'),
(58, 21, 'processing', 'Status changed from confirmed to processing', '2026-01-02 01:10:10'),
(59, 21, 'shipped', 'Status changed from processing to shipped', '2026-01-02 01:10:19'),
(60, 21, 'delivered', 'Delivery confirmed by customer: No additional notes', '2026-01-02 01:10:41'),
(61, 21, 'completed', 'Status changed from delivered to completed', '2026-01-02 01:10:54'),
(62, 20, 'completed', 'Status changed from delivered to completed', '2026-01-06 20:28:36'),
(63, 22, 'pending', 'Order created', '2026-01-06 20:30:04'),
(64, 22, 'shipped', 'Status changed from pending to shipped', '2026-01-06 20:30:49'),
(65, 22, 'delivered', 'Delivery confirmed by customer: No additional notes', '2026-01-06 20:31:17'),
(66, 23, 'pending', 'Order created', '2026-01-06 21:06:23'),
(67, 23, 'confirmed', 'Status changed from pending to confirmed', '2026-01-06 21:07:25'),
(68, 23, 'processing', 'Status changed from confirmed to processing', '2026-01-06 21:07:43'),
(69, 23, 'shipped', 'Status changed from processing to shipped', '2026-01-06 21:07:59'),
(70, 23, 'delivered', 'Delivery confirmed by customer: No additional notes', '2026-01-06 21:08:24'),
(71, 23, 'completed', 'Status changed from delivered to completed', '2026-01-06 21:08:43'),
(72, 24, 'pending', 'Order created', '2026-01-06 21:22:38'),
(73, 24, 'processing', 'Status changed from pending to processing', '2026-01-06 21:23:26'),
(74, 24, 'processing', 'Status changed from processing to processing', '2026-01-06 21:23:30'),
(75, 24, 'processing', 'Status changed from processing to processing', '2026-01-06 21:23:33'),
(76, 24, 'confirmed', 'Status changed from processing to confirmed', '2026-01-06 21:23:36');

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 1, 'Big Mac', 'Dua daging sapi 100% dengan saus Big Mac, selada, keju, acar, bawang di atas roti wijen tiga lapis.', '45000.00', 'products/kPtWQ2d8euBzMk3ZMZoorUyNqgeOWys0oZOILH2R.jpg', 1, 1, '2025-12-25 07:59:36', '2025-12-25 09:20:21'),
(2, 1, 'Quarter Pounder with Cheese', 'Seperempat pon daging sapi segar dengan keju, bawang, acar, ketchup, dan mustard.', '52000.00', 'products/CzF7cVIlGILuZzksqC36DmtbGenSQn77cV7YFqXL.jpg', 1, 1, '2025-12-25 07:59:36', '2025-12-25 20:52:29'),
(3, 1, 'McDouble', 'Dua daging sapi dengan keju, acar, bawang, ketchup, dan mustard.', '35000.00', 'products/ueww1qvTF9DqhA5wmULOqgFOvbL2uc7nmXBhBGA2.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 20:54:26'),
(4, 1, 'Cheeseburger', 'Daging sapi dengan keju, acar, bawang, ketchup, dan mustard.', '25000.00', 'products/gBDaOWCBuBtT5VCv55qOXhNB9SuNFvCnfZrg0xfC.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 20:56:58'),
(5, 1, 'Hamburger', 'Daging sapi dengan acar, bawang, ketchup, dan mustard.', '22000.00', 'products/XA1g4iffCKoeGPGqZiyeuEHYe4Rm1pKSfzXbeQZ5.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:02:15'),
(6, 1, 'Double Cheeseburger', 'Dua daging sapi dengan dua keju, acar, bawang, ketchup, dan mustard.', '38000.00', 'products/SJUxijLN3nkwapozlqWgJaNCQ3iaXS2sRZK49Oeu.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:03:48'),
(7, 1, 'Beef Deluxe', 'Daging sapi dengan keju, selada, tomat, bawang, dan saus spesial.', '42000.00', 'products/w164FisSkmnXdgu8E1g7xWqwa16FFCCGQ9jW0Rii.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:05:50'),
(8, 1, 'Spicy Beef Burger', 'Daging sapi dengan saus pedas, keju, selada, dan tomat.', '40000.00', 'products/2wFaakSvyYkBrXtM5EyQ2nuDnS2Mdurkug21zxR7.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:07:22'),
(9, 2, 'Ayam Goreng McD', 'Ayam goreng renyah dengan bumbu rahasia McDonald\'s.', '28000.00', 'products/hbq3ZQ8LRMTEDfImleiJZ8WQErkAhhHykD5GmFLy.jpg', 1, 1, '2025-12-25 07:59:36', '2025-12-25 21:09:32'),
(10, 2, 'Chicken McNuggets 6 pcs', 'Enam potong nugget ayam renyah dengan pilihan saus.', '32000.00', 'products/SuA34F0qDZ8zsC47JXgvwagJw7vl9xgLigyFmG3j.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:11:35'),
(11, 2, 'Chicken McNuggets 9 pcs', 'Sembilan potong nugget ayam renyah dengan pilihan saus.', '45000.00', 'products/YYy6aWRkMpJxtGvnm3FlNNKZVU6LzXbc0it57sQj.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:12:13'),
(12, 2, 'Chicken McNuggets 20 pcs', 'Dua puluh potong nugget ayam renyah dengan pilihan saus.', '85000.00', 'products/BgN5Na0MJWEQSkOdGfTqXwMky6d25xBziXsX6LWX.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:13:08'),
(13, 2, 'McChicken', 'Ayam crispy dengan mayones dan selada dalam roti lembut.', '35000.00', 'products/RUJxJebqlrSNfva0upSxJ8iz6iVOB9Ks3ICiTHEb.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:14:25'),
(14, 2, 'Spicy McChicken', 'Ayam crispy pedas dengan mayones dan selada dalam roti lembut.', '37000.00', 'products/0C77uDAoNvxlDRtxYk7uwkrZO5FoSoQMuGCtUNSH.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:15:15'),
(15, 2, 'Chicken Deluxe', 'Ayam crispy dengan keju, selada, tomat, dan saus spesial.', '42000.00', 'products/9KZmIhE3xCzheTzkqcifAmVMCQOFLtqwhWklpl9g.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:16:28'),
(16, 2, 'Ayam Goreng Pedas', 'Ayam goreng dengan bumbu pedas khas McDonald\'s.', '30000.00', 'products/aHWIyzHMdZwrVE3ampFatQvbMoUAhmaxzBtNCGRE.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:20:56'),
(17, 3, 'French Fries Small', 'Kentang goreng renyah ukuran kecil.', '15000.00', 'products/LH1bNOVPmXCYy7LGFkRSf0cNmOPqUcMuSyRBi1bl.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:24:12'),
(18, 3, 'French Fries Medium', 'Kentang goreng renyah ukuran sedang.', '18000.00', 'products/I1wFVZavYjrGuWlKOcWzbEAooel4kRvU65XiI8G2.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:25:09'),
(19, 3, 'French Fries Large', 'Kentang goreng renyah ukuran besar.', '22000.00', 'products/DR9gxFBlvwG82eXt7BYUIKUPOReSOGRuZCFk8FCs.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:27:45'),
(20, 3, 'Hash Browns', 'Kentang parut goreng renyah.', '12000.00', 'products/mLc3h0tcU9zP5IOEkKPc2Qwh6G0IDycBx6gnJhTO.jpg', 1, 0, '2025-12-25 07:59:36', '2025-12-25 21:30:21'),
(21, 3, 'Apple Slices', 'Potongan apel segar sebagai camilan sehat.', '10000.00', 'products/eZ1D8IL968JaARVRjTNm3shS4Qb82xrhJlKQyx5S.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:55:21'),
(22, 3, 'Corn Cup', 'Jagung manis dalam cup.', '8000.00', 'products/JwTgvoMMrC4tsv9ftJk9IIb4Z4urdfgk7l1HEL2N.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:23:23'),
(23, 3, 'Salad Sayur', 'Salad segar dengan sayuran pilihan dan dressing.', '25000.00', 'products/SUr0mWcUqgWkfmSS4Q6vKh1pEwrHthZNtN1BiFtq.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:24:15'),
(24, 4, 'Coca Cola Small', 'Minuman berkarbonasi Coca Cola ukuran kecil.', '12000.00', 'products/YShBYK4AiUOTutAbzUooGboA3BMwclz9NKMuFW6P.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:26:18'),
(25, 4, 'Coca Cola Medium', 'Minuman berkarbonasi Coca Cola ukuran sedang.', '15000.00', 'products/IAoEQgNgHSQJ8nfro1YqZ6XVxNWcSsm7cKSuPkBZ.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:39:12'),
(26, 4, 'Coca Cola Large', 'Minuman berkarbonasi Coca Cola ukuran besar.', '18000.00', 'products/1dgmarpJOOfrZnJCMlmuY81aDkpO6cwMoyEPbUGn.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:39:39'),
(27, 4, 'Sprite Small', 'Minuman berkarbonasi Sprite ukuran kecil.', '12000.00', 'products/6GBQds6ME5uaWIFyry9pRkPSqaqG96daolgJKKJ2.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:48:48'),
(28, 4, 'Sprite Medium', 'Minuman berkarbonasi Sprite ukuran sedang.', '15000.00', 'products/n5GqqmCJfujtop7vPHi4IqL9Ehvh65iH8MJsLx2X.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:49:13'),
(29, 4, 'Fanta Small', 'Minuman berkarbonasi Fanta ukuran kecil.', '12000.00', 'products/8npgJJ2O7MJXijM8CYLT9iFaLwqgzbgoL9nfCKvX.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:50:15'),
(30, 4, 'Orange Juice', 'Jus jeruk segar 100% alami.', '18000.00', 'products/Ij5HiJB1014F6vrDbcEqt4uuDlcQYU1RdEgEvRC6.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:54:13'),
(32, 4, 'Iced Coffee', 'Kopi dingin dengan es.', '20000.00', 'products/oJFx9lcSrN9pdatJwzfryRA6X42ChNQPovyqpXVf.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:17:32'),
(33, 4, 'Hot Coffee', 'Kopi panas premium.', '18000.00', 'products/1z3lfWy9NcbtKFh0F5lnlaOVALsMKElEyKnyy6xP.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:18:02'),
(34, 4, 'Iced Tea', 'Teh dingin dengan es.', '15000.00', 'products/rkiLyjEZYGX3Y6eDGXCuHkUrWpQYhR91EIdzlA3f.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:18:55'),
(36, 4, 'Mineral Water', 'Air mineral dalam kemasan.', '8000.00', 'products/VFjgMOnlcD7LcEF87XFG63zgCeymCDVlBVgUJF2l.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:20:10'),
(37, 5, 'McFlurry Oreo', 'Es krim vanilla dengan topping Oreo yang dihancurkan.', '25000.00', 'products/kW6MIlgj0BqvwFrpGtHWoq96ANY6umAjWwxC7SQG.jpg', 1, 1, '2025-12-25 07:59:36', '2026-01-02 00:21:07'),
(38, 5, 'McFlurry M&M', 'Es krim vanilla dengan topping M&M coklat.', '25000.00', 'products/LDvZMyhcxu2D5OelHnTtzwXdx4eL9UwbWATNIoeb.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:21:44'),
(39, 5, 'Vanilla Cone', 'Es krim vanilla dalam cone renyah.', '8000.00', 'products/abD4nS94S8JYTKcwiiz3XVfbvBkn0Q5Pgu5zZ2yR.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:28:09'),
(40, 5, 'Chocolate Cone', 'Es krim coklat dalam cone renyah.', '8000.00', 'products/Y26qySLx1D3ET3dF1g51GNRKdmvSys4yflGOkN1S.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:28:29'),
(41, 5, 'Apple Pie', 'Pai apel hangat dengan kulit renyah.', '15000.00', 'products/4wHaw41ppUGLaOBDXthLOkSMywZeSgMniA7tbiwl.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-01 23:56:22'),
(42, 5, 'Chocolate Chip Cookies', 'Kue kering dengan chocolate chip.', '12000.00', 'products/6beCGY8pTwCWtWnLO9slEUFW08AMvBHgVjDB5Dkq.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:29:34'),
(43, 5, 'Sundae Strawberry', 'Es krim vanilla dengan saus strawberry.', '18000.00', 'products/uUWlx6Q2jlCslHGai8UGGhDwhxz3r4sfNxIJJ7Na.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:30:01'),
(44, 5, 'Sundae Chocolate', 'Es krim vanilla dengan saus coklat.', '18000.00', 'products/2SUmNdpUn5JA605NIdbFscD45GAG1MADCr5sJdJK.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:30:54'),
(45, 6, 'Egg McMuffin', 'Telur, keju, dan ham dalam English muffin.', '32000.00', 'products/6nleAdbmWS8CjbQBRXo5TQdL73tATLfqs0iom2zW.jpg', 1, 1, '2025-12-25 07:59:36', '2026-01-02 00:07:39'),
(48, 6, 'Hotcakes', 'Tiga pancake lembut dengan sirup maple dan mentega.', '30000.00', 'products/gWTZ2yfNjHtDpm1fLYLKgXaj2IUOC6cyTomNFX7g.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:33:02'),
(50, 6, 'Scrambled Eggs', 'Telur orak-arik lembut.', '15000.00', NULL, 1, 0, '2025-12-25 07:59:36', '2025-12-25 07:59:36'),
(51, 6, 'Breakfast Burrito', 'Tortilla dengan telur, keju, dan sosis.', '35000.00', 'products/BB8SOT0tUcdqWR3FVu8X1wxerlFrKcF4t1Z9r5ZU.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:06:07'),
(52, 6, 'Oatmeal', 'Oatmeal hangat dengan topping buah.', '22000.00', 'products/M4QgK46D2o0gziT303MOgoI8N1LELeh9t5E0ELmQ.jpg', 1, 0, '2025-12-25 07:59:36', '2026-01-02 00:35:59');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'products/gallery/Ob4fAaiIHspQzIzVIlgUJLidfwbUbaQfxg95MGc3.jpg', 1, '2025-12-25 09:20:21', '2025-12-25 09:20:21'),
(2, 2, 'products/gallery/3CwsULbrct164PiTsTkQ6THxa1UxtYZjzgW4MTBe.jpg', 1, '2025-12-25 20:52:29', '2025-12-25 20:52:29'),
(3, 3, 'products/gallery/kaMt7dKLRmLNQ3k5QQqnCR63xXtJF8HSO1sDzzl7.jpg', 1, '2025-12-25 20:54:26', '2025-12-25 20:54:26'),
(4, 4, 'products/gallery/ov8IQCfn8gFQnr8lrHfssdlYCZ90yMINoKOuCx2w.jpg', 1, '2025-12-25 20:56:54', '2025-12-25 20:56:54'),
(5, 4, 'products/gallery/EZeISeuhXUc4RXEjJqaERziM5YUugge9hDFegzCf.jpg', 2, '2025-12-25 20:56:57', '2025-12-25 20:56:57'),
(6, 4, 'products/gallery/WxKYSJrGHXbGzPGhYdTjKYvjVi8m2mgNAg9t2x9I.jpg', 3, '2025-12-25 20:56:58', '2025-12-25 20:56:58'),
(7, 5, 'products/gallery/f88uxIDzcoFEkkSUPlRA0uvGhEVt4J9Wbv3UslvT.jpg', 1, '2025-12-25 21:02:16', '2025-12-25 21:02:16'),
(8, 6, 'products/gallery/LeEZWSUxh2YmIUSrJ28TtFosMr1SlGOPUJUzvA1I.jpg', 1, '2025-12-25 21:03:48', '2025-12-25 21:03:48'),
(9, 7, 'products/gallery/7ZuMOOQKgFiBRlmVyvmwNkUCFMCN5NB5prL1ALDA.jpg', 1, '2025-12-25 21:05:51', '2025-12-25 21:05:51'),
(10, 8, 'products/gallery/gZSKBj63669hOhor3aHaDcazkLdfvrvFlwPnwikd.jpg', 1, '2025-12-25 21:07:22', '2025-12-25 21:07:22'),
(11, 9, 'products/gallery/N4H7yavZLLOA6EcEzGRhTH2cAy4aF19IHXv9qtJK.jpg', 1, '2025-12-25 21:08:24', '2025-12-25 21:08:24'),
(12, 9, 'products/gallery/zBDl4HDnZ1JymafuEfQQVzlGOSiGggbCqQsJVlzQ.jpg', 2, '2025-12-25 21:09:32', '2025-12-25 21:09:32'),
(13, 10, 'products/gallery/TIjp8sRx9zqITTJrtEox4NMZEgAzA4MfF0z36m7W.jpg', 1, '2025-12-25 21:11:35', '2025-12-25 21:11:35'),
(14, 11, 'products/gallery/ty6sFFOB05aVrCy2N2f1LLuceG7sYdmrpi3wToMI.jpg', 1, '2025-12-25 21:12:13', '2025-12-25 21:12:13'),
(15, 12, 'products/gallery/s5KpPrOWmnqXYrBwC31EhKWCB7hCJRdVgELSasgd.jpg', 1, '2025-12-25 21:13:08', '2025-12-25 21:13:08'),
(16, 13, 'products/gallery/xb4HqGICMEipYdVrTFGibEnGGEE6SikALwMIEmDz.jpg', 1, '2025-12-25 21:14:25', '2025-12-25 21:14:25'),
(17, 14, 'products/gallery/sSIf8QYQaalepZuDdqFoI2FJupYmbp3Ki3pKgg68.jpg', 1, '2025-12-25 21:15:15', '2025-12-25 21:15:15'),
(18, 15, 'products/gallery/yIWWgqDPuyFMbS3cHPAM7t4wPE1zsVLWd6HW2jKJ.jpg', 1, '2025-12-25 21:16:29', '2025-12-25 21:16:29'),
(20, 16, 'products/gallery/8F6L6W9CSE2mn0rtT4eqHc02KLb9yDOBmRKEK7ai.jpg', 1, '2025-12-25 21:20:56', '2025-12-25 21:20:56'),
(21, 17, 'products/gallery/oBzGK8isk2q4EBDc2vIioHdRX9WibIXjwPqr3xSs.jpg', 1, '2025-12-25 21:24:12', '2025-12-25 21:24:12'),
(22, 18, 'products/gallery/HDLhnux0icttnsShimM8Xf8DivnCpk8AGsrNT4fe.jpg', 1, '2025-12-25 21:25:09', '2025-12-25 21:25:09'),
(23, 19, 'products/gallery/9WYyKEiBeI95xWyPHDzhlbTu4C7GkLLoubWs1ric.jpg', 1, '2025-12-25 21:27:45', '2025-12-25 21:27:45'),
(24, 20, 'products/gallery/L01i4dF1ublMWOHV04oi2qwlsi5iG1J9pj5lZBHB.jpg', 1, '2025-12-25 21:30:21', '2025-12-25 21:30:21'),
(26, 22, 'products/gallery/6rTcmToyNFXP4S4dWdEKXzB76CvOtA0suspiGeip.jpg', 1, '2026-01-01 23:23:23', '2026-01-01 23:23:23'),
(27, 23, 'products/gallery/gxouOUal49PuBNb2qIblfYYTjt7tRlSOAM09alCO.jpg', 1, '2026-01-01 23:24:15', '2026-01-01 23:24:15'),
(28, 24, 'products/gallery/BzVLTRP4xqqRYVHfo7kNuWDN5jtHbvh7TVokyl3Y.jpg', 1, '2026-01-01 23:26:18', '2026-01-01 23:26:18'),
(29, 25, 'products/gallery/ZZ2oO5SePyoKKXXweEjGHiOXdNBZYuOgKwC1ioOT.jpg', 1, '2026-01-01 23:39:12', '2026-01-01 23:39:12'),
(30, 26, 'products/gallery/Gm3j0SVlT4gUFgkv4e9PrjBUVLNHIBw7zajncrTk.jpg', 1, '2026-01-01 23:39:39', '2026-01-01 23:39:39'),
(31, 27, 'products/gallery/tyJoUVdBzNBvicQA4Z31M8JDngI3ZerQk6KhO77M.jpg', 1, '2026-01-01 23:48:48', '2026-01-01 23:48:48'),
(32, 29, 'products/gallery/QTWgLu8dIVdCeI8v5xe2gwqHqqvKZXQNp2VJhrFy.jpg', 1, '2026-01-01 23:50:15', '2026-01-01 23:50:15'),
(33, 30, 'products/gallery/6YW7zxqq9QdaE1IGk05NacjHeg7ifg77mCXMXVkJ.jpg', 1, '2026-01-01 23:54:13', '2026-01-01 23:54:13'),
(34, 21, 'products/gallery/5hT4bWhh0eCBRK50UBaYQjKr8XbOj8ffFniUcANG.jpg', 1, '2026-01-01 23:55:21', '2026-01-01 23:55:21'),
(35, 41, 'products/gallery/9HRN9uixoRe4laMVdQsesBidFiFlToniHyWIEnXj.jpg', 1, '2026-01-01 23:56:22', '2026-01-01 23:56:22'),
(37, 51, 'products/gallery/RSbKAkTfGMqgfEA6UKtVq1CZ5AVk3WwJqy2H6Ywr.jpg', 1, '2026-01-02 00:06:07', '2026-01-02 00:06:07'),
(38, 45, 'products/gallery/adKzX3yRGd1robLQrJ718rS7FVRGT9KNYFPFannk.jpg', 1, '2026-01-02 00:07:39', '2026-01-02 00:07:39'),
(39, 32, 'products/gallery/OvXkdYQNduMmSBQgeEpR44R6QCC0ijUdErWb3u3S.jpg', 1, '2026-01-02 00:17:32', '2026-01-02 00:17:32'),
(40, 33, 'products/gallery/rVRDUZLnBHRcxigkg1z7ikSWJWmeLUzxfAzfKSUE.jpg', 1, '2026-01-02 00:18:02', '2026-01-02 00:18:02'),
(41, 34, 'products/gallery/BqxmSH5YL4RphJZ30UkDlOKJf0A0bDi7vH8WAdO5.jpg', 1, '2026-01-02 00:18:55', '2026-01-02 00:18:55'),
(42, 36, 'products/gallery/NyLuGa8TGY1LfcUobVRZjDg2BciVHQRa95DA3Sy6.jpg', 1, '2026-01-02 00:20:10', '2026-01-02 00:20:10'),
(43, 37, 'products/gallery/YfVod1uyM7CrnPdhroQOjUI9LFlD5Nx9XwXOcav9.jpg', 1, '2026-01-02 00:21:07', '2026-01-02 00:21:07'),
(44, 38, 'products/gallery/v8bYdlrQGVuYfvSDP8Al6Mbn4I64AhHRObV8rKaw.jpg', 1, '2026-01-02 00:21:44', '2026-01-02 00:21:44'),
(45, 39, 'products/gallery/pj9Tqv1Z6mivGVK51LVyazSVzw4auyENkWbRTvWG.jpg', 1, '2026-01-02 00:28:09', '2026-01-02 00:28:09'),
(46, 40, 'products/gallery/OFALnocE6sn4wl9rQxQ72iuCFE8le0d1fAYCJMmA.jpg', 1, '2026-01-02 00:28:29', '2026-01-02 00:28:29'),
(47, 42, 'products/gallery/mgSh5mGolYuUXj3gsYECBfxZ2oKwPwoWfjqkoT0v.jpg', 1, '2026-01-02 00:29:34', '2026-01-02 00:29:34'),
(48, 43, 'products/gallery/OJCgHyOaXCjON0P1DejKcu13CrNPOAbZ2NXjEwlN.jpg', 1, '2026-01-02 00:30:01', '2026-01-02 00:30:01'),
(49, 44, 'products/gallery/GzbpRlGSoaOv9sJ6Wqt8fhSZxxpYDIQMLyDTb4tn.jpg', 1, '2026-01-02 00:30:54', '2026-01-02 00:30:54'),
(50, 48, 'products/gallery/NEmKrGqmq7q6duJsmfXej46XzSRc3GqP5ynvZbqK.jpg', 1, '2026-01-02 00:33:02', '2026-01-02 00:33:02'),
(51, 52, 'products/gallery/6tBGBIFtOF8CcZ4EVEa2GDViR2agQGTTHankqZyP.jpg', 1, '2026-01-02 00:35:59', '2026-01-02 00:35:59');

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
('6MuT1uIsPiG4c8LbouPyktHDTSoC4i4bwnJuitRd', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR1R2NFc2UFhxbHo3NVlrT08zOU9oSmdiT1VpNE9WUGxFRTRubTdWOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9vcmRlcnMvMjQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1767759832),
('qkS65teeLxBojy5iEjP6RieCx8vGw56a9SnTQwxp', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTlpSbGtJQzZnbXJFNWxnSFpJRDlObzhXYmdrRWo0SHAyaTBXc1R2MSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9vcmRlcnMvMjQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1767759820);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(4, 'Prof. Rickey Gerhold IV', 'daphne.stark@example.net', '+1-680-629-3676', '9074 Larson Rapids\nCarlottaland, AR 15213', 'user', '2025-12-25 09:40:33', '$2y$12$JW2N.Us.eI5361PQK5qi6Oeu1DiVkKM6MqKgvKD5CMYolHoHy1qFq', 'YHgQ9o2nSk', '2025-12-25 09:40:33', '2025-12-25 09:40:33'),
(5, 'Admin McD', 'admin@mcd.com', '081234567890', 'Jakarta, Indonesia', 'admin', '2025-12-25 09:47:19', '$2y$12$hYeXKXaZuGuJ98u/tKIPhOQ/YXHwDk5.UXKLmxDJGvGws20gB5C3q', NULL, '2025-12-25 09:47:19', '2025-12-25 09:47:19'),
(6, 'Test User', 'user@mcd.com', '081234567891', 'Jakarta, Indonesia', 'user', '2025-12-25 09:47:19', '$2y$12$uIWmA5rdXMjqCFySnlv8b.5N.Uf2GJLm24slxS1xKSi2/4gtJ6z8a', NULL, '2025-12-25 09:47:19', '2025-12-25 09:47:19'),
(7, 'adit', 'raflipraditta@gmail.com', '081211749731', NULL, 'user', NULL, '$2y$12$uc2ZEh4FXEjej3oPihqP1OGuPNwh7CUQYO4Ew36c6O4L35dd761Py', NULL, '2025-12-25 22:37:31', '2025-12-25 22:37:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carts_user_id_unique` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_cart_id_product_id_unique` (`cart_id`,`product_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_messages_user_id_foreign` (`user_id`),
  ADD KEY `contact_messages_is_read_index` (`is_read`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_order_id_unique` (`order_id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_payment_status_index` (`payment_status`),
  ADD KEY `orders_created_at_index` (`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_status_histories_order_id_foreign` (`order_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_is_available_index` (`is_available`),
  ADD KEY `products_is_featured_index` (`is_featured`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_status_histories`
--
ALTER TABLE `order_status_histories`
  ADD CONSTRAINT `order_status_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
