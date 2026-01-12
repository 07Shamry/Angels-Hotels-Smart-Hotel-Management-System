-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2026 at 09:12 PM
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
-- Database: `hotelchain_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `ip_address`, `timestamp`) VALUES
(1, 6, 'User Logged In', '::1', '2025-11-26 17:28:27'),
(2, 6, 'Booked Room 1 for $450', '::1', '2025-11-26 17:32:13'),
(3, 6, 'User Logged In', '::1', '2025-11-26 18:30:56'),
(4, 6, 'User Logged In', '::1', '2025-11-26 18:35:43'),
(5, 6, 'User Logged In', '::1', '2025-11-26 18:42:40'),
(6, 6, 'User Logged In', '::1', '2025-11-27 07:02:47'),
(7, 1, 'User Logged In', '::1', '2025-11-27 07:39:46'),
(8, 6, 'User Logged In', '::1', '2025-11-27 08:00:05'),
(9, 1, 'User Logged In', '::1', '2025-11-27 08:00:33'),
(10, 1, 'User Logged In', '::1', '2025-11-27 08:05:15'),
(11, 6, 'User Logged In', '::1', '2025-11-27 08:07:09'),
(12, 1, 'User Logged In', '::1', '2025-11-27 08:07:44'),
(13, 3, 'User Logged In', '::1', '2025-11-27 08:16:41'),
(14, 4, 'User Logged In', '::1', '2025-11-27 08:18:34'),
(15, 1, 'User Logged In', '::1', '2025-11-27 08:24:04'),
(16, 1, 'Added Staff: alf@gmail.com', '::1', '2025-11-27 08:25:49'),
(17, 6, 'User Logged In', '::1', '2025-11-27 08:26:35'),
(18, 7, 'User Logged In', '::1', '2025-11-27 08:27:40'),
(19, 6, 'User Logged In', '::1', '2025-11-27 08:29:30'),
(20, 3, 'User Logged In', '::1', '2025-11-27 08:30:33'),
(21, 7, 'User Logged In', '::1', '2025-11-27 08:31:40'),
(22, 6, 'User Logged In', '::1', '2025-11-27 08:32:06'),
(23, 7, 'User Logged In', '::1', '2025-11-27 08:32:49'),
(24, 4, 'User Logged In', '::1', '2025-11-27 08:38:00'),
(25, 2, 'User Logged In', '::1', '2025-11-27 08:39:14'),
(26, 7, 'User Logged In', '::1', '2025-11-27 08:40:06'),
(27, 6, 'User Logged In', '::1', '2025-11-27 08:54:08'),
(28, 6, 'User Logged In', '::1', '2025-11-27 08:54:42'),
(29, 6, 'User Logged In', '::1', '2025-11-27 08:55:26'),
(30, 8, 'User Logged In', '::1', '2025-12-12 22:50:29'),
(31, 8, 'User Logged In', '::1', '2025-12-12 23:00:32'),
(32, 1, 'User Logged In', '::1', '2025-12-13 12:23:26'),
(33, 8, 'User Logged In', '::1', '2025-12-13 12:34:02'),
(34, 8, 'User Logged In', '::1', '2025-12-16 18:00:38'),
(35, 1, 'User Logged In', '::1', '2025-12-16 18:03:58'),
(36, 2, 'User Logged In', '::1', '2025-12-16 18:05:25'),
(37, 2, 'User Logged In', '::1', '2025-12-16 18:06:47'),
(38, 2, 'User Logged In', '::1', '2025-12-16 19:28:26'),
(39, 8, 'User Logged In', '::1', '2025-12-16 19:28:48'),
(40, 2, 'User Logged In', '::1', '2025-12-16 19:53:14'),
(41, 1, 'User Logged In', '::1', '2025-12-16 19:55:20'),
(42, 1, 'User Logged In', '::1', '2025-12-17 19:45:10'),
(43, 1, 'Added Staff: popp@gmail.com', '::1', '2025-12-17 19:46:02'),
(44, 9, 'User Logged In', '::1', '2025-12-17 19:46:47'),
(45, 1, 'User Logged In', '::1', '2025-12-17 19:47:27'),
(46, 1, 'User Logged In', '::1', '2025-12-17 19:51:56'),
(47, 8, 'User Logged In', '::1', '2025-12-17 19:52:33'),
(48, 9, 'User Logged In', '::1', '2025-12-17 19:53:37'),
(49, 2, 'User Logged In', '::1', '2025-12-17 19:53:59'),
(50, 9, 'User Logged In', '::1', '2025-12-17 19:54:35'),
(51, 9, 'User Logged In', '::1', '2025-12-17 19:55:01'),
(52, 2, 'User Logged In', '::1', '2025-12-17 19:55:27'),
(53, 2, 'User Logged In', '::1', '2025-12-17 20:07:48'),
(54, 1, 'User Logged In', '::1', '2026-01-07 13:30:57'),
(55, 8, 'User Logged In', '::1', '2026-01-07 13:31:17'),
(56, 8, 'User Logged In', '::1', '2026-01-07 14:30:12'),
(57, 7, 'User Logged In', '::1', '2026-01-07 14:30:26'),
(58, 1, 'User Logged In', '::1', '2026-01-07 14:30:50'),
(59, 8, 'User Logged In', '::1', '2026-01-07 14:31:53'),
(60, 1, 'User Logged In', '::1', '2026-01-07 14:41:28'),
(61, 2, 'User Logged In', '::1', '2026-01-07 14:43:42'),
(62, 8, 'User Logged In', '::1', '2026-01-09 11:33:10'),
(63, 10, 'User Logged In', '::1', '2026-01-09 11:34:36'),
(64, 1, 'User Logged In', '::1', '2026-01-09 11:38:00'),
(65, 1, 'Added Staff: nn@gmail.com', '::1', '2026-01-09 11:44:41'),
(66, 11, 'User Logged In', '::1', '2026-01-09 11:44:55'),
(67, 10, 'User Logged In', '::1', '2026-01-09 11:45:49'),
(68, 1, 'User Logged In', '::1', '2026-01-09 11:57:18'),
(69, 10, 'User Logged In', '::1', '2026-01-09 12:04:33'),
(70, 8, 'User Logged In', '::1', '2026-01-09 12:05:12'),
(71, 13, 'User Logged In', '::1', '2026-01-09 12:09:20'),
(72, 1, 'User Logged In', '::1', '2026-01-09 12:10:26'),
(73, 13, 'User Logged In', '::1', '2026-01-09 12:11:29'),
(74, 1, 'User Logged In', '::1', '2026-01-09 12:13:31'),
(75, 10, 'User Logged In', '::1', '2026-01-09 12:15:08'),
(76, 13, 'User Logged In', '::1', '2026-01-09 12:15:32'),
(77, 10, 'User Logged In', '::1', '2026-01-09 13:18:15'),
(78, 1, 'User Logged In', '::1', '2026-01-09 13:27:39'),
(79, 1, 'Added Staff: pen@gmail.com', '::1', '2026-01-09 13:30:30'),
(80, 2, 'User Logged In', '::1', '2026-01-09 13:31:15'),
(81, 2, 'Changed Room 3 status to dirty', '::1', '2026-01-09 13:32:51'),
(82, 7, 'User Logged In', '::1', '2026-01-09 13:34:03'),
(83, 14, 'User Logged In', '::1', '2026-01-09 13:34:53'),
(84, 14, 'Changed Room 3 status to available', '::1', '2026-01-09 13:35:19'),
(85, 8, 'User Logged In', '::1', '2026-01-09 13:35:40'),
(86, 10, 'User Logged In', '::1', '2026-01-10 10:45:22'),
(87, 13, 'User Logged In', '::1', '2026-01-10 10:47:09'),
(88, 1, 'User Logged In', '::1', '2026-01-10 10:49:03'),
(89, 13, 'User Logged In', '::1', '2026-01-10 10:50:40'),
(90, 13, 'User Logged In', '::1', '2026-01-10 11:46:06'),
(91, 13, 'User Logged In', '::1', '2026-01-10 11:49:34'),
(92, 2, 'User Logged In', '::1', '2026-01-10 12:46:09'),
(93, 8, 'User Logged In', '::1', '2026-01-10 12:56:17'),
(94, 1, 'User Logged In', '::1', '2026-01-10 12:58:55'),
(95, 1, 'Added Staff: main1@gmail.com', '::1', '2026-01-10 13:38:15'),
(96, 1, 'Added Staff: main2@gmail.com', '::1', '2026-01-10 13:39:35'),
(97, 1, 'Added Staff: main3@gmail.com', '::1', '2026-01-10 13:40:47'),
(98, 1, 'User Logged In', '::1', '2026-01-10 13:42:46'),
(99, 2, 'User Logged In', '::1', '2026-01-10 13:45:01'),
(100, 2, 'Checked-in Booking #1', '::1', '2026-01-10 13:45:39'),
(101, 2, 'Checked-in Booking #13', '::1', '2026-01-10 13:45:40'),
(102, 2, 'Checked-in Booking #16', '::1', '2026-01-10 13:45:41'),
(103, 2, 'Checked-in Booking #18', '::1', '2026-01-10 13:45:45'),
(104, 2, 'User Logged In', '::1', '2026-01-10 13:46:11'),
(105, 14, 'User Logged In', '::1', '2026-01-10 13:46:39'),
(106, 15, 'User Logged In', '::1', '2026-01-10 13:47:07'),
(107, 7, 'User Logged In', '::1', '2026-01-10 13:47:34'),
(108, 4, 'User Logged In', '::1', '2026-01-10 13:47:53'),
(109, 4, 'Checked-in Booking #5', '::1', '2026-01-10 13:48:15'),
(110, 4, 'Checked-in Booking #14', '::1', '2026-01-10 13:48:22'),
(111, 4, 'Checked-in Booking #17', '::1', '2026-01-10 13:48:24'),
(112, 11, 'User Logged In', '::1', '2026-01-10 13:48:50'),
(113, 16, 'User Logged In', '::1', '2026-01-10 13:49:12'),
(114, 9, 'User Logged In', '::1', '2026-01-10 13:49:29'),
(115, 5, 'User Logged In', '::1', '2026-01-10 13:50:25'),
(116, 3, 'User Logged In', '::1', '2026-01-10 13:50:56'),
(117, 17, 'User Logged In', '::1', '2026-01-10 13:51:13'),
(118, 6, 'User Logged In', '::1', '2026-01-10 13:52:54'),
(119, 6, 'User Logged In', '::1', '2026-01-10 13:54:15'),
(120, 10, 'User Logged In', '::1', '2026-01-10 13:55:10'),
(121, 4, 'User Logged In', '::1', '2026-01-10 13:57:02'),
(122, 11, 'User Logged In', '::1', '2026-01-10 13:57:38'),
(123, 10, 'User Logged In', '::1', '2026-01-10 15:01:15'),
(124, 10, 'User Logged In', '::1', '2026-01-10 15:05:09'),
(125, 10, 'User Logged In', '::1', '2026-01-10 19:33:31'),
(126, 13, 'User Logged In', '::1', '2026-01-10 19:36:26'),
(127, 13, 'User Logged In', '::1', '2026-01-10 19:38:31'),
(128, 10, 'User Logged In', '::1', '2026-01-10 19:43:17'),
(129, 18, 'User Logged In', '::1', '2026-01-10 19:44:58'),
(130, 18, 'User Logged In', '::1', '2026-01-10 19:47:31'),
(131, 18, 'User Logged In', '::1', '2026-01-10 19:53:29'),
(132, 18, 'User Logged In', '::1', '2026-01-10 20:00:52'),
(133, 10, 'User Logged In', '::1', '2026-01-10 20:11:40');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('confirmed','cancelled','completed') DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('Pending','Paid','Refunded') DEFAULT 'Pending',
  `check_in_status` enum('Pending','Checked-In','Checked-Out') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `hotel_id`, `user_id`, `room_id`, `check_in`, `check_out`, `total_price`, `status`, `created_at`, `payment_status`, `check_in_status`) VALUES
(1, 1, 6, 1, '2025-12-01', '2025-12-04', 450.00, 'completed', '2025-11-26 17:32:13', 'Paid', 'Checked-Out'),
(4, 2, 6, 5, '2025-12-01', '2025-12-07', 1800.00, 'cancelled', '2025-11-26 18:43:03', 'Pending', 'Pending'),
(5, 2, 1, 10, '2025-11-30', '2025-12-02', 0.00, 'confirmed', '2025-11-27 08:08:04', 'Pending', 'Checked-In'),
(6, 2, 1, 5, '2025-12-09', '2025-12-11', 0.00, '', '2025-11-27 08:08:35', 'Pending', 'Pending'),
(7, 1, 8, 1, '2025-12-16', '2025-12-18', 300.00, 'cancelled', '2025-12-13 12:34:20', 'Pending', 'Pending'),
(8, 2, 8, 4, '2025-12-13', '2025-12-15', 360.00, 'completed', '2025-12-13 12:39:37', 'Paid', 'Checked-Out'),
(9, 2, 8, 4, '2025-12-19', '2025-12-21', 360.00, 'cancelled', '2025-12-17 19:53:02', 'Pending', 'Pending'),
(10, 1, 8, 1, '2026-01-09', '2026-01-11', 300.00, 'cancelled', '2026-01-07 14:32:57', 'Pending', 'Pending'),
(11, 2, 8, 4, '2026-01-07', '2026-01-09', 360.00, 'completed', '2026-01-07 14:34:19', 'Paid', 'Checked-Out'),
(12, 1, 8, 2, '2026-01-15', '2026-01-16', 250.00, 'cancelled', '2026-01-07 14:37:42', 'Pending', 'Pending'),
(13, 1, 8, 1, '2026-01-08', '2026-01-19', 1650.00, 'confirmed', '2026-01-07 14:38:54', 'Pending', 'Checked-In'),
(14, 2, 10, 4, '2026-01-10', '2026-01-11', 180.00, 'completed', '2026-01-09 11:34:48', 'Paid', 'Checked-Out'),
(15, 3, 13, 7, '2026-01-09', '2026-01-10', 120.00, 'completed', '2026-01-09 12:09:40', 'Paid', 'Checked-Out'),
(16, 1, 10, 2, '2026-01-10', '2026-01-11', 250.00, 'completed', '2026-01-09 12:15:20', 'Paid', 'Checked-Out'),
(17, 2, 10, 4, '2026-01-13', '2026-01-14', 180.00, 'completed', '2026-01-09 13:18:31', 'Paid', 'Checked-Out'),
(18, 1, 1, 2, '2026-01-15', '2026-01-19', 0.00, 'confirmed', '2026-01-09 13:28:32', 'Pending', 'Checked-In'),
(19, 2, 13, 10, '2026-01-10', '2026-01-11', 1500.00, 'completed', '2026-01-10 10:52:54', 'Paid', 'Checked-Out'),
(20, 2, 10, 4, '2026-01-11', '2026-01-12', 180.00, 'cancelled', '2026-01-10 19:34:00', 'Pending', 'Pending'),
(21, 1, 10, 2, '2026-01-10', '2026-01-12', 500.00, 'completed', '2026-01-10 19:34:57', 'Paid', 'Checked-Out'),
(22, 1, 10, 3, '2026-01-11', '2026-01-12', 450.00, 'cancelled', '2026-01-10 19:35:40', 'Pending', 'Pending'),
(23, 2, 13, 5, '2026-01-11', '2026-01-12', 300.00, 'cancelled', '2026-01-10 19:36:52', 'Pending', 'Pending'),
(24, 2, 10, 4, '2026-01-11', '2026-01-12', 180.00, 'cancelled', '2026-01-10 19:43:51', 'Pending', 'Pending'),
(25, 3, 18, 7, '2026-01-11', '2026-01-12', 120.00, 'cancelled', '2026-01-10 19:45:07', 'Pending', 'Pending'),
(26, 3, 18, 7, '2026-01-11', '2026-01-12', 120.00, 'cancelled', '2026-01-10 19:53:53', 'Pending', 'Pending'),
(27, 3, 18, 7, '2026-01-10', '2026-01-11', 120.00, 'cancelled', '2026-01-10 19:54:20', 'Pending', 'Pending'),
(28, 3, 18, 7, '2026-01-10', '2026-01-11', 120.00, 'cancelled', '2026-01-10 20:01:14', 'Pending', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `location`, `image_url`) VALUES
(1, 'Grand City Hotel', 'Kandy', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500'),
(2, 'Seaside Resort', 'Colombo', 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=500'),
(3, 'Mountain Retreat', 'Anuradhapura', 'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=500');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` enum('promo','reminder','update') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `hotel_id`, `title`, `message`, `type`, `created_at`) VALUES
(1, NULL, 2, 'Swimming Pool Closed', 'Swimming Pool is closed due to maintenance', '', '2025-11-27 08:06:59');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `type` enum('Standard','Deluxe','Suite') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','booked','maintenance','dirty') DEFAULT 'available',
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `hotel_id`, `room_number`, `type`, `price`, `status`, `image_url`) VALUES
(1, 1, '101', 'Standard', 150.00, 'available', NULL),
(2, 1, '102', 'Deluxe', 250.00, 'available', NULL),
(3, 1, '201', 'Suite', 450.00, 'available', NULL),
(4, 2, '101', 'Standard', 180.00, 'available', NULL),
(5, 2, '102', 'Deluxe', 300.00, 'available', NULL),
(6, 2, '201', 'Suite', 550.00, 'maintenance', NULL),
(7, 3, '101', 'Standard', 120.00, 'available', NULL),
(8, 3, '102', 'Deluxe', 200.00, 'available', NULL),
(9, 3, '201', 'Suite', 400.00, 'available', NULL),
(10, 2, 'HALL-A', '', 1500.00, 'available', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type` enum('Food','Cleaning','Maintenance') NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cost` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `hotel_id`, `room_id`, `user_id`, `service_type`, `description`, `status`, `created_at`, `cost`) VALUES
(1, 1, 1, 6, '', 'Burger', 'Completed', '2025-11-26 18:34:38', 25.00),
(2, 1, 1, 7, '', 'Burger', 'Completed', '2025-11-27 08:28:10', 25.00),
(3, 1, 1, 6, 'Cleaning', 'Clean', 'Completed', '2025-11-27 08:30:03', 0.00),
(4, 1, 1, 6, '', 'pizza', 'Completed', '2025-11-27 08:32:32', 25.00),
(5, 1, 1, 6, 'Food', 'Front Desk: Burger', 'Completed', '2025-11-27 08:39:38', 0.00),
(6, 2, 4, 8, '', 'pizza', 'Completed', '2025-12-17 19:53:19', 25.00),
(7, 1, 2, 0, 'Food', 'Front Desk: Burger', 'Completed', '2025-12-17 19:54:24', 0.00),
(8, 2, 4, 8, '', 'Burger', 'Completed', '2026-01-07 14:35:41', 25.00),
(9, 2, 4, 10, '', 'kottu', 'Completed', '2026-01-09 11:35:05', 25.00),
(10, 2, 4, 10, 'Cleaning', 'Clean', 'Completed', '2026-01-09 11:35:27', 0.00),
(11, 2, 4, 10, '', 'baggage', 'Completed', '2026-01-09 11:35:44', 50.00),
(12, 2, 4, 10, 'Maintenance', 'phone repair', 'Completed', '2026-01-09 11:36:04', 0.00),
(13, 1, 2, 10, 'Cleaning', 'Burger', 'Completed', '2026-01-09 13:18:46', 0.00),
(14, 1, 1, 6, 'Food', 'Front Desk: Burger', 'Completed', '2026-01-09 13:32:23', 0.00),
(15, 1, 2, 10, 'Cleaning', 'Front Desk: Towels', 'Completed', '2026-01-09 13:33:33', 0.00),
(16, 1, 2, 10, '', 'car for one hour', 'Completed', '2026-01-10 10:45:56', 50.00),
(17, 3, 7, 13, '', 'vehicle needed', 'Completed', '2026-01-10 10:47:28', 50.00),
(18, 3, 7, 13, '', 'noodles', 'Completed', '2026-01-10 10:50:56', 25.00),
(19, 3, 7, 13, 'Cleaning', 'towel', 'Completed', '2026-01-10 10:51:11', 0.00),
(20, 3, 7, 13, 'Maintenance', 'phone repair', 'Completed', '2026-01-10 10:51:24', 0.00),
(21, 2, 10, 13, '', 'car', 'Completed', '2026-01-10 10:53:16', 50.00),
(22, 2, 10, 13, '', 'bus', 'Completed', '2026-01-10 10:54:07', 50.00),
(23, 1, 1, 8, 'Cleaning', 'Clean', 'Completed', '2026-01-10 12:56:34', 15.00),
(24, 1, 1, 6, '', 'kottu', 'Pending', '2026-01-10 13:53:49', 25.00),
(25, 2, 4, 10, 'Cleaning', 'towels', 'Pending', '2026-01-10 13:56:26', 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `staff_roster`
--

CREATE TABLE `staff_roster` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shift_date` date NOT NULL,
  `shift_time` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('guest','admin','staff') DEFAULT 'guest',
  `assigned_hotel_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `job_title` enum('Receptionist','Housekeeper','Maintenance','Kitchen') DEFAULT 'Receptionist'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `assigned_hotel_id`, `created_at`, `job_title`) VALUES
(1, 'Super Admin', 'admin@chain.com', '$2y$10$W8ZesF5I.t/VpAPjUOEPXewtwJH1ino2PqZnNmOHwFyePhVt5usm.', '000-000-0000', 'admin', NULL, '2025-11-26 17:19:15', 'Receptionist'),
(2, 'City Reception', 'reception1@chain.com', '$2y$10$6joQByxiEUu0uDv9z6.7gOfZoIooTqdoPAki8XJ.u3mw2CfLsb6oC', '111-111-1111', 'staff', 1, '2025-11-26 17:19:15', 'Receptionist'),
(3, 'City Housekeeping', 'cleaner1@chain.com', '$2y$10$6joQByxiEUu0uDv9z6.7gOfZoIooTqdoPAki8XJ.u3mw2CfLsb6oC', '111-222-2222', 'staff', 3, '2025-11-26 17:19:15', 'Housekeeper'),
(4, 'Seaside Reception', 'reception2@chain.com', '$2y$10$6joQByxiEUu0uDv9z6.7gOfZoIooTqdoPAki8XJ.u3mw2CfLsb6oC', '222-111-1111', 'staff', 2, '2025-11-26 17:19:15', 'Receptionist'),
(5, 'Mountain Reception', 'reception3@chain.com', '$2y$10$6joQByxiEUu0uDv9z6.7gOfZoIooTqdoPAki8XJ.u3mw2CfLsb6oC', '333-111-1111', 'staff', 3, '2025-11-26 17:19:15', 'Receptionist'),
(6, 'Bruce', 'hot@gmail.com', '$2y$10$P9N5coPVKm0GVZW.C66WuOyg8xEsMuGRrAKOx/PV8/jvYX0cRFOIK', '0778881234', 'guest', NULL, '2025-11-26 17:28:16', 'Receptionist'),
(7, 'Alfred Pennyworth', 'alf@gmail.com', '$2y$10$bsTFrKrbRD1fgStHEQfX4OstpxkOTyh9ajki8ISESQICqt5yGQT5W', '0778882222', 'staff', 1, '2025-11-27 08:25:49', 'Kitchen'),
(8, 'Bruce', 'bat@gmail.com', '$2y$10$j/zK.dfVBwb8QYYk38IZe.wrZ5GlfS8Cz4uM.JzEZhvbBl6Zm8TUm', '0778885555', 'guest', NULL, '2025-12-12 22:48:54', 'Receptionist'),
(9, 'Poppy Dax', 'popp@gmail.com', '$2y$10$rDVNQI821WhqduPYVkONwu03LH2kCwYyiVBEnFRJ5NVxD6ED999D6', '0761888127', 'staff', 2, '2025-12-17 19:46:02', 'Kitchen'),
(10, 'Clark Kent', 'sup@gmail.com', '$2y$10$ZDZGcmptOfMHigMoaZa/guErMvafD6rryGmkigYwPax0ltiq.OMs.', '0761222333', 'guest', NULL, '2026-01-09 11:34:25', 'Receptionist'),
(11, 'Nina Well', 'nn@gmail.com', '$2y$10$c.FZWND/1pLsnNTYbZbKVuDRNiRIMPXr.JGaNmSLmHQCY3bkGCYeG', '0761552222', 'staff', 2, '2026-01-09 11:44:41', 'Housekeeper'),
(12, 'nnn', 'n@gmail.com', '$2y$10$/U2WzFczI1muc1PRtxh9yeA6QMrmgK7P65jyB/w3xkIo23UY94KMq', '0774444111', 'guest', NULL, '2026-01-09 12:08:06', 'Receptionist'),
(13, 'mmmmmmmm', 'm@gmail.com', '$2y$10$oeRomD2ly4X8CmipGaBRDeWRupxReUHw3bPFrGm.5N9goHUAuMCgu', '0771234564', 'guest', NULL, '2026-01-09 12:09:12', 'Receptionist'),
(14, 'Pennywise John', 'pen@gmail.com', '$2y$10$ihY6pU3OqJocRA3ymzUB7eQovy7RyIPlmTtXsNT4MqA/VqZKOwmk2', '0778885555', 'staff', 1, '2026-01-09 13:30:30', 'Housekeeper'),
(15, 'John Durai', 'main1@gmail.com', '$2y$10$QC8FvYpwWSxgxYV4WUTQ7uP3BYWM7RfEuhnMz4fnpzv25Y3RJHmPe', '0772223333', 'staff', 1, '2026-01-10 13:38:15', 'Maintenance'),
(16, 'Chandimal Singh', 'main2@gmail.com', '$2y$10$Ldov.KRE.x.0Q4Nqo5WHZuuW1FaDt73UeroUGXOKzsfAyXTDPezLS', '0775654565', 'staff', 2, '2026-01-10 13:39:35', 'Maintenance'),
(17, 'Yash Deen', 'main3@gmail.com', '$2y$10$CbGpWZLvdZR50wnFNow8nu/C5/5keOGuyo4M8JfcI6Sq7xRZd0dgC', '0774441111', 'staff', 3, '2026-01-10 13:40:47', 'Maintenance'),
(18, 'Bruce Antony', 'br@gmail.com', '$2y$10$EfKFBnlghs0Ul4nDJby/JuwLl7nkGNfuVZtF5Ue8c40Xaore6Hycy', '0778884520', 'guest', NULL, '2026-01-10 19:44:50', 'Receptionist');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `staff_roster`
--
ALTER TABLE `staff_roster`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `assigned_hotel_id` (`assigned_hotel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `staff_roster`
--
ALTER TABLE `staff_roster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);

--
-- Constraints for table `staff_roster`
--
ALTER TABLE `staff_roster`
  ADD CONSTRAINT `staff_roster_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`),
  ADD CONSTRAINT `staff_roster_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`assigned_hotel_id`) REFERENCES `hotels` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
