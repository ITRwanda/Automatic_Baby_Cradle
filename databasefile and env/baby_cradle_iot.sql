-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2026 at 09:49 AM
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
(3, 'Cradle Sensor', 'dd332414-fed9-46c7-9cf4-f5a5c9c1ef17', 1, 3, '2026-06-12 14:01:15', '2026-07-15 18:03:17'),
(4, 'Baby Monitor 1', '708e7e8d-24b0-41c9-957e-3fe11f5275d9', NULL, NULL, '2026-06-12 15:03:15', '2026-06-15 08:29:03'),
(5, 'Baby Monitor 2', '04a704e4-387a-4ac6-abce-db41b1701144', 2, NULL, '2026-06-12 16:10:05', '2026-06-15 10:11:26'),
(6, 'Bora  device', 'a72e4d26-22d3-4456-ac18-b3997e5132f0', 4, NULL, '2026-07-15 16:03:27', '2026-07-15 18:06:52'),
(7, 'JD', '2c4fa7d1-4b9a-4de6-a38a-8eae477d789b', NULL, NULL, '2026-07-15 18:05:45', '2026-07-15 18:05:45');

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
(30, 3, 'dht', '{\"temperature\":31.3,\"humidity\":86,\"temp_alert\":false,\"humid_alert\":true}', '2026-07-15 05:37:40', '2026-07-15 05:37:40'),
(31, 3, 'cry_detected', '{\"sound_level\":440}', '2026-07-17 04:42:38', '2026-07-17 04:42:38'),
(32, 3, 'cry_detected', '{\"sound_level\":447}', '2026-07-17 04:42:52', '2026-07-17 04:42:52'),
(33, 3, 'cry_detected', '{\"sound_level\":440}', '2026-07-17 04:43:00', '2026-07-17 04:43:00'),
(34, 3, 'cry_detected', '{\"sound_level\":448}', '2026-07-17 04:43:07', '2026-07-17 04:43:07'),
(35, 3, 'cry_detected', '{\"sound_level\":438}', '2026-07-17 04:43:15', '2026-07-17 04:43:15'),
(36, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:43:25', '2026-07-17 04:43:25'),
(37, 3, 'cry_detected', '{\"sound_level\":439}', '2026-07-17 04:43:36', '2026-07-17 04:43:36'),
(38, 3, 'cry_detected', '{\"sound_level\":439}', '2026-07-17 04:43:52', '2026-07-17 04:43:52'),
(39, 3, 'cry_detected', '{\"sound_level\":447}', '2026-07-17 04:44:00', '2026-07-17 04:44:00'),
(40, 3, 'cry_detected', '{\"sound_level\":439}', '2026-07-17 04:44:07', '2026-07-17 04:44:07'),
(41, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:44:17', '2026-07-17 04:44:17'),
(42, 3, 'cry_detected', '{\"sound_level\":439}', '2026-07-17 04:44:26', '2026-07-17 04:44:26'),
(43, 3, 'cry_detected', '{\"sound_level\":440}', '2026-07-17 04:44:36', '2026-07-17 04:44:36'),
(44, 3, 'cry_detected', '{\"sound_level\":439}', '2026-07-17 04:44:46', '2026-07-17 04:44:46'),
(45, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:50:49', '2026-07-17 04:50:49'),
(46, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:50:59', '2026-07-17 04:50:59'),
(47, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:51:10', '2026-07-17 04:51:10'),
(48, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:51:20', '2026-07-17 04:51:20'),
(49, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:51:30', '2026-07-17 04:51:30'),
(50, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:51:40', '2026-07-17 04:51:40'),
(51, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:51:51', '2026-07-17 04:51:51'),
(52, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:52:04', '2026-07-17 04:52:04'),
(53, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:52:11', '2026-07-17 04:52:11'),
(54, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:52:21', '2026-07-17 04:52:21'),
(55, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:52:32', '2026-07-17 04:52:32'),
(56, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:52:42', '2026-07-17 04:52:42'),
(57, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:52:52', '2026-07-17 04:52:52'),
(58, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:53:02', '2026-07-17 04:53:02'),
(59, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:53:13', '2026-07-17 04:53:13'),
(60, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:53:24', '2026-07-17 04:53:24'),
(61, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:53:38', '2026-07-17 04:53:38'),
(62, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:56:43', '2026-07-17 04:56:43'),
(63, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:57:06', '2026-07-17 04:57:06'),
(64, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:57:17', '2026-07-17 04:57:17'),
(65, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:57:30', '2026-07-17 04:57:30'),
(66, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:57:40', '2026-07-17 04:57:40'),
(67, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:57:50', '2026-07-17 04:57:50'),
(68, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:58:03', '2026-07-17 04:58:03'),
(69, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:58:10', '2026-07-17 04:58:10'),
(70, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:58:21', '2026-07-17 04:58:21'),
(71, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:58:34', '2026-07-17 04:58:34'),
(72, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:58:41', '2026-07-17 04:58:41'),
(73, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:58:51', '2026-07-17 04:58:51'),
(74, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:59:01', '2026-07-17 04:59:01'),
(75, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:59:12', '2026-07-17 04:59:12'),
(76, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:59:25', '2026-07-17 04:59:25'),
(77, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:59:33', '2026-07-17 04:59:33'),
(78, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:59:45', '2026-07-17 04:59:45'),
(79, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 04:59:57', '2026-07-17 04:59:57'),
(80, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:00:05', '2026-07-17 05:00:05'),
(81, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:00:15', '2026-07-17 05:00:15'),
(82, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:00:28', '2026-07-17 05:00:28'),
(83, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:00:36', '2026-07-17 05:00:36'),
(84, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:00:46', '2026-07-17 05:00:46'),
(85, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:00:59', '2026-07-17 05:00:59'),
(86, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:01:06', '2026-07-17 05:01:06'),
(87, 3, 'cry_detected', '{\"sound_level\":447}', '2026-07-17 05:01:21', '2026-07-17 05:01:21'),
(88, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:01:32', '2026-07-17 05:01:32'),
(89, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:02:49', '2026-07-17 05:02:49'),
(90, 3, 'cry_detected', '{\"sound_level\":446}', '2026-07-17 05:03:00', '2026-07-17 05:03:00'),
(91, 3, 'cry_detected', '{\"sound_level\":447}', '2026-07-17 05:03:10', '2026-07-17 05:03:10'),
(92, 3, 'cry_detected', '{\"sound_level\":449}', '2026-07-17 05:16:06', '2026-07-17 05:16:06'),
(93, 3, 'cry_detected', '{\"sound_level\":450}', '2026-07-17 05:16:46', '2026-07-17 05:16:46');

-- --------------------------------------------------------

--
-- Table structure for table `device_user`
--

CREATE TABLE `device_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `device_user`
--

INSERT INTO `device_user` (`id`, `device_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, 3, '2026-07-17 04:40:03', '2026-07-17 04:40:03'),
(2, 3, 2, '2026-07-17 05:12:43', '2026-07-17 05:12:43');

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
(2, 'Test Family', 10, '2026-06-15 08:55:29', '2026-06-15 08:55:29'),
(4, 'VAVA Family', 12, '2026-07-15 17:12:35', '2026-07-15 17:12:35');

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
(6, 3, 30, 3, 'dht', '🌡️ Environment Alert', 'Device \"Cradle Sensor\" at 07:37, 15 Jul 2026: humidity 86% is out of safe range. Please adjust the room environment.', NULL, '2026-07-15 05:37:46', '2026-07-15 05:37:46'),
(7, 2, 31, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:42, 17 Jul 2026. Sound level: 440. Please check on the baby immediately.', NULL, '2026-07-17 04:42:38', '2026-07-17 04:42:38'),
(8, 3, 31, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:42, 17 Jul 2026. Sound level: 440. Please check on the baby immediately.', NULL, '2026-07-17 04:42:49', '2026-07-17 04:42:49'),
(9, 2, 32, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:42, 17 Jul 2026. Sound level: 447. Please check on the baby immediately.', NULL, '2026-07-17 04:42:52', '2026-07-17 04:42:52'),
(10, 3, 32, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:42, 17 Jul 2026. Sound level: 447. Please check on the baby immediately.', NULL, '2026-07-17 04:42:57', '2026-07-17 04:42:57'),
(11, 2, 33, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 440. Please check on the baby immediately.', NULL, '2026-07-17 04:43:00', '2026-07-17 04:43:00'),
(12, 3, 33, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 440. Please check on the baby immediately.', NULL, '2026-07-17 04:43:04', '2026-07-17 04:43:04'),
(13, 2, 34, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 448. Please check on the baby immediately.', NULL, '2026-07-17 04:43:07', '2026-07-17 04:43:07'),
(14, 3, 34, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 448. Please check on the baby immediately.', NULL, '2026-07-17 04:43:12', '2026-07-17 04:43:12'),
(15, 2, 35, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 438. Please check on the baby immediately.', NULL, '2026-07-17 04:43:15', '2026-07-17 04:43:15'),
(16, 3, 35, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 438. Please check on the baby immediately.', NULL, '2026-07-17 04:43:21', '2026-07-17 04:43:21'),
(17, 2, 36, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:43:25', '2026-07-17 04:43:25'),
(18, 3, 36, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:43:30', '2026-07-17 04:43:30'),
(19, 2, 37, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:43:36', '2026-07-17 04:43:36'),
(20, 3, 37, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:43:40', '2026-07-17 04:43:40'),
(21, 2, 38, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:43:52', '2026-07-17 04:43:52'),
(22, 3, 38, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:43, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:43:57', '2026-07-17 04:43:57'),
(23, 2, 39, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 447. Please check on the baby immediately.', NULL, '2026-07-17 04:44:00', '2026-07-17 04:44:00'),
(24, 3, 39, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 447. Please check on the baby immediately.', NULL, '2026-07-17 04:44:05', '2026-07-17 04:44:05'),
(25, 2, 40, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:44:07', '2026-07-17 04:44:07'),
(26, 3, 40, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:44:12', '2026-07-17 04:44:12'),
(27, 2, 41, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:44:17', '2026-07-17 04:44:17'),
(28, 3, 41, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:44:22', '2026-07-17 04:44:22'),
(29, 2, 42, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:44:26', '2026-07-17 04:44:26'),
(30, 3, 42, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:44:31', '2026-07-17 04:44:31'),
(31, 2, 43, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 440. Please check on the baby immediately.', NULL, '2026-07-17 04:44:36', '2026-07-17 04:44:36'),
(32, 3, 43, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 440. Please check on the baby immediately.', NULL, '2026-07-17 04:44:41', '2026-07-17 04:44:41'),
(33, 2, 44, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:44:46', '2026-07-17 04:44:46'),
(34, 3, 44, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:44, 17 Jul 2026. Sound level: 439. Please check on the baby immediately.', NULL, '2026-07-17 04:44:51', '2026-07-17 04:44:51'),
(35, 2, 45, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:50, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:50:49', '2026-07-17 04:50:49'),
(36, 3, 45, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:50, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:50:55', '2026-07-17 04:50:55'),
(37, 2, 46, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:50, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:50:59', '2026-07-17 04:50:59'),
(38, 3, 46, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:50, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:05', '2026-07-17 04:51:05'),
(39, 2, 47, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:10', '2026-07-17 04:51:10'),
(40, 3, 47, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:15', '2026-07-17 04:51:15'),
(41, 2, 48, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:20', '2026-07-17 04:51:20'),
(42, 3, 48, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:25', '2026-07-17 04:51:25'),
(43, 2, 49, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:30', '2026-07-17 04:51:30'),
(44, 3, 49, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:35', '2026-07-17 04:51:35'),
(45, 2, 50, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:40', '2026-07-17 04:51:40'),
(46, 3, 50, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:45', '2026-07-17 04:51:45'),
(47, 2, 51, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:51', '2026-07-17 04:51:51'),
(48, 3, 51, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:51, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:51:55', '2026-07-17 04:51:55'),
(49, 2, 52, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:04', '2026-07-17 04:52:04'),
(50, 3, 52, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:08', '2026-07-17 04:52:08'),
(51, 2, 53, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:11', '2026-07-17 04:52:11'),
(52, 3, 53, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:16', '2026-07-17 04:52:16'),
(53, 2, 54, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:21', '2026-07-17 04:52:21'),
(54, 3, 54, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:26', '2026-07-17 04:52:26'),
(55, 2, 55, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:32', '2026-07-17 04:52:32'),
(56, 3, 55, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:37', '2026-07-17 04:52:37'),
(57, 2, 56, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:42', '2026-07-17 04:52:42'),
(58, 3, 56, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:47', '2026-07-17 04:52:47'),
(59, 2, 57, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:52', '2026-07-17 04:52:52'),
(60, 3, 57, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:52, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:52:57', '2026-07-17 04:52:57'),
(61, 2, 58, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:53, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:53:02', '2026-07-17 04:53:02'),
(62, 3, 58, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:53, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:53:07', '2026-07-17 04:53:07'),
(63, 2, 59, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:53, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:53:13', '2026-07-17 04:53:13'),
(64, 3, 59, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:53, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:53:19', '2026-07-17 04:53:19'),
(65, 2, 60, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:53, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:53:24', '2026-07-17 04:53:24'),
(66, 3, 60, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:53, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:53:29', '2026-07-17 04:53:29'),
(67, 2, 61, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:53, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:53:38', '2026-07-17 04:53:38'),
(68, 3, 61, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:53, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:53:43', '2026-07-17 04:53:43'),
(69, 2, 62, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:56, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:56:43', '2026-07-17 04:56:43'),
(70, 3, 62, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:56, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:56:48', '2026-07-17 04:56:48'),
(71, 2, 63, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:06', '2026-07-17 04:57:06'),
(72, 3, 63, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:10', '2026-07-17 04:57:10'),
(73, 2, 64, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:18', '2026-07-17 04:57:18'),
(74, 3, 64, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:22', '2026-07-17 04:57:22'),
(75, 2, 65, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:30', '2026-07-17 04:57:30'),
(76, 3, 65, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:34', '2026-07-17 04:57:34'),
(77, 2, 66, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:40', '2026-07-17 04:57:40'),
(78, 3, 66, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:44', '2026-07-17 04:57:44'),
(79, 2, 67, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:50', '2026-07-17 04:57:50'),
(80, 3, 67, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:57, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:57:55', '2026-07-17 04:57:55'),
(81, 2, 68, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:03', '2026-07-17 04:58:03'),
(82, 3, 68, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:08', '2026-07-17 04:58:08'),
(83, 2, 69, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:10', '2026-07-17 04:58:10'),
(84, 3, 69, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:15', '2026-07-17 04:58:15'),
(85, 2, 70, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:21', '2026-07-17 04:58:21'),
(86, 3, 70, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:26', '2026-07-17 04:58:26'),
(87, 2, 71, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:34', '2026-07-17 04:58:34'),
(88, 3, 71, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:38', '2026-07-17 04:58:38'),
(89, 2, 72, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:41', '2026-07-17 04:58:41'),
(90, 3, 72, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:46', '2026-07-17 04:58:46'),
(91, 2, 73, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:51', '2026-07-17 04:58:51'),
(92, 3, 73, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:58, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:58:56', '2026-07-17 04:58:56'),
(93, 2, 74, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:01', '2026-07-17 04:59:01'),
(94, 3, 74, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:06', '2026-07-17 04:59:06'),
(95, 2, 75, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:12', '2026-07-17 04:59:12'),
(96, 3, 75, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:16', '2026-07-17 04:59:16'),
(97, 2, 76, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:25', '2026-07-17 04:59:25'),
(98, 3, 76, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:30', '2026-07-17 04:59:30'),
(99, 2, 77, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:33', '2026-07-17 04:59:33'),
(100, 3, 77, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:37', '2026-07-17 04:59:37'),
(101, 2, 78, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:45', '2026-07-17 04:59:45'),
(102, 3, 78, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:49', '2026-07-17 04:59:49'),
(103, 2, 79, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 04:59:57', '2026-07-17 04:59:57'),
(104, 3, 79, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 06:59, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:02', '2026-07-17 05:00:02'),
(105, 2, 80, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:05', '2026-07-17 05:00:05'),
(106, 3, 80, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:10', '2026-07-17 05:00:10'),
(107, 2, 81, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:15', '2026-07-17 05:00:15'),
(108, 3, 81, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:20', '2026-07-17 05:00:20'),
(109, 2, 82, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:28', '2026-07-17 05:00:28'),
(110, 3, 82, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:33', '2026-07-17 05:00:33'),
(111, 2, 83, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:36', '2026-07-17 05:00:36'),
(112, 3, 83, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:40', '2026-07-17 05:00:40'),
(113, 2, 84, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:46', '2026-07-17 05:00:46'),
(114, 3, 84, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:51', '2026-07-17 05:00:51'),
(115, 2, 85, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:00:59', '2026-07-17 05:00:59'),
(116, 3, 85, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:00, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:01:04', '2026-07-17 05:01:04'),
(117, 2, 86, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:01, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:01:06', '2026-07-17 05:01:06'),
(118, 3, 86, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:01, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:01:11', '2026-07-17 05:01:11'),
(119, 2, 87, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:01, 17 Jul 2026. Sound level: 447. Please check on the baby immediately.', NULL, '2026-07-17 05:01:21', '2026-07-17 05:01:21'),
(120, 3, 87, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:01, 17 Jul 2026. Sound level: 447. Please check on the baby immediately.', NULL, '2026-07-17 05:01:26', '2026-07-17 05:01:26'),
(121, 2, 88, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:01, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:01:32', '2026-07-17 05:01:32'),
(122, 3, 88, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:01, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:01:36', '2026-07-17 05:01:36'),
(123, 2, 89, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:02, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:02:49', '2026-07-17 05:02:49'),
(124, 3, 89, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:02, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:02:54', '2026-07-17 05:02:54'),
(125, 2, 90, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:03, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:03:00', '2026-07-17 05:03:00'),
(126, 3, 90, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:03, 17 Jul 2026. Sound level: 446. Please check on the baby immediately.', NULL, '2026-07-17 05:03:04', '2026-07-17 05:03:04'),
(127, 2, 91, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:03, 17 Jul 2026. Sound level: 447. Please check on the baby immediately.', NULL, '2026-07-17 05:03:10', '2026-07-17 05:03:10'),
(128, 3, 91, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:03, 17 Jul 2026. Sound level: 447. Please check on the baby immediately.', NULL, '2026-07-17 05:03:15', '2026-07-17 05:03:15'),
(129, 2, 92, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:16, 17 Jul 2026. Sound level: 449. Please check on the baby immediately.', NULL, '2026-07-17 05:16:06', '2026-07-17 05:16:06'),
(130, 3, 92, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:16, 17 Jul 2026. Sound level: 449. Please check on the baby immediately.', NULL, '2026-07-17 05:16:11', '2026-07-17 05:16:11'),
(131, 2, 93, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:16, 17 Jul 2026. Sound level: 450. Please check on the baby immediately.', NULL, '2026-07-17 05:16:46', '2026-07-17 05:16:46'),
(132, 3, 93, 3, 'cry_detected', '🔔 Baby Cry Detected', 'Your baby was detected crying on device \"Cradle Sensor\" at 07:16, 17 Jul 2026. Sound level: 450. Please check on the baby immediately.', NULL, '2026-07-17 05:16:51', '2026-07-17 05:16:51');

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
(12, '2026_07_15_051516_create_incident_notifications_table', 5),
(13, '2026_07_17_000001_create_device_user_pivot_table', 6);

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
('Oe3ecawcbABXNKHACjVn6nlQulnmSJ6fwY4pGRaa', 2, '192.168.137.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTDdHOU1HcmhWWkg4UEp4V3YyUlZZV3VWUzR0dFhFcWZPRmQ3NEpIQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xOTIuMTY4LjEzNy4xOjgwMDAvZmFtaWx5L3JlcG9ydHMiO3M6NToicm91dGUiO3M6MTQ6ImZhbWlseS5yZXBvcnRzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1784272628);

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
(10, 'Testfy', 'testfy@iot.com', NULL, '$2y$12$hCpxZddH8SslSXIczenUW.N7LjG8p0d/miN1s9saXzWZHGav9idBu', 2, NULL, '2026-06-15 08:55:29', '2026-06-15 08:55:29', NULL),
(12, 'Bora', 'bora@iot.com', NULL, '$2y$12$u5x7rYzY7wekLanW8qKwjuDOxZELTpXln7fNQYxWf/MmzkXTQzB2e', 2, NULL, '2026-07-15 17:12:35', '2026-07-15 17:12:35', NULL),
(13, 'jane', 'janed@someotheraddress.org', NULL, '$2y$12$EPenebF.w/QQermnCQNL/OtWV6o3Ll1BBRb0l8M3LbGN/jml8Qsty', 3, NULL, '2026-07-15 17:48:34', '2026-07-15 17:48:34', 1);

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
-- Indexes for table `device_user`
--
ALTER TABLE `device_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_user_device_id_user_id_unique` (`device_id`,`user_id`),
  ADD KEY `device_user_user_id_foreign` (`user_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `device_activities`
--
ALTER TABLE `device_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `device_user`
--
ALTER TABLE `device_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `families`
--
ALTER TABLE `families`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `incident_notifications`
--
ALTER TABLE `incident_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- Constraints for table `device_user`
--
ALTER TABLE `device_user`
  ADD CONSTRAINT `device_user_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `device_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
