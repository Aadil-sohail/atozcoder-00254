-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2026 at 01:06 PM
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
-- Database: `atozcoder_00254`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-ebay.oos_control.2', 'b:1;', 2108204761),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:46:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"create customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"edit customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"delete customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"create sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"delete sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"view returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"create returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"delete returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:15:\"view categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"create categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:15:\"edit categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:17:\"delete categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:18:\"view subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:20:\"create subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:18:\"edit subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:20:\"delete subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:16:\"view inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"create inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:16:\"view connections\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"create connections\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:16:\"edit connections\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"delete connections\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:21:\"edit company settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"edit smtp settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:16:\"view ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:18:\"create ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:16:\"edit ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:18:\"delete ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:18:\"sync ebay products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:22:\"view stock adjustments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:24:\"create stock adjustments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:22:\"edit stock adjustments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}', 1793352657);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 'eBay Imports', '1', '1', 'eBay Import', '2026-10-24 07:21:48', '2026-10-24 07:21:48');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fav_icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `connection_item_stores`
--

CREATE TABLE `connection_item_stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_connection_item_id` bigint(20) UNSIGNED NOT NULL,
  `ebay_account_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `connection_item_stores`
--

INSERT INTO `connection_item_stores` (`id`, `product_connection_item_id`, `ebay_account_id`, `created_at`, `updated_at`) VALUES
(1, 3, 2, '2026-10-29 02:59:44', '2026-10-29 02:59:44'),
(2, 4, 1, '2026-10-29 02:59:44', '2026-10-29 02:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 'zain zain (testuser_zain123)', NULL, '0913920201', 'ALBAMA, ALBAMA, ALBAMA, AL, 23000, US', '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(2, 'zain zin (testuser_zain123)', NULL, '4859382910', 'MCKQW, LWK, ABC, AL, 43000, US', '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(3, 'abc abc (ragm3495)', 'abc@gmail.com', '2312312312', 'ABC, ABC, SEATTLE, WA, 98102, US', '1', '1', NULL, '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(4, 'zain zain (testuser_buyer1122121)', NULL, '2312312345', 'INX, INQ, ASOIDC, AL, 38000, US', '1', '1', NULL, '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(5, ' (testuser_buyer1122121)', 'buyer1.sandbox@example.com', NULL, NULL, '1', '1', NULL, '2026-10-24 07:25:42', '2026-10-24 07:25:42');

-- --------------------------------------------------------

--
-- Table structure for table `ebay_accounts`
--

CREATE TABLE `ebay_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `store_name` varchar(100) NOT NULL,
  `ebay_username` varchar(100) DEFAULT NULL,
  `marketplace_id` varchar(20) NOT NULL DEFAULT 'EBAY_US',
  `access_token` text DEFAULT NULL,
  `access_token_expires_at` timestamp NULL DEFAULT NULL,
  `refresh_token` text NOT NULL,
  `refresh_token_expires_at` timestamp NULL DEFAULT NULL,
  `fulfillment_policy_id` varchar(50) DEFAULT NULL,
  `payment_policy_id` varchar(50) DEFAULT NULL,
  `return_policy_id` varchar(50) DEFAULT NULL,
  `merchant_location_key` varchar(50) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ebay_accounts`
--

INSERT INTO `ebay_accounts` (`id`, `store_name`, `ebay_username`, `marketplace_id`, `access_token`, `access_token_expires_at`, `refresh_token`, `refresh_token_expires_at`, `fulfillment_policy_id`, `payment_policy_id`, `return_policy_id`, `merchant_location_key`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 'store1', 'testuser_buyer1122121', 'EBAY_US', 'eyJpdiI6IlNORitFQnhDaW1BOVBTTW0zbG1wK2c9PSIsInZhbHVlIjoiTktFWTFMaW1zTVVwZDhKS1NWS1F2VTU3eGNWekRNaGwxT1RyR3dDQ2NjMVhUcm1sYmNzTm5iMkRIUlc3VDhBVlBYT3VlcGthU2pBMGZzeG5HT1dYcEVyMU1SdStDbXh5aWRHMU1LSVNjaGRTeEgzWmNsUTlCTnNpUlVMR1QzbmdiSFpMdEpRbVBpMWZBRWhZcFgycGVZWUJjMjM4a3Y2RmVXUFRkUVQvN0xZL014czhvNStJWWhzUXlBZmVGN21QMis4bFJ0UHRXb3pLZkZpSy9lWi9PMUk2RDBFYUVpVFVPK2VKYkZpTkdTSU1taGY0eUo1ejZMNjhMTUdZb2NnUytNSjNIRGlUS1pQKzZTWXEyZ2pHdEJYVUVkd1k2THFPL3NtRFFHWXpJZGVtZzNOclVrdW55WE9IMlQvS1V3ckJOWldqTklmejFmZzRwQkZLQXdPQW1jR083YUJ2N3Z3OUxpM2RmTUwwbVExbjh2QWNJWWliN2s2ZFRYSWpEVERnUTYybFVVcEdrZ1J3QkZuckxWc0JxelphME4zVmp2TkxHNGZRdlFrTkVTWFNkMFB5YXAzVlJjL0RxNFFUdG9EWmxVWi9Rc1EvOVN3bU9FSU9aZzVMYzBVVy9hMzNYK3I5ci8yQnBsUk1ONXRlVU80SFlCR3pKSy9KdEFmV2NKbDMzQjBvOEFwRnhsRHVTZ0ZkL3hJK3IwRlhxU1JiR0Z1eUNnWjdJQ0tBVDR3Um1CYWhzanhxQ0NRQkdWTjJMdlE5OTE4ZWZPRjF5eVNhcVgxSlhXYUlTV2VFTEhhZllFSXRqbmVmM0lYY3NPdVg1K2dHMldLbG1ZOHdXQjZ5OXBndHZoc1BYZi9TMEI0bmxUakR0ZUhTRmlxNUc4aUZOYlJhOUp6MGEzcGFKUk9JaVRqbEpJMjlsSWtqNmNjVTdNeWE0RitPZjB6d3Btc3lFQVk4REttTFY4d3ZpZ2hjL25rRlNpQkN0UkpMbXV6NFhMM21nQjltR2tVd0JpSktTellid3c5R1VmTjFqeExVL3hZUHNYQmpncUNMK3lIZUpCSTdtTDRycWJRSUNSbzdZSDVkVFNCVkxET0lHZHVTK0dzSGhrdFB2OGtoTnZjUDNlZEFHKzFTVGZ5aEV6b2N4QU9Qck51R3pCdnBFUzY2cVp3VHoyQkFoS0hxc0pVYm5tU2dWL0pIb0hTUWprN0krTW1rM0lEYzhxazlJSGRTOVNQbi81K3VpNnhGZldScjBnWUtiRnFQTERidkZXQnJkSk5oSGhCb1FHdzBjYVc0SWxRZFJMNGFkRmcwRkM5M1BreXp4MnpNQ21hWHdLQnJJNFpCSGxoOURSWGRZZVYvU09YZFRsM2FSdVlBdm5XbDRSaFJSblBZMCtlSlVkTVBXajI3UFJOaE1jaExDNUpsRmZYVXdvam9jS3lKUHVpNTFjalNCQjl1TzBHVWVpWHFSRUZxa0lTZWoydHh2MG1jRTArWHorandITHNSeXNYcUFISGZvMjA4VURCTmRpeFBKUzdrc0p3d3RJaWc5MlJodElGTE9sa05OLzM4Ukl2aGUwRG51bXpUMFVSSHBwazdWczg4bFpqckdPekIxak8xM2l4dkMrZlJVMmtuRE5DOFhRNTZicDJ3Q3dZRXlKQVRPUHNpRXoyc2dJdEc2YUhBSitkUTBMRnZWMEVkbDg2RjdhMVVUak44NmpGZzAzNCtrQ1JWYVRMclpaK04ycVd4WTFSdHh6NXRDbG5XNUJBZkJhZkVwalZvd0Z3dnloY2grMEpreGxGbFVpemI2SEZZc3RPTHdoR09JQ25ZOHFLSVNTYUEyMVNxQXlnMnJFSzNSbVRwU095aVhDbEFPNllSRGxVUVhWWHJLZTEySGYvTll4T3U1eGVEWi9YKzdYbjExTWZqbFhUc1NRM213TWowTEhHZ3R6d0RKcmk1MnZCbmNFSVVDSWZaL2lUdjA3ZEZCbm04c3dCNmhyeWpudG9adzZqYkpHSU15ajJGMlVwT1ExSWo4aHR1TXpWR1V5dXV2VzZnR1NPSGpMb2lqWm9haWEyVzltOG8yVDNGT0FpQ1NWZU5xd1dHaENuK0M4L1Z1ZHhYdXJUZWlGSWJFZ3NvSVE5YmVvZ3FUKy9jS1hISjhIaFArbVRQeHhpczRHYnFYK2tPOE9mM1lQdHMwdTNIRXhwQWlxeGFNSmZDekVSMzVMSXZlRHNjOXNFclF2MmdXaS9PWURxT3pEWUVVT0ZsZnBOTUtEUm8wQ0cxYnB5VSttNkVDMkkzekY2WmQxVFl5ZmlsWnNaOE8zNWJNa3ZmMEdjNTFyR25DSGRyQmI1eTVGSU54L052SEVNcVNoK3NwanNuUkhVN1pzRGtGYnUrVmR4Wmg2RlhicVZ2UEVDYmZxTDFzRmhyUTN3aU91KzVNTHEwV3kwSkh0MHdnTVluWnc5aEdiWEJyZnZScXBxM0Y4UjRjd2dDYkU4alF4U1NvMlJnTTdSNHVkdzl5VmdKTXQyUER4OVBCQ2FmTll2WTBhc2k4eE96Qk54QlQrcFZVRUp3TnpHK0VmRlNnSzRkQ0ZERmtYeXRZYm51WGpiZC9RUjVsdktuNHI3enQrT0NSam12VXBGQTlQUUh2SzU4elZDdGh3ZWd2aXNpRkNNeXBFOTB4NjQ0MGt0VXF6bDFvSW05bUFZYm5tbytweHpIVmpLYVhLSjVreVJQdzhHTmM0NjV3ZjllanZhd1d2YURQb2JpSjF6alFtZkhMZnV3ZlZYQzVremlCVHNiYk5nTkxrWEJFTmNGT0ZCVHVsZUdRYTJQTFFxemsrVFpZc1cxTkNWdzZNL08rbGFEcHplMmU5YWo5S09WVjRMZHBxZUNtb3VmK0RjM0NuN2ZGdU5UK29odXIwR2pkRG1Od1VUc0J4SzdMMUV6aTA2R2xjVEtMSVBWYWR0SkgzTk5VVStESzBEZVFtTFF5NW1BZWx0L1RpQjcwOWRJWS9kdDlRSHpsNU1LMFpUWGVTYjZDdVArNEFqeFpHRU5zYUZVQWU0TmpuSTB2VFUrdnJiZVMvMjR0WnVGY0cvY2JwNUdTZVptRmZUdC9LSEV1SHNqRVV1OWNieU5NajJQMEpJQ1dPY3kxT1BTUFVJdnAvNkhQT0RMc2wvbERWZ1RoUm1GYzBod2FuQ0MvSUUraFc5alhEWjBjVTJ1ZGlra1A2TWloM1kyNVhyNHdNbTUvVUNUUmYyekxlTkJpeVVKanR4bVAvSUdqWWxNaTFZWVlsVU8vZ0oyN01rK0pSeXgzWnJWWW5BVUpwakV3aEU4R2wvS1NVRjNQZHJEelk5L1lRWE5YOE1SMTlUN3FPTmVPVldnTnpEZDFwenhqdWNRajdOc0lCTE0yRXd6eDZ2ZktPbFBIejJaRlJFRlZNMjlHV3piMmIwa0tMWVVyczVrZ2tvTURvN0NWVWpqYUZoV2xlelN0Tm5HbWVLdXlQeGYxMU5ES3NRQ29peXdYamRoWFB0OUlEODNIZXhrWkQrRlU5VklkMHdlaUlkOXd5TS9NaEZGYy9VVnNHMUU3Zm8zTnRnbWhaU0VUNWNEK3hKMlVEbFQ5R0o1cHMxdnJLcUc4K2ZvL2hjdnh4c2tOdjczMWYxUXN3Y0xDdndiNmtyYTB6TExSc09CSStySDBYVUg0SjFjcmJ4NFBMTVdJQ09vZXRaazZDbzRwVnF2YUhSV2JhTVpSN0ViQnFMK2lVQnVXRXE0VWNZeTNCZHdkUjg3NlJIU0hNa25vdDJLLzhqWEV0L0RDQ3JkUFdhVWMwdUZFdGNraDRmdE9OaEhEdnYzRUdmenVXREFrNURaL3c3SDY4YkJCU1lhYzR0Y092VmNMdTMzbGNIYk8yY1h2ZU9LTEZGNHdXWEJFZUZ3OGlHeENpNWR3Uy9pMy8zR25GK3hIUkR0SFpkOVJPdWZaTzJUamh3bmtFajVoUXZpdHFocWZUeWkySXRPa25tanYyNzMvSWxsRjZmZk4yVGh6QmpUTi9KUDE5WGI1eS8wVWZqYVVtYW1aaU5nVFVaL3J0SGZ3UWpJT09VVzRSWEErSkl3NWx2ajA4VU5HNkpRU3FoaW5sRWVMTjhXVE1HcTJQdUgrZE1jNENiTmFRWmlrK250RkxPNUFyZEhxNGcvNkl0bnRleXMrU1N3VEh0OCIsIm1hYyI6ImNhNjdkYmE2NjQ5NzVjMmQ5NmRlNzQ3ZjVkOTBlNTI1Mjc3ZGQwYTA3MTE5NTYxM2IzZjE2ZDk0YWUyZTUyMGUiLCJ0YWciOiIifQ==', '2026-10-29 06:33:02', 'eyJpdiI6Im1maEh0SkNjdnpKdzVQQUZHaGJjcFE9PSIsInZhbHVlIjoiUlRBa1VNMDZIUURHaTcyVkNYZHVXdStUcGlPd1I1dnp5VUJqVHdCVDZVMkRtMER3WVJ3aTV5eFVITTcrL1RENlgxVC9yVnVBaVhzVW9BMVRtOGkrdVFzRUFYMVU0aWdESnQ1VDdUOFZhcVZ1cThNVU14Sm5ObENNeHYzc2ZvMTZMMjFNbzV0cFJYZXdjKytiZDRMSVN3PT0iLCJtYWMiOiI2NGNkMTk4ZmE2MzNjNTkxNWNkMTJmMTA1OWMwNmIzODhlMTQ4NDk0ZDdlZmNkZDVmYThhZWM3M2M1MjZiZmEyIiwidGFnIjoiIn0=', '2028-04-23 19:19:52', '6238383000', '6238384000', '6238385000', 'main-warehouse-16', '1', '1', 'Admin', '2026-10-24 07:19:54', '2026-10-29 04:33:02'),
(2, 'store 2', 'testuser_zain123', 'EBAY_US', 'eyJpdiI6InZPbCtHQ0s5ancybEtlNVdpWVV6aUE9PSIsInZhbHVlIjoiWlk3Nk9BMTNDVUpKdll0UU5kbTJrbkpNV213R3RSclNHMjM1ZXhpMkFWa2RhL2dTSXpRR3NvNWs0U2lhb29Ta3RldlB3YkpoZFdrc2U0SU5TRVpmMWhseUtXK2FCSEdlNkpqMnRqRHRPYnBXN3Y5dTRWWHIzUWI0OHovRDdxTWUxVFJiQk5LTWdxdG8zckJQRjVudXY0UHZSa2RSUFFSWHZNb1FuTmlJWEFIUThWZUprQUJjSC9SWXUvbXBkOEgxNTA5VWNMQUNIU2FmYWZxVDRJNDN0RnZRdHFkMjdaSG1iNk11dnZzWUZJaEtYTFh5NUo2VUlwM0dBRklscG5QNHBkWUg1bGZOWWg1ckRaMFNaV3RZcENKNXFOWGl4azBOWWpheG11RTc5MlJCM1ZSNEFvOU90Ti9JZzNPZ1ZoWk1JLzd2RldBUXJvUHhPWHQzd2Z6K1dWZjIyVVpHTW1qSzY1dUM4RFVUVTMrN1d3c3liamJpNU4xQ0JTWmd1aGJtc3hMQW1kL1doTkJONVpkbWUwNEkwZktja0FvcFcrVElodXhwdE8rdWNkaWh5MTB6amYyNE9kRUV4Y3FrMnl0eGoxNEc5VXhpUFd0ODVCanp4VUlYdWozVDBoQlk5dWVwNk1KQmlJNnVxSHdleG5zL3VsT1NOYVR4aExsOGhZZEwvNTVSblhJNDBtd3h6Sm1ZOWdpRmxJa1RGaXhyTnBORFVUcS81ZFNBUjJSL09Oc2RkNWdpQmN3eG1wSGNYUTQxYjBUSzM4VFRjK1cyS0JZbVIvTXQ3a3diVWtRYjJVTWh4dzBCNE9aZ2h1N2MrWmlIVUFPTlJJSkpJeWRhSjBJMklKaVhEMytPK2RZeFJWd0JQNGV3OWRRMUpXOFR0SkE4RnVlNVZYZzNoSjdVdDJiUHJOSUFsL1FUVzdOWFNaNDBvenFIcE42bGJMejNTV0Jzb0J0amtJMGpYbUxFanpORGdQWG1ETjlMRHptSE1ReW1xWThJc1hsd3dhck5DcTBzS3NTaHBJcTRHMExGTUQ1K1JlQ1FNWU1yRmxOOVQ0RXdMQnVYNTQySlZaQWZNK2YzRkhudFNheGtlMFZYSmdVemdHMWxXd0NMdDRtMmRFVTFjdG5wWmJBVXpRK3kzUE9HZTBGSEx1MnVISzhxYUZGdU56NE9pcHhTVlVLSjcxQlNEWnJSQ0ZMVHk3aDhRSGRJbXB6bkRZb3RwcGNwUmhOTnFiR0lGSzF0UDdwazhMZlNQdGxCQTVSMUNsR3Z4U080Y3NhNE1rMTFsY0RPMkhwVithTit0TzlPZllKcXkxTTR4anRFRUJaRUtBdkpHMzhPNE55YXU3NXNtcWs4aHdwZi9HbU5reTdBVmVSRS9pWXlvS2VXRDRvZDJ6M2xjcTBtSTdUb2lVb1FZOUwrUGhoWWZ1dkxZNmtPcnl6dk95YmVBUkpYZ0FiSFdsRDQwM0FrQk13eFJUNWRuNjVOSHZ1ZXJBT1RQb2NDdVd2RGt6SU9yNkw4RDJGNitXaGRGOU9MbWtnbTNqZVZGQlZ3MkZHeExLRzNTUnJlZHA0VzJrWlpiVGxIcHRUSU1HRDlxalg1cXF5MkVoWTNCV05mSlZ5bzVoeGJqUnhFcCszSVlxd241b2xqU2J6bzNOdlU2OGw2TzZGdWlxUlFVb2ZoaWREUG1tazZVNWdhOUFyRGI5VG5HdW5oOWsxRXVHMEZqRzNtcTluUzUvMmxXZnNFNHlOMDkwSVhDSitTVmEzWGhCVHRoOEV0Vjlla1NTRzYvU0NuRjdUOXFkWUh4Uk5xY2dPdnVJUy93YjdWWFNDMWkxOWRBVU1YQlFEeXJQTjJaR013bDkxWTh1M2JtYTBQL3BaQnNvcnl1S3ViNTM5cEJQV1RYTWpPZG0zbUg3SW92Nk9XWjRGSklJcW9ZcWZGc2IweThkZEZRRnpMUCt6eXBvMlZDcDVJZStCMCtSZnlDVWMxbFhlR3RGSUFFRkZoTTZLUkxxR3RtL1dpYzA0UERwRWprUmZGeVcvbDYyVFVEeWRBbkZNVjdDNjQzNTdwSlg0NW00OUVZTklWamY0ci9EYVVJVmFsZHNIN1FUQU1WS0MyN0wwS1BOWHJubzFSQnIvVFpBeXp2ZkRXSGhlRkdkMlJGRmRNelE0VXNScGtPbFNvYmo1aUhzNEpERXNPVmNTSksxVVZYdHUvRUdWVWpZNk1pSE9IYlJuSVFUcTRVOU02TTVhbVBDblNGbSswMjk5ZU1RcG5JanFjZUZWaHg2clV0em1xUnlJVW13WlVwdGRsWm1LeDhjdUJoUmFEQklRVDZ2L1A5aXBya3Zvd2ZvcHBDVzFLang0RkdmOGVXTVczVUtmYmIzYzRlUXZWbktZOHo5eE5UMHY0eXVIQkFQYlhnNUZOdUhMb252V3NjeDhQaDVEL3E3OFhyYkEvSW5EYkJBQ3lQVHlZWTd1MEliRFF1S0szNlZObVlRYWhHQlA1T0Jxb0QwQk5PaThLWjg2SzBxOUVwaGZuamgrVXdJUS93SkVidk1sL2kzdFlrMGYyek1sR3NPSGlEN3JPWEtvMHBycm96cC9Nb3FBWkZJRFcyRDVKYzJtTWgzYmErSjQ5VTk5bnBlK1VqaWVEY0NBS1hhMEY2UHZCN1cxQXd1RWJqUlAvSms5bXBMMXRUTE4yNHNaak80UHZjUWRuQU5KaUNDVUQvRzlyMlVrRkMwbnh1UlJlOFA2bzVuZmhpKzg5Yk90L2I3WU9oQ1JPRERYRmswSXJYYWo5RXRUK3N2R0s5Rk5ZdVhmRnk1SHhPeW45ZWlpb0VsTnpDVTVraHhwRzE2OE1MZUx5bUdoTkdyK2xtNC8zaHhuaEhnazhiSHUrVHg2OU9ab250SFBJYVg2QUNBUXlBeU5uT1hyTGtFVkNvUUdNUURIK0dRRlJwZnRDUFJxc0tDOXQ4Q2U1OVNUM0w5em0rUllLcEs4YW5kNWY1RmVCOG1LdnY1UlpRMXBnL3FQNWh4UHM1YzdyTEltNmhVVUpoUFpWQXlYNkFGdFY0aUNVbmRzMnZZZjNKUDFCUUlXNytHdU1STjdYZXY2Z1kvSTdIY0RDSWZ1MVYraUVSN20xWU1LWG5KTExMcDRDNUZOS2FPYWF4bFg2NWxrR21XSktXZ3VjbXRRWkZmM3IwQlZFNWQ4RVEyZzI3Y1J6am5ncHVueGRVRDc5VGxDSjh6eEU4YXpaS3Rma08xUFZGRlhJK29wT1FxUWUzSjlSeXJ1c2hlWjUzdVFrSHFWdzNmMEVGZFEwYU1pWWhIVmxiTlIvaERhVm5rbWtiU0piVUdSY1hnaUxPNzNJMWdueitnY3RidDZtdXZIMkdrVytYbElFd3hCVko1akFzeU5LVHJWY1BlM05qQUJuWTc0SDJKNWNicDByKzdJaXI3aWE0U0U4blp4SHc3ZFQzY0tlQVhDdUVCakZkVHFxWFU1c1JpeWwvaWlnYktORlZzVXRONnJYenV2Zzlhd3VZbFlNVHFrVm52MGR1azNtUVpSdi9SV2FRYmdiWm9ic3VYdzEvS0txMXVuSEYxejd3WFBSU00wVGkyZjBURld4SjdOWmJuWG9tSlBtd0FPc0YwMHpnKzFYYWYzaVJHY2RWSEVlWEFiYSt5ckhaR1BIb25sQjBFcHRRcE5yMlg2cStCR1YrMGxaOEZhVlBWUjVJVXZSeU91cUJ3ZzdueXpPRW9IbzVJTmh2Mm9mRjdtam9ua1BXT2JrU0VWVG03a3JiOFYzZ2g0TklTOHM4RWRTVTB6aFFJUjlqclpGWXg2QnpwbE5xVjFQR3doNWgxaTRvN3E0RkJ2YktZbTVRVUhkRXNTOGZwSHRLa3ExbnIvd0VhK3RDWFhWczk5ditzMytuTGh3RkxnaExLK2gvb0hYQnRKSk5lTU55em1LOElER3llbm1HeStHZWhrOGM2S1dVTmFWL2VMR3J4bUhWOTlMQ1U2T0Vqbm13OUV4WlhTL3EvZXlVdkt2cTlablBxMktoTlh6VldvV1l4b0NYallEV3RUZFc2UXk1RGNVZitmM0ZSeFBWdU84TTJkajJqZW93a1I0NUExNzZMMGdodU1MK2xXRTNuVlpmTkdFbHBjQTBDT2hVRGhuMWlOV3FTVT0iLCJtYWMiOiIxMTgyZTNiNzk3OTU5OTRhODEwM2I3NDlmYzYzNTEyNWQ3ZTZkZTRkZDc3NTc3MjM5NjY2NWJhOWM1Y2UxZTE1IiwidGFnIjoiIn0=', '2026-10-29 06:33:13', 'eyJpdiI6InVTTWVzaStYd3UyenZKbFpPK2tJNHc9PSIsInZhbHVlIjoiQ1VXV21BYkNFR1F4WEJNbXBnazJJcUpNSTlUTXNjcC9tRnRxZVk2SmJWWm1GaW9wYjEwZXZ0MzZocXFPV2IyTjkycVduUzE5RC9haHV5MEViaVMrM3NpSGZHZVJIR3dNWnU0OEhmd3MrRWY3dVd6VjdPbXM4OWZhWGIyYWl6dUVNbXVCWXVRa3ZYT2J2ZDJlU01hcG13PT0iLCJtYWMiOiI1ODlkYzQyZGM5ZmNkYTkwMzNkNGM5ZjQ5ZTVmMGFjOWViY2Q0NTZjNDg2ODVmNjVlNmU2YzVjYzI0N2U4NjdmIiwidGFnIjoiIn0=', '2028-04-23 19:20:32', '6234437000', '6234438000', '6234439000', 'main-warehouse-1', '1', '1', 'Admin', '2026-10-24 07:20:33', '2026-10-29 04:33:13');

-- --------------------------------------------------------

--
-- Table structure for table `ebay_import_items`
--

CREATE TABLE `ebay_import_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ebay_account_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_urls` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`image_urls`)),
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ebay_category_id` varchar(20) DEFAULT NULL,
  `listing_id` varchar(50) DEFAULT NULL,
  `offer_id` varchar(50) DEFAULT NULL,
  `condition` varchar(40) NOT NULL DEFAULT 'NEW',
  `already_in_software` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ebay_listings`
--

CREATE TABLE `ebay_listings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `ebay_account_id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(50) NOT NULL,
  `offer_id` varchar(50) DEFAULT NULL,
  `listing_id` varchar(50) DEFAULT NULL,
  `ebay_category_id` varchar(20) DEFAULT NULL,
  `condition` varchar(40) NOT NULL DEFAULT 'NEW',
  `sync_status` enum('pending','syncing','synced','failed') NOT NULL DEFAULT 'pending',
  `last_error` text DEFAULT NULL,
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ebay_listings`
--

INSERT INTO `ebay_listings` (`id`, `product_id`, `ebay_account_id`, `sku`, `offer_id`, `listing_id`, `ebay_category_id`, `condition`, `sync_status`, `last_error`, `last_synced_at`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'common-prod-2', '11780233010', '110590809114', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:21:48', 'eBay Import', '2026-10-24 07:21:48', '2026-10-24 07:21:48'),
(2, 2, 1, 'prod-1-store2', '11489207010', '110590436370', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:21:54', 'eBay Import', '2026-10-24 07:21:54', '2026-10-24 07:21:54'),
(3, 3, 1, '626', '11914857010', '110591225361', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:21:56', 'eBay Import', '2026-10-24 07:21:56', '2026-10-24 07:21:56'),
(4, 4, 1, '374', '11914855010', '110591225360', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:21:59', 'eBay Import', '2026-10-24 07:21:59', '2026-10-24 07:21:59'),
(5, 5, 1, '967', '11914851010', '110591225350', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:22:01', 'eBay Import', '2026-10-24 07:22:01', '2026-10-24 07:22:01'),
(6, 6, 1, '290', '11914850010', '110591225346', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:22:03', 'eBay Import', '2026-10-24 07:22:03', '2026-10-24 07:22:03'),
(7, 7, 1, 'prod-2-store2', '11489208010', '110590436371', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:22:06', 'eBay Import', '2026-10-24 07:22:06', '2026-10-24 07:22:06'),
(8, 1, 2, 'common-prod-2', '11780229010', '110590809110', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:23:24', 'eBay Import', '2026-10-24 07:23:24', '2026-10-24 07:23:24'),
(9, 2, 2, 'prod-1-store2', '11867417010', '110591116257', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:23:26', 'eBay Import', '2026-10-24 07:23:26', '2026-10-24 07:23:26'),
(10, 8, 2, 'comm-pro', '11867420010', '110591116262', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:23:29', 'eBay Import', '2026-10-24 07:23:29', '2026-10-24 07:23:29'),
(11, 9, 2, '146', '11900690010', '110591208639', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:23:31', 'eBay Import', '2026-10-24 07:23:31', '2026-10-24 07:23:31'),
(12, 10, 2, '144', '11900692010', '110591208648', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:23:32', 'eBay Import', '2026-10-24 07:23:32', '2026-10-24 07:23:32'),
(13, 11, 2, '524', '11900691010', '110591208645', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:23:34', 'eBay Import', '2026-10-24 07:23:34', '2026-10-24 07:23:34'),
(14, 7, 2, 'prod-2-store2', '11867418010', '110591116259', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:23:37', 'eBay Import', '2026-10-24 07:23:37', '2026-10-24 07:23:37'),
(15, 12, 2, 'prodname2', '11489140010', '110590436276', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:23:39', 'eBay Import', '2026-10-24 07:23:39', '2026-10-24 07:23:39'),
(16, 13, 2, 'prodname', '11489113010', '110590436251', '20349', 'NEW', 'synced', NULL, '2026-10-24 07:23:43', 'eBay Import', '2026-10-24 07:23:43', '2026-10-24 07:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `product_id`, `quantity`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 1, 90.00, '1', '1', 'eBay Import', '2026-10-24 07:21:48', '2026-10-24 07:21:48'),
(2, 2, 70.00, '1', '1', 'eBay Import', '2026-10-24 07:21:54', '2026-10-24 07:21:54'),
(3, 3, 20.00, '1', '1', 'eBay Import', '2026-10-24 07:21:56', '2026-10-24 07:21:56'),
(4, 4, 30.00, '1', '1', 'eBay Import', '2026-10-24 07:21:59', '2026-10-24 07:21:59'),
(5, 5, 40.00, '1', '1', 'eBay Import', '2026-10-24 07:22:01', '2026-10-24 07:22:01'),
(6, 6, 50.00, '1', '1', 'eBay Import', '2026-10-24 07:22:03', '2026-10-24 07:22:03'),
(7, 7, 120.00, '1', '1', 'eBay Import', '2026-10-24 07:22:06', '2026-10-24 07:22:06'),
(8, 8, 4.00, '1', '1', 'eBay Import', '2026-10-24 07:23:29', '2026-10-24 07:23:29'),
(9, 9, 39.00, '1', '1', 'eBay Import', '2026-10-24 07:23:31', '2026-10-24 07:23:31'),
(10, 10, 20.00, '1', '1', 'eBay Import', '2026-10-24 07:23:32', '2026-10-24 07:23:32'),
(11, 11, 40.00, '1', '1', 'eBay Import', '2026-10-24 07:23:34', '2026-10-24 07:23:34'),
(12, 12, 34.00, '1', '1', 'eBay Import', '2026-10-24 07:23:39', '2026-10-24 07:23:39'),
(13, 13, 8.00, '1', '1', 'eBay Import', '2026-10-24 07:23:43', '2026-10-24 07:23:43'),
(14, 10, 3.00, '1', '1', 'Admin', '2026-10-29 01:07:20', '2026-10-29 01:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `inventory_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(30) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_return_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ebay_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `product_id`, `inventory_id`, `type`, `quantity`, `balance_after`, `sale_id`, `sale_return_id`, `ebay_account_id`, `note`, `inserted_by`, `created_at`, `updated_at`) VALUES
(16, 10, 14, 'stock_added', 3.00, 23.00, NULL, NULL, NULL, 'Added from Add Stock', 'Admin', '2026-10-29 01:07:20', '2026-10-29 01:07:20'),
(17, 10, NULL, 'ebay_order', -1.00, 22.00, 8, NULL, 2, 'eBay order 00-12653-69089', 'eBay Sync', '2026-10-29 02:05:19', '2026-10-29 02:05:19'),
(18, 1, NULL, 'ebay_order', -1.00, 80.00, 8, NULL, 2, 'eBay order 00-12653-69089', 'eBay Sync', '2026-10-29 02:05:19', '2026-10-29 02:05:19'),
(19, 10, NULL, 'ebay_order', -13.00, 9.00, 9, NULL, 2, 'eBay order 110591208648-558759937', 'eBay Sync', '2026-10-29 02:10:15', '2026-10-29 02:10:15'),
(23, 10, NULL, 'adjustment', -3.00, 6.00, NULL, NULL, 2, 'Adjustment #4', 'Admin', '2026-10-29 02:19:05', '2026-10-29 02:19:05'),
(24, 10, NULL, 'adjustment', 1.00, 7.00, NULL, NULL, 2, 'Adjustment #5', 'Admin', '2026-10-29 02:19:48', '2026-10-29 02:19:48'),
(26, 10, NULL, 'adjustment', -1.00, 6.00, NULL, NULL, 2, 'Adjustment #7', 'Admin', '2026-10-29 03:03:51', '2026-10-29 03:03:51'),
(27, 10, NULL, 'ebay_order', -1.00, 5.00, 10, NULL, 2, 'eBay order 110591208648-642121729', 'eBay Sync', '2026-10-29 03:09:16', '2026-10-29 03:09:16'),
(28, 3, NULL, 'ebay_order', -1.00, 4.00, 11, NULL, 1, 'eBay order 110591225361-566755329', 'eBay Sync', '2026-10-29 03:45:30', '2026-10-29 03:45:30'),
(29, 10, NULL, 'adjustment', 4.00, 8.00, NULL, NULL, 2, 'Adjustment #8', 'Admin', '2026-10-29 03:50:49', '2026-10-29 03:50:49'),
(32, 3, NULL, 'ebay_order', -1.00, 7.00, 12, NULL, 1, 'eBay order 110591225361-523501569', 'eBay Sync', '2026-10-29 04:33:12', '2026-10-29 04:33:12'),
(33, 3, NULL, 'adjustment', 1.00, 8.00, NULL, NULL, 1, 'Adjustment #9', 'Admin', '2026-10-29 04:35:15', '2026-10-29 04:35:15'),
(34, 3, NULL, 'adjustment', -2.00, 6.00, NULL, NULL, 1, 'Adjustment #9 edited', 'Admin', '2026-10-29 04:36:20', '2026-10-29 04:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_15_000000_create_companies_table', 1),
(5, '2025_01_15_000001_create_smtp_settings_table', 1),
(6, '2026_06_30_074019_create_categories_table', 1),
(7, '2026_06_30_093958_create_products_table', 1),
(8, '2026_06_30_100001_create_inventories_table', 1),
(9, '2026_07_01_000000_create_customers_table', 1),
(10, '2026_07_01_000001_create_sales_table', 1),
(11, '2026_07_01_000002_create_sale_items_table', 1),
(12, '2026_07_01_114331_create_permission_tables', 1),
(13, '2026_07_01_130559_create_sale_returns_table', 1),
(14, '2026_07_01_130600_create_sale_return_items_table', 1),
(15, '2026_07_02_000001_create_ebay_accounts_table', 1),
(16, '2026_07_02_000002_create_ebay_listings_table', 1),
(17, '2026_07_07_000001_add_ebay_order_columns_to_sales_table', 1),
(18, '2026_07_08_000001_add_ebay_return_id_to_sale_returns_table', 1),
(19, '2026_07_30_000001_create_subcategories_table', 1),
(20, '2026_07_30_102136_add_col_in_prod', 1),
(21, '2026_08_29_000001_create_ebay_import_items_table', 1),
(22, '2026_09_09_000001_create_product_connections_tables', 1),
(23, '2026_09_09_000002_create_product_connection_item_stores_table', 1),
(24, '2026_09_11_000001_add_master_store_to_product_connections', 1),
(25, '2026_10_29_000001_create_inventory_logs_table', 2),
(28, '2026_10_29_000003_create_stock_adjustments_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view roles', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(2, 'create roles', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(3, 'edit roles', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(4, 'delete roles', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(5, 'view users', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(6, 'create users', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(7, 'edit users', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(8, 'delete users', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(9, 'view customers', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(10, 'create customers', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(11, 'edit customers', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(12, 'delete customers', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(13, 'view sales', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(14, 'create sales', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(15, 'delete sales', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(16, 'view returns', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(17, 'create returns', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(18, 'delete returns', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(19, 'view categories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(20, 'create categories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(21, 'edit categories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(22, 'delete categories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(23, 'view subcategories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(24, 'create subcategories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(25, 'edit subcategories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(26, 'delete subcategories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(27, 'view products', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(28, 'create products', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(29, 'edit products', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(30, 'delete products', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(31, 'view inventories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(32, 'create inventories', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(33, 'view connections', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(34, 'create connections', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(35, 'edit connections', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(36, 'delete connections', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(37, 'edit company settings', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(38, 'edit smtp settings', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(39, 'view ebay stores', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(40, 'create ebay stores', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(41, 'edit ebay stores', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(42, 'delete ebay stores', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(43, 'sync ebay products', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44'),
(44, 'view stock adjustments', 'web', '2026-10-29 02:17:32', '2026-10-29 02:17:32'),
(45, 'create stock adjustments', 'web', '2026-10-29 02:17:32', '2026-10-29 02:17:32'),
(46, 'edit stock adjustments', 'web', '2026-10-29 03:55:09', '2026-10-29 03:55:09');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `variant` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `total_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sold_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `warranty_months` tinyint(3) UNSIGNED DEFAULT NULL,
  `warranty_expiry_date` date DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subcategory_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `variant`, `description`, `image`, `cost_price`, `selling_price`, `size`, `total_qty`, `sold_qty`, `warranty_months`, `warranty_expiry_date`, `category_id`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`, `subcategory_id`) VALUES
(1, 'common prod 2', 'common-prod-2', NULL, 'common prod 2', NULL, NULL, 35.00, '12', 90.00, 10.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:21:48', '2026-10-29 02:05:19', NULL),
(2, 'prod1-store2', 'prod-1-store2', NULL, 'prod1-store2', NULL, NULL, 200.00, '100', 70.00, 6.00, 3, '2027-01-24', 1, '1', '1', 'eBay Import', '2026-10-24 07:21:53', '2026-10-29 00:40:28', NULL),
(3, 'Bianca Rivers', '626', NULL, 'Cillum in omnis et e', NULL, NULL, 870.00, 'Dolore vel quis sunt', 20.00, 0.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:21:56', '2026-10-29 03:48:48', NULL),
(4, 'Kessie Beard', '374', NULL, 'Cum reiciendis unde', NULL, NULL, 378.00, 'Aute omnis suscipit', 30.00, 0.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:21:59', '2026-10-24 07:21:59', NULL),
(5, 'Lareina Shannon', '967', NULL, 'Cupidatat obcaecati', NULL, NULL, 720.00, 'Pariatur Natus aliq', 40.00, 0.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:22:01', '2026-10-24 07:22:01', NULL),
(6, 'Mariam Burnett', '290', NULL, 'Commodi eveniet mol', NULL, NULL, 918.00, 'Distinctio Officia', 50.00, 0.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:22:03', '2026-10-24 07:22:03', NULL),
(7, 'prod2-store2', 'prod-2-store2', NULL, 'oijd', NULL, NULL, 30.00, '30', 120.00, 0.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:22:06', '2026-10-24 07:22:06', NULL),
(8, 'common prod', 'comm-pro', NULL, 'common prod', NULL, NULL, 20.00, '10', 4.00, 4.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:23:29', '2026-10-24 07:25:38', NULL),
(9, 'Kane Green', '146', NULL, 'Deleniti aliquip qui', NULL, NULL, 634.00, 'Ratione quis ex temp', 39.00, 0.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:23:31', '2026-10-24 07:23:31', NULL),
(10, 'Amos Short', '144', NULL, 'Aut necessitatibus c', NULL, NULL, 870.00, 'Voluptatum aute volu', 23.00, 17.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:23:32', '2026-10-29 04:36:20', NULL),
(11, 'Nash Mckenzie', '524', NULL, 'Fugit dolores nihil', NULL, NULL, 634.00, 'Et veniam consequun', 40.00, 0.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:23:34', '2026-10-24 07:23:34', NULL),
(12, 'prodname 2', 'prodname2', NULL, 'prodname 2', NULL, NULL, 30.00, '120', 34.00, 0.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:23:39', '2026-10-24 07:23:39', NULL),
(13, 'prod name', 'prodname', NULL, 'this idesicnwon', NULL, NULL, 20.00, '10', 8.00, 0.00, NULL, NULL, 1, '1', '1', 'eBay Import', '2026-10-24 07:23:43', '2026-10-24 07:23:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_connections`
--

CREATE TABLE `product_connections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `master_product_id` bigint(20) UNSIGNED NOT NULL,
  `master_ebay_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_connections`
--

INSERT INTO `product_connections` (`id`, `name`, `master_product_id`, `master_ebay_account_id`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(2, 'Amos Short', 10, 2, '1', '1', 'Admin', '2026-10-29 02:59:44', '2026-10-29 02:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `product_connection_items`
--

CREATE TABLE `product_connection_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_connection_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_connection_items`
--

INSERT INTO `product_connection_items` (`id`, `product_connection_id`, `product_id`, `created_at`, `updated_at`) VALUES
(3, 2, 10, '2026-10-29 02:59:44', '2026-10-29 02:59:44'),
(4, 2, 3, '2026-10-29 02:59:44', '2026-10-29 02:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2026-10-24 07:18:44', '2026-10-24 07:18:44');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `ebay_order_id` varchar(50) DEFAULT NULL,
  `ebay_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_date` date NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `customer_id`, `invoice_no`, `ebay_order_id`, `ebay_account_id`, `sale_date`, `discount`, `total_amount`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'EBAY-110590809114-10000014691310', '110590809114-10000014691310', 1, '2026-09-29', 0.00, 30.00, '1', '1', 'eBay Sync', '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(2, 2, 'EBAY-02-00001-65913', '02-00001-65913', 1, '2026-09-29', 0.00, 1340.00, '1', '1', 'eBay Sync', '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(3, 3, 'EBAY-110590809110-10000016311110', '110590809110-10000016311110', 2, '2026-10-19', 0.00, 30.00, '1', '1', 'eBay Sync', '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(4, 4, 'EBAY-110590809110-10000014797010', '110590809110-10000014797010', 2, '2026-09-30', 0.00, 30.00, '1', '1', 'eBay Sync', '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(5, 4, 'EBAY-110590809110-10000014671610', '110590809110-10000014671610', 2, '2026-09-29', 0.00, 30.00, '1', '1', 'eBay Sync', '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(6, 4, 'EBAY-110590809110-10000014671010', '110590809110-10000014671010', 2, '2026-09-29', 0.00, 60.00, '1', '1', 'eBay Sync', '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(7, 5, 'EBAY-110590809110-10000016311010', '110590809110-10000016311010', 2, '2026-10-19', 0.00, 30.00, '1', '1', 'eBay Sync', '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(8, 5, 'EBAY-00-12653-69089', '00-12653-69089', 2, '2026-10-29', 0.00, 900.00, '1', '1', 'eBay Sync', '2026-10-29 02:05:19', '2026-10-29 02:05:19'),
(9, 5, 'EBAY-110591208648-558759937', '110591208648-558759937', 2, '2026-10-29', 0.00, 11310.00, '1', '1', 'eBay Sync', '2026-10-29 02:10:15', '2026-10-29 02:10:15'),
(10, 5, 'EBAY-110591208648-642121729', '110591208648-642121729', 2, '2026-10-29', 0.00, 870.00, '1', '1', 'eBay Sync', '2026-10-29 03:09:16', '2026-10-29 03:09:16'),
(11, 1, 'EBAY-110591225361-566755329', '110591225361-566755329', 1, '2026-10-29', 0.00, 102.00, '1', '1', 'eBay Sync', '2026-10-29 03:45:30', '2026-10-29 03:45:30'),
(12, 1, 'EBAY-110591225361-523501569', '110591225361-523501569', 1, '2026-10-29', 0.00, 870.00, '1', '1', 'eBay Sync', '2026-10-29 04:33:12', '2026-10-29 04:33:12');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `returned_qty` decimal(12,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `returned_qty`, `selling_price`, `subtotal`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1.00, 0.00, 30.00, 30.00, '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(2, 2, 1, 1.00, 0.00, 30.00, 30.00, '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(3, 2, 1, 1.00, 0.00, 30.00, 30.00, '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(4, 2, 8, 2.00, 0.00, 20.00, 40.00, '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(5, 2, 8, 2.00, 0.00, 20.00, 40.00, '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(6, 2, 2, 2.00, 0.00, 200.00, 400.00, '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(7, 2, 2, 2.00, 0.00, 200.00, 400.00, '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(8, 2, 2, 2.00, 0.00, 200.00, 400.00, '1', '1', NULL, '2026-10-24 07:25:38', '2026-10-24 07:25:38'),
(9, 3, 1, 1.00, 0.00, 30.00, 30.00, '1', '1', NULL, '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(10, 4, 1, 1.00, 0.00, 30.00, 30.00, '1', '1', NULL, '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(11, 5, 1, 1.00, 0.00, 30.00, 30.00, '1', '1', NULL, '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(12, 6, 1, 2.00, 0.00, 30.00, 60.00, '1', '1', NULL, '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(13, 7, 1, 1.00, 0.00, 30.00, 30.00, '1', '1', NULL, '2026-10-24 07:25:42', '2026-10-24 07:25:42'),
(14, 8, 10, 1.00, 0.00, 870.00, 870.00, '1', '1', NULL, '2026-10-29 02:05:19', '2026-10-29 02:05:19'),
(15, 8, 1, 1.00, 0.00, 30.00, 30.00, '1', '1', NULL, '2026-10-29 02:05:19', '2026-10-29 02:05:19'),
(16, 9, 10, 13.00, 0.00, 870.00, 11310.00, '1', '1', NULL, '2026-10-29 02:10:15', '2026-10-29 02:10:15'),
(17, 10, 10, 1.00, 0.00, 870.00, 870.00, '1', '1', NULL, '2026-10-29 03:09:16', '2026-10-29 03:09:16'),
(18, 11, 3, 1.00, 0.00, 102.00, 102.00, '1', '1', NULL, '2026-10-29 03:45:30', '2026-10-29 03:45:30'),
(19, 12, 3, 1.00, 0.00, 870.00, 870.00, '1', '1', NULL, '2026-10-29 04:33:12', '2026-10-29 04:33:12');

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `return_date` date NOT NULL,
  `ebay_return_id` varchar(50) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_items`
--

CREATE TABLE `sale_return_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_return_id` bigint(20) UNSIGNED NOT NULL,
  `sale_item_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `condition` varchar(50) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('ISsvpoj62dSm2JBzSbAjkATAS2V353Egsj58ojce', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJSYkR5dU9xdEdUVm5iZ2s1TnFOQm15YmljTG53R0FyYjBkYjUyTVh3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2F0b3pjb2Rlci0wMDI1NC50ZXN0XC9lYmF5XC9zdG9yZXMiLCJyb3V0ZSI6ImViYXkuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1793266919);

-- --------------------------------------------------------

--
-- Table structure for table `smtp_settings`
--

CREATE TABLE `smtp_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mailer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'smtp',
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `port` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `encryption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `from_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `ebay_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('increase','decrease','set') NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `change` decimal(10,2) NOT NULL,
  `stock_before` decimal(10,2) NOT NULL,
  `stock_after` decimal(10,2) NOT NULL,
  `pushed_listings` int(10) UNSIGNED DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_adjustments`
--

INSERT INTO `stock_adjustments` (`id`, `product_id`, `ebay_account_id`, `type`, `quantity`, `change`, `stock_before`, `stock_after`, `pushed_listings`, `reason`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(4, 10, 2, 'decrease', 3.00, -3.00, 9.00, 6.00, 1, NULL, '1', '1', 'Admin', '2026-10-29 02:19:05', '2026-10-29 02:19:13'),
(5, 10, 2, 'increase', 1.00, 1.00, 6.00, 7.00, 1, NULL, '1', '1', 'Admin', '2026-10-29 02:19:48', '2026-10-29 02:19:56'),
(7, 10, 2, 'decrease', 1.00, -1.00, 7.00, 6.00, 2, NULL, '1', '1', 'Admin', '2026-10-29 03:03:51', '2026-10-29 03:04:06'),
(8, 10, 2, 'set', 8.00, 4.00, 4.00, 8.00, 2, NULL, '1', '1', 'Admin', '2026-10-29 03:50:49', '2026-10-29 03:51:04'),
(9, 3, 1, 'decrease', 1.00, -1.00, 7.00, 6.00, 2, NULL, '1', '1', 'Admin', '2026-10-29 04:35:15', '2026-10-29 04:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `phone`, `email_verified_at`, `password`, `status`, `close`, `inserted_by`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'testsoftware@gmail.com', 'testsoftware', '1234567890', NULL, '$2y$12$0JNMJWT747C0VSwptEAN1.tRjYvQuMu/XPfIYoHNOTtosFKZEQpy2', '1', '1', 'Admin', NULL, '2026-10-24 07:18:45', '2026-10-24 07:18:45');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `connection_item_stores`
--
ALTER TABLE `connection_item_stores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `connection_item_store_unique` (`product_connection_item_id`,`ebay_account_id`),
  ADD KEY `connection_item_stores_ebay_account_id_foreign` (`ebay_account_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `ebay_accounts`
--
ALTER TABLE `ebay_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebay_import_items`
--
ALTER TABLE `ebay_import_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ebay_import_items_ebay_account_id_sku_unique` (`ebay_account_id`,`sku`);

--
-- Indexes for table `ebay_listings`
--
ALTER TABLE `ebay_listings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ebay_listings_product_id_ebay_account_id_unique` (`product_id`,`ebay_account_id`),
  ADD KEY `ebay_listings_ebay_account_id_foreign` (`ebay_account_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_product_id_foreign` (`product_id`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_logs_product_id_foreign` (`product_id`),
  ADD KEY `inventory_logs_sale_id_foreign` (`sale_id`),
  ADD KEY `inventory_logs_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `inventory_logs_ebay_account_id_foreign` (`ebay_account_id`),
  ADD KEY `inventory_logs_type_index` (`type`),
  ADD KEY `inventory_logs_inventory_id_foreign` (`inventory_id`);

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
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_subcategory_id_foreign` (`subcategory_id`);

--
-- Indexes for table `product_connections`
--
ALTER TABLE `product_connections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_connections_master_product_id_foreign` (`master_product_id`),
  ADD KEY `product_connections_master_ebay_account_id_foreign` (`master_ebay_account_id`);

--
-- Indexes for table `product_connection_items`
--
ALTER TABLE `product_connection_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_connection_items_product_id_unique` (`product_id`),
  ADD KEY `product_connection_items_product_connection_id_foreign` (`product_connection_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_invoice_no_unique` (`invoice_no`),
  ADD UNIQUE KEY `sales_ebay_order_id_unique` (`ebay_order_id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_ebay_account_id_foreign` (`ebay_account_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sale_returns_ebay_return_id_unique` (`ebay_return_id`),
  ADD KEY `sale_returns_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_return_items_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `sale_return_items_sale_item_id_foreign` (`sale_item_id`),
  ADD KEY `sale_return_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustments_product_id_foreign` (`product_id`),
  ADD KEY `stock_adjustments_ebay_account_id_foreign` (`ebay_account_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategories_category_id_foreign` (`category_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `connection_item_stores`
--
ALTER TABLE `connection_item_stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ebay_accounts`
--
ALTER TABLE `ebay_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ebay_import_items`
--
ALTER TABLE `ebay_import_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `ebay_listings`
--
ALTER TABLE `ebay_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `product_connections`
--
ALTER TABLE `product_connections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_connection_items`
--
ALTER TABLE `product_connection_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `connection_item_stores`
--
ALTER TABLE `connection_item_stores`
  ADD CONSTRAINT `connection_item_stores_ebay_account_id_foreign` FOREIGN KEY (`ebay_account_id`) REFERENCES `ebay_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `connection_item_stores_product_connection_item_id_foreign` FOREIGN KEY (`product_connection_item_id`) REFERENCES `product_connection_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ebay_import_items`
--
ALTER TABLE `ebay_import_items`
  ADD CONSTRAINT `ebay_import_items_ebay_account_id_foreign` FOREIGN KEY (`ebay_account_id`) REFERENCES `ebay_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ebay_listings`
--
ALTER TABLE `ebay_listings`
  ADD CONSTRAINT `ebay_listings_ebay_account_id_foreign` FOREIGN KEY (`ebay_account_id`) REFERENCES `ebay_accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ebay_listings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `inventory_logs_ebay_account_id_foreign` FOREIGN KEY (`ebay_account_id`) REFERENCES `ebay_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_logs_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_logs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_logs_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_logs_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`);

--
-- Constraints for table `product_connections`
--
ALTER TABLE `product_connections`
  ADD CONSTRAINT `product_connections_master_ebay_account_id_foreign` FOREIGN KEY (`master_ebay_account_id`) REFERENCES `ebay_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_connections_master_product_id_foreign` FOREIGN KEY (`master_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_connection_items`
--
ALTER TABLE `product_connection_items`
  ADD CONSTRAINT `product_connection_items_product_connection_id_foreign` FOREIGN KEY (`product_connection_id`) REFERENCES `product_connections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_connection_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `sales_ebay_account_id_foreign` FOREIGN KEY (`ebay_account_id`) REFERENCES `ebay_accounts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD CONSTRAINT `sale_returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`);

--
-- Constraints for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  ADD CONSTRAINT `sale_return_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `sale_return_items_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`),
  ADD CONSTRAINT `sale_return_items_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_ebay_account_id_foreign` FOREIGN KEY (`ebay_account_id`) REFERENCES `ebay_accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_adjustments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
