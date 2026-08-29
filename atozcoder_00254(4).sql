-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2026 at 12:22 PM
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
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:39:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"create customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"edit customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"delete customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"create sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"delete sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"view returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"create returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"delete returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:15:\"view categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"create categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:15:\"edit categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:17:\"delete categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:18:\"view subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:20:\"create subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:18:\"edit subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:20:\"delete subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:16:\"view inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"create inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:21:\"edit company settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"edit smtp settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:16:\"view ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"create ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:16:\"edit ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"delete ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:18:\"sync ebay products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}', 1788084920);

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
(1, 'new', 'testuser_buyer1122121', 'EBAY_US', 'eyJpdiI6IjZyTUEvdFRnUVhlbHdyUE5rYS9BdVE9PSIsInZhbHVlIjoieHNYbENzWXVra1crM1ZZREZRTnVhOU1tS3FkUDk0TDRyYkN2YXUrVWRlNk9hcjdMS002YVVpZjgzZlZpU1dvVjRkN1BDeUw0ZDdRTzFUR0dMZTkrdEUvbmY2cnRya3d6Zk5mT0FwUkV0K0F4dmFMbFBuRTQ3c1hIVDI0NFZMRVpLMFh2S1I1WXVXUHJFcVpLbDZoUUJXZk5jRlZRYzJNdW44MjhtdCs1T3RsWkVOMFVDeXIyeE1TYzdLYVBmOVphaTZwTEtTc1BUNkphWm4xd0VzTTdmTEVuZER1UmFHN1psU2hpYTNiK0hNdzUwOUFHMkI5SWdMUmVEU2h0ZU9ya2hMSTYrRE9xWTZQWTNmN0VxdEhQSkdYQk0vaUtLMG5NbEg1N1RiZ2Y0L01qN0paa0syVnY2V3B5WGErYWNPSllMWmhQbm0reEtQQThzKy9XTGkzUmFuZk56WHRtYmRhaEhPOGhXRkE1V0k2Tjh2Rkc0ZDNsQlRuQTl3NHlGdk9qYThvT2JpOWllRjBXZ2M0VG5aZFNFNTIvb0pxcFMzN2llRUFiMGNTa0FCVGkwWndUQWRqMkxhOW16MUZ0YUZLNE1nQXNySWw5RkFld1BJMjZkTkl1Z3lZRGpja1p2NjlSdWJEVFg3Sk1xZWtvQUVmYUNrRUpvYStGOEJqNHkrOGI5WlhIRkk5aDh1MTVGZEpvdmxObkhtUStRbjBjZzg0d25Pamp6YnFRdlkvbUVtdHVyZlhPdlh4WDNHa3BnUHB6SkRENzlEY0tHQVVFSVowd2F6VWJMUEN3bTE0OEV3NDNWcHJXV2dQaGsxNk9mSFNwaEptOXVyUkNwTXM5eFBkMnhmMjNGcHc0TXZtNnk1VE9WTDN6N3VMN245ZFBxVjRKK0RDZE1ic05MMk5kd2FaUnFFd1hQZ05qMHlvV0NJUUF1S2h1UGlnbXZjZWpRWDMzdU92ODQ3V29GSm1KL245QUZEL1gwcUcrVGVzQmQzRXVkRDZwZktYRXVXblhhcVk2NWJidUZFWGgvNzZUaHBIRU1ESDl0YmF5VXFyMHdDS3BEQnVTMVlyM1gxUzUyd0E4Z2NNTzFtY3BRZkhGVkVLdFdRNlRuUXJ3RUVxVHgvTThZL0xBUnZyWi9Edzg1aTBCZTRGWTh1SDRHQVQ2YWMrMmpEUDEzcTJkY3V0dWN5dnpDQ0U3R3NzQVJJeUh1OXdSbWRzL0hYWDJtSS9KQVduTHo4eW1sK0JjRUdxTUlVUUs2a3VPU2VkUnhtMHlyOHB2Y2VKTXNPNi9tZ3hsWUdPeVV0bGFSbm1RQkg4bkhQc3BISkJ4SSt2Qit5KzUzVVlKS1lRdGRBWUdFOSt4YmlJMHRsdUdOYkpJT0w0NTJqNmQ0eVlNTXBBN0lyK1RSWFJwaTFCWXVId1dDaHEvNytJSk9YUjBPNGRQNkVwQ0NwekF2YXZyMzI5TVZ2MjA0Q3lnUWR4bXhyNXpIeTlONjR0Uk9YWHk1SnhrNEs4bkd1T1FHR0lLd1ZrSkpLUnV1M2VHaHNvUWRWRjF1VnVJdHNESDRQSFdQNFpiSkVkVlRZd2F6WkwyRElkWGhHRDhaMTU1c0dCVVNYS2NJcmIraVdDVTNNT2VwdE03L2ZRS1o3c0U0QUhzTlhHaWx3djk3RU1qelhVTUtWUDZnSG5PSG9waWpYbFVRWmx4Q2huZHJVc3JZWHVMV052eE5GYWlaSktxZFZ3L2h3cFpUcC9rMnVQOXg0UjhySzRtWmNCeVBVdmZmUktFUThXMlRPVTNRQngvS2ZaeGFiYVQ5Tm50U00wRlVCa2Y1VVd1c2hqeVh0VStsRXIyTGxNdUkyZTJLUmdINHhZYzBTTU9LMEpNaXAvckduZHlBOXo1MmowcVZJU2IzU3FGbUxxSVZuOEJEV1VqQnlTc3VBN0pZcFRaK1RsV3lDWDltb3hIK29YMnJxRXVOL2hnVFRWMmdsUU0rNi9PTDJPbVQyS2N5WGVBRWJKVGhQQ09NY2VsZlY4WHduUGZSZVdTWW1QUGhQTUFpbTJ0SXh2SkdyNjB2QnlDN0JYV2dkOGw3djAvMXZEMUJtaktCQ0dhWmt2OU1iUXJ3TTJmOU1QNzAwNFVlVjdVeWVzRlNzNTN3dm9IbFU0NFlXUXdsUEZCRnhhWXV4TFoyWmxCTDR3Y2x6T0c3dVdtaXlJYzNiZXM1R2xDdXpiZ1FhbkpQUWJ1NW1mT2ZTRm9Fb0V0eE9JR3dOanVnM1pWejRsZUdmbGNsMHpvMTlEdGJRMjZQbE0zeHk1M2lNZk8yY3ZUWnZlNFBnWGNienBURExqQ3RhckdaTGd1VjQxQ2Rzb0Qxb1FxSmZyQVBXOXVDZUEyMGtJZjN3TEtXOWplckdqVE5kcVpkSE0yQ3NmdEExbVE5QVh0VjNaZVE5QTBGSnBrU1hjU3ZZditYQnJlNWlvS3E5Ym9WNEMvQUwwYkFWdXBIWFpoZXN4UXA5UTEzSjRJM1F4WXRXY1RWQzFwSXkrV20wOHRINHZ4enU2b3VuL3czZ1lVekZSU3J0TEJjVXdMSG1kdERPK1Q0NmY0ZUdMOUloVjUrajlHRDlXUFFKalBFVCtmVVFEYjhsbUFMemNZWW5ZVW1zNmE1NmJEK1VJQUdDaWp1WHZuU0o4NTdwQ1hHZDFpeExub2V2MGk2Wlk2UHVjZnExZ3lvdkIrWnJDRVgzNURaVm1DZWZESCtkR2xhd2N6TXJXWU1wblp4YzR3S0VnTG5WQnh0MzlhRUtSVk9LMUt4U1JLblRkMy92UWtoMllJNmcydVNjaHQwWENzWWtpMGZmQzVLOTRsY3l3YlVUTzVJYUtqeFBnUzM1U0V4NlFsTXM0SHJ5NHFjbDFVTzB2OEtWNnZaNUdicHR3V3l3My9nTnY3MHFOZzQvVll0UW9TbHZsVG1EZ3ZQVUxUMmZvclB0Z0JHMHZ5b1pRSE1hMEtrYzMyZldyNTU0ODBPK09XM3NaOW5EYzZzb05tNGJQejViS3RWRExNU1RuMkRxbWhXR0E5eXVDNjFudXg5bis0ZGkwaFplZ2lWRGdtSFF6YmhYYmtBWVdFS3FtYXRXSXdERTNHK1NIbENoRTdEMUU2Ync0ZXpzT1ByOWV1cmtvUUtRNlBjTDdqeXgzZFFudUpwMGhKUFBId0ZMUlA4ZkNiSDg1OXJJMXhKWjZWa1JEOURYbEhRRHdwU0prTm5qS3hpM3ZRZzRsVzhrczNEdWZwUXJyUVNsU3pydzIyNnpwTTNoVUZSSFhlRlJZK2hlRkR0WXQrbWpCZ0d5bWh6WFJyVU9UNVhNa1QvalVla1JPY2Yzd3Z0c2tBTlhYQ3ZYRE1BYXFKS2lWSDR5NVd3WHBtdjF3SjA2bHBjQmlYQXBCL2t4Qnd1NWl3TkdoYlg4bEhnWERwMjVTdTZkeFBhcmlJT0hNTGVrcHoyL1g0aVAxUm9qVS9DNEY5NlpXeFFXVnA5dktHeWJZeFd0dGdta1IvYjVYeHJLaDkyQjBQZVcwU1UzNmZaTHNJVDFIRDlkcU4zWDdYT1BJM0RrbHlKelE2ajgrbVlzVGZIZFJRc1ZMZkdGNUphUFF2UkUrSHdKT3l1ZWlJUzAwdU1yVm1qNEVPZnhjKy91NURpQjZYa1QzcHlkZ0E5RkZHb1o2VkVDRjA2WDRNLzhMQUhueVZRbmpJZ0loUkF6dFYrQTRTNkhScDNXRUZHaHlUWEE1RmZGZ0JVWFZZOE9oQ2hiRmszMUtIaVQ5MXZxOGN6VkV6REszRlgvUytQaXNReWdmZm9oR290VC9pcmNFQkZ1Z2g5amJiSVRmd1hXRHE1Q3VtNmR4M3JyclZYQ3hidXpxQzRMRkg1WlFJb0I1Y2xnSnN3dU9rSnJVUFZxc2VvemFPUG1mQW1XaDBVTW55ZTFpWHZYdzRVVWI0T3BYQ2hWVEkvSDIzemJjMXI1RUs0dElzZFJlV3FVSGFmblNhQnJ1RDBrc3VCL2NnVFRDNnBiQk9ucDNpMXkwY0RQejlkQnc4SVRYMldXTnFBWnQ2Y0pFQ1BpV0tpNjVHV3ZXR01KbmNlbEIvYVQrVmd0cXZ4Z01BcXZrdit2cFFkN3RhVHA1TzhubVovTnI0bHlIWUJ1Q01IRkRRZTZveVdPWjQ2T3ljazlwVURWdE5MbjhRMys0TTlwYTI3dGt6a1lKTk5oTW52UksvTnc9PSIsIm1hYyI6ImY1NDY0MjZiY2IyM2VmMjBlMTU1NmU2YjRiZTdhNWI5N2Y4MGU3YmU0NTI4NDM0OGQ3YTg0MDhlYzFhNTVjZjEiLCJ0YWciOiIifQ==', '2026-08-29 04:20:09', 'eyJpdiI6IjdSdktUb0luWVRnOVMyVFJRTlVZMnc9PSIsInZhbHVlIjoiYk8yYy96RHE1QytQU1dseThMSm8rNDBJcTJTWHcrWENOZ0dzSjJwUGJybkpKNGJzMHVTTkVKWmZlME9JcG0rVWRWQjd2SVd6c0x6cEhrdzNjTjlSUXN3QmRQRUZ3aDJjbUVjOEdzNzNTbnBvdlhFUWxHdWdhcnM2U0NFZ2hYb1BYWTl3RlZMRFVMTlZkOExuS3YxTVBRPT0iLCJtYWMiOiJhMGZmMGQ2NzhkMzcwZTUwYmQzMjA1MGQ1MjU4MmZlYWRmOGUyZjIzYTFmOGQwMzFlZjU3NzcyMWRhZWJjOTM5IiwidGFnIjoiIn0=', '2028-02-27 14:20:09', '6238383000', '6238384000', '6238385000', 'main-warehouse-16', '1', '1', 'Admin', '2026-08-29 02:20:11', '2026-08-29 02:20:16'),
(2, 'loop', 'testuser_zain123', 'EBAY_US', 'eyJpdiI6IkJnWlltSGdIZHBYZHVCQkZXaFd1N1E9PSIsInZhbHVlIjoiZXF0ZE5WL0dWeUFwZlFuc2U5U2VId2FoUXg0YW0rUm8wNklFRGo5Nm1Db3g2cER6TnBnb1E0VUhpQjM1TmVZNzY4R2xjVWZOMmFETnhGejZxTnM3OFdtTk9veG1BWHpZTmlIWkRqeVk3VkNBazV0b3ZLZzdLbWJVQ2pxckRzTHo0Q0xnV3k4SitXeUxrQjBJSmFzeXZpbHVGMHZ5dkhsV1RNa1NMUW42dEhWai9KSTFNNVRDdUdIcVBGaFZLL2lhMkZ6cEtlSEh6T0FUVmxwQ0xGb0duYnBXYUhrMmpMaS82RWErd3E2SG5UalR3bThXYmV5Q09xODR4Q0R5aEFBaUEwaVR4MERkL3gvWi9LVHBOTk8vUHNZN3NkaEdaTlgwK1gySGlDSU9qOHdsZFhuQnFCVnZhTVZzVHdoeVRYckFlMlF1VlJpRDVMeTJFWGR3blEyR25UY2dWRUNxMTladHlaZ29BTmtFMDBtd3pNUmIwbUsza2poN3Q3K2lncHgrUWdxdGRzbkRFSm1tRGNibTJPZ1BNb0NyOWlGV0s2bXp2bW1kMlNyMytQY01sTUVKN0ZiNDBKYWNnRmlFbVdlT21oUHFSY2ppbDYwSFQwc2E2REd0bzlkKzJnekNpYkcvKy8xemdJTlRTV3IySWk4OStTMFlCRVIyMW1rakFJeVJjYURSVjFUNUttakt0QUU4QUdsZHV4bDhaT2crVFd3ZGpucGg1QWpIcHB1OHJRY1Z1VjJjaEE4VURSeDdyRHhVZFMwSy8zSFJMTUhkNU94bWJQZXFLcHRzNVhqYnh4OGFjYWNncmFvV2NqZ2RoRkpSZ1VjVllLSzlLUUVIQVFmYzJGRk9HUWNWenM5Z2pva0J1WFJBWUNQbWNSeFhPdU1WYmpZTjA1SmE4V0dWSlhRU2o1V2RBOVluK29vQmk3OHFOOXJYL1RHM0hwYTFQUndTejlPMHVjUHhZMFB0WjdMS1pNblJTSGl6QmQvY0ZrUUhxU0JUcWxzTmdhMGxxQ3NPMkFtcUVscWZ5L05XdmpmUG1wSnpWVXpjT25vaGs0MTkzRzRFeWRvVVl3RkNlT0lsbzZvRzFuNkNpbGF5aCtnS0p2QUNuY0tDSE9lZWRiZmlkc3hDUHhiTXBQUE95aVMzMFVDVGRMVmo1M3ZadE9EbDk3WmtiNUpxeXREUnpWeXJzTlYrM25RWDFPaUZoTjdPVHlEaFJXWXRNV2pIY3cvd3VudWg1RjQwWjBrOExOVk9oZnEwdm9hQ00zZElmM2NaMkR4ZkIrQzRlZTBiR3dNQ0VFc1Q2c2pOS3JMRDdWeFJNQ0lLcThUeVd1M0dWblFLbWZybnQ1RlFGQ0RScE5jdjArbTRUeUNVOVc1WlJPUXhzU3dZdU1LMmgvOHlCOUs3RVV6YnRQR2tONG4zUmduNVZrNTIxMERPSlFoaTc1dXhkYW13VTl1RjAyN2x6UGJzNW5VaEJIeXQ2NXUzczJzRWFyNGhSY3pGR3ptTGlML2E3N016WGJRSERoZHR0S1M0YjBtSTVlak9PRG9oVmNSMlBIUHdpNU9BVkNzNlFMQVFYOUFhTTg3WEZFdDdOTGtydmpaZkhuL2FUR1N2VGpsMGRoOGJrWnQ3bWkvRTVoZXpEZUovOEMrZEQwRTNvYXZjQzRiS1E4TjNqa1VTMVdxOVpaM1JHRzY5MGo5Z0JqdkpNTXVwRTgramp4dWNwRi9Ec2NLQ2E5dEdIWHVjYi9xd3dTOEYrZjJkK09ZRnFUNzF0bDBsSXVjdnM4TFU3S3pEdlF6Y1hZYytNbDBmTmtyMEM5cERjV3RLWGtUbUJnZ3FpbHRBT1lsUkt5dG9wbWpQMTN0QzVleWlybmp4YVlEWnNwTzE1MlowYzZaaTF0UlI5eGdoV2lVdVlCTzl1RzVSQkVHNmFSN0kzK2NvOUZSK3ZpT0diMFZyVFVSTzNBeFQ4ODFsUDVCblRuOHBWVEpQY1o1djVFWU02YTUrN0dya1lxUkJ4NXd0NGk2VFVTZ3NwRDRPWnErUXpJN3V5NFF0bUMweDRwSG0ySk90ZHdtOWVmVzJVTHNsWWw4TCtBYmFpSjhSQksrYXpzWTFlc244T2pjR3owL2hKNy9EL1dGWll0emxVaTVVUW5yYjdVVHRmV2tyK2MwQTlzTk8yWWZPZTIwMkNZYkdmdVd4a0RBVVNrQnpXR3cxenZ1MGxaNHRmeUxYb1o1WWRoZFpYZlQ3eXBVWXZiaEpqWkZMNWtrQkFqU0QvbFBQNlkzaWorczdWNVk2UURtTlFMRWx1SmljNnJvOHVVRlhSVGlQSWhrT1RZWEx4WUJ2MEtHZEFFYk5ZU1FCSmFGU3N3SWRFc0FvL2pjZHQwTW5YQVpEWGcwNTkzSlFtenF1Tmp2amY3V1dXZmlPcXdXTm90QU5QVnBpeU9hb2Fhb2tOdXFnYWlWNVd5QjU4THJzejlGUDcvK1VZMjlCdEtsbkhMeThxQmdyNUQ5WmF2ZXl1OXByVmRYMEEzZFBhV2E3L0xnbnd1OE91bngyQUR6dmViVHdKM1hNS0tXVkY2b1hlVzNRN2YzUVpBOFdLNkxKaDliRzhqOGRnQ0NWVlIrTm8wQUR3Q1ZNMnpvbHZEN2N4YThkZlZlRmtqVldNTTFaaWZlUWlXMEdZakVuM3ovY0ZBNXZ2MExzd0ZMb2grVzZHTGVUQXMxcytLSWh3bkpYbzMydlpGMEo2WGFENEVBSWZVWmQ5RUFOSDQ1YTJXYW5iQXB6bE9rQmZUYVltUTJwWVhUNVN4UklVM3VIT3Z6aWpLMHhEOVNEOHlGcXA5RXI5ak55M0svR0ZyWUtTZjREeHdtN3RtZC9GekFHZzJQNWJGaU5zVWFrUlNKY25NMlJjaXU4QkQ2NHo4aGF6NkljYWppV2hCSUdlNFlrRmMyT3ArNEtpT0tvaVV5SnJFVzlrYmZDUTNMTWs0SktjSzkzNFVxOXp6b1pBcnpWbWR3ZDlzUHdOdmdmb2pOamJUVzQ4Q3lJOG5RdlFWbGoyQWFaUVlzKzZnS1ZxK1NxN2twd1AxTTRPUENRMmtFRVFiVWJXSW5Wd29vYWV5LzQyWHM2enR2VmlibjdRTFpkL0EvU0YremEyK3VlYjl6OThzT0xaTlphNE9TZEthaGpNT2dVVEo5c2I3ZGJZWVBkVW1ROVlqdm1RQ1ZFa3UwdWFMeEUzTjkwaG5FcU1iU0FTN0kycERwNUN5UWhBL1lXSVRIRG8yWU4vWDBYdy9LRE84YVJqc2RSMWE2RTE3b2pLRm1KalpySXRJWThLNnY0c2E2VDR4clNEMFZWK2pVQUZiT2dQUnNueFh6Y1JXZU9OVkNLY1ZMNjBUUEpsT21nMnprYU51bFAxME1WWW14OXpJYjBqbW9RSThnK2F3dHVKQUN3dVFLQ3VLalU2SjhaTGRyUVN5dmZkUzZHU1RGODZDTDQ4b01welRuTEhBMUNMeUp0bnNmeDlsU2Zpd2Q1bGc5aTVtYTh6OG0wSTVTQnBRY24wUVVmZHM5bU94TE80Smw2T0dSZUo5Zmsyb0lqYUEvV0ZyRmw3Zk5VNjRxTjFwRUhWdmNJOExJZmJnaVNtQjRaY3dzQlBwelorVUdpblB4ZkhBS2VjRFFqbnBXaWM2YzhNbm9qTHRUTlE3S2Y5QWJod3RVUnZPRlBiZzlOVlVpNW1JUGU5cmhwNmZaQWEzSzVPYktkQVJiUkorREhQVGd5NUFUR1MxWCtxWGhTbUpyZDkrc2h0WTExdmMyTlo4cHpEZ1hzU0ZHS3A0ci9RcTc2K0JZNzEvL1U2S1hxblRBRW5UT2d1dGxsOVhvTHFjYVdiV0l2ZWRFVy9qV2h4Q0QzLzVLZTJMKzhFOUJ3c3FzY01QN2hUWW9ZZFl2Y3lwOFRnNWh1allvZk1acFB1K0xRbHMwOXZ5eHVxY1dsdklYdVhLZW9BakxwcUpTb1QzeWdpNENEbWZuN0tTS3JSRktIamYwMTQzM0tmYnh0MWJ5ZHQ1L2FKWUx2ZldDSjZGdm1jcHJVZTdpaTNheHRSQXZpbkZrKzNHNzdISC8vL0RzSEhYd2dlcmZCdkxMS1ZHemM5UjlWdk9vZVlVYVFEck40MEZNOFZNZndERXBHZHBXQ2VjWT0iLCJtYWMiOiI4YTUwOGE3ZmIyNTVjYzlmYTZhY2NlYmJiNGRiZjY4MzI2MWE4MGI5YzdiY2QzZmNlZDdiMDIxNmNkMjM0Yjc5IiwidGFnIjoiIn0=', '2026-08-29 07:17:23', 'eyJpdiI6InoyeTEydWl2WllXcUY0TVErK0FXVXc9PSIsInZhbHVlIjoiT3dzS28zYUE3SmRvaW1xZStPZ0Exc2MrTTJkMjI2akM2SUFTbncvMXlqaDlWdVFiVmd3ckV2bm1IMDB3bERnT0JZTW5DRCt4RjRXZHZSWVltbUlON3F3Tjc2dmwzQ0hXbktmYzR4WERUSmM3cFUrUTZvNGY0emdyMXYyb283Y0hJSDhRc1dRTmtsYkUzOXBCTk1HYkF3PT0iLCJtYWMiOiIyNzNiNDNjZGNmMWExYTRhODY0MTc2YmRhYzI0Y2JjYjc4NDA5YTI4NjI5ZWRlNTcwYzBlOWY3NDc2NjYyMTIwIiwidGFnIjoiIn0=', '2028-02-27 14:55:41', '6234437000', '6234438000', '6234439000', 'main-warehouse-1', '1', '1', 'Admin', '2026-08-29 02:55:43', '2026-08-29 05:17:23');

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
(5, 5, 2, 'prodname', '11489113010', '110590436251', '20349', 'NEW', 'synced', NULL, '2026-08-29 05:18:15', 'eBay Import', '2026-08-29 05:18:15', '2026-08-29 05:18:15'),
(6, 6, 2, 'prodname2', '11489140010', '110590436276', '20349', 'NEW', 'synced', NULL, '2026-08-29 05:19:46', 'eBay Import', '2026-08-29 05:19:46', '2026-08-29 05:19:46');

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
(21, '2026_08_29_000001_create_ebay_import_items_table', 2);

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
(39, 'sync ebay products', 'web', '2026-08-28 09:12:33', '2026-08-28 09:12:33');

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
(5, 'prod name', 'prodname', NULL, 'this idesicnwon', NULL, NULL, 20.00, NULL, 30.00, 0.00, NULL, NULL, 2, '1', '1', 'eBay Import', '2026-08-29 05:18:15', '2026-08-29 05:18:15', NULL),
(6, 'prodname 2', 'prodname2', NULL, 'prodname 2', NULL, NULL, 30.00, NULL, 40.00, 0.00, NULL, NULL, 2, '1', '1', 'eBay Import', '2026-08-29 05:19:46', '2026-08-29 05:19:46', NULL);

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
('Eq52XayT6pIRgpK35fnnIQ1WYCI88uJdmtMRGpPU', 1, '103.253.18.30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0', 'eyJfdG9rZW4iOiJNOFEwZzRZNzFpc0JoYzlrUGhvTERVZE9vMm1LTkQwYkd6WVFVaEp6IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC8wMTNhLTEwMy0yNTMtMTgtMjAubmdyb2stZnJlZS5hcHBcL3Byb2R1Y3RzXC82Iiwicm91dGUiOiJwcm9kdWN0cy5zaG93In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1787998841);

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
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ebay_accounts`
--
ALTER TABLE `ebay_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ebay_import_items`
--
ALTER TABLE `ebay_import_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ebay_listings`
--
ALTER TABLE `ebay_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
