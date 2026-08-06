-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2026 at 02:15 PM
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
('laravel-cache-ebay.app_token', 's:1940:\"v^1.1#i^1#p^1#f^0#r^0#I^3#t^H4sIAAAAAAAA/+VYe2wURRjv9UUqIFGUVkE8FiQi2b3Zx+3drdyZa0tLob0WrrSlKGQfs3Tt3u6ys0t7PKQ0pJUYYnhpokFLDIkJ0RCBRP8wGBIwkIiPkACGKtFgiEJMo6hIFXevR7lWAkjPeIn3z2Vmvvnm9/t93zczO6CruOSpngU9v070jMvv6wJd+R4POR6UFBfNvb8g/9GiPJBh4OnrmtVV2F1wcR7iE6rBLYHI0DUEvZ0JVUNcqjOM2abG6TxSEKfxCYg4S+Ti0bpajiIAZ5i6pYu6inlrKsMYLZA0xfKyLAdJgWT9Tq92w2ejHsYYkQxAEbIUZIIhngo54wjZsEZDFq9ZYYwCFIuDAE6TjSTgaIYjg0QgwLRi3iZoIkXXHBMCYJEUXC4118zAenuoPELQtBwnWKQmWhWvj9ZUzo81zvNl+IqkdYhbvGWjka0KXYLeJl614e2XQSlrLm6LIkQI80WGVhjplIveAHMP8Iek9oeATDOMHAoBlqaErEhZpZsJ3ro9DrdHkXA5ZcpBzVKs5J0UddQQnoeilW7FHBc1lV73b7HNq4qsQDOMzS+PLos2NGCRqIlUXouuxVshTPDmajxe3oJTTIiRBIEVcNLPSgLLMOl1hpylVR61UIWuSYqrGfLGdKscOqDhaGmoDGkco3qt3ozKlgso0466ISEbaHVjOhRE22rT3LDChKODN9W8cwCGZ1uWqQi2BYc9jB5IKRTGeMNQJGz0YCoV09nTicJYm2UZnM/X0dFBdNCEbq7yUQCQvpa62rjY5uiIubZurafslTtPwJUUFRE6M5HCWUnDwdLppKoDQFuFRRjWT7OhtO4jYUVG9/6tI4Ozb2RBZKtAAkGeZiiaAhJDCqxIZaNAIukc9bk4oMAncSc/26FlqLwIcdHJMzsBTUXiaL9M0UEZ4hIbknEmJMu44JdYnJQhBBAKghgK/o/q5G4zPQ5FE1rZSfVspXlFs699bV28uspaY0ttCxLBuTEQW62vbRZq4tFqO9CmrjYWgyXVUdARvttiuCX5ClVxlGl01s+9Wl+gIwtKY6IXF3UDNuiqIiZzK8C0KTXwppUst5NOOw5V1fkbE9WoYdRkacPOFsl/tlfcG+0snlP/zRl1S1bIzdvcYuXOR44D3lAI9xQiRLfW9YRP550riNu9MoV6TLwV5/KaU6xFPTHEVpGGbp1Eii6B1oiECZFum86Fm6h3b2GNejvUnEPNMnVVhWYTOeZyTiRsixdUmGt1nYUEV/gcO3HJQNDv7EzAHxwTLzF1nq7MtS1pzDtxYbfHexf0l0BeTeQWdcRrkqB3/gufDL6R7xeRvNSP7PYcB92eo/keD6gAODkXzCkuWFpYMAFDigWJNBxC4WUCKas05/PchEQ7TBq8YuY/WGpeiu6cWmUfOoJb77S82ph3X8YrSt9zoGz4HaWkgByf8agCpt0cKSInlU6kWBCgSRLQDBlsBTNvjhaSUwofWpFYeOjdUGH/usFn509aPo89W7moA0wcNvJ4ivKckOeVGYNVC/EN6/d8/8lOenN//wytLKYMFH27fPe0me9P31K7+xS2a+vhbSf2/7joQFXLKxt6Tm0kX9Zrl358cPre8xd6mibtrz/xWO/i7UXnSptPH9z04rrjsy+/HeqrmvDT0TnfHXvkvdIDswo6Ck5f39d/fHILNb5sX19JadHl34pjc+qvNX/2QRycbP+i9sqVx7exX/ZGWo1rR37+8+ID1VfPTpHf/PSZD6/GNm0+t+v1Fcyeq57mlTtQuGX9th++kX9vTtZ9NK1+45nBt65/NU451fQ1U728d+oFfcaW6JQnt16g2o4NDhx++PMXzls7Jp/Z88Qv/pI3tq8Rlr02e2Bja8kfL52cvrf2UtPBY08PSEMh/Qv+BQqm3xIAAA==\";', 1785500659),
('laravel-cache-nobody@example.com|127.0.0.1', 'i:1;', 1785241580),
('laravel-cache-nobody@example.com|127.0.0.1:timer', 'i:1785241580;', 1785241580),
('laravel-cache-product-import-pending:1:3cb68e30-9380-4029-8ab7-e676dd9a871a', 'a:4:{i:0;a:6:{s:7:\"chassis\";s:3:\"F20\";s:3:\"sku\";s:13:\"BMW-F20-XEN-L\";s:4:\"name\";s:32:\"BMW 1 Series 2012-2016 Headlight\";s:7:\"variant\";s:10:\"Xenon Left\";s:5:\"price\";d:800;s:8:\"quantity\";d:10;}i:1;a:6:{s:7:\"chassis\";s:3:\"F20\";s:3:\"sku\";s:13:\"BMW-F20-XEN-R\";s:4:\"name\";s:32:\"BMW 1 Series 2012-2016 Headlight\";s:7:\"variant\";s:11:\"Xenon Right\";s:5:\"price\";d:800;s:8:\"quantity\";d:6;}i:2;a:6:{s:7:\"chassis\";s:3:\"F22\";s:3:\"sku\";s:13:\"BMW-F22-LED-L\";s:4:\"name\";s:32:\"BMW 2 Series 2017-2021 Headlight\";s:7:\"variant\";s:8:\"LED Left\";s:5:\"price\";d:1050;s:8:\"quantity\";d:40;}i:3;a:6:{s:7:\"chassis\";s:3:\"F35\";s:3:\"sku\";s:13:\"BMW-F35-XEN-R\";s:4:\"name\";s:32:\"BMW 3 Series 2013-2015 Headlight\";s:7:\"variant\";s:11:\"Xenon Right\";s:5:\"price\";d:550;s:8:\"quantity\";d:25;}}', 1785483870),
('laravel-cache-product-import-pending:1:65baf199-dbe1-4a72-b1e0-1a82b53c8b64', 'a:2:{i:0;a:6:{s:7:\"chassis\";s:3:\"F20\";s:3:\"sku\";s:13:\"BMW-F20-XEN-R\";s:4:\"name\";s:32:\"BMW 1 Series 2012-2016 Headlight\";s:7:\"variant\";s:11:\"Xenon Right\";s:5:\"price\";d:800;s:8:\"quantity\";d:6;}i:1;a:6:{s:7:\"chassis\";s:3:\"F35\";s:3:\"sku\";s:13:\"BMW-F35-XEN-R\";s:4:\"name\";s:32:\"BMW 3 Series 2013-2015 Headlight\";s:7:\"variant\";s:11:\"Xenon Right\";s:5:\"price\";d:550;s:8:\"quantity\";d:25;}}', 1785487248),
('laravel-cache-product-import-pending:1:c133f70b-065e-4dca-a57d-9314da0e01c7', 'a:2:{i:0;a:6:{s:7:\"chassis\";s:3:\"F20\";s:3:\"sku\";s:13:\"BMW-F20-XEN-R\";s:4:\"name\";s:32:\"BMW 1 Series 2012-2016 Headlight\";s:7:\"variant\";s:11:\"Xenon Right\";s:5:\"price\";d:800;s:8:\"quantity\";d:6;}i:1;a:6:{s:7:\"chassis\";s:3:\"F35\";s:3:\"sku\";s:13:\"BMW-F35-XEN-R\";s:4:\"name\";s:32:\"BMW 3 Series 2013-2015 Headlight\";s:7:\"variant\";s:11:\"Xenon Right\";s:5:\"price\";d:550;s:8:\"quantity\";d:25;}}', 1785487408),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:39:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"view users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"create users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:10:\"edit users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"delete users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:14:\"view customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"create customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"edit customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:16:\"delete customers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"view sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"create sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:12:\"delete sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"view returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:14:\"create returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"delete returns\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:15:\"view categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"create categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:15:\"edit categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:17:\"delete categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:13:\"view products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:15:\"create products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:13:\"edit products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:15:\"delete products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:16:\"view inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:18:\"create inventories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:21:\"edit company settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:18:\"edit smtp settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:16:\"view ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"create ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:16:\"edit ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"delete ebay stores\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:18:\"sync ebay products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"view subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:20:\"create subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"edit subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:20:\"delete subcategories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"Admin\";s:1:\"c\";s:3:\"web\";}}}', 1786103859);

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
(2, 'abc2', '1', '1', 'Admin', '2026-07-04 03:20:15', '2026-07-04 03:20:15'),
(3, 'eBay Imports', '1', '1', 'eBay Import', '2026-07-21 02:54:37', '2026-07-21 02:54:37'),
(6, 'imported', '1', '1', 'Admin', '2026-07-28 02:33:02', '2026-07-28 02:33:02'),
(14, 'cat123', '1', '1', 'Admin', '2026-07-30 05:18:43', '2026-07-30 05:38:49'),
(16, 'Excel Imports', '1', '1', 'Admin', '2026-07-30 08:18:07', '2026-07-30 08:18:07');

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
(4, 'Muhammad Zain (testuser_buyer1122121)', 'buyer1.sandbox@example.com', '2312312345', 'FAISALABAD PUNJAB PAKISTAN, FAISALABAD PUNJAB PAKISTAN, FAISALABAD, IL, 38000, US', '1', '1', NULL, '2026-07-07 07:50:04', '2026-07-07 07:50:04'),
(5, ' (testuser_zain123)', 'zzainzzahoor@gmail.com', NULL, NULL, '1', '1', NULL, '2026-07-31 06:00:54', '2026-07-31 06:00:54');

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
(4, 'Zain\'s Store', NULL, 'EBAY_US', 'eyJpdiI6IkZPNWdldytHdW9WSkQ0cEtNbldDVVE9PSIsInZhbHVlIjoibmcvUVNvNWZYYWRHejQ3WFUzdkdWOEhvV2gxNjFKYll4V09jckl5a1BmYXA1Q0dOc3FoUWN3dm9WcHB2cC9SVjNGUjIrZmJWNVQvY2hnb0tRaDlTVFA3NDFyZXdWSk9hcGlwUE1ySm9CSlNaeU8vRld3ZUZ6MGV1RldjQkNaeHBoUm5HTnFUUmJTbDNSM3Jsbmo4OUkvcHJYWGxHMnBwOFdSb2ZORXpGV1hJeXJtY2tIeDJLRDdOSzgwbk1vc28zMSsrNHd6ek9xeTlVRjV6Q3JOdW5oSUFZT1ZMNmNRV2xpbStGQ255S2hLcmVCMGpzUDl6VTJjUmFDVit6ZDA4UStFNEpHeGNXaG5WbHNqb09tY3lTQzJJMkJnZEZWS2hZN3Q5R1VJYU55d1hTalNWQzV6NDhHcTdJaVRpc29JYjV0MytJaSs0M1hVSlVrZGRIR0QwOHlHZ1FEL0ZobEtCUTVPcGpsc3RQcVFtUDFKdytVcW9RcmdEd2NralVIVnpicFpBaVk1OXRQY0NTaVJmdlJzZ3NrUnBTNEZGbVlNT1FNSGdLd3BJMGI0ZWRMaGtPMTRIMFoxVnNOWFhXRXoxOHk1Kys0NnVXdWJqUEJmMlBMT2lXRlhGdUZ2U01BeE5yK0JlNEVnMHI5N1hJSHNiRmRiRlgwRXpucDlLdExnajVpZFNFSHhMODNXak5weHRjTFpkYzRvZnZ1SlhOdzBtV240YnBHY2lSNWhPaVpZNFZiZXl1MjN1N2laeEQ5Znd6cWRHeG1xc3ZHai9zem94L1JTTXZVZlo0WHRxT0lMb1pLYUdSaUM2Vk9wSkhRTnI5cnpCeGVWYWY2bHh3OTlpTmZrbmhrTFh3NGJKZVY0c3RnK0VsVDJwd3dteGhMcmFGcTlpK1Z4bzh3T05ienVXd0JLMksrejRaWU1BN2ZUWDAzMzQ4WG52aGUxay9YUzkwUy9RVjlRS1dibDFFeElIZWljREtJbHJoVFFPeklCKzJIb1FhUzI4RDdEYkxpOUhXK285Y3hFeUdqbGttaXp2MytGanIyYWt0OVFNdXhEMUptUVRSNVM3Y1drc1BEOUlvZGpFN2Vlc3lpdmtYd25TTUc5dTV4SE9yRVg4Z2hBdlAwclYweFYzaERkLzJjcE9wOVZ6WVBnOWRrZVdDNXdxK2pEb3ZDcyt0UXBuTGhDdGkzeFdkM1BMV3JNVlRXaDFVWXBNZVh4bDlPRzdOdjVoMzh6TU12bkM5QUtGM2FNYUpMdE1qLzdKaHVNY1A0Y3dDS0lCYmEyRFlMNVRZN0g5bWhLY0RrNDBsZVZJdSt1MnkvcFRycEtrdkVaVUpuRzhzMTk1dkpwWnc1bXh1UldVeVgwVno2UDJzcjlhZzBZQkFXU1NqeHZ1UXJ2T09jSVVSQlBtSkJLWTRBKzdtR3lwWldac1R4ZHdYMlJBT05yTUtYZjFPdDNVajh1bStqNjhYMjJkbE5jLzJaYnBqTFE2MUE0RzdjeTBlbHlxTnRxS1NlT3JFbzcxTHhrUkpCYWxVdTMvU210am5KM1p4TGIrSHhNQUNOZUpFNWVvaDVVd2pxZzFvU0lrd21Cdm00UFk2NHdXcmZ4TTdHYzM3VE44a0hBTmJKR3FyaHpoUW80WWpvTTM4S3Y1TGhsMDU4dE5aSGFxNkQzT0JRL1B0c01IcTR2RnllZUMzUHJpenhQTEVJU1lXc2VVRUpOK21BYldYayt2eXM5SElEb3RBVmtVb2J0aUpSK091MUEyd2owRWM3T0JRUCtZUE8yOXY3cWlUMzg1SnBLOEZlR08vMjkrcUZiQVYzRnY2NitPMExRWlFoSFFlbkU2N1FpT0FST25XM1RIMTFqOXVLRWhoQkV5QXRDZTlvVitiTC9EajBHSVVDSTVvalFNYktLZEljZGZoU3JydWlZMEVEVFJDOStwc3lZelVuWFhQdlEzYk9rVFR2NVB4VDhIYldrQ1Q3VmVvYWNQU0pkYTE1N0NpYmZtTTF3b2JhT3RkWitEZStqZmVRSHZCY2poa2VvWSs0aXVmNmJVM3NXT3hTYTQybFFTZUhadmpjQk5LWjN3c28zKzlsQklRNXVTVDMzU1BUZ0ZFcEJOUE8wNkt6RDIxV0NJOVBvYnBuZWl0Tm8wbWVqL0o1VEJKemwyK3Z5UHVrWWl3RUlvaGhGZWhycFIrZWV3eDQ0VDBWbjlJYmdIZGEzaWt3WFNZT2o0N3A0R1lKM2ZCaVVNU2RjYSt2TUtGMnFrcnZqT0lJYlFaN0R1bDZPb2hZdkNBeDN5R0hzTnlMUjZjd1p5NzhiTENkTEJqY2NHQThWVytCN1hsaHluazJKZ3Jnek9xdDdYNFNVbDZMYXE3NnZQMzNwUVlGMWtoVDN4WjN5Zm0zWWY0SlRGYWNSN0JvZFN2VXRDdURjcFNIbzJLUUVPY2k1RkhmKzFPSDNpODRhc3lKdnk1ckkvWFRsZ0hCcXUxdnBFdFhNZHhHbWhMazRZNS9xY3JEcXlTTlE0bTNGc0YvNS9zUklud28xbHJKOVlHcnpOQUx5VUo0QTJWMnVzVmROV0xpdmRXR1gxVW11dmNpM3ZuMUtGZEQ4Tk01RWN2R2lPRmU5N2NiZCtaK0FrUlZXQndocWtnQjFBMjBueFdydmh2enA5dW4vaDZ1SkZlaTI5SUliKzFpRitwMms0SGRiL3lGdWZBRHBNWUtGVzRIdFJDMldPRC9ubEJvV09oZXBQcFYrY2U5OStOZHZpSktOUTlyQ0FpcWVrK0FOMFBta3Z0UFZQK0RYNGszQ2VmME5iaXhhaFEyaWZydXZFZ09oSXdUcENrWHh5T0J2RkIrUVRhRVhZUW5VL0twZ3RxdjlCRWRzY2hQbGltRzMydjY2OVR1SWZEMnVoMUxEREN1b1pmazZlTW1mYlJPTGtsMDZLQmkvQmswM3dLMHpKZ21OOWFZc2F2NVYwVUVlUVRqK3dVemZKdW5OVWxWalhBVXlVQTJoZGZXWllzY2UycGFJM1hNbFY3WTYzUXZwbUFVVDdTV2RRbmRUd1IyTjJzdFJiVTJnU2cvWjVwK05UajJLWjZkSEJHbjVpS09ETE9wQ00wNHdGRDl5QWJLeVNMd3BSQllMMUp2cU5DaGF6QStzME40SklGUmN4djh4VFQ0MXpVeml0UFoxQ2NIeUt4aS96S1ZmbWxjcmVkVDFWcmFDR0pBRm02VUFWTEhYWmpSZDRCZU9Mczcyd0I5WGFDdWVxMFl4SXlPZjFXWGxORE1ka0tvQmNIb2ZuYzl2SVpPQnczcUdSNmFFTG43Qm1HYlMrZHVQVlF1dUdhcEY3ZW5iY1QvL044UWpoNndEbUprYm1GajRyTXQ4SHZvZG1yRzYvWUFWa2FpdUFkcWU5UUxFMVl4QVBSMytwM0JLbFhvRzJHdzJzQzRBcVFKTEFDTnd3ZWtGRG8yanpRaFppQXhyeGNwNUNDK2cxOFBkL1o0OXk5M0pQanVuM1EyV25GSm91blVGbGNjVlRLYmZOQi94TkIrTkZyQTB4NTBPeWdMYlpmQnJyL0RzWEt4OThvRkpacWljK3V4ZVhvbXN3cTVZeURRcWxSVmliZEpHTVdZSjlrOEphRlVUeHdTNmxwVnQ1dmpVRjNPam81aG1IRTRnM05QQjllRTZtd21QZXFKQ3V5VFJ3bXQ1N1RtSm9IYWtZVFJkTTVaVlJVT1M4c0ZzSXIwSmxidUhpQ2VyR1VSckIwMzZLZVpYOFdXRWplYkJyMDRPTllGMU5oY3ZnbWovWkhvS0k2Y3JJeXY0anFIcTFIbC8xNU9JOTJ3c2tyN09NQXdNd2IvQU94Yk10bUMraTJCZVdiT3dRY25DQUc0RXVVT0tjNVZ3T3JSR1pwQllKOS8rU3h0SkRsUjMxQjl3T1VWdlBkU2lBZE1kcWVlcFB2NU1FZGNmVGd6SC9aLzVBaUFGUjJieDBnVUtuWE05MFRWT3VjUjdNcVBBY1dvTUpBZklHa1ZVcFh2Vjd6cUhXcEx3RzAydElaejVNVnpvRHdjc2VmdGV5WEVlcHJ3TVR0OENkQkI1TlRBbkUzRmVteXJCMnY3Y3ZCV0M4MzFESkRaQ21CY2dlWGRhUVBjcUI4NEE3a1FGQmdUSHRJa21yUDV4bz0iLCJtYWMiOiI3NDJmNjMzYmEwZjAzMzhhMjI0OGEwNzM0YjhiYWI0YTYyNTkzNjhhZjMwNjdmNjRhOGUzMDE1NDc5N2M0ZmUyIiwidGFnIjoiIn0=', '2026-07-31 07:43:10', 'eyJpdiI6IkgxWktqR3FVbXovWWphZGhWSGZTTkE9PSIsInZhbHVlIjoibG5DcUhqMDBuWFB2b2JKaCtHajZxMUFVNmR6Yk8yaThBZ1FVQkw0Y005SUU3UUhTd2w0dEpSS3pDOHd6YWIzUyszVmY0ZzVyRkp3WHlzZi9EZDEwMk4wR1Q2YS94RGRGUm1zVXZhVVpSc0VBdnZoeGZFcit6SkYrTmw4UytVOWhWMGdJSVhHSk9rR1hOL2JIdm5aSTB3PT0iLCJtYWMiOiI2MTdiM2VjYjg0N2JjYzhlODVkOTgwYzgwYjk2NGE1ZTUzOWVlNWQ3OTBhMjA5MTI4NWJmODU0NjhmOGVjYmJiIiwidGFnIjoiIn0=', '2028-01-19 14:37:56', '6234437000', '6234438000', '6234439000', 'main-warehouse-1', '1', '1', 'Admin', '2026-07-21 02:37:01', '2026-07-31 05:43:10'),
(5, 'Test Store', NULL, 'EBAY_US', 'eyJpdiI6Im5zTXNycThCWFgvenVVVWY4RmorRFE9PSIsInZhbHVlIjoiNWJFb3BjSGo0QVVPcmNKRENHTm55MU1zRGJML1hrZU1OYk9WeEJ5ZncrK0dNYUFWVmVDeWxRV0FvclZIYmZheEV1aDQyRWtuTjRIV0lHWXNRajA0UnBHNmNwNVMrNSsyUUZKRXJ2TzFxdXgvVWdTcmJtNlRjZlc3SG9kSnN5d08rc0F1RmtvY2hyK3AwdzVta0J0RUtibVdPenRxSHJJZzVOSFN0SE9FckdjMGRJY3NHc3N2NGJHeXNGczdJTlEyL2sxVU9JakQrZ2RXOHYzUUR1NnhxWGFXUjgwb2htTzAvSE9GUmxzaEEybk1DTHoxZW9xQTJPOE9hbDUwNktmakM3Q2dtOGRrZUJET1RvZVpmTzcvanN2dEx2d21kYmRLQ2xIWXV5VFhxYU1pcDJqZjFJcDZlSTcvZTdIUmR0N1FqdFNqeVBlQXNSME1XbE54RE5GZHlFa09sUzFCckZ2MVY4cE5Kb1QxaHhCajlDbjV4WE1sbWpKd1F2Ylo3TmRPcyt2bnpjR25HdGMraUVTMmVHdk9NQUVsd3JTUFdhUTVGcFAvcnJ6NGE0S0dxSGRnbkRNV05sRllvL3M4R1ZtVlRuTWYvUS9yekxWQ0RzcWw3VHlobncvbS8zM1B6anJKcW1KYzh3RGdTbGNXTjJHUDcxNmlaYU9CaXgwcTZqd2JGTTZaVGdvVHZtQzlwSUJEd3BTYnRoWGJGdU02eDArbVFQWUU4YjhaVUVMbWtzRnE0eTZhcHYzZVI0S0xvQ1RaU00xMXc5ZDI5UEdjZVhvSUFOUU0wWXZxWWhkYUp0VFhWK2dncFc5NXpSdzJ5QnlmMDNYN0NEWm96UGNPd29BVWEwUVUzcXZDWXAvUDYvdmNqWW8vbEg5YytzMlhQOEV0SVkrQTcrOFFkN3kzRStETVR6Y2NybXNjRXRLY0o5UTBmdmRSYmw2WERncXZERWUwUjJhUE9HWExSZ2pIQnY4Qm5xY1FoU0hZeUpNamdMVW54QmdWeTgvUmFhVmQ2ekt1VjVlS3dBb2lkYks1bEVIb0VqOEhvenQyQ2VRcUY3OXlsSHpwVGszYVVPdk53TjQ4NHYrVGg3SEJFNFdCNEprOVpFVWExUjhRdkJpZ3c0NjFSVlNRalI4OWkxUTljaGZqU3B4NXZoUlJ2bnNwK0JFRk8xc2s1N1NnZ3BkN3BxdTBUMjZKWENKTGZEWXdibGJZWHU1YXpOWUF2SjlsUDg2M1d0bmV1MUFkbUFEWGlJby9xdkJ3MGYyZWYyUTVEY3kyYU83VXV0K3BRc2cvUTNacVVDQjc2dlkvMHEyNUZWUWRrTW5nS1o2WkFsZ1lSQlQ3K0NHMFBCV2xCT0ZnZ2RTUWxZZ2xZMS9XMm9yNEFMbFA3aDJGK0h2VnM2VzYvTE9zL3lFdXh6QVN3STlJOFg0VDVDUVY2bXREeE1UVDg4SkNPOTVmWUxKNU53MkM2VDJPODB4b2VtdWd4ZkdZTWFlMU1IUGlla2hyTU01bURBLzZ0ZnFIQjV6dWFYM0JoaWtEa0REaHROVjVTZm0yd1BkdmQxNnYwakhiUDJlV291eERMSGg5c0pxTTJSZWY1TmU4K3h1aEhjcFQ5MUtxNFBpcGdMMkgxVVEwSlFXcWZZY0RBWDNCSTJwRkR4eHJOMW5BWE9qdGk4VHZBMjQzZkVXUnF6REtqZWZZNWJpSlF2R3pmVTkrUUZqcVdzUXF0RVJ0ZDZlRHpuV0lzQmE1RjdUOFI5dnd2TDdUSHpmMEFlVTRhbFozcFVMbjdsU0hPOGFLcnFHV0dPelZpdEpXQXFGc3daamdBeGxWdXA2ckQ2cTE2Z3JIVTlaZjdTMmN3MXVLQ3VFZTBWaUNid0dBTkJvamVocDN0NEtaQytIdnB3ZnNNUFJsU0xWQmxkVERjZGhiNmE3Y1lwbGxoMCs1OVcyWGhvL1NtYkhCTDRjRDRjMUlIZnVhcUE0Tkh0WXVjYWVvQ0JUN3VQblpyeUZsTkhwNlF3VmdYeERJM3BPbVlSaVNNSGhNNlFiQkJyTWw1Vk0wdGkzdlBCSlQ3clkrWHNiaWgwOE02WHNyYmN0d0ZQeTcwRnpaL1JKVldwdGtHcWtxNHljMjZxSE5obXRpOGl0RW51Uk44WHR4MEhVNjB2bXF3ZE92dFgrUUc5T2xBSnBsTEdiaXp2bGZpTG9NbXNySFllR1pPR1Q3NUdrUDViamFTNXJtNTZ3eURZUTlxNWp4VTEwUTRiMVlRcUlZUUgvUWE0SFV4TkZwWnlCNmJsVkNOZEZJOHRmTkF1NEdWcW1TOHFsU3Q3ZW5HS2kwejRXVW9EWnducVhrT0hOQnZ4SnIyemQvMFQ1VjBMM2R5YVBiTkQyZi9qdWxaUXNLSXc3Mlk4VDRjeVhrOUF0Q0Zvc0g1KytzS0l0MStvdHBNczJwVFUyOXdqYkRKMmZSZ1VkelM3ejFaQnpXVGpWdzF4T2dMdkR6YTVub3BRblN4NFc3RUFyREl5dTh4blhNSW5XSVAwYXppS0lqMkV3SCtrMDhZYStVdEZ2WU96dHMzU2JNRzRGamtrTXF4dmNyeDBrcnREQWhsK0hyQkFOaHBTdWFsTUpzZ2ZucFJkTjR3TThkSGdJSlptSHZLMUcyNE16R2F3dkRBL09mTVdzbk9rbWFIRStvM3hLK1VwREE4cEgxaFkyeTBEY2R1YnRva295TmxOYmlzNWp6WnM0TnozNmNQSENXUitrRFRhUThaZnZXWnhoYzJBU1p3VVY2eHZveUQydnNYWUVmMkdaMTVFUVNHb05jc0lzSFBHQ0VLNEFsSmloeU5NNmdneHRLc2RlN3gzaFRMa2NSNnZLUnFhQkhVVkZIeUgrZElIWXNqYzF3NklXRFVqdGMrZENmNUlEejl1VC9UeElTS2kxcEcyZUZGc3VSMlBxSXhLRmdlZWFUVUt3cHgwMW9WMTZjUmdSZlFZWUFMdmd6dU5TQ3pDUHVvZjJmUW9BeGloS0xKLzJBbHc0cDgzNFBsMDZ5a3g4UWNWYkhjMVpGMjBuUHFrTnl5RVhLbDdpNDd1QXlhdzdkeGVQa0NpWHpOY2g5V3FzWmFWZURyYzUyc21JOU1HLzRYdEU4NXdGbGdhOU1CQ1dhdHdVTXVhR1RZa244M3hyRkFaUVhnM0FoSERFdkhGc2NIWjNIZG5UQnZsc2k3dnBEWWw0WXZwbzJlZ1JRNmROSndZVlVHTmVCYVF3U09MdFZGVmJicC84c1FjYVpvSVZlVmowd2hJZjgrN1h1S1c5dGF6dHo0bFJiN083d1ZJZzYxN2cySkRqaExzOU1sSkxCMkV2UjNJR0VLM3N3WVFhcWQ5ZlJTZnhiZXBkSTg4cURabTl3T3Rxd1pBVVEyMTI4TVRxYXBsRk1SeUlJMGZGQkZGNHhTWi9na1RML2R1ekx4b0lkQ2h4cm9BeVovaXZ3Ni9MK08ydEFhK0ovNkJGYUNzUUJ2QVZzaDRMbGJ6a2tEV0JFMkZSOFdCUk43Y0I4K0dab2xzZnpmSlNJT3FtNTNRcTc5Wld0UTl2MW1zSnFyVzlsU2NSQUUrdHpQNTJ5cTg5eHQ2UWdxSGlhTkQ0SjJRQThjS3htRnZWbmlvVkdBYjVkSlRQU1JrMmN2SW1wVnNpclFPU2wvVHpOZld5NWFaVmtsaTZ6T3FwWEMxaGdYT1drd3hqU21MNDhSeHhCL3JKU09lUkt4aU9tcGFUam1UTVI1Rnh2QjMwaWlGUnN2NTVNOHpYSFduSEtyTVptSXFlZmh6L3JscVM3QVBIY09QSVFBVmlsYW1kMHBlY01KVnp4VWVhTFloS3lnYU5sMHQ1c2dhblpsQjR5ZWttalFYMWxmdnExTGs1bEZ5RDN6TWQ4WnpNVy9Bc0hRTWljNml0SGRCMURVclFSL3RqeHI2NlJxUVBNM2RVOGlwL1VtNUxkdDB5QjN1LzFGeTgyRzh1OXZ3TGZoWmloZGRLWGdzenNEZGg2R3FrYjM4RXVURnJJK085SS9tWStaV0FQL1Z2bk93bDA2aHVJNDF4d2FXSUFPS1o0a2xFSXFNVjJYRFlJUDNqVlhaQUZ5cTdsVk1KQ0hvLzN1SWtUTk00SDJVazRUYjNWcnVWUE1kYktBTTI2U2VoOEdYYUQ4MHdoVkpYZU9IV2hISWNoT2h0RyIsIm1hYyI6ImY1YTQxYTUyMTdkMjNlYTBlODdlNTU0MWU0OTRmYTZjZTkxNTFiZDhmZmI5NDJmOWY2YTYwMDFiNWVmYTQ2MTkiLCJ0YWciOiIifQ==', '2026-07-31 07:43:13', 'eyJpdiI6IkYrMndVdUFCOFRoSlhGQmxJWlBXYVE9PSIsInZhbHVlIjoiaG53N0hDelUvVFhBcjgzUkFuM0pDcWNEaEJuaS9BR3JGSUNONVN0VWdPK1BwR2xEMG5wN0ZxSk81d2NaaktSeVkxTkpwbklZd25kKytzNzE2cU1DeEttTTE3SGxETXo0a3pSK2thVjNHb3E0cmtoOUxyTXFsbUN0MjRQb3h2VnQ5QjlLaGtNRmhVUXZNWkVZeFVjdDBBPT0iLCJtYWMiOiJhOWEyNWY5NjMxZmQzNjUwZGQ1MDBjMGJiNDg2NzdkZGQ3MTc3YWZmNjJlNjUyM2M5MmY0NmIwNDViODY0Zjg1IiwidGFnIjoiIn0=', '2028-01-19 14:38:37', '6234437000', '6234438000', '6234439000', 'main-warehouse-1', '1', '1', 'Admin', '2026-07-21 02:38:38', '2026-07-31 05:43:13'),
(16, 'abc', NULL, 'EBAY_AU', 'eyJpdiI6IkxVMS8ySXdSYStFOURTTlc0enc1ekE9PSIsInZhbHVlIjoia2w0cEJrU2JZRzBGai9TeWltVWRFYVhXa2xPSldhdy9ZRnlqQlZmSythdWY5STN4UEJ1eVVoNjBpQU5MZU56QUxPdDltWUx0Um5rRkwrWnh4MUpFbUNpaTVaNStkcUtidEpPT2diOGorSjF6dHdoSFVoalU2c3M2bnQ0V2pWbTlvYzlPdmI2ckVjbWlocFNCMzFud0l3V29mMUN5SHRSNzZpVzN6VWRCa2ZqQ0R1UGkzei9GZUZPS013b3hndUhvdk9xV0Q4Yzc2cE5VMFl6T3A5c016dU12QXo3ckpDV255Vlh3Q0l0V082bkljSlpyckJxNmVLSSs1TFNqZnlPcnVSVXB6VHZjOXdjckNYSDhNam8zdmk0V2llc1VabmF6dG1UNEY2RXBNQVZkTFA1eTJhL1kyM2FMZTRqTkxXZWZVdFlmOUswQTZvSHdHSFJwWlJTeXNjTkZCSnRCdEpHbWJ2UUdsMDdqRHZhc0lEZWVoMTROWFFpSkFjeitCNGE3OTZwTllzd0VkLzl5M0V3azl0cHVqaGNuTzI0ak8zMUpnc2h3MksvRW5uQmdXQWN5Y3FkVjh3V0J3NjBTQXZKekRGMTFXZS90OUlyMmlnU2ltZ3JjalR5cSs4U3Y0dm9VNUNpTm5sYUlSaU84Q2VSQ0FEZjROQm5mUUJNZEZaU01CS0hlV0pqNjJGTEdTNXJJcngrczRYQVl6R3ByOFIwYVBtQ09uaWYwUDVCT3RZM0pQUXpCaWZaNUk1dTBvWkxNbmRSc1MwMnJ2WUdTMHNuZ2NBbmVHMHFEcC9la3hEQTViOXdpakdLeE0xRThNWmFBVk9CbnJ4dmlXcWFjcEdlTGpuenZ4VjRzVXFWYjF1MTJURVlqQlZWOVpmWW9NU2dad3R6MGFjYzJQOEpnTngva1RKb0NBNEhjcFBicjVuVE02TXdmbGFaU1BLQnBSMGxzOEQ1czAwZHF6UnNvMy9sbG5YQzdVSDZUYkN5MitVS1RDVWtmeVVlcGVhZ0lyb0NSaDVwSnRVOUdYcFJ0N09OMFl6QkN2dkxCRU11MkFZQmovQVZacXNUOUx0a0JNOFJoOWdwdEdnVGxtM3ptS1p4M0d3Mloxdk0rSU1hek5vb2VoYXlnWEFCWXFVbVdzTGE2bTBsaEZhbVpPcUV4R1BZa3ZvS2tSb2Vpd1FGUVJ3d3U3emw5YTRMNkFVckZJR0tCR3lRMnFFQ1NPdlM4Q1ZxTjBsckRvME80OGVVZmVoNVhXUzY2WmJDQUtPeExweWh6ZVR0aWtmYUV1QlAwRUQ5R1hNZWRDeFhDTURBTVYzaU9Mb1RtMlc4NUR3TWJjWUdkU1Q3SUM4T04zY0NhUUVrbnU3VXhabUlsVEMwYWtVazJBLzBqMjVxdmljTkJadUFlQ1FsT3cwVzN4aWpDZG1WcGsvdjFxR3l6b2NESU9iNnZEOW90aUh5cmZCNDZoVno2cFA5ckFjbms3dlcwQmdsYy93RngySFN3S0xvOStaK25EdnF5a2pmZVpOWWUrMmJ2VnV3ZjZWc1JCc2MvcDFqWDJRRUg3SGNuNXVIV0dIWkcrLy9kYnhIajRMdjR6T0ttYVRWRFErVjhHNTQ3R0ppQXUrcnBFZnFCUU9uRHJpS3VBSEt2UHpTdEN2Mkkrb3piVkFjWXNCQ01pYmN6ckdWbkRKOGVycmtQZ2ViMFhZYlZzZGNwR2tMby9WOW90MFphYmJPSFUwYVNybkg2d0N5SzgvQ3FnRGl1OGNSbE1TbzI2WWp2OUlpLy9SblViMm5TVFVxb1hOaktMcUFvSlFlNmhqWWhxYnhtdTVlUTAwSEcrbTZ2Z1IrVldlSlVvN3JRYW9TeEVGNDZzL1NCNG9ZQUQwdVRyS2w4eVR0RERkazQ0RXJNc016RGZESkV5RUJqZkdia2tJbkNpNmRXQmQ3STlXRkJzaXhKR3dBUUVOVEVBbDd3TVEwY09MMXk1cGtEUEZHQWk2L0R1UFVwYytXYkNDZGpRUGFqNkZMV25WWkJLS0IwK2pJU2pQQzJJeXcrSGZLK3BSamRIaHF1eXVJa0FQOWNRYUM2eXIzY1dBTlFRWVVRTDk4NmtXWmtRYXg1ZkF3eVdQVjY2aEdSWk5VYkhIaDFBK0FNZWsvMzVFWXRiOEhaZktxUUVma3JBdEFWSmRwMk1ESlFteFVaa3VMSGZEcTFZaHB3TzlNWU9oWDczNW9Wbi9vK2duUTdKYWh4ZEttTjdnNGpSaDdwYXFvaTlqSG5NYi9oanpvUithY2VJdWhuZDVEeWpVSWsrUURHbXZtbm15SWU5ZWZFSCtKMVVoa1BMMHNuV2dMdU1uYlBOQ2xOTHR2M2I4SU1KUS8xUXRPRGJVbHdUMytLMjFZam1oQXhEODJZRTdkMk56aXdQakoxNW96QUEycDVDSFM3dUdjVkhHcEN1b0ZjTzllNWtNUmJ1VFBocUtRdUlGbldVRHg2SnEzbks4b2xjQzZVbmpnUE8vOG41MDg3dlo1ZllTVFJJdmJHT3hGTnM4aW14N2Z4Wkh3K1dObHE0RG5SK2JaS1pEamNzYUJlZEV0MHpwRERwZTlDMFJ0VldPUVcyV1oxWFZaL1lzekk0aGd1bUloTVpYTDVrS2Nmc1FKbmNuenY4UHBrODFOenVrTmhMa1VIRlRIdzZISzk3azYzc2oycG9QbnVIc3F1c05pRHFvdjFCNDNkY3p3aitqamxjWlMxOFQ0NERzQjRVcXd2ZVJhWE5tVW9IU3p2ck84WmJqemlxbTR6YVN5VUp5eTZJaUpzNWdjWkZGb3ZES01KVklQZzNqY3JIU3p4R0VYclNZVitIeDZJODVXNHIwSnNPa0ZSdEU5QWFqWUdvdnZkTmhHNEZ0aFgvYzREV2Q1Sktic0szbjEzcTF0ZlRhbWdVQ21ZTWwySHRucXdLNWZSNERDN0w4Zjg1UzlzOUZuWk1EWlNEU3BYOUpJWXdiYnlvYjdFTGZHKy8wRjMzK3prM3RNb3IyZ2FvcFdYUjZFUElIVUNwSCtuVnlQd3dSU1ArOWplVnNubko5SHdPKy9CZXhFczdqWlZVZmJsdEFZRzlUbzNBcWZXN3ZoUmEyMHlEUFovZGkrdFFVdy9YeGtkRU1aZ2w2bXdPcDZnNUJGQkdiOEpLZjMwekxrQ2QxcjdYeXdCMk1IRWVXWDY2RGhsUE1PNFovQkltZ2laZnFYWlRONWUzS09kMndkaXQ4ZFY3QzFiUkpBNVNEUDBHNnQ0NDE1d1Jjei9BM3hmVkJTN1VJVXNrWkZVb0ZOZ0kzTUZaakRrL1NON3A0R1NpQWpGY0hKOEQvMjA0andTZG5kTGpCY3BrNi9PQ2xqQjFnRXZRR1R3YXRVQ2UvM1hDQlkybkZYVHJoand5RXJZOW1TejEva2JwM1VBcGZUNWxOZlJsRzdYTmdZaWtEU0lqMG5ZcUw1bHBHZGZweUpnaGpjankvS0hUNFNkUjN6bkZnc0JJTit3RksxYWt6WFJVdzI5NXhnV08vc2xJY003UG5sdVIwS0ppVUM1SzhmQTk4Q3RNODhwTFlyVStaYmNxdysvbEtMNEVOUGZ1dDZUNVZUbXRKOXdTRUJnQVFCKzRjRVdMaWlVREVEMjhid3RxRWRpQkxOVWZpYXJ2TnBKSjF4NjBSaEpybWxFOFFNSWhEZWFkR0tBSWQzUnRJd1p4V0ZhbWxaVU9MZldpV1p1cW5FSGc4VUQ1VzlQb0oyZnJ5Qzdnd20wWlhpSURLQmJJZ01HbFBwa24rd3VrRFA5SUltVWxxbGdFNHV3NXQrdDhHRWpCZFZyZXJYallmQnArSGhtQ3RwTWNMTWpVaW94cHF6YXVSdFh1alJ0MmpmbjlaN00vc29GZk5QVUFWZmZ5TTFIY2s0MEJ0cExqK2FKdklpQVQ0ZHA5TlZ1enUvb2dEakt3TlpSYnByaTJnVmc1bnNZalVPVGhpaWZ0NEZlbzRvRmowNlRUc0x2ejRDWEs0cGFkN1h2Q2lCQTgvc1dyWW51cWtlcVRqUXJKWkVPU1hkMW0xS1VvOGk5akJUZVlOazUwTCtkS2YzMWFaaTlyNWdwbm1xdmh3NjdHZ0Uzcy9zSU1yOE4ydHpERlllTTFrNVo5TVgyVktqczRmK2JIakZ0bWI0MzV4aHJMYWluTHZGaCIsIm1hYyI6ImZlMzY1NWUwMjE1NzI3Y2Q2ZDIyNDFjMWZlYTY2YzlkMzEzNWMzM2VkN2EyZGNmNGZlOWQ4MGQ1MzYxNWI2NDUiLCJ0YWciOiIifQ==', '2026-07-31 07:34:16', 'eyJpdiI6IkpZcjVTemM5djlPQks0bmk0S3VrMGc9PSIsInZhbHVlIjoiUUVpOU15dXg5SHhLbkhYNFRUWXVDOUY3OVhlTmN3dDJJc0YyM0YwQzh1a1pNbnpwNk1VSkNlWWVDSTFNZUY0RUhIRjN6OUdsUXl6SU5Ta0M2bWZRenZGYmhicGM5c0owazBYS25pV3hNMlB5R0JmMC90QTRtTm11ZW5rdFh3Q3IyV2pEOHpYY0QwOWc4Tng5WjNTVzVBPT0iLCJtYWMiOiJhNmI4Y2NlYjVlOTg0Y2ZhNWVmMGQ4YzJjYjkyMzJhYWYwMTVlMzMzMGNkZTAxNzFiZDgyMjJjODNhNWU3ODI3IiwidGFnIjoiIn0=', '2028-01-19 19:06:04', '6238386000', '6238387000', '6238388000', 'main-warehouse-16', '1', '1', 'Admin', '2026-07-21 07:06:05', '2026-07-31 05:34:16');

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
(41, 245, 16, 'BMW-F20-XEN-L', '11394837010', '110590104029', '33710', 'NEW', 'synced', NULL, '2026-07-31 05:36:26', 'eBay Import', '2026-07-31 05:36:26', '2026-07-31 05:36:26');

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
(204, 173, 30.00, '1', '1', 'Admin', '2026-07-28 05:03:10', '2026-07-28 05:03:10'),
(224, 213, 10.00, '1', '1', 'Admin', '2026-07-30 08:18:07', '2026-07-30 08:18:07'),
(225, 214, 6.00, '1', '1', 'Admin', '2026-07-30 08:18:07', '2026-07-30 08:18:07'),
(226, 215, 40.00, '1', '1', 'Admin', '2026-07-30 08:18:07', '2026-07-30 08:18:07'),
(227, 216, 25.00, '1', '1', 'Admin', '2026-07-30 08:18:07', '2026-07-30 08:18:07'),
(240, 225, 10.00, '1', '1', 'Admin', '2026-07-31 02:16:11', '2026-07-31 02:16:11'),
(241, 226, 40.00, '1', '1', 'Admin', '2026-07-31 02:16:11', '2026-07-31 02:16:11'),
(242, 227, 6.00, '1', '1', 'Admin', '2026-07-31 02:16:28', '2026-07-31 02:16:28'),
(243, 228, 25.00, '1', '1', 'Admin', '2026-07-31 02:16:28', '2026-07-31 02:16:28'),
(244, 229, 10.00, '1', '1', 'Admin', '2026-07-31 03:09:58', '2026-07-31 03:09:58'),
(245, 230, 40.00, '1', '1', 'Admin', '2026-07-31 03:09:58', '2026-07-31 03:09:58'),
(246, 231, 6.00, '1', '1', 'Admin', '2026-07-31 03:10:05', '2026-07-31 03:10:05'),
(247, 232, 25.00, '1', '1', 'Admin', '2026-07-31 03:10:05', '2026-07-31 03:10:05'),
(248, 233, 10.00, '1', '1', 'Admin', '2026-07-31 03:10:48', '2026-07-31 03:10:48'),
(249, 234, 40.00, '1', '1', 'Admin', '2026-07-31 03:10:48', '2026-07-31 03:10:48'),
(250, 235, 10.00, '1', '1', 'Admin', '2026-07-31 03:13:28', '2026-07-31 03:13:28'),
(251, 236, 40.00, '1', '1', 'Admin', '2026-07-31 03:13:28', '2026-07-31 03:13:28'),
(252, 237, 10.00, '1', '1', 'Admin', '2026-07-31 03:14:00', '2026-07-31 03:14:00'),
(253, 238, 40.00, '1', '1', 'Admin', '2026-07-31 03:14:00', '2026-07-31 03:14:00'),
(254, 239, 6.00, '1', '1', 'Admin', '2026-07-31 03:14:08', '2026-07-31 03:14:08'),
(255, 240, 25.00, '1', '1', 'Admin', '2026-07-31 03:14:08', '2026-07-31 03:14:08'),
(256, 241, 10.00, '1', '1', 'Admin', '2026-07-31 03:24:32', '2026-07-31 03:24:32'),
(257, 242, 40.00, '1', '1', 'Admin', '2026-07-31 03:24:32', '2026-07-31 03:24:32'),
(258, 243, 6.00, '1', '1', 'Admin', '2026-07-31 03:24:38', '2026-07-31 03:24:38'),
(259, 244, 25.00, '1', '1', 'Admin', '2026-07-31 03:24:38', '2026-07-31 03:24:38');

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
(242, 'BMW 2 Series 2017-2021 Headlight', 'BMW-F22-LED-L', 'LED Left', NULL, NULL, NULL, 1050.00, NULL, NULL, NULL, 40.00, 0.00, 2, '1', '1', 'Admin', '2026-07-31 03:24:32', '2026-07-31 03:24:32', 1),
(243, 'BMW 1 Series 2012-2016 Headlight', 'BMW-F20-XEN-R', 'Xenon Right', NULL, NULL, NULL, 800.00, NULL, NULL, NULL, 6.00, 0.00, 14, '1', '1', 'Admin', '2026-07-31 03:24:38', '2026-07-31 03:24:38', 11),
(244, 'BMW 3 Series 2013-2015 Headlight', 'BMW-F35-XEN-R', 'Xenon Right', NULL, NULL, NULL, 550.00, NULL, NULL, NULL, 25.00, 0.00, 2, '1', '1', 'Admin', '2026-07-31 03:24:38', '2026-07-31 03:24:38', 1),
(245, 'BMW 1 Series 2012-2016 Headlight', 'BMW-F20-XEN-L', NULL, 'BMW 1 Series 2012-2016 Headlight', NULL, NULL, 800.00, NULL, NULL, NULL, 10.00, 10.00, 3, '1', '1', 'eBay Import', '2026-07-31 05:36:26', '2026-07-31 06:00:54', NULL);

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
(11, 5, 'EBAY-110590104029-10000009667110', '110590104029-10000009667110', 16, '2026-07-31', 0.00, 8000.00, '1', '1', 'eBay Sync', '2026-07-31 06:00:54', '2026-07-31 06:00:54');

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
(12, 11, 245, 10.00, 0.00, 800.00, 8000.00, '1', '1', NULL, '2026-07-31 06:00:54', '2026-07-31 06:00:54');

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
('byEljzrKOrROt0UwLGfax6WPGrBGW8ux6IPUxKZl', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0', 'eyJfdG9rZW4iOiJMRHd5SlFSdGY1SGREWW9vdVV3RjdhTWdUR0g5ODA4OGpmVGpxajdKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2F0b3pjb2Rlci0wMDI1NC50ZXN0XC9wcm9kdWN0cyIsInJvdXRlIjoicHJvZHVjdHMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1786018517),
('QEARsjrC1NKJQbuA3bweemazBQeVkOWPlLTME5Jl', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'eyJfdG9rZW4iOiI1TjVRTXhQODB2d2NrWlRNczJVS1gxd2ZFUjZwUFhUS0V3b3FBckZGIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2F0b3pjb2Rlci0wMDI1NC50ZXN0XC9yZXR1cm5zIiwicm91dGUiOiJyZXR1cm5zLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1785499001);

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
(1, 'abc-1', 2, '1', '1', 'Admin', '2026-07-30 01:04:51', '2026-07-30 01:04:51'),
(11, 'abc-2', 14, '1', '1', 'Admin', '2026-07-31 02:15:43', '2026-07-31 02:15:43');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ebay_accounts`
--
ALTER TABLE `ebay_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ebay_listings`
--
ALTER TABLE `ebay_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `smtp_settings`
--
ALTER TABLE `smtp_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
