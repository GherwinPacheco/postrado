-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2024 at 03:57 PM
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
-- Database: `postrado`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `wood_id` int(11) NOT NULL DEFAULT 0,
  `varnish` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `product_id`, `price_id`, `quantity`, `color_id`, `wood_id`, `varnish`, `date_created`, `added_by`) VALUES
(130, 1, 71, 1, 0, 1, 0, '2024-08-30 12:45:35', 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `archived` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `archived`) VALUES
(1, 'Door', 0),
(2, 'Hamba', 0),
(3, 'Cabinet', 0);

-- --------------------------------------------------------

--
-- Table structure for table `category_specs`
--

CREATE TABLE `category_specs` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `specs_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_specs`
--

INSERT INTO `category_specs` (`id`, `category_id`, `specs_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 2, 2),
(5, 2, 6),
(6, 2, 7),
(7, 3, 1),
(8, 3, 2),
(9, 3, 4),
(10, 3, 9),
(11, 3, 12),
(12, 3, 13),
(13, 3, 14),
(17, 5, 1),
(18, 5, 2),
(19, 5, 4),
(20, 5, 5),
(22, 3, 11),
(23, 3, 10),
(29, 6, 16),
(30, 6, 17),
(31, 6, 18),
(32, 6, 19),
(33, 6, 20),
(34, 6, 21),
(35, 6, 22),
(36, 6, 23),
(40, 7, 1),
(41, 7, 2),
(42, 4, 1),
(43, 4, 2),
(44, 4, 3),
(45, 8, 1),
(47, 10, 3),
(49, 11, 25),
(50, 12, 1),
(51, 13, 5),
(52, 9, 2);

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `id` int(11) NOT NULL,
  `color_name` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `archived` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `color`
--

INSERT INTO `color` (`id`, `color_name`, `price`, `archived`) VALUES
(4, 'Light Woodstained', 200, 0),
(5, 'Dark Woodstained', 250, 0),
(6, 'Sample Woodstained', 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `varnish_price` float DEFAULT NULL,
  `for_deliver` float DEFAULT NULL,
  `for_install` float DEFAULT NULL,
  `deliver_install` float NOT NULL,
  `down_payment` int(11) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `store_address` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `location_link` varchar(255) NOT NULL,
  `gcash_name` varchar(255) NOT NULL,
  `gcash_number` varchar(255) NOT NULL,
  `terms_heading` mediumtext DEFAULT NULL,
  `mailer_email` varchar(255) DEFAULT NULL,
  `mailer_pass` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `varnish_price`, `for_deliver`, `for_install`, `deliver_install`, `down_payment`, `business_name`, `store_address`, `contact_no`, `location_link`, `gcash_name`, `gcash_number`, `terms_heading`, `mailer_email`, `mailer_pass`) VALUES
(1, 200, 100, 500, 600, 40, 'Postrado', '83V9+5QW, Manila National Rd, BiÃ±an, 4024 Laguna', '(049)429-4811', 'https://maps.app.goo.gl/6pd9tEJbLco4KDdP6', 'Adonis Postrado', '09494294811', 'These terms and conditions are rules given to our customers while using and purchasing products through the website. Please read these terms carefully before making a purchase:', 'postradowoodworks@gmail.com', 'bnfr jhhg vzrk lbnf');

-- --------------------------------------------------------

--
-- Table structure for table `custom_products`
--

CREATE TABLE `custom_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` float DEFAULT NULL,
  `varnish` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `wood_id` int(11) NOT NULL DEFAULT 0,
  `pickup_method` varchar(255) NOT NULL,
  `down_payment` float DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `request_status` varchar(255) NOT NULL DEFAULT 'admin_pending',
  `cancel_details` varchar(255) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  `image_count` int(11) DEFAULT NULL,
  `sketch_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_products`
--

INSERT INTO `custom_products` (`id`, `product_name`, `category_id`, `quantity`, `price`, `varnish`, `color_id`, `wood_id`, `pickup_method`, `down_payment`, `completion_date`, `description`, `request_status`, `cancel_details`, `date_created`, `added_by`, `image_count`, `sketch_count`) VALUES
(7, 'Custom_Door#7', 1, 1, 5000, 0, 4, 1, 'for pickup', NULL, NULL, 'Hi ako po si BINI Mikha', 'ordered', NULL, '2024-08-30 13:48:54', 2, 1, 1),
(8, 'Custom_Door#8', 1, 1, 300, 1, 4, 1, 'for pickup', 300, '2027-09-09', 'papabutasan ng dalawa para sa door knob pati secondary lock', 'ordered', NULL, '2024-08-30 22:24:20', 2, 1, 2),
(9, 'Custom_Door#9', 1, 1, 33, 0, 4, 2, 'for pickup', 23, '2025-09-09', 'hnjdhjddjjdjdjdjjd asdnklasdkjljkasdadjs asdklasd ljkasd', 'ordered', NULL, '2024-09-12 15:15:52', 2, 1, 2),
(10, 'Custom_Hamba#10', 2, 1, 300, 0, 4, 1, 'for pickup', 300, '2025-09-09', '12313123', 'ordered', NULL, '2024-09-12 15:19:00', 2, 1, 1),
(11, 'Custom_Door#11', 1, 3, 500, 0, 5, 1, 'for pickup', 1000, '2025-09-09', 'asdasdad', 'ordered', NULL, '2024-09-12 15:20:26', 2, 1, 2),
(12, 'Custom_Hamba#12', 2, 4, NULL, 0, 5, 2, 'for pickup', NULL, NULL, 'hddhdhd', 'declined', 'Ayoko na. ', '2024-09-12 15:42:54', 2, 5, NULL),
(13, 'Custom_Cabinet#13', 3, 10, 20, 0, 4, 2, 'for pickup', 200, '2025-09-09', 'asdasdasd', 'ordered', NULL, '2024-09-12 17:16:45', 2, 6, 2),
(14, 'Custom_Hamba#14', 2, 33, 20, 0, 4, 1, 'for deliver', 400, '2050-09-09', 'ssss', 'ordered', NULL, '2024-09-12 17:20:03', 2, 1, 1),
(15, 'Custom_Hamba#15', 2, 33, 400, 1, 5, 2, 'for installation', 0, '2025-09-09', 'asdasdasd', 'ordered', NULL, '2024-09-12 17:21:05', 2, 4, 3),
(16, 'Custom_Door#16', 1, 2, 390, 0, 4, 1, 'for installation', 500, '2025-09-09', 'bla bla bla bla bla bla bla bla', 'ordered', NULL, '2024-09-22 10:52:45', 2, 2, NULL),
(17, 'Custom_Door#17', 1, 2, 399, 0, 4, 1, 'for installation', 600, '2030-09-09', 'bla bla bla bla bla bla bla bla', 'ordered', NULL, '2024-09-22 10:53:11', 2, 2, 1),
(18, 'Custom_Hamba#18', 2, 2, NULL, 0, 0, 1, 'for deliver', NULL, NULL, '333', 'declined', 'Ayoko na. ', '2024-09-22 11:04:24', 2, 1, NULL),
(19, 'Custom_Hamba#19', 2, 2, 23, 0, 0, 2, 'for pickup', 40, '2026-09-09', 'adasda asd', 'ordered', NULL, '2024-09-22 15:13:53', 2, 1, 1),
(20, 'Custom_Door#20', 1, 1, 30, 0, 0, 1, 'for pickup', 30, '2026-09-09', 'weqweqeqeqweqweqweqweqwe', 'ordered', NULL, '2024-09-22 15:21:00', 2, 3, 2),
(21, 'Custom_Door#21', 1, 1, 30, 0, 0, 1, 'for pickup', 30, '2026-09-09', 'weqweqeqeqweqweqweqweqwe', 'ordered', NULL, '2024-09-22 15:21:00', 2, 3, 2),
(22, 'Custom_Cabinet#22', 3, 2, 22, 0, 0, 1, 'for pickup', 23, '2030-11-12', 'as', 'ordered', NULL, '2024-09-22 15:32:01', 2, 1, 2),
(24, 'Custom_Hamba#24', 2, 1, 300, 0, 4, 2, 'for pickup', 300, '2025-09-09', 'aad', 'updated', NULL, '2024-09-25 13:48:45', 2, 2, 3),
(25, 'Custom_Door#25', 1, 1, 300, 0, 0, 2, 'for pickup', 300, '2025-09-09', 'asdsd', 'ordered', NULL, '2024-09-25 13:49:50', 16, 2, 2),
(26, 'Custom_Hamba#26', 2, 2, 23, 0, 0, 1, 'for deliver', 46, '2025-09-09', 'asad', 'ordered', NULL, '2024-09-26 15:28:31', 16, 1, 2),
(27, 'Custom_Hamba#27', 2, 2, 4, 0, 0, 2, 'for pickup', 10, '2025-09-09', 'aaaaaa', 'updated', NULL, '2024-10-14 17:46:48', 16, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `custom_product_specs`
--

CREATE TABLE `custom_product_specs` (
  `id` int(11) NOT NULL,
  `custom_id` int(11) NOT NULL,
  `specs_name` varchar(255) NOT NULL,
  `specs_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_product_specs`
--

INSERT INTO `custom_product_specs` (`id`, `custom_id`, `specs_name`, `specs_value`) VALUES
(10, 7, 'Height', '70cm'),
(11, 7, 'Width', '200cm'),
(28, 15, 'Height', '500cm'),
(29, 11, 'Width', '200cm'),
(30, 10, 'Width', '123cm'),
(31, 9, 'Height', '222'),
(32, 8, 'Height', '231313'),
(33, 17, 'Height', '231313'),
(34, 17, 'Width', '213'),
(35, 13, 'Height', '231313'),
(36, 13, 'Height', '100xm'),
(37, 16, 'Height', '231313'),
(38, 14, 'Height', '231313'),
(39, 19, 'Height', '231313'),
(40, 20, 'Height', '231313'),
(41, 22, 'Width', '231313'),
(43, 25, 'Height', '100cm'),
(44, 24, 'Height', '100cm'),
(45, 24, 'Width', '100cm'),
(46, 24, 'Depth', '100cm'),
(47, 26, 'Height', '100cm'),
(48, 27, 'Height', '100cm');

-- --------------------------------------------------------

--
-- Table structure for table `email_verification`
--

CREATE TABLE `email_verification` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_verification`
--

INSERT INTO `email_verification` (`id`, `email`, `code`) VALUES
(147, 'josephampongan7@gmail.com', 927451),
(201, 'pachecogherwin8@gmail.com', 970581),
(211, 'gherwin.pacheco@cvsu.edu.ph', 787004);

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `minimum_qty` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `cost` float NOT NULL,
  `status` tinyint(11) NOT NULL,
  `archived` tinyint(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_archived` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `material_name`, `quantity`, `minimum_qty`, `unit`, `cost`, `status`, `archived`, `date_created`, `date_archived`, `added_by`) VALUES
(1, 'Lacquer Varnish', 3, 1, 5, 250, 1, 0, '2024-07-11 12:23:28', NULL, 1),
(2, 'Rugby', 3, 1, 5, 85, 1, 0, '2024-07-11 12:23:44', NULL, 1),
(3, 'Stikwel', 5, 2, 5, 300, 1, 0, '2024-07-11 12:24:08', NULL, 1),
(4, '2\" Nails', 3, 1, 9, 30, 1, 0, '2024-07-11 12:26:05', NULL, 1),
(6, '1\" Nails', 3, 1, 9, 50, 1, 0, '2024-07-11 12:28:58', NULL, 1),
(7, 'Finishing Nails', 3, 1, 9, 80, 1, 0, '2024-07-11 12:29:11', NULL, 1),
(8, 'Plywood', 8, 2, 1, 1200, 1, 0, '2024-08-15 15:17:55', NULL, 1),
(9, 'Plyboard 0.8mm', 10, 5, 1, 1300, 1, 0, '2024-08-15 15:18:06', NULL, 1),
(10, 'Plyboard 20mm', 12, 5, 1, 1520, 1, 0, '2024-08-15 15:18:19', NULL, 1),
(11, 'Gmilina Lumber', 10, 5, 1, 900, 1, 0, '2024-08-15 15:18:33', NULL, 1),
(12, 'Mahogany Lumber', 10, 5, 1, 800, 1, 0, '2024-08-15 15:18:51', NULL, 1),
(14, 'Light Woodstain Oil', 3, 1, 5, 250, 1, 0, '2024-08-27 22:32:41', NULL, 1),
(15, 'Dark Woodstain Oil', 3, 1, 5, 250, 1, 0, '2024-08-27 22:32:57', NULL, 1),
(16, 'Soft-Close Hinge', 12, 6, 1, 240, 1, 0, '2024-08-27 22:33:25', NULL, 1),
(17, 'Butt Hinge', 10, 4, 1, 85, 1, 0, '2024-08-27 22:33:51', NULL, 1),
(18, 'Drawer Lock', 5, 2, 1, 125, 1, 0, '2024-08-27 22:34:06', NULL, 1),
(19, 'Drawer Slide Jig', 10, 4, 1, 320, 1, 0, '2024-08-27 22:34:33', NULL, 1),
(20, 'Nickel Cabinet Knob', 8, 2, 1, 85, 1, 0, '2024-08-27 22:35:03', NULL, 1),
(21, '1\" Black Screw', 0, 1, 9, 125, 3, 0, '2024-08-27 22:36:38', NULL, 1),
(22, '2\" Black Screw', 1, 1, 9, 140, 2, 0, '2024-08-27 22:36:51', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `material_usage`
--

CREATE TABLE `material_usage` (
  `id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `mode` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material_usage`
--

INSERT INTO `material_usage` (`id`, `material_id`, `quantity`, `mode`, `date_created`, `added_by`) VALUES
(4, 7, 5, 'add', '2024-08-08 20:17:13', 1),
(5, 7, 9, 'deduct', '2024-08-08 20:17:25', 1),
(6, 7, 4, 'deduct', '2024-08-08 20:19:32', 1),
(7, 7, 2, 'deduct', '2024-08-08 20:19:50', 1),
(8, 7, 5, 'add', '2024-08-08 20:19:59', 1),
(9, 7, 8, 'deduct', '2024-08-15 14:23:26', 1),
(10, 6, 22, 'deduct', '2024-08-15 14:23:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `custom_id` int(11) DEFAULT NULL,
  `custom_name` varchar(255) DEFAULT NULL,
  `order_status` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `notif_status` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `receiver_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `order_id`, `custom_id`, `custom_name`, `order_status`, `message`, `notif_status`, `date_created`, `receiver_id`) VALUES
(43, 5, NULL, '0', 'pending', 'Your order has been submitted and is waiting for approval', 'read', '2024-08-30 13:23:04', 2),
(44, 6, NULL, '0', 'preparing', 'Your order has been approved and is being prepared now', 'read', '2024-08-30 14:45:26', 2),
(45, 9, NULL, '0', 'pending', 'Your order has been submitted and is waiting for approval', 'read', '2024-09-14 18:35:16', 2),
(46, 9, NULL, '0', 'preparing', 'Your order has been approved and is being prepared now', 'read', '2024-09-14 18:35:43', 2),
(47, 7, NULL, '0', 'declined', 'Your order has been declined by the admin', 'read', '2024-09-14 21:40:19', 2),
(48, 10, NULL, '0', 'pending', 'Your order has been submitted and is waiting for approval', 'read', '2024-09-14 21:46:29', 2),
(49, 5, NULL, '0', 'declined', 'Your order has been declined by the admin', 'read', '2024-09-14 22:19:45', 2),
(50, 10, NULL, '0', 'declined', 'Your order has been declined by the admin', 'read', '2024-09-14 22:20:15', 2),
(51, 11, NULL, '0', 'pending', 'Your order has been submitted and is waiting for approval', 'read', '2024-09-15 09:37:43', 2),
(52, 11, NULL, '0', 'preparing', 'Your order has been approved and is being prepared now', 'read', '2024-09-15 09:38:35', 2),
(53, NULL, 14, '0', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'read', '2024-09-22 10:31:04', 1),
(54, NULL, 13, '0', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'read', '2024-09-22 10:37:50', 2),
(55, NULL, 17, 'Custom_Door#17', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'unread', '2024-09-22 10:53:11', 2),
(56, NULL, 17, 'Custom_Door#17', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'read', '2024-09-22 10:53:54', 2),
(57, NULL, 16, 'Custom_Door#16', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'read', '2024-09-22 10:53:59', 2),
(58, 19, 17, 'Custom_Door#17', 'preparing', 'Your custom order Custom_Door#17 has been added as ORD-19 and is now being prepared.', 'read', '2024-09-22 10:55:24', 2),
(59, NULL, 18, 'Custom_Hamba#18', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'read', '2024-09-22 11:04:24', 2),
(60, NULL, 18, 'Custom_Hamba#18', 'custom_declined', 'Your request for custom order has been declined by the admin.', 'read', '2024-09-22 11:21:00', 2),
(61, 20, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'read', '2024-09-22 13:27:41', 2),
(62, 20, NULL, NULL, 'preparing', 'Your order has been approved and is being prepared now', 'read', '2024-09-22 13:28:50', 2),
(63, 20, NULL, NULL, 'preparing', 'Your order has been approved and is being prepared now', 'read', '2024-09-22 13:29:57', 2),
(64, 13, NULL, NULL, 'ready', 'Your order is now ready for pickup', 'read', '2024-09-22 13:30:25', 2),
(65, 21, 13, 'Custom_Cabinet#13', 'preparing', 'Your custom order Custom_Cabinet#13 has been added as ORD-21 and is now being prepared.', 'read', '2024-09-22 13:51:26', 2),
(66, 22, 16, 'Custom_Door#16', 'preparing', 'Your custom order Custom_Door#16 has been added as ORD-22 and is now being prepared.', 'read', '2024-09-22 15:10:13', 2),
(67, 23, 14, 'Custom_Hamba#14', 'preparing', 'Your custom order Custom_Hamba#14 has been added as ORD-23 and is now being prepared.', 'read', '2024-09-22 15:10:22', 2),
(68, NULL, 19, 'Custom_Hamba#19', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'read', '2024-09-22 15:13:53', 2),
(69, NULL, 19, 'Custom_Hamba#19', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'read', '2024-09-22 15:14:13', 2),
(70, 24, 19, 'Custom_Hamba#19', 'preparing', 'Your custom order Custom_Hamba#19 has been added as ORD-24 and is now being prepared.', 'read', '2024-09-22 15:19:07', 2),
(71, NULL, 20, 'Custom_Door#20', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'read', '2024-09-22 15:21:00', 2),
(72, NULL, 20, 'Custom_Door#20', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'read', '2024-09-22 15:21:14', 2),
(73, 25, 20, 'Custom_Door#20', 'preparing', 'Your custom order Custom_Door#20 has been added as ORD-25 and is now being prepared.', 'read', '2024-09-22 15:24:46', 2),
(74, 25, 20, 'Custom_Door#20', 'preparing', 'Your custom order Custom_Door#20 has been added as ORD-25 and is now being prepared.', 'read', '2024-09-22 15:27:52', 2),
(75, 25, 21, 'Custom_Door#21', 'preparing', 'Your custom order Custom_Door#21 has been added as ORD-25 and is now being prepared.', 'read', '2024-09-22 15:30:28', 2),
(76, NULL, 22, 'Custom_Cabinet#22', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'read', '2024-09-22 15:32:01', 2),
(77, NULL, 22, 'Custom_Cabinet#22', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'read', '2024-09-22 15:32:12', 2),
(78, 28, 22, 'Custom_Cabinet#22', 'preparing', 'Your custom order Custom_Cabinet#22 has been added as ORD-28 and is now being prepared.', 'read', '2024-09-22 15:33:01', 2),
(79, 29, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'unread', '2024-09-25 13:03:59', 16),
(80, 30, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'unread', '2024-09-25 13:04:57', 16),
(81, 31, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'unread', '2024-09-25 13:06:26', 16),
(82, 32, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'unread', '2024-09-25 13:07:28', 16),
(83, 32, NULL, NULL, 'preparing', 'Your order has been approved and is being prepared now', 'unread', '2024-09-25 13:09:48', 16),
(84, 31, NULL, NULL, 'preparing', 'Your order has been approved and is being prepared now', 'unread', '2024-09-25 13:14:51', 16),
(85, NULL, 23, 'Custom_Hamba#23', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'unread', '2024-09-25 13:48:27', 2),
(86, NULL, 24, 'Custom_Hamba#24', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'read', '2024-09-25 13:48:45', 2),
(87, NULL, 24, 'Custom_Hamba#24', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'read', '2024-09-25 13:49:22', 2),
(88, NULL, 25, 'Custom_Door#25', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'unread', '2024-09-25 13:49:50', 16),
(89, NULL, 25, 'Custom_Door#25', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'unread', '2024-09-25 13:50:37', 16),
(90, NULL, 25, 'Custom_Door#25', 'custom_updated', 'Your custom order\'s details has updated by the carpenter.', 'read', '2024-09-25 13:52:01', 16),
(91, NULL, 25, 'Custom_Door#25', 'custom_updated', 'Your custom order\'s details has updated by the carpenter.', 'unread', '2024-09-25 13:58:45', 16),
(92, 33, 25, 'Custom_Door#25', 'preparing', 'Your custom order Custom_Door#25 has been added as ORD-33 and is now being prepared.', 'read', '2024-09-25 14:11:29', 16),
(93, NULL, 24, 'Custom_Hamba#24', 'custom_updated', 'Your custom order\'s details has updated by the carpenter.', 'unread', '2024-09-25 18:04:34', 2),
(94, NULL, 26, 'Custom_Hamba#26', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'unread', '2024-09-26 15:28:31', 16),
(95, NULL, 26, 'Custom_Hamba#26', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'unread', '2024-09-26 15:28:51', 16),
(96, NULL, 26, 'Custom_Hamba#26', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'unread', '2024-09-26 15:28:54', 16),
(97, NULL, 26, 'Custom_Hamba#26', 'custom_updated', 'Your custom order\'s details has updated by the carpenter.', 'unread', '2024-09-26 15:29:33', 16),
(98, 34, 26, 'Custom_Hamba#26', 'preparing', 'Your custom order Custom_Hamba#26 has been added as ORD-34 and is now being prepared.', 'unread', '2024-09-26 15:29:49', 16),
(99, 30, NULL, NULL, 'preparing', 'Your order has been approved and is being prepared now', 'unread', '2024-09-26 15:30:59', 16),
(100, 29, NULL, NULL, 'declined', 'Your order has been declined by the admin', 'unread', '2024-10-12 02:46:48', 16),
(101, 35, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'unread', '2024-10-12 02:48:13', 2),
(102, 35, NULL, NULL, 'declined', 'Your order has been declined by the admin', 'unread', '2024-10-12 02:49:07', 2),
(103, 36, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'unread', '2024-10-12 02:50:54', 16),
(104, 37, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'unread', '2024-10-14 17:37:46', 16),
(105, NULL, 27, 'Custom_Hamba#27', 'custom_pending', 'Your request for custom order has been submitted and is waiting for admin\'s approval.', 'unread', '2024-10-14 17:46:48', 16),
(106, NULL, 27, 'Custom_Hamba#27', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'unread', '2024-10-14 17:49:23', 16),
(107, NULL, 27, 'Custom_Hamba#27', 'custom_approved', 'Your request for custom order has been approved by the admin.', 'unread', '2024-10-14 17:49:26', 16),
(108, NULL, 27, 'Custom_Hamba#27', 'custom_updated', 'Your custom order\'s details has updated by the carpenter.', 'unread', '2024-10-14 17:52:24', 16),
(109, 38, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'unread', '2024-10-14 18:05:42', 16),
(110, 39, NULL, NULL, 'pending', 'Your order has been submitted and is waiting for approval', 'unread', '2024-10-20 21:17:05', 16),
(111, 39, NULL, NULL, 'preparing', 'Your order has been approved and is being prepared now', 'unread', '2024-10-20 21:17:48', 16),
(112, 38, NULL, NULL, 'preparing', 'Your order has been approved and is being prepared now', 'unread', '2024-10-20 21:18:27', 16),
(113, 37, NULL, NULL, 'preparing', 'Your order has been approved and is being prepared now', 'unread', '2024-10-20 21:18:46', 16),
(114, 39, NULL, NULL, 'ready', 'Your order is now ready for pickup', 'unread', '2024-10-20 21:19:41', 16),
(115, 39, NULL, NULL, 'complete', 'Your order has been completed', 'unread', '2024-10-20 21:20:18', 16),
(116, 13, NULL, NULL, 'complete', 'Your order has been completed', 'unread', '2024-10-20 21:20:26', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `home_address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `completion_date` date NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `pickup_method` varchar(255) NOT NULL,
  `service_fee` float NOT NULL,
  `total` float NOT NULL,
  `paid_amount` float NOT NULL DEFAULT 0,
  `order_status` varchar(255) NOT NULL DEFAULT 'pending',
  `date_created` datetime NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_approved` datetime DEFAULT NULL,
  `date_completed` datetime DEFAULT NULL,
  `cancel_details` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `home_address`, `contact`, `completion_date`, `payment_method`, `pickup_method`, `service_fee`, `total`, `paid_amount`, `order_status`, `date_created`, `added_by`, `date_approved`, `date_completed`, `cancel_details`) VALUES
(4, 'Block 1 Lot 1 Barangay 1 Carmona Cavite', '09979979979', '2024-08-30', 'gcash', 'for deliver', 150, 23600, 23600, 'complete', '2024-08-18 21:31:46', 2, NULL, '2024-08-18 00:00:00', NULL),
(5, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2024-09-05', 'cash', 'for pickup', 0, 1700, 1000, 'declined', '2024-08-30 13:23:04', 2, NULL, NULL, 'aaaaaa'),
(6, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2024-09-22', 'cash', 'deliver and install', 600, 5600, 2240, 'preparing', '2024-08-30 14:42:57', 2, '2024-09-11 13:29:57', NULL, NULL),
(7, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '0000-00-00', 'cash', 'for installation', 500, 28550, 11420, 'declined', '2024-09-12 17:21:05', 2, NULL, NULL, 'Ayoko na. '),
(8, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '0000-00-00', 'cash', 'for pickup', 0, 2250, 1000, 'preparing', '2024-09-12 15:20:26', 2, '2024-09-11 13:29:57', NULL, NULL),
(9, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2025-09-09', 'cash', 'for deliver', 100, 4300, 4300, 'preparing', '2024-09-14 18:35:16', 2, '2024-09-11 13:29:57', NULL, NULL),
(10, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2026-09-09', 'cash', 'for pickup', 0, 17700, 0, 'cancelled', '2024-09-14 21:46:29', 2, NULL, NULL, 'Mali order ko. qqq'),
(11, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '0000-00-00', 'cash', 'for pickup', 0, 2400, 960, 'preparing', '2024-09-15 09:37:43', 2, '2024-09-11 13:29:57', NULL, NULL),
(12, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '0000-00-00', 'cash', 'for pickup', 0, 500, 300, 'preparing', '2024-09-12 15:19:00', 2, '2024-09-11 13:29:57', NULL, NULL),
(13, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '0000-00-00', 'cash', 'for pickup', 0, 233, 233, 'complete', '2024-09-12 15:15:52', 2, NULL, '2024-10-20 21:20:26', NULL),
(14, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '0000-00-00', 'cash', 'for installation', 500, 28550, 20000, 'preparing', '2024-09-12 17:21:05', 2, '2024-09-11 13:29:57', NULL, NULL),
(15, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '0000-00-00', 'cash', 'for installation', 500, 28550, 20000, 'preparing', '2024-09-12 17:21:05', 2, '2024-09-11 13:29:57', NULL, NULL),
(16, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '0000-00-00', 'cash', 'for installation', 500, 28550, 20000, 'preparing', '2024-09-12 17:21:05', 2, '2024-09-11 13:29:57', NULL, NULL),
(17, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2025-09-09', 'cash', 'for installation', 500, 28550, 11420, 'preparing', '2024-09-12 17:21:05', 2, '2024-09-11 13:29:57', NULL, NULL),
(18, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2027-09-09', 'cash', 'for pickup', 0, 700, 300, 'preparing', '2024-08-30 22:24:20', 2, '2024-09-11 13:29:57', NULL, NULL),
(19, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2030-09-09', 'cash', 'for installation', 500, 1698, 1000, 'preparing', '2024-09-22 10:53:11', 2, '2024-09-11 13:29:57', NULL, NULL),
(20, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2026-09-09', 'cash', 'for pickup', 0, 3900, 1560, 'preparing', '2024-09-22 13:27:41', 2, '2024-09-11 13:29:57', NULL, NULL),
(21, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2025-09-09', 'cash', 'for pickup', 0, 2200, 900, 'preparing', '2024-09-12 17:16:45', 2, '2024-09-11 13:29:57', NULL, NULL),
(22, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2025-09-09', 'cash', 'for installation', 500, 1680, 1680, 'preparing', '2024-09-22 10:52:45', 2, '2024-09-11 13:29:57', NULL, NULL),
(23, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2050-09-09', 'cash', 'for deliver', 100, 7360, 2944, 'preparing', '2024-09-12 17:20:03', 2, '2024-09-11 13:29:57', NULL, NULL),
(24, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2026-09-09', 'cash', 'for pickup', 0, 46, 40, 'preparing', '2024-09-22 15:13:53', 2, '2024-09-11 13:29:57', NULL, NULL),
(25, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2026-09-09', 'cash', 'for pickup', 0, 30, 30, 'preparing', '2024-09-22 15:21:00', 2, '2024-09-11 13:29:57', NULL, NULL),
(28, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2030-11-12', 'cash', 'for pickup', 0, 44, 23, 'preparing', '2024-09-22 15:32:01', 2, '2024-09-22 15:33:01', NULL, NULL),
(29, 'Address LMAO', '09099099909', '2025-09-09', 'cash', 'for pickup', 0, 1900, 0, 'declined', '2024-09-25 13:03:59', 16, NULL, NULL, 'Galit ako. sdddd'),
(30, 'Address LMAO', '09099099909', '2026-09-09', 'cash', 'for pickup', 0, 1700, 690, 'preparing', '2024-09-25 13:04:57', 16, '2024-09-26 15:30:59', NULL, NULL),
(31, 'Address LMAO', '09099099909', '3035-09-09', 'cash', 'for pickup', 0, 2100, 2100, 'preparing', '2024-09-25 13:06:26', 16, '2024-09-25 13:14:51', NULL, NULL),
(32, 'Address LMAO', '09099099909', '2024-12-09', 'cash', 'for pickup', 0, 1900, 1000, 'preparing', '2024-09-25 13:07:28', 16, '2024-09-25 13:09:48', NULL, NULL),
(33, 'Address LMAO', '09099099909', '2025-09-09', 'cash', 'for pickup', 0, 300, 300, 'preparing', '2024-09-25 13:49:50', 16, '2024-09-25 14:11:29', NULL, NULL),
(34, 'Address LMAO', '09099099909', '2025-09-09', 'cash', 'for deliver', 100, 146, 146, 'preparing', '2024-09-26 15:28:31', 16, '2024-09-26 15:29:49', NULL, NULL),
(35, 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '2025-09-02', 'cash', 'for pickup', 0, 8000, 0, 'declined', '2024-10-12 02:48:13', 2, NULL, NULL, 'Galit ako. zzzzzz'),
(36, 'Address LMAO', '09099099909', '2025-09-09', 'cash', 'for deliver', 100, 2200, 0, 'cancelled', '2024-10-12 02:50:54', 16, NULL, NULL, 'Galit ako. zzzzzzzzzzzzzzzzzzzzzz'),
(37, 'Address LMAO', '09099099909', '2025-09-09', 'cash', 'for deliver', 100, 8500, 8500, 'preparing', '2024-10-14 17:37:46', 16, '2024-10-20 21:18:46', NULL, NULL),
(38, 'Address LMAO', '09099099909', '2025-09-09', 'cash', 'for deliver', 100, 16500, 16500, 'preparing', '2024-10-14 18:05:42', 16, '2024-10-20 21:18:27', NULL, NULL),
(39, 'Address LMAO', '09099099909', '2025-09-09', 'cash', 'for pickup', 0, 1700, 1700, 'complete', '2024-10-20 21:17:05', 16, '2024-10-20 21:17:48', '2024-10-20 21:20:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'normal',
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_id` int(11) DEFAULT NULL,
  `variant_name` varchar(255) DEFAULT NULL,
  `variant_price` float NOT NULL,
  `varnish_price` float NOT NULL,
  `color_id` int(11) NOT NULL,
  `color_price` float NOT NULL,
  `wood_id` int(11) NOT NULL DEFAULT 0,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `type`, `product_id`, `product_name`, `quantity`, `price_id`, `variant_name`, `variant_price`, `varnish_price`, `color_id`, `color_price`, `wood_id`, `total`) VALUES
(1, 1, 'normal', 1, 'Main Panel Door', 2, 1, 'Normal', 5500, 50, 1, 150, 0, 11400),
(11, 4, 'normal', 4, 'Hamba for Flush Door', 1, 4, 'Normal', 1700, 0, 1, 150, 0, 1850),
(12, 5, 'normal', 2, 'Flush Door', 1, 70, 'Standard', 1700, 0, 0, 0, 0, 1700),
(13, 6, 'custom', 7, 'Custom_Door#7', 1, NULL, NULL, 5000, 0, 0, 0, 0, 5000),
(14, 7, 'custom', 15, 'Custom_Hamba#15', 33, NULL, NULL, 400, 200, 5, 250, 0, 28050),
(15, 8, 'custom', 11, 'Custom_Door#11', 3, NULL, NULL, 500, 0, 5, 250, 0, 2250),
(16, 9, 'normal', 4, 'Hamba', 2, 68, 'for Flush Door', 1700, 200, 4, 200, 0, 4200),
(17, 10, 'normal', 1, 'Main Panel Door', 3, 71, 'Standard', 5500, 200, 4, 200, 0, 17700),
(18, 11, 'normal', 4, 'Hamba', 1, 69, 'for Panel Door', 2000, 200, 4, 200, 0, 2400),
(19, 12, 'custom', 10, 'Custom_Hamba#10', 1, NULL, NULL, 300, 0, 4, 200, 0, 500),
(20, 13, 'custom', 9, 'Custom_Door#9', 1, NULL, NULL, 33, 0, 4, 200, 0, 233),
(21, 7, 'custom', 15, 'Custom_Hamba#15', 33, NULL, NULL, 400, 200, 5, 250, 0, 28050),
(22, 7, 'custom', 15, 'Custom_Hamba#15', 33, NULL, NULL, 400, 200, 5, 250, 0, 28050),
(23, 7, 'custom', 15, 'Custom_Hamba#15', 33, NULL, NULL, 400, 200, 5, 250, 0, 28050),
(24, 7, 'custom', 15, 'Custom_Hamba#15', 33, NULL, NULL, 400, 200, 5, 250, 0, 28050),
(25, 18, 'custom', 8, 'Custom_Door#8', 1, NULL, NULL, 300, 200, 4, 200, 0, 700),
(26, 19, 'custom', 17, 'Custom_Door#17', 2, NULL, NULL, 399, 0, 4, 200, 0, 1198),
(27, 20, 'normal', 4, 'Hamba', 2, 68, 'for Flush Door', 1700, 0, 5, 250, 0, 3900),
(28, 21, 'custom', 13, 'Custom_Cabinet#13', 10, NULL, NULL, 20, 0, 4, 200, 0, 2200),
(29, 22, 'custom', 16, 'Custom_Door#16', 2, NULL, NULL, 390, 0, 4, 200, 0, 1180),
(30, 23, 'custom', 14, 'Custom_Hamba#14', 33, NULL, NULL, 20, 0, 4, 200, 0, 7260),
(31, 24, 'custom', 19, 'Custom_Hamba#19', 2, NULL, NULL, 23, 0, 0, 0, 0, 46),
(32, 25, 'custom', 20, 'Custom_Door#20', 1, NULL, NULL, 30, 0, 0, 0, 0, 30),
(35, 28, 'custom', 22, 'Custom_Cabinet#22', 2, NULL, NULL, 22, 0, 0, 0, 0, 44),
(36, 29, 'normal', 4, 'Hamba', 1, 68, 'for Flush Door', 1700, 200, 0, 0, 0, 1900),
(37, 30, 'normal', 4, 'Hamba', 1, 68, 'for Flush Door', 1700, 0, 0, 0, 0, 1700),
(38, 31, 'normal', 4, 'Hamba', 1, 68, 'for Flush Door', 1700, 200, 4, 200, 0, 2100),
(39, 32, 'normal', 4, 'Hamba', 1, 68, 'for Flush Door', 1700, 200, 0, 0, 0, 1900),
(40, 33, 'custom', 25, 'Custom_Door#25', 1, NULL, NULL, 300, 0, 0, 0, 0, 300),
(41, 34, 'custom', 26, 'Custom_Hamba#26', 2, NULL, NULL, 23, 0, 0, 0, 0, 46),
(42, 35, 'normal', 5, 'Kitchen Wall Cabinet', 1, 67, 'Standard', 8000, 0, 0, 0, 0, 8000),
(43, 36, 'normal', 4, 'Hamba', 1, 68, 'for Flush Door', 1700, 200, 4, 200, 0, 2100),
(44, 37, 'normal', 5, 'Kitchen Wall Cabinet', 1, 67, 'Standard', 8000, 200, 4, 200, 0, 8400),
(45, 38, 'normal', 5, 'Kitchen Wall Cabinet', 2, 67, 'Standard', 8000, 200, 0, 0, 0, 16400),
(46, 39, 'normal', 2, 'Flush Door', 1, 70, 'Standard', 1700, 0, 0, 0, 0, 1700);

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `price_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `product_id`, `price`, `price_name`) VALUES
(3, 3, 2000, 'Normal'),
(9, 9, 1500, '2 seater'),
(10, 9, 3000, '4 seater'),
(11, 9, 5000, '6 seater'),
(27, 10, 1500, 'Normal'),
(50, 16, 9000, '2 Seater'),
(51, 16, 11000, '4 Seater'),
(53, 7, 1500, 'Normal'),
(67, 5, 8000, 'Standard'),
(68, 4, 1700, 'for Flush Door'),
(69, 4, 2000, 'for Panel Door'),
(70, 2, 1700, 'Standard'),
(71, 1, 5500, 'Standard'),
(72, 8, 12000, 'Standard');

-- --------------------------------------------------------

--
-- Table structure for table `price_specs`
--

CREATE TABLE `price_specs` (
  `id` int(11) NOT NULL,
  `price_id` int(11) NOT NULL,
  `specs_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `price_specs`
--

INSERT INTO `price_specs` (`id`, `price_id`, `specs_id`, `value`) VALUES
(5, 3, 1, '90cm'),
(6, 3, 2, '210cm'),
(7, 3, 6, '5cm'),
(8, 3, 7, '13cm'),
(49, 9, 1, '76cm'),
(50, 9, 2, '74cm'),
(51, 9, 3, '107cm'),
(52, 10, 1, '76cm'),
(53, 10, 2, '74cm'),
(54, 10, 3, '152cm'),
(55, 11, 1, '107cm'),
(56, 11, 2, '74cm'),
(57, 11, 3, '244cm'),
(62, 13, 1, '11'),
(63, 13, 2, '1'),
(64, 13, 6, '1'),
(65, 13, 7, '1'),
(78, 17, 1, '1'),
(79, 17, 2, '1'),
(80, 17, 6, '1'),
(81, 17, 7, '1'),
(82, 18, 1, '1'),
(83, 18, 2, '1'),
(84, 18, 6, '1'),
(85, 18, 7, '1'),
(86, 19, 1, ''),
(87, 19, 2, ''),
(88, 19, 6, ''),
(89, 19, 7, ''),
(90, 20, 1, ''),
(91, 20, 2, ''),
(92, 21, 1, '1'),
(93, 21, 2, '1'),
(94, 22, 1, '1'),
(95, 22, 2, '1'),
(108, 27, 1, '42cm'),
(109, 27, 2, '89cm'),
(110, 27, 4, '45cm'),
(111, 27, 5, '49cm'),
(288, 50, 16, '76cm'),
(289, 50, 17, '74cm'),
(290, 50, 18, '152cm'),
(291, 50, 19, '42cm'),
(292, 50, 20, '89cm'),
(293, 50, 21, '48cm'),
(294, 50, 22, '45cm'),
(295, 50, 23, '2 chairs'),
(296, 51, 16, '107cm'),
(297, 51, 17, '74cm'),
(298, 51, 18, '244cm'),
(299, 51, 19, '42cm'),
(300, 51, 20, '49cm'),
(301, 51, 21, '48cm'),
(302, 51, 22, '45cm'),
(303, 51, 23, '4 chairs'),
(313, 53, 1, '91cm'),
(314, 53, 2, '89cm'),
(315, 53, 4, '60cm'),
(316, 53, 9, '3'),
(317, 53, 12, ''),
(318, 53, 13, ''),
(319, 53, 14, ''),
(320, 53, 11, 'Butt Hinges'),
(321, 53, 10, '2'),
(403, 67, 1, '91cm'),
(404, 67, 2, '89cm'),
(405, 67, 4, '60cm'),
(406, 67, 9, '3'),
(407, 67, 12, '2'),
(408, 67, 13, ''),
(409, 67, 14, ''),
(410, 67, 11, 'Soft-Close'),
(411, 67, 10, '2'),
(412, 68, 1, '80cm'),
(413, 68, 2, '210cm'),
(414, 68, 6, '5cm'),
(415, 68, 7, '13cm'),
(416, 69, 1, '80cm'),
(417, 69, 2, '210cm'),
(418, 69, 6, '5cm'),
(419, 69, 7, '13cm'),
(420, 70, 1, '70cm'),
(421, 70, 2, '210cm'),
(422, 71, 1, '80cm'),
(423, 71, 2, '210cm'),
(424, 72, 1, '80cm'),
(425, 72, 2, '119cm'),
(426, 72, 4, '47cm'),
(427, 72, 9, '2'),
(428, 72, 12, '2'),
(429, 72, 13, 'Left Side'),
(430, 72, 14, '7'),
(431, 72, 11, 'None'),
(432, 72, 10, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sale` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `production_duration` int(11) DEFAULT NULL,
  `archived` tinyint(11) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL,
  `date_archived` datetime DEFAULT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `category`, `description`, `sale`, `status`, `production_duration`, `archived`, `date_created`, `date_archived`, `added_by`) VALUES
(1, 'Main Panel Door', 1, 'A sturdy door with a central panel design, typically used as the primary entryway for homes.', NULL, 1, 4, 0, '2024-07-11 12:55:31', NULL, 1),
(2, 'Flush Door', 1, 'A smooth, flat door with a sleek surface, often used for interior rooms.', NULL, 1, 4, 0, '2024-07-11 12:56:21', NULL, 1),
(4, 'Hamba', 2, 'A wooden frame used for doors to provide structural support and decorative finishing.', NULL, 1, 4, 0, '2024-07-11 13:00:28', NULL, 1),
(5, 'Kitchen Wall Cabinet', 3, 'An elevated storage unit mounted on the wall, designed to hold kitchen essentials and save counter space.', NULL, 0, 12, 0, '2024-07-11 13:06:29', NULL, 1),
(7, 'Kitchen Base Cabinet', 3, 'A floor-standing storage unit that supports countertops and provides ample space for kitchen utensils and appliances', NULL, 1, 4, 1, '2024-07-11 13:12:44', '2024-08-30 22:48:00', 1),
(8, 'Closet Cabinet', 3, 'A versatile storage solution for organizing items in any room, often featuring shelves and drawers.', NULL, 0, 16, 1, '2024-07-11 13:14:41', '2024-08-30 22:29:54', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_materials`
--

CREATE TABLE `product_materials` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_materials`
--

INSERT INTO `product_materials` (`id`, `product_id`, `material_id`) VALUES
(6, 3, 2),
(7, 3, 1),
(8, 3, 3),
(16, 6, 2),
(17, 6, 1),
(18, 6, 6),
(19, 6, 3),
(27, 9, 2),
(28, 9, 1),
(29, 9, 3),
(35, 11, 2),
(36, 11, 1),
(43, 12, 2),
(44, 12, 1),
(45, 13, 2),
(46, 13, 1),
(47, 14, 2),
(48, 14, 1),
(49, 15, 2),
(50, 15, 1),
(57, 10, 2),
(58, 10, 1),
(59, 10, 4),
(60, 10, 6),
(61, 10, 3),
(95, 16, 2),
(96, 16, 1),
(97, 16, 3),
(101, 7, 2),
(102, 7, 1),
(103, 7, 3),
(183, 5, 21),
(184, 5, 6),
(185, 5, 4),
(186, 5, 1),
(187, 5, 14),
(188, 5, 20),
(189, 5, 10),
(190, 5, 16),
(191, 5, 3),
(192, 4, 4),
(193, 4, 11),
(194, 4, 12),
(195, 2, 6),
(196, 2, 15),
(197, 2, 1),
(198, 2, 14),
(199, 2, 12),
(200, 2, 8),
(201, 2, 3),
(202, 1, 15),
(203, 1, 11),
(204, 1, 1),
(205, 1, 14),
(206, 1, 12),
(207, 8, 21),
(208, 8, 6),
(209, 8, 17),
(210, 8, 15),
(211, 8, 18),
(212, 8, 19),
(213, 8, 10),
(214, 8, 8),
(215, 8, 3);

-- --------------------------------------------------------

--
-- Table structure for table `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL,
  `added_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reason_options`
--

CREATE TABLE `reason_options` (
  `id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `archived` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reason_options`
--

INSERT INTO `reason_options` (`id`, `reason`, `type`, `archived`) VALUES
(1, 'Ayoko na', 'customer_cancel', 0),
(2, 'Ayoko na', 'admin_decline', 0),
(3, 'Galit ako', 'customer_cancel', 0),
(4, 'Galit ako', 'admin_decline', 0),
(5, 'Mali order ko', 'customer_cancel', 0),
(6, 'Wala ako sa moods', 'admin_decline', 0),
(7, 'LMAO AMPANGIT NUNG PRODUCT', 'customer_cancel', 0),
(8, 'LMAO HHHHA', 'customer_cancel', 1),
(9, 'Yeasss', 'admin_decline', 1);

-- --------------------------------------------------------

--
-- Table structure for table `specs`
--

CREATE TABLE `specs` (
  `id` int(11) NOT NULL,
  `specs_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specs`
--

INSERT INTO `specs` (`id`, `specs_name`) VALUES
(1, 'Width'),
(2, 'Height'),
(3, 'Length'),
(4, 'Depth'),
(5, 'Back Seat Height'),
(6, 'Top & Side Jamb Width'),
(7, 'Jamb Thickness'),
(9, 'Divider'),
(10, 'Doors'),
(11, 'Hinge Type'),
(12, 'Row'),
(13, 'Hanger'),
(14, 'Drawer'),
(16, 'Table Width'),
(17, 'Table Height'),
(18, 'Table Length'),
(19, 'Chair Width'),
(20, 'Chair Width'),
(21, 'Chair Depth'),
(22, 'Back Seat Height'),
(23, 'Chair Capacity');

-- --------------------------------------------------------

--
-- Table structure for table `terms_conditions`
--

CREATE TABLE `terms_conditions` (
  `id` int(11) NOT NULL,
  `title` mediumtext NOT NULL,
  `content` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terms_conditions`
--

INSERT INTO `terms_conditions` (`id`, `title`, `content`) VALUES
(40, 'Product Information', 'All product descriptions and images on our website are for reference only. We make every effort to ensure the accuracy of the information provided, but we do not warrant the accuracy or reliability of any product details.'),
(41, 'Product Availability', 'Furniture product availability is subject to change without notice. We reserve the right to discontinue or modify products at any time.'),
(42, 'Pricing', 'Prices for our furniture products are subject to change. The price displayed at the time of your purchase is the final and applicable price. No discussion about discounts will be allowed.\r\n'),
(43, 'Approval Process', 'Approval of order will be processed only after the partial down payment has been settled. Failing to settle down payment of order within the working hours will be automatically declined.'),
(44, 'Production', 'Each furniture has an assigned duration for production. This will be a guide before choosing expected date in checkout. The ordered product will be held for you once it completed the production process, and further instructions will be provided for the completion of the purchase.'),
(45, 'Cancellation of Order', 'Only the pending for approval orders will be subjected for cancellation. Orders that already settled down payment will not be applicable for cancelling. ');

-- --------------------------------------------------------

--
-- Table structure for table `unit_of_measurement`
--

CREATE TABLE `unit_of_measurement` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(255) NOT NULL,
  `archived` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit_of_measurement`
--

INSERT INTO `unit_of_measurement` (`id`, `unit_name`, `archived`) VALUES
(1, 'Piece', 0),
(5, 'Bottle', 0),
(6, 'Kilo', 0),
(9, 'Box', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `home_address` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `status` int(11) DEFAULT 1,
  `date_created` datetime NOT NULL,
  `remember_cookie` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `first_name`, `last_name`, `suffix`, `home_address`, `contact`, `password`, `role`, `status`, `date_created`, `remember_cookie`) VALUES
(1, 'Admin', 'adonispostrado@gmail.com', 'Adonis', 'Postrado', '', '83V9+5QW, Manila National Rd, BiÃ±an, 4024 Laguna', '09494294811', '$2y$10$W0woY.Lgp86N8BKgyLjtSOXmODTfQaZaBvRSgGYyLnlPkFIz2Mxc2', 1, 1, '2024-06-20 19:17:00', NULL),
(2, 'Customer', 'parkjisoongtwice@gmail.com', 'Jisoo', 'Park', '', 'Block 8 Lot 6 Barangay 1 Carmona Cavite', '09918488021', '$2y$10$NvkNUxgvX9t9IUDvAkbFr.0qEwM.EHpplvOIbpgzf//h4uTv53.MS', 3, 1, '2024-06-20 19:17:00', '67150b404bdc8'),
(3, 'Carpenter', 'junboao@gmail.com', 'Jun', 'Boao', '', 'Block 1 Lot 1 Barangay 1 Canlalay Laguna', '09979979979', '$2y$10$W0woY.Lgp86N8BKgyLjtSOXmODTfQaZaBvRSgGYyLnlPkFIz2Mxc2', 2, 1, '2024-06-20 19:17:00', NULL),
(15, 'jpoggers', 'josephampongan7@gmail.com', 'Joseph', 'Ampongan', '', 'Blk. 28 LRT Extension Brgy. Gregoria de Jesus GMA Cavite', '09071043495', '$2y$10$.nZEhAbmHJayHKgD6L2fVe3ekg5C5ha61xjHshXHZgFSFKSjO3Riq', 3, 1, '2024-09-03 17:53:25', '66d6dfa716105'),
(16, 'TaongWood', 'pachecogherwin8@gmail.com', 'Taong', 'Wood', '', 'Address LMAO', '09099099909', '$2y$10$NvkNUxgvX9t9IUDvAkbFr.0qEwM.EHpplvOIbpgzf//h4uTv53.MS', 3, 1, '2024-09-22 09:09:25', NULL),
(25, 'LMAO', 'verynaice8@gmail.com', 'LMAO', 'XD', '', 'Block 1 Lot 1 Barangay 1 Carmona Cavite', '09099099909', '$2y$10$xzdp7869KW0vn89QUp2PwO2yQl5sBOedGrY9TUXoF7CRAip7JbQo.', 1, 1, '2024-09-25 12:57:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wood_type`
--

CREATE TABLE `wood_type` (
  `id` int(11) NOT NULL,
  `wood_name` varchar(255) NOT NULL,
  `archived` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wood_type`
--

INSERT INTO `wood_type` (`id`, `wood_name`, `archived`) VALUES
(1, 'Normal Wood', 0),
(2, 'Special Wood', 0),
(3, 'Legendary Woodss', 1),
(4, '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_specs`
--
ALTER TABLE `category_specs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_products`
--
ALTER TABLE `custom_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_product_specs`
--
ALTER TABLE `custom_product_specs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_verification`
--
ALTER TABLE `email_verification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_usage`
--
ALTER TABLE `material_usage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_specs`
--
ALTER TABLE `price_specs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_materials`
--
ALTER TABLE `product_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reason_options`
--
ALTER TABLE `reason_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specs`
--
ALTER TABLE `specs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit_of_measurement`
--
ALTER TABLE `unit_of_measurement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wood_type`
--
ALTER TABLE `wood_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `category_specs`
--
ALTER TABLE `category_specs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `custom_products`
--
ALTER TABLE `custom_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `custom_product_specs`
--
ALTER TABLE `custom_product_specs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `email_verification`
--
ALTER TABLE `email_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `material_usage`
--
ALTER TABLE `material_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `price_specs`
--
ALTER TABLE `price_specs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=433;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_materials`
--
ALTER TABLE `product_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reason_options`
--
ALTER TABLE `reason_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `specs`
--
ALTER TABLE `specs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `unit_of_measurement`
--
ALTER TABLE `unit_of_measurement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `wood_type`
--
ALTER TABLE `wood_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
