-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2026 at 07:29 PM
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
-- Database: `baby_cradle_iot`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `device_token` varchar(255) NOT NULL,
  `family_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `device_name`, `device_token`, `family_id`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 'Cradle Sensor', 'dd332414-fed9-46c7-9cf4-f5a5c9c1ef17', 1, 3, '2026-06-12 14:01:15', '2026-06-18 07:19:17'),
(4, 'Baby Monitor 1', '708e7e8d-24b0-41c9-957e-3fe11f5275d9', NULL, NULL, '2026-06-12 15:03:15', '2026-06-15 08:29:03'),
(5, 'Baby Monitor 2', '04a704e4-387a-4ac6-abce-db41b1701144', 2, NULL, '2026-06-12 16:10:05', '2026-06-15 10:11:26');

-- --------------------------------------------------------

--
-- Table structure for table `device_activities`
--

CREATE TABLE `device_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_type` varchar(255) DEFAULT NULL,
  `payload` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `device_activities`
--

INSERT INTO `device_activities` (`id`, `device_id`, `event_type`, `payload`, `created_at`, `updated_at`) VALUES
(1, 3, 'cry_detected', 'detected=1', '2026-07-02 07:47:17', '2026-07-02 07:47:17'),
(2, 3, 'cry_detected', 'detected=1', '2026-07-02 07:49:07', '2026-07-02 07:49:07'),
(3, 3, 'cry_detected', 'detected=1', '2026-07-02 07:54:16', '2026-07-02 07:54:16'),
(4, 3, 'cry detected', 'detected=1', '2026-07-02 08:10:35', '2026-07-02 08:10:35'),
(5, 3, 'cry detected', 'detected=1', '2026-07-02 08:11:16', '2026-07-02 08:11:16'),
(6, 3, 'cry detected', 'detected=2', '2026-07-02 08:12:34', '2026-07-02 08:12:34'),
(7, 3, 'cry detected', 'detected=2', '2026-07-02 08:19:31', '2026-07-02 08:19:31'),
(8, 3, 'dht', '{\"temperature\":28.8,\"humidity\":58,\"temp_alert\":false,\"humid_alert\":false}', '2026-07-13 13:24:05', '2026-07-13 13:24:05'),
(9, 3, 'dht', '{\"temperature\":30.1,\"humidity\":82,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 13:24:25', '2026-07-13 13:24:25'),
(10, 3, 'dht', '{\"temperature\":30.8,\"humidity\":84,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 13:24:33', '2026-07-13 13:24:33'),
(11, 3, 'dht', '{\"temperature\":29,\"humidity\":60,\"temp_alert\":false,\"humid_alert\":false}', '2026-07-13 13:25:04', '2026-07-13 13:25:04'),
(12, 3, 'dht', '{\"temperature\":29.5,\"humidity\":45,\"temp_alert\":false,\"humid_alert\":false}', '2026-07-13 13:25:34', '2026-07-13 13:25:34'),
(13, 3, 'dht', '{\"temperature\":29,\"humidity\":42,\"temp_alert\":false,\"humid_alert\":false}', '2026-07-13 13:26:05', '2026-07-13 13:26:05'),
(14, 3, 'dht', '{\"temperature\":29.1,\"humidity\":41,\"temp_alert\":false,\"humid_alert\":false}', '2026-07-13 13:26:35', '2026-07-13 13:26:35'),
(15, 3, 'dht', '{\"temperature\":29.6,\"humidity\":40,\"temp_alert\":false,\"humid_alert\":false}', '2026-07-13 13:27:05', '2026-07-13 13:27:05'),
(16, 3, 'dht', '{\"temperature\":29.5,\"humidity\":40,\"temp_alert\":false,\"humid_alert\":false}', '2026-07-13 13:27:35', '2026-07-13 13:27:35'),
(17, 3, 'dht', '{\"temperature\":29,\"humidity\":40,\"temp_alert\":false,\"humid_alert\":false}', '2026-07-13 13:28:06', '2026-07-13 13:28:06'),
(18, 3, 'dht', '{\"temperature\":28.5,\"humidity\":40,\"temp_alert\":false,\"humid_alert\":false}', '2026-07-13 13:28:36', '2026-07-13 13:28:36'),
(19, 3, 'dht', '{\"temperature\":30.5,\"humidity\":82,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 13:37:48', '2026-07-13 13:37:48'),
(20, 3, 'dht', '{\"temperature\":29.4,\"humidity\":86,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 13:38:03', '2026-07-13 13:38:03'),
(21, 3, 'dht', '{\"temperature\":30.3,\"humidity\":83,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 13:42:25', '2026-07-13 13:42:25'),
(22, 3, 'dht', '{\"temperature\":30.6,\"humidity\":83,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 13:42:40', '2026-07-13 13:42:40'),
(23, 3, 'dht', '{\"temperature\":31.2,\"humidity\":81,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 13:59:08', '2026-07-13 13:59:08'),
(24, 3, 'dht', '{\"temperature\":32,\"humidity\":86,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 13:59:10', '2026-07-13 13:59:10'),
(25, 3, 'dht', '{\"temperature\":32.7,\"humidity\":85,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 13:59:11', '2026-07-13 13:59:11'),
(26, 3, 'dht', '{\"temperature\":30.9,\"humidity\":83,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 14:06:21', '2026-07-13 14:06:21'),
(27, 3, 'dht', '{\"temperature\":30.3,\"humidity\":85,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-13 14:06:36', '2026-07-13 14:06:36'),
(28, 3, 'dht', '{\"temperature\":31.1,\"humidity\":83,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-15 05:37:08', '2026-07-15 05:37:08'),
(29, 3, 'dht', '{\"temperature\":32,\"humidity\":91,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-15 05:37:24', '2026-07-15 05:37:24'),
(30, 3, 'dht', '{\"temperature\":31.3,\"humidity\":86,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-15 05:37:40', '2026-07-15 05:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `families`
--

CREATE TABLE `families` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `family_name` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `families`
--

INSERT INTO `families` (`id`, `family_name`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Twagirimukiza Family', 2, '2026-06-11 07:47:11', '2026-06-11 07:47:11'),
(2, 'Test Family', 10, '2026-06-15 08:55:29', '2026-06-15 08:55:29');

-- --------------------------------------------------------

--
-- Table structure for table `incident_notifications`
--

CREATE TABLE `incident_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `device_activity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incident_notifications`
--

INSERT INTO `incident_notifications` (`id`, `user_id`, `device_activity_id`, `device_id`, `event_type`, `title`, `body`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 2, 28, 3, 'dht', '🌡️ Environment Alert', 'Device \"Cradle Sensor\" at 07:37, 15 Jul 2026: humidity 83% is out of safe range. Please adjust the room environment.', NULL, '2026-07-15 05:37:08', '2026-07-15 05:37:08'),
(2, 3, 28, 3, 'dht', '🌡️ Environment Alert', 'Device \"Cradle Sensor\" at 07:37, 15 Jul 2026: humidity 83% is out of safe range. Please adjust the room environment.', NULL, '2026-07-15 05:37:19', '2026-07-15 05:37:19'),
(3, 2, 29, 3, 'dht', '🌡️ Environment Alert', 'Device \"Cradle Sensor\" at 07:37, 15 Jul 2026: humidity 91% is out of safe range. Please adjust the room environment.', NULL, '2026-07-15 05:37:24', '2026-07-15 05:37:24'),
(4, 3, 29, 3, 'dht', '🌡️ Environment Alert', 'Device \"Cradle Sensor\" at 07:37, 15 Jul 2026: humidity 91% is out of safe range. Please adjust the room environment.', NULL, '2026-07-15 05:37:31', '2026-07-15 05:37:31'),
(5, 2, 30, 3, 'dht', '🌡️ Environment Alert', 'Device \"Cradle Sensor\" at 07:37, 15 Jul 2026: humidity 86% is out of safe range. Please adjust the room environment.', NULL, '2026-07-15 05:37:40', '2026-07-15 05:37:40'),
(6, 3, 30, 3, 'dht', '🌡️ Environment Alert', 'Device \"Cradle Sensor\" at 07:37, 15 Jul 2026: humidity 86% is out of safe range. Please adjust the room environment.', NULL, '2026-07-15 05:37:46', '2026-07-15 05:37:46');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0000_00_00_091217_create_roles_table', 1),
(2, '0000_01_00_000000_create_users_table', 1),
(3, '0001_01_00_000000_create_families_table', 1),
(4, '0001_01_01_000001_create_cache_table', 1),
(5, '0001_01_01_000002_create_jobs_table', 1),
(6, '2026_06_05_141045_create_devices_table', 1),
(7, '2026_06_11_094117_add_family_id_to_users_table', 1),
(8, '2026_06_12_134639_create_device_activities_table', 2),
(9, '2026_06_12_000000_add_fields_to_device_activities', 3),
(10, '2026_06_15_000000_add_user_id_to_devices_table', 4),
(11, '2026_06_12_172531_create_device_activities_table', 5),
(12, '2026_07_15_051516_create_incident_notifications_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'System administrator', '2026-06-11 09:51:12', '2026-06-11 09:51:17'),
(2, 'family_parent', 'Family parent', '2026-06-11 09:51:21', '2026-06-11 09:51:25'),
(3, 'caregiver', 'Family member', '2026-06-11 09:51:28', '2026-06-11 09:51:32');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BCkMlseAlgerRcjZ3LiKgncUiwWQNPbcRXEYnjyO', NULL, '192.168.192.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjU3WXF2TmhHanh1NXQwYW1VcGgweDhxOWhnMFNXd0RtR3VXdWJ4MSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xOTIuMTY4LjE5Mi4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO319', 1783807863),
('BYYqB4rqzm1kwBrS8NsRD3xMTOL5RTX2JyPE2m7D', NULL, '172.23.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZDFKMDMzWXlOYm1VVm96UHZtZHBlRWNlZ1RsWVhsZ3l5NDdxcGxjRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xNzIuMjMuMC4xOjgwMDAvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1783884359),
('GnV22zVIni0wetByfE2uTDVkDPeMrrRT5sFAwpiR', 3, '172.29.208.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicEFpeGVXQWRERXE5akVBSFdXYXdJYU1LUm5HSFhWdTJxMkZHSm1XZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xNzIuMjkuMjA4LjE6ODAwMC9jYXJlZ2l2ZXIvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE5OiJjYXJlZ2l2ZXIuZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1784099690),
('HNU3iCmYoaBEnsqwbNM2Q3vmyB5q05sLhnhXIis1', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYlF1Yzk3VVFPUEZZRHJUdVB2RndSeTRSYnpseGR6NHltRlJxeEZhdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJlZ2l2ZXIvcmVwb3J0cyI7czo1OiJyb3V0ZSI7czoxNzoiY2FyZWdpdmVyLnJlcG9ydHMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1784099628),
('k8OeDdrnup2P3X6tHXU7NCyBAeTWn1ekLcQKV3h7', 3, '192.168.201.236', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWGROWTZSWjR1ZGEyTG9nRzl0NVZIR3dBWXVTQWFvdmpYcms2TjRTSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xOTIuMTY4LjIwMS4yMzY6ODAwMC9jYXJlZ2l2ZXIvcmVwb3J0cyI7czo1OiJyb3V0ZSI7czoxNzoiY2FyZWdpdmVyLnJlcG9ydHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1784101099),
('KdXISwCCqe1qUeHyFOUIwqbRTszoTVM4HxycJfca', NULL, '192.168.1.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTldzbTRaOWVOS3lFQVZ4VGhCV1ZPRHB6NUMxajFTRmNvOEp6eHRpdyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xOTIuMTY4LjEuODc6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1783964620),
('Z4DxF7Kayqf1znJnZHoZc9dLGrW4WWb0wfCJvf4b', NULL, '192.168.191.236', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNlFObGpRZG9Ib2NkaWhEVUxPdlp0MDVqcEhhM0p6NnBTdTNTTDFRMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xOTIuMTY4LjE5MS4yMzY6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1783885794);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `family_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `remember_token`, `created_at`, `updated_at`, `family_id`) VALUES
(1, 'System Admin', 'admin@iot.com', NULL, '$2y$12$TV5b2L1BOpKboq5JToTWcOYlmYm54Uh0epuDgJiqGCFVtwSfycstS', 1, NULL, '2026-06-11 07:47:11', '2026-06-11 07:47:11', NULL),
(2, 'Jean Parent', 'itprofessional680@gmail.com', NULL, '$2y$12$.E3YhncApdTrMm8CuIRL3uKercY7tfSomwbLtwifuOwmZpEenEV1K', 2, NULL, '2026-06-11 07:47:11', '2026-07-02 07:49:01', 1),
(3, 'Dukuze VAVA', 'valentineduk@gmail.com', NULL, '$2y$12$CaDpkRVAxC8WoYmAeIT3Gux3p4beec5R/vE38YCKZrFTZ/7wC75ZG', 3, NULL, '2026-06-11 07:47:11', '2026-07-02 06:58:20', 1),
(4, 'Member Two', 'member2@iot.com', NULL, '$2y$12$zCskN13K9ECFEkXmSKSY8eBTM1rLLgSWTf3SaiHpZREGV6v/YL49y', 3, NULL, '2026-06-11 07:47:12', '2026-06-11 07:47:12', 1),
(5, 'Member Three', 'member3@iot.com', NULL, '$2y$12$lb7wZES7EztWc44ld.2YwuAicoESDf7oX4c2cRe1wYOVfTGFKgpLC', 3, NULL, '2026-06-11 07:47:12', '2026-06-11 07:47:12', 1),
(10, 'Testfy', 'testfy@iot.com', NULL, '$2y$12$hCpxZddH8SslSXIczenUW.N7LjG8p0d/miN1s9saXzWZHGav9idBu', 2, NULL, '2026-06-15 08:55:29', '2026-06-15 08:55:29', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `devices_device_token_unique` (`device_token`),
  ADD KEY `devices_family_id_foreign` (`family_id`),
  ADD KEY `devices_user_id_foreign` (`user_id`);

--
-- Indexes for table `device_activities`
--
ALTER TABLE `device_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_activities_device_id_index` (`device_id`),
  ADD KEY `device_activities_event_type_index` (`event_type`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`id`),
  ADD KEY `families_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `incident_notifications`
--
ALTER TABLE `incident_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_notifications_device_id_foreign` (`device_id`),
  ADD KEY `incident_notifications_user_id_read_at_index` (`user_id`,`read_at`),
  ADD KEY `incident_notifications_device_activity_id_index` (`device_activity_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_family_id_foreign` (`family_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `device_activities`
--
ALTER TABLE `device_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `families`
--
ALTER TABLE `families`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `incident_notifications`
--
ALTER TABLE `incident_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_family_id_foreign` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `device_activities`
--
ALTER TABLE `device_activities`
  ADD CONSTRAINT `device_activities_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `families`
--
ALTER TABLE `families`
  ADD CONSTRAINT `families_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_notifications`
--
ALTER TABLE `incident_notifications`
  ADD CONSTRAINT `incident_notifications_device_activity_id_foreign` FOREIGN KEY (`device_activity_id`) REFERENCES `device_activities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incident_notifications_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incident_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_family_id_foreign` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
