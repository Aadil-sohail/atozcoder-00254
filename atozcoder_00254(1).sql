-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2026 at 03:16 PM
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
('laravel-cache-nobody@example.com|127.0.0.1', 'i:1;', 1785241580),
('laravel-cache-nobody@example.com|127.0.0.1:timer', 'i:1785241580;', 1785241580),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:39:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"create customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"edit customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"delete customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"create sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"delete sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"view returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"create returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"delete returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:15:\"view categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"create categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:15:\"edit categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:17:\"delete categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:16:\"view inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:18:\"create inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:21:\"edit company settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:18:\"edit smtp settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:16:\"view ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"create ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:16:\"edit ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"delete ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:18:\"sync ebay products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"view subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:20:\"create subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"edit subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:20:\"delete subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}', 1785476878);

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
(1, 'abc', '0', '0', 'Admin', '2026-07-04 03:20:01', '2026-07-30 06:07:01'),
(2, 'abc2', '1', '1', 'Admin', '2026-07-04 03:20:15', '2026-07-04 03:20:15'),
(3, 'eBay Imports', '1', '1', 'eBay Import', '2026-07-21 02:54:37', '2026-07-21 02:54:37'),
(6, 'imported', '1', '1', 'Admin', '2026-07-28 02:33:02', '2026-07-28 02:33:02'),
(14, 'cat123', '0', '0', 'Admin', '2026-07-30 05:18:43', '2026-07-30 05:38:49');

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
(1, 'abc', 'abc@gmail.com', '121212', '121212', 'uh', 'images/company_images/1785240743_E880D782-3D75-4219-9090-4905ED3D9894.png', 'images/company_images/1785240743_Vector (3).png', '2026-07-03 06:08:15', '2026-07-28 07:12:23');

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
(1, 'Keiko Pena', 'facy@mailinator.com', '+1 (172) 321-4024', 'Fugiat qui asperior', '1', '1', NULL, '2026-07-04 04:52:30', '2026-07-04 04:52:30'),
(2, 'Hillary William', 'jahebaxyky@mailinator.com', '+1 (378) 948-7889', 'Harum et nulla eiusm', '1', '1', NULL, '2026-07-04 04:52:35', '2026-07-04 04:52:35'),
(4, 'Muhammad Zain (testuser_buyer1122121)', 'buyer1.sandbox@example.com', '2312312345', 'FAISALABAD PUNJAB PAKISTAN, FAISALABAD PUNJAB PAKISTAN, FAISALABAD, IL, 38000, US', '1', '1', NULL, '2026-07-07 07:50:04', '2026-07-07 07:50:04');

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
(4, 'Zain\'s Store', NULL, 'EBAY_US', 'eyJpdiI6IlQ4UFdrZFhoTHEvbW1ORHZDRHNHT1E9PSIsInZhbHVlIjoieHZPSUtGS3dWWmJEY2V1QkNtcUpNRkpwcHhwK3JWQ3U3dktEWVZuc2hraUJiKzdjUitNYTlIakdtazlicmZIV2h0ZjBLOE9kTEtkQitqN05QdUFHZVNVYU5Tb3pEODBoYkFsSmlsWW1wdDBSa2FjNDNJdFk3OTlNajhQQ3lpOVZ1QzRxdkRUK1dGV2FXK00rYVRsUUF3QUszb0ZuMmtjQXlPZ2hkMitoMkRvRmllN2d0azZqZmlTcEZWVXFxcDlLc0dQcE44bDc0YXRKZlZndWNKVTlUN2d5bFFDT2VGMnVBOHI5Rk1hU3NyQko2b3hGdGVFak9JcnMzek1JTlppRG9Uejg4MlA1aUZYSFFMblQxNU1IczVYUURJMjVpRkk2Ky9mZ3M4akVtNTloNkZpMlNZbWlIZmYwVnd0amVaYWU3WEFZSmxGcXV5dkM1dXJxTkxCOHFBNm42T0JDN1NaajdlMTRGZlgxbVBidFBYZUtoODk4bTRXM1ZoOUZnWGd6SnZJdUF1aWNraEd5WlRNMlc3WmxvYzJZM2huS3Q2REpLNk96MUFxMmkxTmRYa0lhaDlMdDV5NGxuYVMxd0l0MmZENmlzNWdockVsK1pDMVNxN2tsWGFyN0ltdUhpZ2g1UTRBU21VbEZmQXlUKzB4cldCcWZZdk1Kbkk5N2pES3pPckR6QUNHMVhCUkowQjQ4VEZPQVY4em5Xa0NmWmVTTDRLU0Zma0JxdWp2UFYrdlNYZzlQeTB0aWphdUgwbnZocmZ0cHpFVDFkRXloNDNKdVZOUlFsREdrakNJZytIeEVxTUNscDkxMlkvUnhCQjNyTkM2S1o3T3JHa1VXVTJON0xjQlhUQWpFVmNPSDNLRUVVdWkxOFBUcVl1SUkvQWZjTGNHaUJMLzgyM2xRNSt0U3NBMVlCNUo4NzJxVUJiMk1lR0dNZndPczhtUER5RURsOHpHOE5iN0RUU01UQ2oxbDcvRUZnYkpSMWFubmxpeDIzN1dQZnRnR01tSmVLdm92MmRaV1dlSVlBZnZFMksrWXcwV0x0bFJ6ZnFuTFBCNm0vU2ZwNU9TdlBlK2F3MldaditxazVEK05WV0UyS215SkZ2MWxvbWVjb1hhcjNXeDZPR2NKZ2lueDk3UVo4UjY5ZEtoWTJXWElwYVM4bVVOT0xtZjJ0b20yWjkzSFdXVXg0QzdKSVpMd2dhQmRZSHdUcUFubU5QdnlsWVhFOWlNYTV4czlyY1Rja0d5Rm5RNFJYaURSZG42S3NNbVhWTllRUzVpTmQ4MlREcUJlTnZ2bkRpNzJsbnJ3RDhmS1M4eFY0YkdvOG93a2J0Ujd2bm1DUjc3NW5La1FKU1hVUlZRRnd0MnVuRVFwaFhGSlp4OFk3RzJPaDBhMG9ZSjJCSFBkNUc0UURyY1Flc2QwR3dETnpiUjJ1bVRZanNZVmtNR1BoenF2OGpSU3VoRmc4OVYvUXp5Z2hMeXEwenpYYnNCS0NyaWhSems0RmQ1SXJTbDlCcXBTMzhKd1JBazBPZmk1K25RcTRrd21ZUjBuM2FJZVZoOW55d1RtYVBpZkUrMjBwMm1XRlI2VEhTc1h2TzRZOU5pcFVpancvb1ZTcFc5SkNXbjdabmNYbjVJNTVxUTFTNVptaXpTZmZGcUpNZDJYYW9Kbk9rNWdORWxqT0dMaWtON2xQMEFxTlFrYnVDOU5hc0FDb1VtOUFVV2t0UmVGRlZ6VGlvOVQydE5nbG1zZG9NUTBCdTZ1ajUySm1JZWZuOUs4U3UvMlhqVEFBbzJKMHFHcnYrZllqUTJHZ1gwa2gxSjQweEFVZmJQU0ZYODgvS1FZQWlFdjV4UjU2dy93NXgwczJWWUtyWU1BY280TUp0TzgyS0NtYmVoZ2RoZ1VMc2Y3WHg2bFgvSjMrZlM3Q01KdEZZWE1mbDBtRFJSaEN1S3NPbjVaQUxpb0NRSjB4T2J0aWtvNmRhdlUwM2lwdEFLU2xXMWdOQVhvRTBOS1JQZTJuRDlvc2U1NE1HOEltVUdBVWIvQmxlTFZZbExqQVhSamV5aFZORmhrYkF2ZU9yaWtiS0J6aGlhTzBrQzhtcTF6SXFBOVgxYlI3ekorQTZDQnI3NmVUWGE3Y1N4RnQzWXJjdTgweVVBUDNXNStQT2szYU9SUDRCQWsxYUZ0RERQMXkyL0VuY3lKUndRcUJvSkh1eS9RWUNjWGZCNjJVZnYxbW1WZTZsWGhzSnFuUzJjalFWTjdhUysyV2FNMGc4WFRvdXdkUXJRSU05M2RFVFBJZHl0NXJKR2JyL2xHS2daS0Z4OWo2RTJkTXdGZFN3aTBYMloyQitRWWdHNEl5NzlCeGxtZHNPR1N5SE9rNXBjazRoaTVOQnlRRUticGoxcHNWeUxJank2bFpLOHpBL2VPb3k1c3lDYUowWjYxblArbFppOXRRRFF6UkZMeDFFTi9qR0g3ajg5eDFER1RwazFGRUxGblU4Y2ZDU0x1cGhLQlN3bW1SeGpUSHhTeVdveVVXNzQrMnd4M25rMzN0dXN4a21xV2F2VTR4Nml0aVIwQkRNVEVhYk5QQksveEV1a2NJNXFtOFcyb3YveDRRVHYwOWZmTWtYY3M2MU50ckNEaWlqRHpaa0tCQjIwd3JONTR5RXF5a3ZkR2RhTUg3T0VxSktybTVQUUJUSTNHcXNaeFdsdlJQRUhBcTVIdVpLa1FwQWM1RTJxcEZtKzFuMjAyL29XY0dwQWtaSkhFaG5LTlhmMU1GdnRlS2tSMVp5NUxMWGt0Y0lUUmVpdDY4d0dkeHBnSUE5YUlteUdGamJwSm96WmNjVGRuaTlCdW92RGYyU21BUUlvK3Y4TjZUTkNkWGs1SEI5YWFkaXNLNEQyUzFNM0piZVAzSm1ZQldTS29hZXVJMDdMamdrTFpRTE1TcnJ5UytUMWtxNVpYTVhPTGZmU1YwL1VMRVpkSVBvTEZMWnVvaGwydjJCMTl1WER0R3czVGszVENoVFd5OTRTaTIrekVpQXI4VGtYUjRSM0NhT0tDMlMycXBZUVJPbitIcW1RT2E3OFZacGRmL3hDb2tnTU5OTTB4Y1hsSHBQa1I3dHpLOE9GbG5jZzF5cDAyTXN1c2VFeG1xWXdRWDVEYlh3NGNOTmRuamVGNEZLUWhmVkN5UGpUUjJWd0pXMEF2TjFWR3J5TDB1UHBrY0hVVG0vcDlLTnFEbllhQ2djZ09LWVdEZ2RyMFZVVy9BVFBRdzF4RkFUWFlwUHBtZkNtNlFjclRsYlZnZ0NLdzhrUnU3TFZydzl4emRveVQrZTFRZC9hNWtNTmNZY3kycEFCRzVJSmxWblU3WEtLQWJuY25FV3hyREE0c0JWVXZJamJnRjhseTdPWTJWanJoZmNYQ000VzhBaVVzY3JEV2tkYWpKdk1EdzM2NmtRRjAxZTJnem9QekZHQkNmSi9BY25EYkc1VldPTXJuODhaSEl1eDBJYVk0M1BwRS9QMGJXaENBWHJ1bU9JRG5ybHFsSWFqQ1dvdlNXbjcrNnFZZlhFTGVsSG5XSXd5Y1lRTkhwTXZYdFVpSTRCZ3dpbUFVZmRINGhFdHlabndIaGdtL3JaK3BHbUdYK05JdTRZN1RqcDgyeFJYQW92K3RnSWpIL3RnTXgvNXNqa0hLMVFrMHdIRjlZQ2JaMExpWGpIbHBBZVlXNmViSDU0aExKRFFMU292bGltU3lQbERGSzhGbW9GMEFXR3F4Z2ZidE5JRW4yNEtHQXdGSlU3SERaTk1uaUw4ZUtGajUxRUxMdjhtZ3NvL2F6M2l0Mmg1NkZKUTlHRzh5dDc3RjRFRXNtWXIwM2htUmJTVWJ0Q0NHOXlVeFBhRkhjajZ1ai9ReHBCWVZpMmpMK1VFZklkUjd6eHNKVDh4bjBPdHpqNWsxc0tXYXl3bzNkaXVSQm4xMFIrbnhIRSs5OW5aTDdnMktqdzZJWXRsT3FzZVhieTJHWUxodnZ1VElBdkRqVE9tWGJGK00wKyt5elR3Ukl3VjVyUHBzNHBudmFvMy9WVU9EeHVTZStBS2tjbXFlSFhSenVIYVo2c05tUW15OUh6T1BtTmt5cUZLanRtSFVFMkdjTE5KbWZhY0VtYm5qV1U3S0d4VmZ4bnkxVHpFM1JnUT0iLCJtYWMiOiI2N2UxNDlmODZjODQ5ZWM5MGI1YTUwOWJlMjAyMmYzNzI3ZjBjNWI1ZDk2NDE0YTIzOGE3NDY3YTQzN2RlNWYyIiwidGFnIjoiIn0=', '2026-07-21 08:37:03', 'eyJpdiI6IkgxWktqR3FVbXovWWphZGhWSGZTTkE9PSIsInZhbHVlIjoibG5DcUhqMDBuWFB2b2JKaCtHajZxMUFVNmR6Yk8yaThBZ1FVQkw0Y005SUU3UUhTd2w0dEpSS3pDOHd6YWIzUyszVmY0ZzVyRkp3WHlzZi9EZDEwMk4wR1Q2YS94RGRGUm1zVXZhVVpSc0VBdnZoeGZFcit6SkYrTmw4UytVOWhWMGdJSVhHSk9rR1hOL2JIdm5aSTB3PT0iLCJtYWMiOiI2MTdiM2VjYjg0N2JjYzhlODVkOTgwYzgwYjk2NGE1ZTUzOWVlNWQ3OTBhMjA5MTI4NWJmODU0NjhmOGVjYmJiIiwidGFnIjoiIn0=', '2028-01-19 14:37:56', '6234437000', '6234438000', '6234439000', 'main-warehouse-1', '1', '1', 'Admin', '2026-07-21 02:37:01', '2026-07-21 06:37:03'),
(5, 'Test Store', NULL, 'EBAY_US', 'eyJpdiI6IjVDelJ6dmJmcU5KdUNlbWx1TndRZkE9PSIsInZhbHVlIjoicE4zUHltbEpjZU96ZXFRSVMxalRSb1ZNQ25vQTMzYWVMYVo4MDIzNDVvc2l0WXlSd3ZBczJYME9nUkpXVWluRWxYUkdCV24yUnJ1enhTZllaaFMwUHFmMW94dkQ1N0JScW50cEw1aUhtaUwzVXBNWld4dkptZVUxaXhBRVhyaDB1RVRwSlZ3VjBWRTRZRmkxZUY2TThrekRJNzF4VC9OUGxLcWhldEE2b01FeU1aUjNjNTN2dVV4ZWs5MFlhbHFFcW52d1JCcytxL2ptTzZnZkZOSnYvbnMyUFBnUHRCelFrQ3MxMDllQXhpMjRxWUY3b2ZVMDMwUy9vaDgycjNnelYrTTdpNW4vSUJCZEw5cGt3bXY0MlBsT3hlbCtmNllNdUhZeEVjL3BFWUkzQi8zZ0dScWZqb0dRVTVpVGo2NENDb2hwZFBKVXhqc0tPazA4M0dXYVA5ZGZhbnU0V0ZjUm1xZVNqbHNvbWJOSWVQa3hSejVsOUlrWjVFZGROVHhaMzhJMzFBN2hncHhsRU9DSStCT0p0K2lQelRzMkdScjFNNEpBL1JvdHhKdGZ4RnNSTnU5YXRIaFJyb3l0aUZJQmhrOHlCalVEczJjOGs1allpTG40bUhMYWt0Nk5FUXpuczFGbEpRNXpuWTcvU0lXaUxoN0NRUlhJaGlhckk2NW1QaFliRmpLaCt0ZktkczJvVEkrcUdDcTRuWWhXZFduRDB6azh5enJMcGlVeWNDT3ZQSFVqb3hTMDkwaFZZdEl6V01VQkNBS2ZrMVdSdVRBeTBzWkVXWjg0TWlYUVNvKzlKd2JSVkRhSkNRcGJzVUFGVTJKSURENTEzY2hKd1JDTWw0RVJWMmd4MzFLWlZsbjYwYW9PbHJ1eGhtdWk3dlV1d25OaXhVWVZlRHQrWnVSdmlWTjkrSHBsN3pBSzlUNTJyR1oydGxLUVhXaEkyT1U5VEw4cnIvcU1CRk9xK3B5MmNEa1J6WUhyOU5oQVFwbmlkVEpKV292bmlCOFRFSElVUTFpMEY3NGQ3TnhZS1U2OGZ2d3o2SjkzaWpZa3cxWlBOK0Q5ZUNoT2FXOUV1ZmplbVMwR1psa2VhK1BQWlhpYzhoaGoxZmd1Z05YRSsvK1BMc2E5em1DbmpWaGZXeWNaWEtDZVJ6LzZ2WEJLYVJsaFlFV2pXU0NUUHpZMVdCcE14bHJqTndQR2VWNzgxdHJna3pnRmsvUTdLSjl0NnNGR0ZRdVArUUpYczZVampGWENOcStLUFVmdmJoMHRQVUNPNlZBSlA3dTlPY1dYbW8yd0F1dHZjTkNBOVZYN3VOc3JmWjhiaW9KUU8vaGpoSk8xRlF1dnVzWHVnMHFINlZscmJFY2xtYUF0Ym44ckVTQ3IrRC9BNnFSWDhVVmZ5SzZReFVGTFV5V2kyTk5yV3h2bFRWQUEzVHNwZ050UklnUTlabThpWWZGNEh0NllUSXBaUGNBNUxvamxVTUpTM1FpdkpNUzZCVlRNZ3Q0ZERYRmR1ZDkzWHYwQ3RreGJXamxneER6Y2oxbng1SkZmT3FSdnlaOFoxWGhucUJkcmkvT1ZFQUwzamtnMmJlYk5oc1RhM2EvRWtQWW1GYXZLTmZOb0xEVCtpY1lsMGRNUEZxbFRTTmFLcUNaaHZ0SmZ4RUsvWnBoWFRubWJSUzdUQ1JOODZIMTNQd0RDOUJqVmlzYUIrUTlwa25HR1FCeVJDOGlZTTcva3R5WnFGRlZXMW5MeEZLL3M2bG5hK1NXUGZqTW5KNjQwZEpkMmNkczUxeEgwbkY2MVIxR3FqWGNmUW5VY01jZFIraTRKeFplMC9RZXIya1ZLdXQrbW9GV0lhZklaUFpFcEhvakFybVdLQkdUYXRDY2ZZVDdJc05iWWtmQ0NCNzQ4cjJxVkRjNndya3NMdVRjMENsV3hjTnErNWpBcCtialBHamRRd2xvbGFaVnIzSnZCa2t4L0xrMG0vMGFnVno0NWZJd0t6azRibkNYalRVQWZuTlUrODJZaHlPbDBUdE05R1NQZUcvYkg4NzFTUHdwZXNQSDRuRTdFT01uNS9tQy9UdWx4ejJoZkQ3dGxVVDRkSlgwRWRqb2NUMEg3UHdqSlZaSEJ5c1NMMlU1UEk3UWJUVVNUWDAzYlN5OS9rSVRFMnVtS3RLSlB4VEZ1cEdmdzJoSlp0V3lLTWRQNXpHcmhRWGY5bGgzOGY4bDVUQmpKbHJoU0pYNFhoMjJuSWJOKzUxaFJTeExMREpRM0twOWZ1czZndE94WkFuTXg0dWd6TmtqQW44ZUxNREMzUWRxenRTNlhldUxPSU1rbEprL0VyejIrL3lsQkpJeDBOTFZpemZyT0hzREhqYjMvTllvNjNmVUVNMlg3cE5Md0tTUktLaFIwZ0lKcDRuVEFjUDJQQ3p4bVNnc05LLzh5L3B5U2hFVUNhWUNWUGFQWVVvZTJOSkJKMWtac0NVeVI4YWs1NnE0N2hqQ290Y0JLVndLRHZVVWhKNHErZTBramZIVUtNVTJpU0oyZlM2VTc4cUc3dmJwaFZpaUYwS2x2ZEpmZ2FFcjZTYWM4TWR3a216MytzSlMwN0ZCdHZsWW1CdFJ5WG5EV2U2T1VMdk1EVWxtUEZoSG9YQjM5SWFBbVZzcFU3VlRHc2Q3Y1FIWHk0c0lKL2lYOXV2aWtQMkRmeUwvMmJHa0UwMXNnNGxzclRZSDNPL0NZV2o4MXg2eDhBUzgwWmMyMGpiU1R3UFBVL3Y1SWt5dUVXcWpXZFVtMjRXTnNkMkI1dWlRczAwdHE4M1gxSGV0cWVwMG9KYzlWY2RFWlloTUx0S2lFU3dMMW1wL2RLWlBDWVQyTVRmOC9hWVo5WXBIRzRtQTh2eFhHdDVWWUJvTC94U2lxVllET3dLVTZOeG0xVGlESU5icng4K2ZkNnB0V3F4Z0NmYlduZ1RLMnhNYmIvYnprMS93QUQybEZRbHFldGpQRmp2Q3hPeXhLSWwxUWhUdW1pSEVrSlpKYTNwS3U0ZHhVd3JkMTNyTVhrLzc0eEp3VFg3aEFmR0o4NFdEVnBYYTViQWVwK0VPa3YrejhJdVBkaUtDR1Via0pUSjhmeG5FMHlBSzZxMlNoVmljSTlmTk5LNFBpVEh6cUFSbVFYeG1RUm9lK3E5anMxM2ZDL3lMRTdWVzJ2T1ZhZ1JMVEh0ZVROWVVTQnA5UEE0dmNMTnhZNUFod29PbWovQTc0VnJnMjZuTDlEbEgza2l4ZDdER3phRXdtS1BZUGVVajdlZDlYNzBGSmtLdDRWT3p6aGZ1VGs4dWpnSkMyVWpwWGdTSER5aWNYc3llMFJtTzJCTlFmTEJ4UmJVbHhkd1haVEFXWkM1aVVlcUdIZHZQR2lMSVNjaEVhVytQc2VjSllNb1Fua1BZTHRiQjBtQUxKWHhZcytuejVybm1aS25wTFhKWE5HQzdCR21IaDNVNVNLaUhobEw2R1lNdnBDREdPUUNDV1hwaUlpVUREbkp1K25nRzBRY1M4RmhlRUxTTW5ZL3kxZTlYZmMxUXZNTVZwdlVDWTcra3hiM0hiTFNyVWphY09IZm5yZktwM0NIdnp1bFdoaVQvYnEzSmt0RzIzYzJaY0liWitIVXdxaUJmeUtqeHRkNDMzNWpZMjFjcVNHejRleDFTNzA5ZDdlanp6RU1HYXVtVFo0QnJ1Zkladk9uMWZlNFp6bE5uemtvbUZvdy9jMERPV2RzMHhwZDJCcktwOEtwRFFZdVgxd2JCZVUzWkNud2tVRkF6eGtRWCsyM0I2M2plZ2F6WEFxZytPMjlmTzRtMkNIVlhkL2NRT1pOMi85MGxrTisvM0dwb1RZazJaNWtCKzRrYkF4ektuYzVmRTlYQ203Z01CazAzTFhvYUQ5K0VlZnErTS9GZ0lCdnNGd2lKNnFOak91RmFLS08vNEFLRFFsN09wMGlNSFk4M3JiT1ZCR3RpQ3luVG9WOVBtZlB2RktxdnFaYXA0eFRXRzF0MXFaeTZCdDh4Z2xIVzgxUnhDc29iS093dFArdkdxRGpDR2V6MTd4Qks1S3ovYnlwbjlURFMzcnFzQjJXcEppa0ZnbEJWMk1pcG84RWtZbzNXbjdTcVpWOVlkazBtSGlrSjNIZHNYTUFJVzlIYjNvVEVvQ2lTdnlvZnNuei9zamxtYiIsIm1hYyI6IjVmODExMmI4OTk0MzI2NTExNjNhYmIyN2IwM2NiYTdlMjlmNDVjNjgxZWZjYTEyYjI2ZWNmZDI4NTM2NjA4M2IiLCJ0YWciOiIifQ==', '2026-07-21 08:38:20', 'eyJpdiI6IkYrMndVdUFCOFRoSlhGQmxJWlBXYVE9PSIsInZhbHVlIjoiaG53N0hDelUvVFhBcjgzUkFuM0pDcWNEaEJuaS9BR3JGSUNONVN0VWdPK1BwR2xEMG5wN0ZxSk81d2NaaktSeVkxTkpwbklZd25kKytzNzE2cU1DeEttTTE3SGxETXo0a3pSK2thVjNHb3E0cmtoOUxyTXFsbUN0MjRQb3h2VnQ5QjlLaGtNRmhVUXZNWkVZeFVjdDBBPT0iLCJtYWMiOiJhOWEyNWY5NjMxZmQzNjUwZGQ1MDBjMGJiNDg2NzdkZGQ3MTc3YWZmNjJlNjUyM2M5MmY0NmIwNDViODY0Zjg1IiwidGFnIjoiIn0=', '2028-01-19 14:38:37', '6234437000', '6234438000', '6234439000', 'main-warehouse-1', '1', '1', 'Admin', '2026-07-21 02:38:38', '2026-07-21 06:38:20'),
(16, 'abc', NULL, 'EBAY_AU', 'eyJpdiI6IkpFMTAzUWtpdElsanQ3cm9jUUpEVXc9PSIsInZhbHVlIjoiQmlPdzhZdlV1cjFNUXNYV1FJd1RtNkZLMnhZWVE3TlI4ZlhYRG5QYVJKdXlJSXQ1YXJDbWVsbklXVHB6b2NSSXVlNzVvRlZFQmhEZjhMN0F5Y1phRFJ4K3ZaYmk2R0dEa2h3S2ttK3lJRkpZaU5IY2YxVlQ1cVgyQkljRUFRVEVORjZjd2VWS1BCOGdteXVCL2ZGaTVRZFV5bnpRQUxZeDVDakpNQ3V4R2kzUm9ZZHR4SThrbkdad2Z0MmVpQlF6L1FwNXFoQmd1cExWTDJmUFd4VXV3c3pwR0lyVm5wNkIrNno1U1pTN21uZG43LzNLcWJJUmFHSjVRVE84QUJ3VUlWOHQ2bFY1YStXcjlzSjRVUzZIWFVwRGN0MC9aMHhxTmNTSW9sT1FFaGVGaERmNXZpak0vek9lQVZ6bWp2TTNEM25SclJJQXdieThWWDl4WFo1dkt1US9KTHZjM3FxNzVjT3M4VzdOVEppMkRzNUJWK25iQk41Wk4zV3o1bE5FbDdBWE5WYkticTRZTk04eUtmVmxBRjJUM3pycEdCanBsMkJjK3gzR3BwRW5oYk5oS3I1VkNoRFdYY3gyMlJLOXVraFhlTkg5bVFMa2djVjM0djBpem9EdmR2OVp0ZkhZZmV0NEQ3VGNtMWEvdzZuQVA5OE9UTHJSVnhNUUxtWmpoSTNBYXFDZlF3eHMrMU1wK3RKZUxjREl2aTBIeFZ0bTJzTWx2SDZ3eWZ1TTZDc2tSaE01UUhnVnBjem04eEtLcG8zNHh4QXcrV1N2RTZQZlcxaDMzVW9CbmRwWXNHR01KUjdyVm1xYUhnR0xBMEFSVEgzVmlmUFBRZTJsOXBLdTA3VVl3a045c3Y2dnhHMk9jYTVlQ3dObTA2d0JlMTI3bjFtcG02RHErL1BzYmpmeWdaZnJHb29yZERnWk54YWczNkMzRUMzQ2F4MlJTQXZmaGxkT25VSzFWOUxLb3d0aEFuSU03Z0VMdjhkUjV2YmVzT2hyYytaV1h1L0tiaTdDNjJkYWlIUktndURMVmhDTzFwc0xFc3prSndWZyt0M2JJWVpYYmQ0Y0UrR1lmMDV0TUFmV2pCV0dXamJkeHdsUUxHU09SdTF0OFdHN3dmK0V1ejM0QXZmZGVxanVZOTIvcWRmeXRsb1hSamR2aVl0b2YweEFxZ1crYkZjK21mamhvblVST0l0YUxldHZ6cExFKytQWjJWRW1QeXh5NWJ4ZnRJcFVRTDdWRHNDbjRUUXpnSERWN3Y0WEFDTEoyRGlMcTZsU0lsSjhxRFh1b1hzT241VmVkL0FWdDExVmVFSjROc09VRURIeFUzZGhwMXBCWFJMZ1cxUVNWa3UwY3p1S0tlVDlqcW1tRDY5a2VZR1NLT2xWN2pUQ1NxWW5qbTZIS0x5MHl3Uk5nUVl6cWJ0bDVPRy9RVzFmVmt1UEcvTXZaZGNCU25GN3FGbFJzb0RlZDhzRk5RQXdLaFBEWG9UNGI4SDdXZzJGTkVocnJvcVhIQ0hYTDFrOFB1bnZZWnRURHJLS1VocUUvOVBtUXRsbkcwQzAyclRFc1pvYmxQeG1ncGhHY2p5NWxGUWNvYm5FV1NpZVJBak95eGhLODc5YlZoTWo0U3J3N3NCd3VkeHRHOHR1QnArUHplcXNDYjhLOE50c1BIR2lCSm9pcVQ0c3RMT3JTa3hpUHg1cHAzN1JXdnFzeXNYQkNnSmhNZXp2WlVOREJkT1BhVmtMSWY5M1NqU0VpK1hCMVFab1RVUU9WVU5JQ2ppR3RyNDIzcDgzRk9ZNThwVTJPY3o4Nzk2K3VieEs3ekxqY0ovNzBJSjJzRDA4YjAvSFpQNlNBN1hzaVMrSzNYR1JWbG1uazV5QnU3L3hLbUhYVjU3M2p4WWhDNXAxVUhTRW9Fb1NLQ1pQOTJLTVprcDE3aUV2QzhuM3lPZUxkOVRNRkFiL0lYa1FxekNOcGpHeTBoVDUzWTdZODg3L2hMSTBxS0RkYmVPWm1uZlFSTzhMWXFoM3BiM21OUXR0REtBQVJqQlJpQm14QTdLTHF3TzdlYWV4dmR5anozY0RuTnhZVkQ3UEpGQTZBRWF2ZUl5WnJDblNheWRqbDliMHNUdENpdVVmV1ZqQTU4UmJ2S2drUzRVN29XN2NTZ3NuZm1QZlF4RmhaaThacVRwZ0t4ODBQT1pqcWl2c1c3SGRNOEN2bDdWUVVlRGduS0NnS2tMaEdZM1V1eVRiZHFHdE1qVGF1TTB5bzltSDlJVWoxZy9qOGtYNjM5MWVnRnVxOStrZHRic3QrWXFTZTBIczg5U0JmeFBqaldsenowb0lxMy9VVGh3U0JqZzR5bldoeHdwRXYvSThMUzlodU9MbG1QVzdPRm1oM3lzRXJHUkVEM1RUQ0xkc283ankzejBVaVF2Mk5RbGUxazlUUXRZN3hGbEloeVlLekZzUTlyU1Zid29GeC90QTJYZlBLeCtySTFSQVpGN2hOaDc1RlJ1c1oxdlpITlh2clpOcVdKWFUrVWx2T3VBRDBlbXR2Szg5Nnl1S1RCM3lRKzRaZ3BsdEF0OHJ6NWZadHdURDNObWtuVnZLWlVJRHFpcUFQQXhkMXFQMUl1QUtidVpweXV3WVBFYlF1QkV5eUxpRlUzeHE5aWk4NFdWQlJ2WVAyQ21KdTZTSTJnWk9veVUzVWNCS0x4TVdYYlJTSENVMTRWMzhCbmR0Ly9KNUthYU13cUFxbklyUHY5VjRUeUFCSXBEdkFldTRFcnh4a3JqTEVmUkVJaTBLb0o1cHdHNzJYWS9Wc0RYNGwzcVNaMzdteWs2bmd3ZHUvaXVxaW53ZHJWWHV2QnRjeTNVa1c1dUxqSzhQaXBCYjUrZVZlUVhIS1JJNmF3ZUpaUVBVY04zdEFoeUkwNVIyNUd4WTJ4eW9OWXFrd3pSZmxBeUF3NURrTGdXekFCV3VMc2M4WS9kc1ZjeUtteFpFQWRUM0ZWNTlzNWJWYzBRQ0pTR2UyVmwvRFgzc1FhV1VMcDVzWlF2eGp6MkxhamJxTkhnYndIcFowM3p2MWpoTjE5S2V6dXRrK2ZwNC9pYTBwS20zcjMrTmtaaGxCWWROZUFab2UraDhTNHFFSDBmaWVQM01qVDd1N0ZiWEc3T1NEV21uZkJtc3BoTU9paW1Iai8vY2UyRG5McERWSlpVZTVXVFVSV2xZdkowV3lVRE1hK05GM3l3QkNXN0pCVGluNkdyQ2prWjNCRHlEK3ZZc01GYWZkVXE0S3pMZGNKNkEzOXJ4RFZITG5rYkNnaGhYNjNWK0hnUGxqQWVsdVczWFM4Y2x5QzBZWmdReHZlcHhyVzBJczB6WU5Ja2U1d1dtY0VVbThWejRUK2I2QmhJMnc5cm11S3ByYkFaemVMcGtuV2lTOW9TdUN6Tjhrc2M0UDY3UDVZRlh0NGtPTHlmZUIxeVVhZXlPb0Vxak1PVVRlUEk2N1o5ajhVMGRJUDJDVE9keWh1L2FROXV1UlF3NHZTZHhyZ0MrVEpMQTRxYzNsdnpvb0tTZVZQcmdlRGNFcXV4VzhlaDRSNWdLLzdKazkvZTlLYk9COCtNQkR3ZktHMkxyL002U1h0Um5KRU9MWnVha1BqN2F2MTV0QkU0a0p3YUxPT1ZTUlh3ZHErYkFTODY4SjV0clV3N1FHM2p6VWNiTzJVMnY0Rk5iYVhVL3lPS0tYcXRFS1hJWTBRYllOU2pjdVZXRVgwMi83NVVRQ3d0OVZHZFNuaXhjRlpzL0NhSmJHNFdOL1VPWENpM0swLzQ0emMvNmk0ZEFjWENWTG1WcW9mcVNNNUtFQmt2M1QwZ3grazNPOXZxVEY1bnZhcEI2ajVrdHRKbGdScWdTemVCRXlJMHJleG9sc1R2NkEwb0g1ajFUcWI2TlBUY3ZLVEVsWDNrYzlZNzVaM3R5akgrR3d1RzR2OW1SSlIzMWw0V21KU0pEekFyMmxWbEt6WTVycXUrQ3QxSm1Idnh3TERndktqSWpmQ2NSMWQ3RjdwTzdtYzJjWjFmRzZBSk56czYwSHIwOW4rWkZqUUZmQ1ZGamVWU0ZzclRLUEFwV3dTaXNrT3ZhOG1hZHMxQVB0TEZvQlE1MXdKd0tiOUFzbmVCMXFRYjZ5cjl1eVprLzhIRTliQi9VaFp2RDlKUlcxU2ZCVUtRQyIsIm1hYyI6ImE5MDI0ODAwYTY1Y2RlYTQzNjEzYWE4MTFkZGFhZmNiN2Q0M2YxOGVlYjkwMzViNDgxZjJhYjFiYTNhNzIzZjEiLCJ0YWciOiIifQ==', '2026-07-21 09:06:04', 'eyJpdiI6IkpZcjVTemM5djlPQks0bmk0S3VrMGc9PSIsInZhbHVlIjoiUUVpOU15dXg5SHhLbkhYNFRUWXVDOUY3OVhlTmN3dDJJc0YyM0YwQzh1a1pNbnpwNk1VSkNlWWVDSTFNZUY0RUhIRjN6OUdsUXl6SU5Ta0M2bWZRenZGYmhicGM5c0owazBYS25pV3hNMlB5R0JmMC90QTRtTm11ZW5rdFh3Q3IyV2pEOHpYY0QwOWc4Tng5WjNTVzVBPT0iLCJtYWMiOiJhNmI4Y2NlYjVlOTg0Y2ZhNWVmMGQ4YzJjYjkyMzJhYWYwMTVlMzMzMGNkZTAxNzFiZDgyMjJjODNhNWU3ODI3IiwidGFnIjoiIn0=', '2028-01-19 19:06:04', '6238386000', '6238387000', '6238388000', 'main-warehouse-16', '1', '1', 'Admin', '2026-07-21 07:06:05', '2026-07-21 07:06:51');

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
(4, 4, 4, 'qwertyuiop', '11234516010', '110589777446', '20349', 'NEW', 'synced', NULL, '2026-07-21 02:39:13', 'Admin', '2026-07-21 02:38:56', '2026-07-21 02:39:13'),
(8, 4, 5, 'qwertyuiop', '11234516010', '110589777446', '20349', 'NEW', 'synced', NULL, '2026-07-21 02:54:42', 'eBay Import', '2026-07-21 02:54:42', '2026-07-21 02:54:42'),
(33, 25, 5, 'oqwier', '11211767010', '110589735254', '20349', 'NEW', 'synced', NULL, '2026-07-21 07:09:50', 'eBay Import', '2026-07-21 07:09:50', '2026-07-21 07:09:50'),
(34, 26, 5, '179', '11211762010', '110589735248', '20349', 'NEW', 'synced', NULL, '2026-07-21 07:09:51', 'eBay Import', '2026-07-21 07:09:51', '2026-07-21 07:09:51'),
(35, 27, 5, '65', '11211756010', '110589735241', '20349', 'NEW', 'synced', NULL, '2026-07-21 07:09:53', 'eBay Import', '2026-07-21 07:09:53', '2026-07-21 07:09:53'),
(36, 28, 16, 'listingtest', '11347646010', '110589986300', '259144', 'NEW', 'synced', NULL, '2026-07-21 07:22:10', 'eBay Import', '2026-07-21 07:22:10', '2026-07-21 07:22:10'),
(37, 29, 4, 'oqwier', '11211767010', '110589735254', '20349', 'NEW', 'synced', NULL, '2026-07-21 07:24:03', 'eBay Import', '2026-07-21 07:24:03', '2026-07-21 07:24:03'),
(38, 30, 4, '179', '11211762010', '110589735248', '20349', 'NEW', 'synced', NULL, '2026-07-21 07:24:04', 'eBay Import', '2026-07-21 07:24:04', '2026-07-21 07:24:04'),
(39, 31, 4, '65', '11211756010', '110589735241', '20349', 'NEW', 'synced', NULL, '2026-07-21 07:24:05', 'eBay Import', '2026-07-21 07:24:05', '2026-07-21 07:24:05');

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
(1, 4, 100.00, '1', '1', 'Admin', '2026-07-04 06:10:26', '2026-07-04 06:10:26'),
(2, 5, 200.00, '1', '1', 'Admin', '2026-07-04 06:10:36', '2026-07-04 06:10:36'),
(3, 4, 20.00, '1', '1', 'Admin', '2026-07-04 06:11:05', '2026-07-04 06:11:05'),
(4, 5, 30.00, '1', '1', 'Admin', '2026-07-04 06:11:14', '2026-07-04 06:11:14'),
(5, 4, 120.00, '1', '1', 'Admin', '2026-07-04 06:57:32', '2026-07-04 06:57:32'),
(84, 109, 10.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(85, 110, 40.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(86, 111, 10.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(87, 112, 30.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(88, 113, 20.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(89, 113, 40.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(90, 114, 20.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(91, 114, 50.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(92, 114, 10.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(93, 115, 6.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(94, 116, 6.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(95, 116, 6.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(96, 117, 4.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(97, 118, 20.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(98, 119, 50.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(99, 120, 30.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(100, 120, 6.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(101, 120, 6.00, '1', '1', 'Admin', '2026-07-28 04:11:24', '2026-07-28 04:11:24'),
(142, 161, 10.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(143, 162, 40.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(144, 163, 10.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(145, 164, 30.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(146, 165, 20.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(147, 165, 40.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(148, 166, 20.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(149, 166, 50.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(150, 166, 10.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(151, 167, 6.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(152, 168, 6.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(153, 168, 6.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(154, 169, 4.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(155, 170, 20.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(156, 171, 50.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(157, 172, 30.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(158, 172, 6.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(159, 172, 6.00, '1', '1', 'Admin', '2026-07-28 05:01:22', '2026-07-28 05:01:22'),
(160, 173, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(161, 174, 40.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(162, 175, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(163, 176, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(164, 177, 20.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(165, 177, 40.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(166, 178, 20.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(167, 178, 50.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(168, 178, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(169, 179, 6.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(170, 180, 6.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(171, 180, 6.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(172, 181, 4.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(173, 182, 20.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(174, 183, 50.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(175, 184, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(176, 184, 6.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(177, 184, 6.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(178, 185, 6.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(179, 185, 4.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(180, 186, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(181, 186, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(182, 187, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(183, 188, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(184, 189, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(185, 189, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(186, 190, 20.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(187, 190, 6.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(188, 191, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(189, 191, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(190, 192, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(191, 193, 4.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(192, 194, 4.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(193, 194, 4.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(194, 195, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(195, 187, 40.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(196, 188, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(197, 196, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(198, 196, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(199, 197, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(200, 198, 70.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(201, 199, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(202, 200, 10.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(203, 173, 40.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(204, 173, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10');

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
(17, '2026_07_04_000001_add_warranty_to_products_table', 2),
(18, '2026_07_04_000002_add_returned_qty_to_sale_items_table', 3),
(19, '2026_07_07_000001_add_ebay_order_columns_to_sales_table', 4),
(20, '2026_07_08_000001_add_ebay_return_id_to_sale_returns_table', 5),
(21, '2026_07_30_000001_create_subcategories_table', 6),
(25, '2026_07_30_102136_add_col_in_prod', 7);

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
(1, 'view roles', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(2, 'create roles', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(3, 'edit roles', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(4, 'delete roles', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(5, 'view users', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(6, 'create users', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(7, 'edit users', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(8, 'delete users', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(9, 'view customers', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(10, 'create customers', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(11, 'edit customers', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(12, 'delete customers', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(13, 'view sales', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(14, 'create sales', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(15, 'delete sales', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(16, 'view returns', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(17, 'create returns', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(18, 'delete returns', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(19, 'view categories', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(20, 'create categories', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(21, 'edit categories', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(22, 'delete categories', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(23, 'view products', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(24, 'create products', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(25, 'edit products', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(26, 'delete products', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(27, 'view inventories', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(28, 'create inventories', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(29, 'edit company settings', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(30, 'edit smtp settings', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(31, 'view ebay stores', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(32, 'create ebay stores', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(33, 'edit ebay stores', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(34, 'delete ebay stores', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(35, 'sync ebay products', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54'),
(36, 'view subcategories', 'web', '2026-07-30 00:47:48', '2026-07-30 00:47:48'),
(37, 'create subcategories', 'web', '2026-07-30 00:47:49', '2026-07-30 00:47:49'),
(38, 'edit subcategories', 'web', '2026-07-30 00:47:49', '2026-07-30 00:47:49'),
(39, 'delete subcategories', 'web', '2026-07-30 00:47:49', '2026-07-30 00:47:49');

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
  `warranty_months` tinyint(3) UNSIGNED DEFAULT NULL,
  `warranty_expiry_date` date DEFAULT NULL,
  `total_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sold_qty` decimal(10,2) NOT NULL DEFAULT 0.00,
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

INSERT INTO `products` (`id`, `name`, `sku`, `variant`, `description`, `image`, `cost_price`, `selling_price`, `size`, `warranty_months`, `warranty_expiry_date`, `total_qty`, `sold_qty`, `category_id`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`, `subcategory_id`) VALUES
(4, 'prod 1', 'qwertyuiop', NULL, 'Rem officiis aliqua', NULL, 15.00, 20.00, 'Rerum ut tempore au', 12, '2027-07-04', 240.00, 141.00, 2, '1', '1', 'Admin', '2026-07-04 06:09:47', '2026-07-08 04:49:40', NULL),
(5, 'prod 2', '532', NULL, 'Lorem sit id qui ips', NULL, 20.00, 30.00, 'Voluptas libero volu', 5, '2026-12-04', 230.00, 10.00, 2, '1', '1', 'Admin', '2026-07-04 06:10:14', '2026-07-04 06:11:47', NULL),
(28, 'listing test', 'listingtest', NULL, 'this it listing test description', NULL, NULL, 200.00, '2134', NULL, NULL, 100.00, 0.00, 3, '1', '1', 'eBay Import', '2026-07-21 07:22:10', '2026-07-21 07:22:10', NULL),
(29, 'Tanner Kim', 'oqwier', NULL, 'Aut est pariatur Te', NULL, NULL, 231.00, 'Dicta ea delectus p', NULL, NULL, 1.00, 0.00, 3, '1', '1', 'eBay Import', '2026-07-21 07:24:03', '2026-07-21 07:24:03', NULL),
(30, 'test prod', '179', NULL, 'Veniam quae aliquam', NULL, NULL, 120.00, 'Sunt asperiores quo', NULL, NULL, 196.00, 10.00, 3, '1', '1', 'eBay Import', '2026-07-21 07:24:04', '2026-07-29 02:45:35', NULL),
(31, 'Shoshana Clark', '65', NULL, 'Velit ad proident n', NULL, NULL, 552.00, 'Proident recusandae', NULL, NULL, 300.00, 0.00, 3, '1', '1', 'eBay Import', '2026-07-21 07:24:05', '2026-07-21 07:24:05', NULL),
(173, 'BMW    1    Series\n2012 ~ 2016 XENON', 'F20', NULL, NULL, NULL, NULL, 800.00, NULL, NULL, NULL, 80.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:11', NULL),
(174, 'BMW    2    Series\n2017 ~ 2021 LED', 'F22', NULL, NULL, NULL, NULL, 1050.00, NULL, NULL, NULL, 40.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(175, 'BMW    3    Series\n2013 ~ 2015 XENON', 'F35', NULL, NULL, NULL, NULL, 550.00, NULL, NULL, NULL, 10.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(176, 'BMW    3    Series\n2019 ~ 2022 LED', 'G20', NULL, NULL, NULL, NULL, 800.00, NULL, NULL, NULL, 30.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(177, 'BMW    4    Series\n2013 ~ 2016 XENON', 'F32', NULL, NULL, NULL, NULL, 1200.00, NULL, NULL, NULL, 60.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(178, 'BMW    5    Series\n2011 ~ 2013 XENON \nWITH AFS', 'F10', NULL, NULL, NULL, NULL, 1200.00, NULL, NULL, NULL, 80.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(179, 'BMW    5    Series\n2018 ~ 2020 LED', 'G30/G38', NULL, NULL, NULL, NULL, 950.00, NULL, NULL, NULL, 6.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(180, 'BMW    5    Series GT\n2010 ~ 2015 XENON', 'F07', NULL, NULL, NULL, NULL, 800.00, NULL, NULL, NULL, 12.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(181, 'BMW    X2    \n2019 LED', 'F39', NULL, NULL, NULL, NULL, 1250.00, NULL, NULL, NULL, 4.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(182, 'BMW    X2    \n2014-2017 XENON', 'F25', NULL, NULL, NULL, NULL, 750.00, NULL, NULL, NULL, 20.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(183, 'Mercedes  A  Class\n2012 ~ 2018 LED\n(Single)', 'W176', NULL, NULL, NULL, NULL, 1450.00, NULL, NULL, NULL, 50.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(184, 'Mercedes  A  Class\n2019 ~ 2022 LED', 'W177', NULL, NULL, NULL, NULL, 750.00, NULL, NULL, NULL, 42.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(185, 'Mercedes  B  Class\n2016 ~ 2019 LED', 'W246', NULL, NULL, NULL, NULL, 1400.00, NULL, NULL, NULL, 10.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(186, 'Mercedes  C   Class\n2014 ~ 2018 LED\n(Single)', 'W205', NULL, NULL, NULL, NULL, 650.00, NULL, NULL, NULL, 40.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(187, 'Mercedes  E  Class\n2017 ~ 2020 High\nPerformance\n(Single)', 'W213', NULL, NULL, NULL, NULL, 650.00, NULL, NULL, NULL, 70.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(188, 'Mercedes  S  Class\n2014 ~ 2017 LED', 'W222', NULL, NULL, NULL, NULL, 1300.00, NULL, NULL, NULL, 40.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(189, 'Mercedes  CLA117\n2016 ~ 2019 LED', 'W117', NULL, NULL, NULL, NULL, 1200.00, NULL, NULL, NULL, 60.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(190, 'Mercedes  CLA118\n2019 ~ 2023 LED\n(Single)', 'W118', NULL, NULL, NULL, NULL, 1200.00, NULL, NULL, NULL, 26.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(191, 'Mercedes  GLA156\n2017 ~ 2019  LED', 'W156', NULL, NULL, NULL, NULL, 900.00, NULL, NULL, NULL, 40.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(192, 'Mercedes  GLC253\n2016 ~ 2019  LED', 'W253', NULL, NULL, NULL, NULL, 750.00, NULL, NULL, NULL, 10.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(193, 'Mercedes  GLS166\n2016 ~ 2019  LED', 'GLS166', NULL, NULL, NULL, NULL, 2050.00, NULL, NULL, NULL, 4.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(194, 'Mercedes  GLB247\n2020 ~ 2023  LED', 'GLB', NULL, NULL, NULL, NULL, 1000.00, NULL, NULL, NULL, 8.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(195, 'Audi Q3 8U\n2016-2018 LED', '8U', NULL, NULL, NULL, NULL, 1150.00, NULL, NULL, NULL, 10.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(196, 'BMW X1\n2015-2018 Xenon', 'F48', NULL, NULL, NULL, NULL, 800.00, NULL, NULL, NULL, 20.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(197, 'Audi A32013~2016\nXenon', 'A3 S3', NULL, NULL, NULL, NULL, 650.00, NULL, NULL, NULL, 30.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(198, 'Audi A3\n2016~2020 LED', 'A3 S3 8V', NULL, NULL, NULL, NULL, 1000.00, NULL, NULL, NULL, 70.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(199, 'Audi A4\n2015-2019 LED\n-', 'A4 B9', NULL, NULL, NULL, NULL, 700.00, NULL, NULL, NULL, 30.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(200, 'Audi Q3\n2015 -2018 \nBI-Xenon', 'Q3 8U', NULL, NULL, NULL, NULL, 850.00, NULL, NULL, NULL, 10.00, 0.00, 6, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10', NULL),
(205, 'sub cat prod', 'oiwej', NULL, 'qoiwjef', '[\"uploads\\/products\\/product_6a6b28661be74.jpg\"]', 100.00, 120.00, '123', 3, '2026-10-30', 0.00, 0.00, 1, '0', '0', 'Admin', '2026-07-30 05:33:10', '2026-07-30 06:07:35', NULL);

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
(1, 'Admin', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54');

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
(39, 1);

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
(1, 2, 'sale1', NULL, NULL, '2026-07-04', 0.00, 360.00, '1', '1', NULL, '2026-07-04 06:11:47', '2026-07-04 06:45:25'),
(3, 4, 'EBAY-02-00000-92110', '02-00000-92110', NULL, '2026-07-07', 0.00, 40.00, '1', '1', 'eBay Sync', '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(4, 4, 'EBAY-02-00000-92107', '02-00000-92107', NULL, '2026-07-07', 0.00, 40.00, '1', '1', 'eBay Sync', '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(5, 4, 'EBAY-02-00000-92067', '02-00000-92067', NULL, '2026-07-07', 0.00, 20.00, '1', '1', 'eBay Sync', '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(6, 4, 'EBAY-02-00000-92117', '02-00000-92117', NULL, '2026-07-07', 0.00, 20.00, '1', '1', 'eBay Sync', '2026-07-07 07:55:44', '2026-07-07 07:55:44'),
(7, 4, 'EBAY-02-00000-92158', '02-00000-92158', NULL, '2026-07-07', 0.00, 80.00, '1', '1', 'eBay Sync', '2026-07-07 08:06:27', '2026-07-07 08:06:27'),
(8, 4, 'EBAY-02-00000-92869', '02-00000-92869', NULL, '2026-07-08', 0.00, 100.00, '1', '1', 'eBay Sync', '2026-07-08 02:40:32', '2026-07-08 02:40:32'),
(9, 4, 'EBAY-02-00000-92963', '02-00000-92963', NULL, '2026-07-08', 0.00, 120.00, '1', '1', 'eBay Sync', '2026-07-08 04:49:40', '2026-07-08 04:49:40'),
(10, 1, 'qwedwqedqwd', NULL, NULL, '2026-07-29', 10.00, 1190.00, '1', '1', 'Admin', '2026-07-29 02:45:35', '2026-07-29 02:45:35');

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
(1, 1, 4, 20.00, 17.00, 20.00, 400.00, '1', '1', NULL, '2026-07-04 06:11:47', '2026-07-04 06:45:25'),
(2, 1, 5, 10.00, 0.00, 30.00, 300.00, '1', '1', NULL, '2026-07-04 06:11:47', '2026-07-04 06:11:47'),
(4, 3, 4, 2.00, 0.00, 20.00, 40.00, '1', '1', NULL, '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(5, 4, 4, 2.00, 0.00, 20.00, 40.00, '1', '1', NULL, '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(6, 5, 4, 1.00, 0.00, 20.00, 20.00, '1', '1', NULL, '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(7, 6, 4, 1.00, 0.00, 20.00, 20.00, '1', '1', NULL, '2026-07-07 07:55:45', '2026-07-07 07:55:45'),
(8, 7, 4, 4.00, 0.00, 20.00, 80.00, '1', '1', NULL, '2026-07-07 08:06:27', '2026-07-07 08:06:27'),
(9, 8, 4, 5.00, 0.00, 20.00, 100.00, '1', '1', NULL, '2026-07-08 02:40:32', '2026-07-08 02:40:32'),
(10, 9, 4, 6.00, 0.00, 20.00, 120.00, '1', '1', NULL, '2026-07-08 04:49:40', '2026-07-08 04:49:40'),
(11, 10, 30, 10.00, 0.00, 120.00, 1200.00, '1', '1', NULL, '2026-07-29 02:45:35', '2026-07-29 02:45:35');

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

--
-- Dumping data for table `sale_returns`
--

INSERT INTO `sale_returns` (`id`, `sale_id`, `return_date`, `ebay_return_id`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-07-04', NULL, '1', '1', 'Admin', '2026-07-04 06:12:45', '2026-07-04 06:12:45'),
(3, 1, '2026-07-04', NULL, '1', '1', 'Admin', '2026-07-04 06:42:30', '2026-07-04 06:42:30'),
(4, 1, '2026-07-04', NULL, '1', '1', 'Admin', '2026-07-04 06:45:25', '2026-07-04 06:45:25');

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

--
-- Dumping data for table `sale_return_items`
--

INSERT INTO `sale_return_items` (`id`, `sale_return_id`, `sale_item_id`, `product_id`, `quantity`, `condition`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 10.00, 'good', '1', '1', NULL, '2026-07-04 06:12:45', '2026-07-04 06:12:45'),
(3, 3, 1, 4, 2.00, 'good', '1', '1', NULL, '2026-07-04 06:42:30', '2026-07-04 06:42:30'),
(4, 4, 1, 4, 5.00, 'good', '1', '1', NULL, '2026-07-04 06:45:25', '2026-07-04 06:45:25');

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
('4PGDUCR6FiKHn2wJvLVE2mYbK3qd1POwUzCJQr24', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'eyJfdG9rZW4iOiJZZ3V2NXZFaGNkVVdJRWhBd1RMTjlHenphVVViOXY4b0gzU3pFWTNqIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvYXRvemNvZGVyLTAwMjU0LnRlc3RcL3N1YmNhdGVnb3JpZXMiLCJyb3V0ZSI6InN1YmNhdGVnb3JpZXMuaW5kZXgifSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1785417352),
('mSsXWFcbq0Wa8ysatj4LfWhT2mdCxWa0Hov7qjMz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'eyJfdG9rZW4iOiJ6THNyVDY3R1RhRUZkenN2aTVIUFp0SFBBQ3VPVVg2SWhESnhwR0czIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvYXRvemNvZGVyLTAwMjU0LnRlc3RcL3Byb2R1Y3RzXC9jcmVhdGUifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2F0b3pjb2Rlci0wMDI1NC50ZXN0XC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1785407984);

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
(1, 'abc-1', 1, '1', '1', 'Admin', '2026-07-30 01:04:51', '2026-07-30 01:04:51');

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
(1, 'Admin', 'testsoftware@gmail.com', 'testsoftware', '1234567890', NULL, '$2y$12$bw06IkHzb0ZnQRKW1rF0tOe5P92R332YPaedme6YEJlaFjjhbHrBC', '1', '1', 'Admin', NULL, '2026-07-03 05:58:55', '2026-07-03 05:58:55');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ebay_accounts`
--
ALTER TABLE `ebay_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ebay_listings`
--
ALTER TABLE `ebay_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

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
