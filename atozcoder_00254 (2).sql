-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2026 at 03:11 PM
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
('laravel-cache-ebay.app_token', 's:1936:\"v^1.1#i^1#f^0#p^1#r^0#I^3#t^H4sIAAAAAAAA/+VYe2wURRjv9SUFWiOpItTIsRgj1t2bvcfe3YY7c20prZS2cGehVYL7mOWW7u1ed2fpAzSXQgAxhphg/EPFgvAHoqlESiKYkmAIIBqCBBJADQR8xGAiIcYgD53dHuVaCSA94yVeLtnMzDff/H6/7/tmZhekikueXlO35vdSxwP5fSmQync46AmgpLiosqwgf2pRHsgwcPSlnkgV9hb8NMvgEkqSXQCNpKYa0NmVUFSDtTtDhKmrrMYZssGqXAIaLBLYaGReA+umAJvUNaQJmkI462tChI93+4NB0cf4RY/f63PjXvWmz5gWIngh6BW4YMAjikAEbg6PG4YJ61UDcSoKEW7gZkjgx/8Y7Wa9QdYDKC/NtBHOFqgbsqZiEwoQYRsua8/VM7DeGSpnGFBH2AkRro/URpsi9TWzG2OzXBm+wmkdoohDpjGyVa2J0NnCKSa88zKGbc1GTUGAhkG4wkMrjHTKRm6CuQ/4ttQSVhj6JImRfHghyZ8VKWs1PcGhO+OwemSRlGxTFqpIRt13UxSrwS+DAkq3GrGL+hqn9ZhvcoosyVAPEbOrIq2R5mYiHNENhVMjPWQbhAlO7yCjVYtItzfoFXme4Unax4g84/Wm1xlyllZ51ELVmirKlmaGs1FDVRCDhqOl8WRIg42a1CY9IiELUKad96aEINhmxXQoiCaKq1ZYYQLr4LSbdw/A8GyEdJk3ERz2MHrAVihEcMmkLBKjB+1UTGdPlxEi4gglWZers7OT6vRQmr7U5QaAdi2a1xAV4lhHwrK1at22l+8+gZRtKgLEMw2ZRd1JjKULpyoGoC4lwl7G52GCad1HwgqP7v1bRwZn18iCyFaBCAGvx8/4GMgDX5DhQTYKJJzOUZeFA/JcN4nzsx2ipMIJkBRwnpkJqMsi6/FJbk9AgqTIBCXSG5QkkveJDElLEAIIebwRBv5HdXKvmR6Fgg5RdlI9W2levdDV3jMvOqcWLTfFeF0iUNkIGju0noV8fTQyx/THlY7kfLBgTgR0hu61GG5LvlqRsTIxvH7u1XqdZiAojoleVNCSsFlTZKE7twLs0cVmTkfdVWY3bkehouDHmKhGksn6LG3Y2SL5z/aK+6OdxXPqvzmjbsvKsPI2t1hZ8w3sgEvKlHUKUYJV61rCpXH4CmJ1L7FRj4m3jC+vOcVa0BJDbGVx6NZJ2XQpY7lA6dDQTB1fuKkm6xYW09qhig81pGuKAvUWeszlnEiYiOMVmGt1nYUEl7kcO3Fpf8DjdQd8fjAmXoJ9ni7JtS1pzDtxYa/DeQ/0F0BOSeQWdYNTRV7r+hdeGVwjv1+E8+wf3es4DHodB/IdDlANSLoSzCwueL6wYCJhyAhSaTiUzEmUIS9V8eu5Dql22J3kZD1/0mT9YmRjRa05sJ9EHy16K5Y3PuMrSt9i8Ojwd5SSAnpCxkcV8NitkSL6wcmlbgb4gZ/GQfaANjDj1mgh/UhhedXx0tWtv8aqzx8puzJh99yDB8oubQClw0YOR1EeDnneyk1bxJfOXBjYqBxvXf3ujbzS2oeXfNr3WyiwufKwXjblFHVsdfyhwYUdP6wr2rNjcBx99ZOm4r35TEl1xe73vetT+9Dec3P/nPbeiYbA6XLylbq1+uGrFTUr3hl48sjWr1I1m88d23n2Q3j9uzI+Pm3HoS8/aCKOKhe/ab924PFVJ1t6npl14dr2msutKzaWu6d/PP7N/kvCq9e296+dumvDtmc/R+B86CniQmzdZ8qhl1c1zj5zsVTd9VzdwLazMysPXv656sWKK99uCr4w7sc3rvdPf02e2XD69Z2npp8fbPwj72v3Mv8e8MsX5SfWbt0y2DHp+3L2rHly8Yyr5P5LR2+0HNu3cuLba+KRfnXKUEj/AkQrR1nfEgAA\";', 1783435171),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:35:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"create customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"edit customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"delete customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"create sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"delete sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"view returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"create returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"delete returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:15:\"view categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"create categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:15:\"edit categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:17:\"delete categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:16:\"view inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:18:\"create inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:21:\"edit company settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:18:\"edit smtp settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:16:\"view ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"create ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:16:\"edit ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"delete ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:18:\"sync ebay products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}', 1783507528);

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
(1, 'abc', '1', '1', 'Admin', '2026-07-04 03:20:01', '2026-07-04 03:20:01'),
(2, 'abc2', '1', '1', 'Admin', '2026-07-04 03:20:15', '2026-07-04 03:20:15');

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
(1, 'abc', 'abc@gmail.com', '121212', '121212', 'uh', 'images/company_images/1783076895_example-logo.jpg', NULL, '2026-07-03 06:08:15', '2026-07-03 06:08:15');

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
(2, 'Zain\'s Store', NULL, 'EBAY_US', 'eyJpdiI6Ik9yTDEwK1paQVJlYlhKendOd0FScXc9PSIsInZhbHVlIjoiakFjM3VMaE93ZEZXbTNvVkN0MTE1T2UvK0s4b2tLZEpad0N5aTJoSjBmWVBQNDdhaUV2cHZoUitHNkp2cTlpS1JVNzV3M1BTQ1Irajdoanh1QjQ2MUtxKy90S3ZLbDZaT1dzNjNaN0RuL1V0ZnZld005QVg5QUlnZ3ZKNk5ZdWpsb0ViT2I0ZXVLLzlhZEl2MmtVK3hDcC9FZjU2UTcza2NjbEVyS1dDMG5IM2NnU0hPVTJ3azh3WGdLTzZlOWJjK2lNR0toUS84dzN2UHZ0TSsyY01teWM3VWFYakpWUXE4cTdsRythR1pid3c4YVBEMElQSzBWWEZtY2RXQWpKM2Zob2tGb21FTGVDYW9rUUZTTDBzMGpXTHNCUTUxZnRBOS80a2N4amw1c1gwTHFEajE3RlAyWit6WnVLd1lZdkJhZGN4aFgzZTF3TGVmZ0N2Z0pobGVkeXV1QzZQU3RFRG5tWFpsa2hXZzU3SW9xZ3FHdm5GcHdoZmJJVjlBcUJlTzFleXdBd2lyb0JhOHRIRTEydG02aHdtTUt0QTIzMFAzTjNtVDJ1bE1iQTcrSXZuamQwTTVYMjBvQ1h3QzhSRWlqZ2VOUVNMVmlzK05oNTF3RlFpb0MvaCtWT3lPVVFkTnlYTjVFbVpwTEw3ZjJ2T3Axb0NSU0RFUm9xWmlpZG5zM0QwWXNhU1dWRXYzNjRZSll2WWliVk9qb3lyYUNXMUM4bXJybGJyUG03NWlncFV4ak5DMVZCcHRGcjBETTJ2WGI2VFVHbWtHcGxQSThsbXpkNjZEOEpNcDZVcDcrNVZ5TzBHZzI2OHFOYytpdjc3d0duOExuSmgzZVhJNHM4RGplRUdTbXM1TEZpbWIweWtScG1GYkdnQzdVbnhVQnB2Qm5YV0ZHb1RDcDFEUWVFMkZoOEdybDhFYnY0SlBxSDE4QjUrTDl5M2VaTGtHTk5qM0sxRXJmK1pmMmsvWjZQS2hOa0FrVEdqTk5rVkxvenZ6NjU4VDBIK0ZyT0VpWC9JYWkwM0Q4U1QvYkNldG5xREVOL0UrQzRxZ0J3bUZpeXdPbzJKUkdMNDBzY01DYmJMcGxmaUJ4N013elRtdVR4bThWcVdycHd0bUM2TlhhNHVDUGFDcFVvK3pUcUpCang3NVc3c0M1WklxY1Q2ZDZ3K2kvUTRFdEZPODN1UlV3SzZucGQwYW80VHVQL0hEdGJmOVpSUUh6d2w2M2NOejByMDNxbFZWaFpJbW9FTFBLY3lJWG1walg4NTNrd2NWdmJhZzhBUktqbWFVdVhGazBDYnJ4Nit5TnpmSlpJRDJmK3RKaEZEU0habXlOK1ZRWTIwTDJJa1dvOG5wNkdiMksvS2VGTVlSMU5QMHdjaEM2bjhuc1VGUnptTVE3MFFGa0JNenpoRmhQdDZxNkttU2t3K0F1ejY4Ykp5clFVYUIydHczMk4vM0xjTlhDZHN1QjVvUU9yUlhJRnBiUWhDK3RnOURSV0hUOTRTa01vKzg1aDZVcXFCdVNjL2szcXQxbHFCa01sT1J6S1E0Vk1CMEJBL1FQWHZVT1BXSDVyUUk2SGcxRHQ3UjR0bFVDT0hQZGFwN2ovQ05PWkd5K2Q0aHVqR1RPV1RKS1dmMVd1R3ErQ1pDZ3FaTUdaWWN4ZFcwclNGYnNqbFhPZGZQN2lmcWxSbEpFTExVMkU5ZU43SHRrYmpDMVF2Y0Vvd3BTUkxXK21qMENzbjJ5TWxTVXpQay84OGQ1bWk3cFZjSmNQek1VUXl4OERNaXkyV0ZKb3BPeTkycm5QQ2pLU3RGNm04TmorelREWHFVaEFISkwzOW8xNDkrVGFrNXpBZm1qeDBTTkxlcitid2xBSENqdlg3YU43eWU5ZWczamJVTVNJTzZQQjgycm90dGppRzkxenNDZHFUcDhCRkdYMkZpRWNkZ1ppZndhQXJPKzlOQjBMSGZYZWUySzRvdVMzb2s1RjB3Mjl6NU1mUy9qZVJzRUk4d3ZtNDU5dENDRm9qVnkwanN0eDZraVJ1cWFHdzRsRUxBNEVxQUhsdzVZYVZkNnAzMUV1bWZSNGRRQSs1TG4vTzhkQjFkMmRmT2dMajVBTHNHV2VieDU0VWkxdkNwVE5Zb2VXMFRJV0dHSzY0bmZwQiswSno2eWpqeXRrdlFTTitCOExETVJ2Z3NqaFcvalg0QjJxZXdmczdidkZTOTgzQjVEazBJRmo0NlBaSlcxdytSQUZYSDlWUmhUaU5CV1ZEVTB1UEd6d3RyWWs4c1NvUlBJTFJJY2JHOTdsM2FPaWo3NW1QTTl1aXVyYjhvTEFtUSt1UzE4RkRYdWwva0F0VExqTy9lR0NGR2dqSUVPYjdoNThnM2Z4U1k2dVRSN004WHBDS1BFazAzc3ZwQzVYSUhJQzJydFBldCtRZGozRm9PbzNZc2ZLTUpTU3R6aHhFcmVJV0RKRHJnaStLdWdqL0V1ZEE5Z0hGUEFWQ0xHRkdzYkxoNDBGdk42WFArVktoWkxwbEVsSU1TbVlxR0ZEdmc5ZzhQK09OMHEvWDVGdmxxalZrWTg1UnVwUkJ1L3V6VWpzaDk0ZndaQkNGVk9wVnk3NWsrTUZ0MkIyY1haYU5jd1F6RWVBZHNMN2prTHhrdHpnRnowek9EbG15L1dlNGtuUlFxT3N5c0xQdm9HbUN0cGk3TkdMTVNUdTEybW4xWmdhSlNZcFB0VUpRQ2xPblo0QVMvQnJUdGJDT2NzaG1DdllHUEl3YnZoU3lJNzJseXJNZ3hVKzNqTmxNYzJwTTY4NHhVRS9HNFAvbXFXb2tMQkhPRnpQMDFDa25YVTc1N21GUndXdTdUVXk5Uk8zT21oRnZCaEgzaHJ3VlhXZThMOHo5eVUrWlZSb3VwcVgrc3pxYXFrc3p1SVZwQjNZMGhqL3BZMFpsejIrM1gvOVIzVXhXUWs1YkNHdmo2RWZsVmVkMnByZ09lUm5PaXk1ZGErQVpLbURlRC9sSzBJU0FheGdDQ1ZacG4rRlpheEhrekl0UzZuYWExa1J2MmNxeTUwSklWTmx1VE9Ebk11NGdvc0tyZC9wOENuRldFSm1ocTU0Y1JNTGNTV2Q0ekx0V3Bkb3B1ODJqSlhWUjh0M3JIQlZlTVJVd3VrRFNSY3cycyt3MHZxcENhaFYwZjJxVXF5UEtCbU9JdHdYQkpyZEY0Sm96ekVacjlrMHZoTmdmcEFLVlh5SDJjSHVmU3NjL0ZPMHNCNW9kOVEvUDNlaWJ0dE5uOUpoZkIzMGMrUzJyeWtab2FTVWNQV2JZZVVUTnpVWnE5TU1GNDBGM2s5MUFLZytyS2hOa2g1UHhEaEIyMzJVeVQvOXhqKzdPNXFGRXpQNTBRU1JBc2hMdXpUSDkycUNleE1OdWxqQm9KWXl1TkZIM0RNOEl4WUcvQURVVVlsanZDdTJzRTF2Z1lTeVRrZXluS05zL2xzeU1lUkdlZXkxVjNhMGpwTGRMQ2JmdzVQMTczajBiZ0o2Z3lRVmxvVlpVZHpFTnVBYThuUkdydnZNYjVEdHBubTJGRlMzMUwwWmxUL3hCNDJqNHRneG4rM0R5dnpIbFM0UHpwaWVXdkRkUmtxZ1NXd0EyQkxheUVLenJTT21MTTFoL3ZBWng0d2pKMUNhdEZuRlkxS25qaWZ2L1JPQVhSUmVJZGVweTF3U28yTzlob2FXcFJSVzJuRko5RzN2Z3hueUtEeEY5VUI3Nys1QVJaUkNHalR1enNFcnY5UUp2SktnSXllWE16MUhPUHh4SDVyZUExVFNZREN6TUtHOG8xMUE5T05YNWRRTUx6VHFuOC9SNGUwV0hzZW1veXlkcmFzd3AzRWhTUDE5ZUpldkZTdUJZTGsrK1VzY1dLbnRBc2htdFBjYWlsdkFtdnR4YUI4WityeXprZktWWUR3TWJJUlBINUFWdUFqQm9KMnltYVFyNmJBUTQyV0tQVEgyZng4c3JOWXYyUEtURWhKTHQ0TDNHbE1MT0NpTE8zTzRXTEttazk1RDJRNWVJYXlPVUtoRFJQZEhOTkR5S0ZCY1NWWU9kcTR3WThHWmZGa2FZeFcwT2FlcnFMbUxzOWlBamxES3BwQnNHVHhDcGVYbkhjMEJBL1FPMjBJZGVHM3NnY3RzQlltUzZxT0IzZHhjSURlZmR0S3BIbFhvNSIsIm1hYyI6IjJjNTJiZmI3MWI1YTE4NzhmMzcwMTY4NTc2OGQ0ODRlNWU0YTk3NDUwMmZlNmUwODk1MmVhODExZTBiOWFmMjMiLCJ0YWciOiIifQ==', '2026-07-07 09:49:03', 'eyJpdiI6IldvdU1XUDQvMUdNdFIwaTZrM25wU2c9PSIsInZhbHVlIjoiU2tkK05qWU0zSEE0Z2FWREtPMjIyYlB0NTNMa2VXenFSMndFdmsxSm15ZmZmem5IUnpiUkhRNVFLR3JFc2poOW44U0NWSWZMNHBTQTdud1MvN0tCdlRLc0dUWGhGTnBBRVQ4ZTVTNElUMENUSmd2a2I3Z1lNQmdCZjZwQVBTanY3VUxYKzFqMkJNZWJPWERzdGRWblpnPT0iLCJtYWMiOiIwNDI2NDIxMzc2M2MzYTJiZDFkYWI1YmVjMzZiNzliYzhiZGM4MGNlNzk0NTE0YWY0ZmNiOWIyODFjZDdhMDY3IiwidGFnIjoiIn0=', '2028-01-05 19:49:03', '6234437000', '6234438000', '6234439000', 'main-warehouse-1', '1', '1', 'Admin', '2026-07-07 07:49:04', '2026-07-07 07:49:10');

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
(2, 4, 2, 'qwertyuiop', '11234516010', '110589777446', '20349', 'NEW', 'synced', NULL, '2026-07-07 07:49:37', 'Admin', '2026-07-07 07:49:28', '2026-07-07 07:49:37');

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
(5, 4, 120.00, '1', '1', 'Admin', '2026-07-04 06:57:32', '2026-07-04 06:57:32');

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
(19, '2026_07_07_000001_add_ebay_order_columns_to_sales_table', 4);

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
(35, 'sync ebay products', 'web', '2026-07-03 05:58:54', '2026-07-03 05:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `description`, `image`, `cost_price`, `selling_price`, `size`, `warranty_months`, `warranty_expiry_date`, `total_qty`, `sold_qty`, `category_id`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(4, 'prod 1', 'qwertyuiop', 'Rem officiis aliqua', NULL, 15.00, 20.00, 'Rerum ut tempore au', 12, '2027-07-04', 240.00, 130.00, 2, '1', '1', 'Admin', '2026-07-04 06:09:47', '2026-07-07 08:06:27'),
(5, 'prod 2', '532', 'Lorem sit id qui ips', NULL, 20.00, 30.00, 'Voluptas libero volu', 5, '2026-12-04', 230.00, 10.00, 2, '1', '1', 'Admin', '2026-07-04 06:10:14', '2026-07-04 06:11:47');

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
(35, 1);

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
(3, 4, 'EBAY-02-00000-92110', '02-00000-92110', 2, '2026-07-07', 0.00, 40.00, '1', '1', 'eBay Sync', '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(4, 4, 'EBAY-02-00000-92107', '02-00000-92107', 2, '2026-07-07', 0.00, 40.00, '1', '1', 'eBay Sync', '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(5, 4, 'EBAY-02-00000-92067', '02-00000-92067', 2, '2026-07-07', 0.00, 20.00, '1', '1', 'eBay Sync', '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(6, 4, 'EBAY-02-00000-92117', '02-00000-92117', 2, '2026-07-07', 0.00, 20.00, '1', '1', 'eBay Sync', '2026-07-07 07:55:44', '2026-07-07 07:55:44'),
(7, 4, 'EBAY-02-00000-92158', '02-00000-92158', 2, '2026-07-07', 0.00, 80.00, '1', '1', 'eBay Sync', '2026-07-07 08:06:27', '2026-07-07 08:06:27');

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
(8, 7, 4, 4.00, 0.00, 20.00, 80.00, '1', '1', NULL, '2026-07-07 08:06:27', '2026-07-07 08:06:27');

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `return_date` date NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1',
  `close` enum('1','0') NOT NULL DEFAULT '1',
  `inserted_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_returns`
--

INSERT INTO `sale_returns` (`id`, `sale_id`, `return_date`, `status`, `close`, `inserted_by`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-07-04', '1', '1', 'Admin', '2026-07-04 06:12:45', '2026-07-04 06:12:45'),
(3, 1, '2026-07-04', '1', '1', 'Admin', '2026-07-04 06:42:30', '2026-07-04 06:42:30'),
(4, 1, '2026-07-04', '1', '1', 'Admin', '2026-07-04 06:45:25', '2026-07-04 06:45:25');

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
('d5tQL6i0TCOBkgUxMSpthfwDn7NqYL9zfJfI0gKW', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJydWI2bmZpczV4d25DZkNWRTBPZEpXNFpqMVRRa21HWFFLOGdrbUhoIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzcyYjMtMTYxLTI0OC0xODctNjEubmdyb2stZnJlZS5hcHBcL2N1c3RvbWVycyIsInJvdXRlIjoiY3VzdG9tZXJzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1783429613),
('NWlaZ5CLwLI1vnFfzq4igpibU4QfFLZrixUdoksk', NULL, '127.0.0.1', 'WhatsApp/2.23.20.0', 'eyJfdG9rZW4iOiJXSFF1U01IcmdmRkpSR1hId0w5S1l2QVBXSldvZ09idVdBeEMyY2JFIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzcyYjMtMTYxLTI0OC0xODctNjEubmdyb2stZnJlZS5hcHAiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1783429737);

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
  ADD KEY `products_category_id_foreign` (`category_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ebay_accounts`
--
ALTER TABLE `ebay_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ebay_listings`
--
ALTER TABLE `ebay_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
