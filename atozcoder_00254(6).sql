-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2026 at 04:01 PM
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
('laravel-cache-ebay.app_token', 's:1936:\"v^1.1#i^1#p^1#I^3#r^0#f^0#t^H4sIAAAAAAAA/+VYa2wUVRTu9pnyMkEUUx4uozGBZmZnZnenMyO7ZVr6WLN97ra0JYbM4w4dOzsznUfbrQk0JYJEsSpUSPqDIhGMAv4AIkb0RwGN4gORiMb4SzRqfBEwJBajM9OlbCsBpGvcxM0mk3vvued+33fOuffOoAOFxSs2126+MtdTlDs6gA7kejzYbLS4sKB0Xl5uSUEOmmbgGR14cCB/MO+7lQabkDW6GRiaqhjA25eQFYN2O0OQpSu0yhqSQStsAhi0ydMxpi5K4whKa7pqqrwqQ97I6hAEKIoPEkFBxAWCRTFg9yrXfMbVEORHMRQQYlmZQIAAx2H2uGFYIKIYJquYIQhHcQJGKfsfx1Daj9E4iZB4oAPytgLdkFTFNkFQKOzCpd25ehrWm0NlDQPopu0ECkeY6lgDE1ldVR9f6UvzFU7pEDNZ0zKmtipVAXhbWdkCN1/GcK3pmMXzwDAgX3hihalOaeYamDuA70rNcWQZJQiUSAYCBMniGZGyWtUTrHlzHE6PJMCia0oDxZTM5K0UtdXgHgO8mWrV2y4iq73Oo8liZUmUgB6CqiqYdqaxEQozuiGzCtMPdwCQYPVuOFbRBuMBKiBwHMHBWJAQOCIQSK0z4Syl8rSFKlVFkBzNDG+9alYAGzSYLg2eJo1t1KA06IxoOoDS7fBrEmJkhxPTiSBaZqfihBUkbB28bvPWAZicbZq6xFkmmPQwfcBVKASxmiYJ0PRBNxVT2dNnhKBO09Ron6+3txfp9SOqvt6Hoyjma6uLxvhOW0fIsXVq3bWXbj0BllwqvF3Ftj1tJjUbS5+dqjYAZT0UDhBBP0GldJ8KKzy9928daZx9UwsiUwXCEqJIonzQHxCpoADITBRIOJWjPgcH4NgkbOdnFzA1meUBzNt5ZiWALgm0PyjiflIEsEBQIhygRBHmggIBYyIAKAAcx1Pk/6hObjfTY4DXgZmZVM9Umleu8XX118Vqqs0eS+isTZCl9Wh9t9q/hovEmBqrrFPu1prQ5hoG7Q3dbjHckHylLNnKxO31s6/Wa1XDBMKM6MV4VQONqizxyewKsF8XGlndTFZYSbsdA7JsP2ZEldG0SIY27EyR/Gd7xZ3RzuA59d+cUTdkZTh5m12snPmG7YDVJMQ5hRDeqXU14VNZ+wridK9zUc+It2RfXrOKNa8mJthKwsStE3HpIkYPj+jAUC3dvnAjDc4tLK52AcU+1ExdlWWgt2IzLudEwjJZTgbZVtcZSHCJzbITFysjSSpAkSQ5I168e56uy7YtacY7cf6gx3sb9JsBKyeyi7rBKgKn9v0Lrwy+qd8vwjnuDxv0vIcOek7lejxoJQpjpejywryW/Lw5kCGZAEnBQSRWRAxpvWK/nusA6QJJjZX03PkL9R+ZHYuqraNjsHmwbWc8Z1baV5TRR9H7Jr+jFOdhs9M+qqCLr48UYHctnIsTKIVSGOrHcLIDfeD6aD52b/6CjvuPPHs8Kpy4p6Tl8h7ftt2HirwfonMnjTyeghw75Dk9R84Ftw1/dbJmzqGxkQWfLD589PF9vy39fJQ5t/bbd99a8f6f0aIDm34vHhk72nz6/BPassCrx4598+X86L7FF0fGt75gtTwUHyr5aDjy5iDoGzoUxaNnzraUEBsvnOULln6x6sTJgR3xhw8MDI8W+fdveGP8zDPflw891b928PV1y/ac6tVfHN7fXdJT4vv04t114x1b9PNdW7aWl7/25KyX94qeC+3Hf5k3Vji08mDxc6U726+An8dmd59oX1W596UNPy15u/7prVfbPqs5PB71LLoaOFnI/LH9MtvUWlEbP/JKOW/0nVnywcjXyec/Ll2+a9OCXTq86NLu7Y3vXDq394cVj5zeWPUr1FSVM5ycCOlfytyknt8SAAA=\";', 1788956489),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:43:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"create customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"edit customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"delete customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"create sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"delete sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"view returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"create returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"delete returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:15:\"view categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"create categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:15:\"edit categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:17:\"delete categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:18:\"view subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:20:\"create subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:18:\"edit subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:20:\"delete subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:16:\"view inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"create inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:21:\"edit company settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"edit smtp settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:16:\"view ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"create ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:16:\"edit ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"delete ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:18:\"sync ebay products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:16:\"view connections\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:18:\"create connections\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:16:\"edit connections\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:18:\"delete connections\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}', 1789911903);

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
(1, 'cat1', '1', '1', 'Admin', '2026-08-29 04:00:49', '2026-08-29 04:00:49'),
(2, 'eBay Imports', '1', '1', 'eBay Import', '2026-08-29 04:07:41', '2026-08-29 04:07:41');

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

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `company_email`, `company_phone`, `company_mobile`, `company_address`, `company_logo`, `fav_icon`, `created_at`, `updated_at`) VALUES
(1, 'abc', 'abc@gmail.com', '1234567890', '1234567890', 'abc', NULL, NULL, '2026-08-28 09:13:29', '2026-08-28 09:13:29');

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
(19, 11, 2, '2026-09-19 08:49:16', '2026-09-19 08:49:16'),
(20, 11, 1, '2026-09-19 08:49:16', '2026-09-19 08:49:16');

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
(1, ' (testuser_buyer1122121)', 'buyer1.sandbox@example.com', NULL, NULL, '1', '1', NULL, '2026-09-02 02:48:28', '2026-09-02 02:48:28'),
(2, ' (testuser_zain123)', 'zzainzzahoor@gmail.com', NULL, NULL, '1', '1', NULL, '2026-09-02 04:43:18', '2026-09-02 04:43:18');

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
(1, 'new', 'testuser_buyer1122121', 'EBAY_US', 'eyJpdiI6IkovL1VybSszZGpMc2tIZlkwQ0MvWEE9PSIsInZhbHVlIjoiNGMxdWZFbEJRTFhlVEhyclZOK2txMDcvQjFpT1FXR1B0UmJKbGNDRjQvR1lNeEhPVE81T1NQODJQZjd2QzlDcUh6TWJOQ2ludmpWek5lcnl5VjdUSjloL2xSOGxKMmV6U3E5b3FCZ3RwcmNzOGMweG9ManNCbHE1dC9uTklVUEdvSDJLZkYyNXV3RUQ3UGUyb0RGclRWVjN6T1VNOU1hUGVDaGlpaFRoajZKakFEMEE0VDQwdkFFOStSbnR5anlFL3dSRlVrNUNtVjBlWVQvazRHRExNZUpGdkxuV2xZV0NRZEFad3lXRmNZdytSZXRpdDZMUVBGV29YSVlQaThOMjJMMXZVTzNncWpJdFpWREJrMzYrZ1pYd1JHNEh3RGIyakJBcHUzZGVRemc5Q1psTnlyTVFJTlJNRjNkYjF5VWdvOU1VZG4wbFJjdUVHWGdrenNvak1QOW1zaTRCT1JmTW9PNmdTVVJRWEZtN3l4QTVJNnY1OWZJZkIrTlluMHhZRS9MVWFNL0VyNi9QaFpNdW1FOGNCMzdIRXhLa3R4TUJOM3NUV2hGcHY4NHNFdEtQZVQycUhKaTF2S3NzWllBK2tjNitKSUVzRm4zbnIraVhJTS8raGk0REhIVmpwczc2a295TE5GanVIOVlpbEdpelYvN3R3UXpNcXZoUVE0TGN2TFhqSkxDeGI4S2FEKy9GS3RQaXo5VWtxVlZDTW9TTWJhcVRSNVZ1YkZEYWUxVjZlT2NCc1FEN2Z6N3o2S1FpZkZGd3BQVTBwS2UxZEViZkc3VUgveXJRaEp1b0NUUE1hT3NjZHlzRDVjbTZFRndvTzJqcHduWXkyNHJycm52MnZreDJvVitKNXJBM0JzNXljaGFLRzE5SHIyM0JwUXppTDlMeGZFVlp6Zy9OeFJsb1JQMUdTZWIzTk5hVGdTMDFJajFOZkZDZmIrNnlrT1E2OStrTEthbnpXelZ2OXBFTitXQmVkQVRqakVzZFl4K0pPd1Y5MmcxRXlPVS80aEFEUlpvM3BTTDJ0NE1RV01VR0s1VmJWa21QdWw1QkZ4VmxBTWdlTTVOdVpqaktBY0ZmdGt6Q3RsSzh2WmRORW9HUU4vc1J4YXZVVEFzYm5XOStYQTNoN2Vsb3MzOEg4N3JIZ2xEc1JEZHJSTkZwRk9uMTFEWVBTWmJ1YjVYUUdFY3JEZlRKbGtUMDg5ZzQyZFNmMjRaRnVDRFZKSHlISmlTRHVPVCtrang0UXdtOVQ1eVIzeTJoUmpHTDdXamc4eXBMcks0QzhlczdUcTBybUZDNHlpNXZSVjlwb3NnUnRNM0t3N1hGa2ZzWWI0ZjloRWN6dWQwOUZCT01DWldpWnF3WDBCNytpb2RGb2tkcjk5NHVUcFlsc2lFdlc4ZzEzM2F3REhkNWVyZzUrYTRvNVd3TDltRldLVjNvaVlIYktqUThqdVZzd0tTNjJ6NllXd1kycDBmZzduenhnczRmM2o2NVVJWUg4cGM3ckR5NWJXL29yaFJPaHFIZjdSQ2FaMEdueVdMU1BlVUh2Mkgxdy9nWTFFaVVuV0wya2t3V0ZwRjZBdUpoOGh6SWRoMDlEQ0JyNlBTamd0WjJSYnVoTHN5Rjh2cVdKRElnMEFQVHJlK01oL2ZLS2tWTnRCejdER1JYZGJLeGQ0ZUdlSVlPNWVFejBPSk55cFdRUTFrZEdURUU1UDFjNmZlVDBRTlFlanc5dWxxcDlQdjFkU1d2YjI0dnlmQmtqamdick1GNzh6ekNPUUprTThyRUg2UUQxOVdreGNCSnU3UEFuU0JhcU82OWZnRTRuWWJpNzVTNHFyU3pDY1kvRWhzWk84TVkwaDNxMGRXSkNNYThUSFphUnB1bVRPQVpRREs4U2I3cU11ZnNmcXdaWWlQZVpxTk9aZFlHaXJxeEp3N3BqcHlWUDlIQ0ZnSGhVd2J6NXNrK3NoQmE3Q2lScU9PK0p5aTFqQ0hERnQ2VGxCdTlqb0pSdk52S3RWd0EzMmRVWDYyYmtObVFxUndYOVRMUyt5emN2eEcrVnhrSUtVWlNVVkJMb3RDTWtsR1A2VjY4U3krdmMwbGF3TGJ1V0pSZFhzUG0vVmdwT3p2TWZUdlYyakVsUmxyb0I1NTZOaGFUeXVJWHhpdjh1T21xQndDa3ZQcFhjV2t4eXNsY25zdVFSbm9YbDVGQVdOdHRqQjB2bkdOT21QTUM3c2lQbk1TeG5FckFrT3FMOGY1LzBtWW5qM3c2ZTQzV2JZd01tSC9mcjNHQ2pHM3QwR3ZMNmRCZU0wL1dtTHkrR3NRMUhKNFZMcE0vTlpWUUpJQXdvcmE3SEJNTDhlUXY1dTZaekJBS25ENFRlblRJY2UxZnlWYTRuSFNDaEJrSGxIYlZaUGFGbWs0b3NQUDFFT0gzZWp4dzF6azBFV2RHUTJNKzB6WnVFZzJ2aEJmQU8zNFl3OEtJU0lucmlucFNMWUFIbHVOUEo2MzBVUHhqTHRuNFE5TnkxQmZnKzkzaDlTRUNGaTRsNldhOEJ6RCtWVXN3WmltNW9Jcm93bjRoK2QwbjN6RytFL2E3VTVUb20wcVdKK0RwdnU4YklHNVY5Y0huaGRLQWZoYWNCR1l6anRtK1BZN1ZmaDdSR2Ywd0pMb3JBaldSYndRMDYxV1ZGMFRNbGc1aEgraEdOZCtSbkYzaGJlc3lNSHNOZzlUNjltWHF1NElPZzF1NVI0MWFUNDczSkN1N3RJUzVIUDhmUWhiditUQ09qVFRlWDVSbDRMTVltVVlnNWxneEQ0REE5Z0VFeHZNM1Q0cEhVUlFnekR3TmgzWHUzSndweTROVWpkRjdlajF0TzBtYUdscmNFMnpCWVdkSHNJQzhJeEZ5SmFJdFptd2x4T1hFWG8yeW5FbHRuaDFEVlE5MzVaSytPU1ZYdVkzVGUwb0pvV1dVWWFzY0tkTHZvblo2dUJHV3RwR3ZjMElCMUc0cUFoOUM4bVQzS053Q1hoVk5nZU9FeW1PU0M1Z2pqKzJXejhGaGJseFNjZG9xeTBOV0dXeGF2VDVxQ25jMkZBVmtjNWFoajEyQkZHOFZIMWJKQmM0ZnVTdzNDZGpuN0FINHpXcXBaaC9idHZndXNXWDh0c2tuRjlhZmt4b2h0UmdBTjgwMHMrNFNTUWV4RTcyVzM0NFJIdlpIK0ZJWHpSY1JtWnhzYnVjWVVoZTlnOCswNWtTMy9ac2JncWxYQXJDbG1GSHRBUGdNWUZGM1Eyb003Y1ZuYjBhOWtydDNxbjdWVnVlUTJrVkFabjBKRi9lYmZZbTgrdHhjYlFON2ZyOXRQbmx0aHI2bTN6TDhNZDVaN1NhRHJnMnk0K0JRMllITHZaU1NEZEhsQ3A1bmFIR3RnRW5hSHUzWTB6ZGZkMzFJUEhxUEtuMXZzRWM5TkZ4YVVIVW9GWEs2a0RqZ01YWksxMndaMEFFV3Q2ZzhDUTcxNlFPbXhzdUxhd3NzZ01ZYlZBWmZBekNLcnkySFl0R1IwS3puT1AxSlR4TVpxU1Z3R1Z1TCsyaVlNKzBqSy9RN1NMTnRjSnN1M3ozb3BwcEFmWFpuZ3I0dnlCNVJVL0R6aVdBYnFRL0xRbnVpY3QxSFYwU2Jray8rYnh1MDQ5ODFBK3FsRGF3Q0k4T1ozZUJRVVpmYmEvWDJnT0Noeko1ODBWM3dMekNadFh2ZmQ4eWNEYWNVYkI0dnY3TTRtdVRLd25vS094QktlY2tnR09rSjBuMlR4M2VDUGhsQWI5OWY2ZFlpK1dXcnpYMElQZGNVeXdrRlBuR3dsM3F4TlQ1Y3JSRXVKcGRwd0xXZW9rRzFucFJjUFhSVEJ5TmI1eFA0dVpWMmJ4ZlBTZFZ6QnhtRWRWa2l6UzU4NFptVS9nSSsxcE5zNXZxc2pVQUdrNHErdmNvekg2TnliZDBta0h4anFZc2MwdXZHZnZpam9jbmphU2ozc2NEVmtqeEhTalB0VVpYUittaEJObEF4WVFHVXViVU9nclRWNDZnaUpKZWhWZFlPOWtIYS9YSzdKR0taWHVaUWd6UWVFcXFVRlJreDNaVGMxRXRENWdydGlId1ZRSUgxNXlWSlVvM3UycWQya2h0MzlmQVh1TUw4MGpXQjJZT0ZvS1pIQVJEWHRlR1dLYTRndXI5bTNjRk9PM2F2cmtFbiIsIm1hYyI6IjBhY2RlOGY5NmM4ZmQyMDA1OTNhODcwN2RhOGRkNDNiMmEyMjI3ZWVhZDJlZTdjMzgzZWIyOGJkZDBmOWQ2NzMiLCJ0YWciOiIifQ==', '2026-09-09 07:02:41', 'eyJpdiI6IjdSdktUb0luWVRnOVMyVFJRTlVZMnc9PSIsInZhbHVlIjoiYk8yYy96RHE1QytQU1dseThMSm8rNDBJcTJTWHcrWENOZ0dzSjJwUGJybkpKNGJzMHVTTkVKWmZlME9JcG0rVWRWQjd2SVd6c0x6cEhrdzNjTjlSUXN3QmRQRUZ3aDJjbUVjOEdzNzNTbnBvdlhFUWxHdWdhcnM2U0NFZ2hYb1BYWTl3RlZMRFVMTlZkOExuS3YxTVBRPT0iLCJtYWMiOiJhMGZmMGQ2NzhkMzcwZTUwYmQzMjA1MGQ1MjU4MmZlYWRmOGUyZjIzYTFmOGQwMzFlZjU3NzcyMWRhZWJjOTM5IiwidGFnIjoiIn0=', '2028-02-27 14:20:09', '6238383000', '6238384000', '6238385000', 'main-warehouse-16', '1', '1', 'Admin', '2026-08-29 02:20:11', '2026-09-09 05:02:41'),
(2, 'loop', 'testuser_zain123', 'EBAY_US', 'eyJpdiI6IkdNSnhIUWdXbGZaVkdtalpVTzJWTXc9PSIsInZhbHVlIjoiU2c2SnlTODM1TXVDS2ZNellRVGR0ODdBWEEvbUxlSDFJRVFkQ2FaRzNkMmRNZnJMYlpXZjZFcTBwb0hsbm9VVng4eGpybjU0V0NCNEJValZ3YTd2bHpmQnhqQUNmeG1vdmlPajJsdTF0VSs5WXFpR3Q3RUFBMDNWcCtKdzdHQVM2b1BmVTh6REI1Yk1LWUR6R0F5cnR1WjhvbTkwUUVIdmdKU1BrTUNraThwYzYxS2ZwRWx5dVEyV3RaL2xrSkQzRjlyVWFzMjY3SHlHS2pjUU9HUlZRc3hMRm5YQXVpRmlydDEwMmFjY3Qvakw4d3hzdkdaZWpoc1JqRnR6bTl3TTlYTXZSbVQ3VmcxSW1XeGlydDYzOTVkejhOV1RJUWlydjdUdUFMOWVSSUs1azlUSDNyZmxyaXhLayttYkp1TTVWcXNVOEcxV2FTQ1NMNGJaMmhaYTJNTkdrbXdoQVVtLy9lMTEydFh3U2NERG9pZC9wZU5QYzI0aG5iZGNsLzBSdUJUQXA0cUNGZHZhTU9WL25TeVYwRUNYRFlnbkZVcWozeXVzeFRod1l0ZmdtV3R1UmQxNUFoVmllT3NWQ0pyZWVXcjJCS1pRb1dMcWJHdWM1UEx5QUdwdFFUV1RWdDZ0Q3JlNmVjdk5vcExCY0ZuMkhHU0dlR29Wa0FxNG0wQy9IME8xZ1N3aVhjMTBQQWNMdFAwMENTWkxicjM1OHR2dmp5UmJOaFE3QXhIbGJoeXMweTJWWDRtZlc3MTExcisyMFVJdmhDVjlxQVdleXZCVkdSaURjMUxvQlF4a0dZZFk1UFNOckdqdXN4K0FLNXlQdWNyS05LQnZMNWkwN1RsN1ppUExkRE1EVVVCOG9lREFFYnU0WU5xcDVCbU5KV1dBeXAyajdic3d2RHpYRHRySlZ5OXplSFdPaUZWZ2NncHFHK0F1Nm56RWZZQUNUelQremNkZE9nVHBxbFRVSEo3NkZJUG1JQTVuRXJUZ1c3Q2pQSGhKcnBhYjFBYlIvTXFLS1dRUjFmS0M4R0RiK1k0M3pkeDNacXZocWxPNmNuUjZuTHVNR0ZBeHBRQ3FJdVUzNnhia1hOS1lvdy9GR1BLZWQ5cUUwVHZmRWswVDR0WkJHbStZbUVxYktISS9zUDBZVDE4QkpLc3FEV2poOXZ0ZkNIR1hvWEpBY3RjcXRQV2svN044TG5ZZG5BUUVpS0hoazM0amxWK3hlb0dybGlnZHNPWGlKSWJHUWZIZWRiUDNVM2grSEZtM2hxd2JuYkRWQkhPajEvVURsOFR4d2lJODNUL1RNeUVMYzhSQ3hoSzA3RkZmVStrcDh6YVZUUWpTbkhJL0hhTVlscFNkanJhMTIwRkJPSHptc0dzbk1BbllZeDJwMXk3UVNTempJR2FHN1hsVVBweS9Kdk9YSnV0NURPejJwcm16eUtFVmlzSitRdStwQUY4MEFseFAyWnZaNEYxbUxTbmUxbW1TU2ZhMVVZL1grRVk2UmFBd1hOeVB1Ti9GVkZJekVaOGZjb1FGOUs2eHB5bURjenhCRHdkSkdBYTc5VnRYUUJ2YkNYYlVpRFRMamVWUUNiS0NjWDJuc3V0S3gvZHVvTC9VOVdmWEJ4WDJ3LzdrWDdhRVpJRlBpOWZRQVIxTXZ2dXdmMHhieTdyMkthV1YrcURFY3hQbmduTFlqZkN3RFhQNC9JU3hoaVRDeWxwTGU3azF6Z3haUmtLUFQ3azhSaTdyL0huMWlzQkxXWlZzWFZsckl2L25wSGdlaUJJN0NVQTUyUTZtZWM4cElvS05sTkdiYVJzeVVhMklZd2JUU2xYT3lTMFdSdU5KcmNBZjVvWVJ1T1hTVTNKNTFoS0ZNQ0xLWlM4TnNnT3RjdlRqQWhBc3RDNzNCWTlCcnVETGFnWC8zL2F6RmRvcHJub0VKSEVXTURxNXpURm1CcnZuYTFmU1oxSngxQVRRcnVibSt6Qng5V2I5VVJ4UXRVN3ozRUFTMXZuSlVuYTJEaUpOOXA2MlhnRXVmVUNVbVVqQXJMMXlFMzJuTytjRHBFSHdMeWRqS0IzdThSd2FpZE5yK1pCMWRQc25TeGhhK1BtdWttYTUyWlhEUlN4Y2M1emtYSWhHMjBaVnIzTFpTTzZaeTUvekk2QXB4enRVVStzRDdLRDJ4NjJPbTlJS2VwOXR0eDJHWWRLaFlKUTkrMkNpWm5nTHQ5UFlRdDE3T2M1Qm1oMG5NZVlCZ2pRTWdhZ3pkWjBiK2lqZ3BlcVdwUGk0V0s1Y0lLOU9LSjdENEpPclcxeDZERE0xa0xPc0FUdGZ4Y2N6R1VVMEE3VXc0TTA5VnNMSUQ3cGNKMGNIMGNqck5pSjJhZTRlRENUYVVveGRlL0w1cWg1UmF3ZlVFRzlvQ3FxZEVreXoxdlhYWGlBak5IM0xQY3dWTlU1anU5Q3ZjR25RWmdnZ2ovcFFZQ0dkS2FFUEYxbDk5dFJUZm1kVEpWdloyejlibk1EdnVFOVBBUkt2SWtZR25CUnRpaU4rUlNONGFsK0ZPeFBicktzU3FTZElaRTZmb012RDZsQmJPYjRjWWlKQ2wrQzRrdVVNcFQrK0N5RFovK0ExQWZza0JxbEdEbFRtRERyckY4N0lNcEYrSWNEWkw1amIrRnlnaHFNZDAxQmc0bENydWt4NE1CVkdSc0tuUVNBenJpbGFBQzlML3RYbkVEMUdtRkgrVUZwY0UwWGVRRWE4emhXVlVyZ0tXdXR1c1BlVnRuMXdnWjVMbEk3dFBIOXJKOEdrSk9yVDk1bnJQOEZGK0dUWmNWWU1kVXF5U0JXV3JJdlFSd3p6RTlFTEFWWHVUL0xnaVNjVEtIUTV3TmtPamsyQ2t3Z3k5d2Z6RWhiemp6RkJXY2hZRXFnek9OMTRWanVkS1NHakhKUFZXb2YrRzJaOVhlWW51aisrV2dLWjdrVjU2S2oxdkdLaUQycTlZUDF6b2xiZ2lFVERwb3RsK0M3eEhBK01jVkZaQkF6L2NKMGxZdXFzZjAxVlV3aldmUCtmbHB3ZW4weFRXTzRvdFdzb21tQ1VSaVNweVNFRitWcmRjV1hIQmlLVWI4dG9mbzBBY2JiTGwrS1phUDdraER3L1RCc2ZxdTVOdDhwMVA4S0NWOW9HcFZqMGphbVN5VXNMRzhOS1RaSE1pbzFwTjU2d0NHaVZ6R0JLUXQ4UWdXZkV2cHlYNXV6M2hLazNuZERRODN0d2NDa3pvQ2Jpbkp5Q2ZZVWVLd3pqRUVQdGsrek9NQ3gzRFIrTEVoT3FZMHYrT0JuZVRCcjZCYldIdWpNeXdvc0hmaVVrVWRZVk1Ia25zN1VrUTlVdGcxdUxHSlFrclVTdkVNNXhveDl2RW13ZTVYMmhLZVZwS0VqRTlpUm02anAza1hENnhoTkp2STg5OGM3MzJXNXpUaXJYaVI4blFMdlVJV1Zxak1mdGVQM1lxRHY3YnVQZkUxS3drb3lrMnlGanZnL2NJR2hiTFRlYVRuS0dMcXFjQWZKbDJlM1RVVng4VWJENmZ0YVlaS2p1b2RMaXdKT1N1cUpCNTFrUXBJUi8vZnRSbEJYUFg4OFVWcEl6RnBvQlZ0NVBEQnpXVVBlNGRJNXdjcmRSbWI1bFJjRXlmMjNiNmRORmQwYkFqU3cycHhtVGxuUFUwVEVaR05Ld2hLZnlKdmpqTkFJQlpLRDVYRFljL295QWY0Y0FrdFhXQ2RuQkJWd1U2TTBrTTNjM3FlNzRlNk9JRDBjelNiOXRzVGpRSkdCb3lpVGZZRHB3SmIwU0NxN0FLdGtZbXdWYWJ5d28wYkQ2N01Xa1lIWDc1TCtwUlNEdW90ZkJFMmZNL3VOeGI2QXlSZzF6R1JsMUNZKytVVU9nMWIrdm5qVVp0U2Q0SmZQS1lvVjEzb1Nxc2RuVkQ2L0NHZHJoVEpZalpZZDhmN0JBK01rN0pRM09OMUtRL1gwN0QxZ3JaQm1JZEJlTXIreDlPRTZxWnBVYm1qV3JGYnpwdXBjOTcrQmNYeklwUTdGYStKNE9YNXBydThROFNkRkFueTlGQldjb1hOOFBscEVRN2J6K3BZaFJ4Ny92VXBINE9GUE83SkJvdzkrSm11TXhsZGZ5MlNpcTdKdUlqM3FaY2ppelVvMD0iLCJtYWMiOiI4MzUwZWMxNWViZjZiMzA3ODY0YjYzYWIzNDZhZGViYTdlYTM2YWE5OTQ3NzE5ZDc1NzgwYzcyNzhjNDJlN2JkIiwidGFnIjoiIn0=', '2026-09-09 07:02:26', 'eyJpdiI6InoyeTEydWl2WllXcUY0TVErK0FXVXc9PSIsInZhbHVlIjoiT3dzS28zYUE3SmRvaW1xZStPZ0Exc2MrTTJkMjI2akM2SUFTbncvMXlqaDlWdVFiVmd3ckV2bm1IMDB3bERnT0JZTW5DRCt4RjRXZHZSWVltbUlON3F3Tjc2dmwzQ0hXbktmYzR4WERUSmM3cFUrUTZvNGY0emdyMXYyb283Y0hJSDhRc1dRTmtsYkUzOXBCTk1HYkF3PT0iLCJtYWMiOiIyNzNiNDNjZGNmMWExYTRhODY0MTc2YmRhYzI0Y2JjYjc4NDA5YTI4NjI5ZWRlNTcwYzBlOWY3NDc2NjYyMTIwIiwidGFnIjoiIn0=', '2028-02-27 14:55:41', '6234437000', '6234438000', '6234439000', 'main-warehouse-1', '1', '1', 'Admin', '2026-08-29 02:55:43', '2026-09-09 05:02:26');

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
(51, 44, 2, 'common-prod-2', '11567878010', '110590599044', '20349', 'NEW', 'synced', NULL, '2026-09-09 06:34:24', 'eBay Import', '2026-09-09 06:34:24', '2026-09-09 06:34:24'),
(52, 45, 2, 'prodname2', '11489140010', '110590436276', '20349', 'NEW', 'synced', NULL, '2026-09-09 06:34:26', 'eBay Import', '2026-09-09 06:34:26', '2026-09-09 06:34:26'),
(53, 46, 2, 'prodname', '11489113010', '110590436251', '20349', 'NEW', 'synced', NULL, '2026-09-09 06:34:29', 'eBay Import', '2026-09-09 06:34:29', '2026-09-09 06:34:29'),
(54, 44, 1, 'common-prod-2', '11567880010', '110590599046', '20349', 'NEW', 'synced', NULL, '2026-09-09 06:34:57', 'eBay Import', '2026-09-09 06:34:57', '2026-09-09 06:34:57'),
(55, 47, 1, 'prod-2-store2', '11489208010', '110590436371', '20349', 'NEW', 'synced', NULL, '2026-09-09 06:34:59', 'eBay Import', '2026-09-09 06:34:59', '2026-09-09 06:34:59'),
(56, 48, 1, 'prod-1-store2', '11489207010', '110590436370', '20349', 'NEW', 'synced', NULL, '2026-09-09 06:35:01', 'eBay Import', '2026-09-09 06:35:01', '2026-09-09 06:35:01');

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
(37, 44, 5.00, '1', '1', 'eBay Import', '2026-09-09 06:34:24', '2026-09-09 06:34:24'),
(38, 45, 38.00, '1', '1', 'eBay Import', '2026-09-09 06:34:26', '2026-09-09 06:34:26'),
(39, 46, 28.00, '1', '1', 'eBay Import', '2026-09-09 06:34:29', '2026-09-09 06:34:29'),
(40, 47, 120.00, '1', '1', 'eBay Import', '2026-09-09 06:34:59', '2026-09-09 06:34:59'),
(41, 48, 94.00, '1', '1', 'eBay Import', '2026-09-09 06:35:01', '2026-09-09 06:35:01');

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
(21, '2026_08_29_000001_create_ebay_import_items_table', 2),
(22, '2026_09_09_000001_create_product_connections_tables', 3),
(23, '2026_09_09_000002_create_product_connection_item_stores_table', 4),
(25, '2026_09_11_000001_add_master_store_to_product_connections', 5);

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
(1, 'view roles', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(2, 'create roles', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(3, 'edit roles', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(4, 'delete roles', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(5, 'view users', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(6, 'create users', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(7, 'edit users', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(8, 'delete users', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(9, 'view customers', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(10, 'create customers', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(11, 'edit customers', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(12, 'delete customers', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(13, 'view sales', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(14, 'create sales', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(15, 'delete sales', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(16, 'view returns', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(17, 'create returns', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(18, 'delete returns', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(19, 'view categories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(20, 'create categories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(21, 'edit categories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(22, 'delete categories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(23, 'view subcategories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(24, 'create subcategories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(25, 'edit subcategories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(26, 'delete subcategories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(27, 'view products', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(28, 'create products', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(29, 'edit products', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(30, 'delete products', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(31, 'view inventories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(32, 'create inventories', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(33, 'edit company settings', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(34, 'edit smtp settings', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(35, 'view ebay stores', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(36, 'create ebay stores', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(37, 'edit ebay stores', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(38, 'delete ebay stores', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(39, 'sync ebay products', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33'),
(40, 'view connections', 'web', '2026-09-09 01:24:37', '2026-09-09 01:24:37'),
(41, 'create connections', 'web', '2026-09-09 01:24:37', '2026-09-09 01:24:37'),
(42, 'edit connections', 'web', '2026-09-09 01:24:37', '2026-09-09 01:24:37'),
(43, 'delete connections', 'web', '2026-09-09 01:24:37', '2026-09-09 01:24:37');

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
(44, 'common prod 2', 'common-prod-2', NULL, 'common prod 2', NULL, NULL, 30.00, '12', 5.00, 0.00, NULL, NULL, 2, '1', '1', 'eBay Import', '2026-09-09 06:34:24', '2026-09-09 06:34:24', NULL),
(45, 'prodname 2', 'prodname2', NULL, 'prodname 2', NULL, NULL, 30.00, '120', 38.00, 0.00, NULL, NULL, 2, '1', '1', 'eBay Import', '2026-09-09 06:34:26', '2026-09-09 06:34:26', NULL),
(46, 'prod name', 'prodname', NULL, 'this idesicnwon', NULL, NULL, 20.00, '10', 28.00, 0.00, NULL, NULL, 2, '1', '1', 'eBay Import', '2026-09-09 06:34:29', '2026-09-09 06:34:29', NULL),
(47, 'prod2-store2', 'prod-2-store2', NULL, 'oijd', NULL, NULL, 30.00, '30', 120.00, 0.00, NULL, NULL, 2, '1', '1', 'eBay Import', '2026-09-09 06:34:59', '2026-09-09 06:34:59', NULL),
(48, 'prod1-store2', 'prod-1-store2', NULL, 'prod1-store2', NULL, NULL, 200.00, '100', 94.00, 0.00, NULL, NULL, 2, '1', '1', 'eBay Import', '2026-09-09 06:35:01', '2026-09-09 06:35:01', NULL);

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
(9, 'common prod 2', 44, 2, '1', '1', 'Admin', '2026-09-19 08:49:16', '2026-09-19 08:49:16');

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
(11, 9, 44, '2026-09-19 08:49:16', '2026-09-19 08:49:16');

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
(1, 'Admin', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33');

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
(43, 1);

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
('THSh3dANUBF1ewBJRZKcdpR49dlsElg2zJaHHzP8', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:155.0) Gecko/20100101 Firefox/155.0', 'eyJfdG9rZW4iOiJsVm5pTlJwSERrRTFyblltZ2NVVUpNQnJBQmh0MllPMmlMNm1aNHZIIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2F0b3pjb2Rlci0wMDI1NC50ZXN0XC9pbnZlbnRvcmllc1wvY2F0ZWdvcnlcLzIiLCJyb3V0ZSI6ImludmVudG9yaWVzLmNhdGVnb3J5In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1789113531),
('uaLwFu8mqaW9GL3bPYhDkQegdSFYkElZ2143Z0pt', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:156.0) Gecko/20100101 Firefox/156.0', 'eyJfdG9rZW4iOiJQV1JOMmNEZzZVNDR6SldnUlliRlRCRTlxd29vR2dKUWZoUlNRM2VxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2F0b3pjb2Rlci0wMDI1NC50ZXN0XC9jb25uZWN0aW9ucyIsInJvdXRlIjoiY29ubmVjdGlvbnMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1789826378);

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

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `category_id`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 'sub-cat1', 1, '1', '1', 'Admin', '2026-08-29 04:01:12', '2026-08-29 04:01:12');

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
(1, 'Admin', 'testsoftware@gmail.com', 'testsoftware', '1234567890', NULL, '$2y$12$q9wmLN8bGCtVs5fxw.njauL1H6uxMIfC0DEFqVDRgNm8JKY1ZHt0q', '1', '1', 'Admin', NULL, '2026-08-28 09:12:34', '2026-08-28 09:12:34');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `connection_item_stores`
--
ALTER TABLE `connection_item_stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ebay_accounts`
--
ALTER TABLE `ebay_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ebay_import_items`
--
ALTER TABLE `ebay_import_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `ebay_listings`
--
ALTER TABLE `ebay_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `product_connections`
--
ALTER TABLE `product_connections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_connection_items`
--
ALTER TABLE `product_connection_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
