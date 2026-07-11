-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2026 at 09:06 PM
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
-- Database: `ceylontourmatelk`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `tourist_id` int(11) DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL,
  `guest_count` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `commission_amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `tourist_id`, `listing_id`, `status`, `start_datetime`, `end_datetime`, `guest_count`, `total_price`, `commission_amount`, `created_at`) VALUES
(1, 9, 4, 'cancelled', '2026-07-08 07:00:00', '2026-07-09 07:00:00', 2, 1500.00, 75.00, '2026-07-06 17:22:34'),
(2, 9, 4, 'cancelled', '2026-07-07 21:00:00', '2026-07-08 07:00:00', 2, 1500.00, 75.00, '2026-07-06 17:23:05'),
(3, 9, 4, 'cancelled', '2026-07-08 21:22:00', '2026-07-08 22:22:00', 2, 1500.00, 75.00, '2026-07-06 17:25:15'),
(4, 9, 4, 'completed', '2026-07-07 21:00:00', '2026-07-08 07:00:00', 2, 1500.00, 75.00, '2026-07-06 17:27:43'),
(5, 9, 2, 'completed', '2026-07-09 13:00:00', '2026-07-09 14:00:00', 1, 12121.00, 606.05, '2026-07-08 06:37:29');

-- --------------------------------------------------------

--
-- Table structure for table `booking_rooms`
--

CREATE TABLE `booking_rooms` (
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_rooms`
--

INSERT INTO `booking_rooms` (`booking_id`, `room_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `commission`
--

CREATE TABLE `commission` (
  `id` int(11) NOT NULL,
  `rate` decimal(5,2) DEFAULT NULL,
  `type` enum('booking_commission','listing_fee') DEFAULT 'booking_commission',
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commission`
--

INSERT INTO `commission` (`id`, `rate`, `type`, `updated_by`, `updated_at`) VALUES
(1, 5.00, 'booking_commission', 4, '2026-07-04 06:58:25'),
(2, 500.00, 'listing_fee', 4, '2026-07-10 14:55:37'),
(3, 700.00, 'listing_fee', 4, '2026-07-10 14:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `experience_listings`
--

CREATE TABLE `experience_listings` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `experience_type` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `max_group_size` int(11) DEFAULT NULL,
  `availability` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `experience_listings`
--

INSERT INTO `experience_listings` (`id`, `listing_id`, `experience_type`, `duration`, `max_group_size`, `availability`) VALUES
(1, 2, 'Historical', '1 hour', 10, 'Weekdays only');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_listings`
--

CREATE TABLE `hotel_listings` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `star_rating` int(11) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `total_rooms` int(11) DEFAULT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_listings`
--

INSERT INTO `hotel_listings` (`id`, `listing_id`, `star_rating`, `amenities`, `total_rooms`, `check_in_time`, `check_out_time`) VALUES
(1, 4, 4, 'asdasd', 12, '08:00:00', '18:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `type` enum('tour_guide','shop','hotel','experience') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `user_id`, `title`, `description`, `location`, `city`, `price`, `status`, `type`, `created_at`) VALUES
(1, 6, 'test', 'test', 'galle', NULL, NULL, 'approved', 'shop', '2026-07-05 09:33:43'),
(2, 7, 'testexp', 'asdasd', 'galle fort', 'Ratnapura', 1800.00, 'approved', 'experience', '2026-07-06 08:11:20'),
(3, 2, 'sdad', 'asda', 'asd', 'Anuradhapura', 1400.00, 'approved', 'tour_guide', '2026-07-06 09:41:57'),
(4, 8, 'hotel1', 'asdasd', 'asdasd', 'Batticaloa', 15000.00, 'approved', 'hotel', '2026-07-06 15:41:01'),
(5, 6, 'Lucky Jewellery', 'nsdnalksdnlas', 'Galle Fort', NULL, NULL, 'approved', 'shop', '2026-07-08 07:05:02'),
(11, 6, 'Mahinda Jewellers', 'Jewelry store', 'Habaraduwa', 'Galle', 500.00, 'approved', 'shop', '2026-07-11 16:38:49');

-- --------------------------------------------------------

--
-- Table structure for table `listing_images`
--

CREATE TABLE `listing_images` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listing_images`
--

INSERT INTO `listing_images` (`id`, `listing_id`, `image_path`) VALUES
(14, 11, 'uploads/listingimages/1783787929_0_saasasasa.jpg'),
(15, 11, 'uploads/listingimages/1783787929_1_unnamesasd.jpg'),
(16, 11, 'uploads/listingimages/1783787929_2_unnameasasad.jpg'),
(17, 11, 'uploads/listingimages/1783787929_3_unnamesaasd.jpg'),
(18, 11, 'uploads/listingimages/1783787929_4_unnamed.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `listing_id`, `message`, `is_read`, `created_at`) VALUES
(1, 9, 6, 1, 'hi this is a test message', 1, '2026-07-07 11:27:36'),
(2, 9, 6, 1, 'sdasda', 1, '2026-07-07 11:27:55'),
(3, 6, 9, 1, 'hello how are you doing', 1, '2026-07-07 11:28:18'),
(4, 9, 6, 1, 'hi im doing good', 1, '2026-07-08 08:05:25'),
(5, 9, 6, 5, 'hi', 1, '2026-07-08 08:05:34'),
(6, 9, 8, 4, 'hi', 1, '2026-07-08 08:24:34'),
(7, 8, 9, 4, 'hi', 0, '2026-07-08 09:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `created_at`) VALUES
(1, 7, 9, 'Your booking for \"testexp\" has been confirmed! Please proceed to payment from My Bookings.', 1, '2026-07-08 06:38:32'),
(2, 4, 8, 'Your listing \"teas\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-08 10:25:21'),
(3, 4, 10, 'Welcome to CeylonTourMate.lk, shop! 🎉 Your Shop owner account has been approved. You can now log in and start adding your listings!', 1, '2026-07-08 10:33:35'),
(4, 4, 6, 'Your listing \"Lucky Jewellery\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 15:22:57'),
(5, 4, 6, 'Your listing \"Lucky Jewellery\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-09 15:23:06'),
(6, 4, 6, 'Your listing \"Lucky Jewellery\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 15:23:38'),
(7, 4, 6, 'Your listing \"Lucky Jewellery\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-09 15:23:56'),
(8, 4, 8, 'Your listing \"hotel1\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 15:30:23'),
(9, 4, 8, 'Your listing \"hotel1\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-09 15:46:54'),
(10, 4, 6, 'Your listing \"Lucky Jewellery\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 15:56:42'),
(11, 4, 6, 'Your listing \"Lucky Jewellery\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-09 15:56:53'),
(12, 4, 6, 'Your listing \"Lucky Jewellery\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 15:57:02'),
(13, 4, 6, 'Your listing \"Lucky Jewellery\" has been restored and is now live on CeylonTourMate.lk!', 1, '2026-07-09 15:57:05'),
(14, 4, 6, 'Your listing \"Lucky Jewellery\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 15:57:26'),
(15, 4, 6, 'Your listing \"Lucky Jewellery\" has been restored and is now live on CeylonTourMate.lk!', 1, '2026-07-09 16:02:22'),
(16, 4, 6, 'Your listing \"Lucky Jewellery\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 16:02:25'),
(17, 4, 6, 'Your listing \"Lucky Jewellery\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-09 16:02:29'),
(18, 4, 6, 'Your listing \"Lucky Jewellery\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 16:02:32'),
(19, 4, 6, 'Your listing \"Lucky Jewellery\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-09 16:07:15'),
(20, 4, 6, 'Your listing \"Lucky Jewellery\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 16:07:17'),
(21, 4, 6, 'Your listing \"Lucky Jewellery\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-09 16:07:21'),
(22, 4, 6, 'Your listing \"Lucky Jewellery\" has been hidden from the platform by admin. Please contact admin for more information.', 1, '2026-07-09 16:07:24'),
(23, 4, 6, 'Your listing \"Lucky Jewellery\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-09 16:07:26'),
(24, 4, 12, 'Welcome to CeylonTourMate.lk, naveenn! 🎉 Your Shop owner account has been approved. You can now log in and start adding your listings!', 0, '2026-07-10 15:19:44'),
(25, 4, 6, 'Your listing \"test2\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-11 16:13:25'),
(26, 4, 6, 'Your listing \"Lucky Jewellery\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-11 16:22:47'),
(27, 4, 6, 'Your listing \"Mahinda Jewellers\" has been approved and is now live on CeylonTourMate.lk!', 1, '2026-07-11 16:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `shop_listing_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `type` enum('booking','listing_fee') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `fee_month` int(11) DEFAULT NULL,
  `fee_year` int(11) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `shop_listing_id`, `booking_id`, `type`, `amount`, `payment_status`, `fee_month`, `fee_year`, `payment_date`) VALUES
(1, 9, NULL, 4, 'booking', 1500.00, 'completed', NULL, NULL, '2026-07-06 17:35:28'),
(2, 9, NULL, 4, 'booking', 1500.00, 'completed', NULL, NULL, '2026-07-06 17:35:46'),
(3, 9, NULL, 5, 'booking', 12121.00, 'completed', NULL, NULL, '2026-07-08 06:39:08');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `tourist_id` int(11) DEFAULT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `hotel_listing_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `room_type` enum('single','double','suite','family') DEFAULT NULL,
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `is_available` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `hotel_listing_id`, `description`, `room_type`, `price_per_night`, `capacity`, `is_available`) VALUES
(1, 1, 'asdasd', 'single', 1500.00, 2, 1),
(2, 1, 'dasdsa', 'suite', 5000.00, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `shop_listings`
--

CREATE TABLE `shop_listings` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `shop_type` varchar(100) DEFAULT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_listings`
--

INSERT INTO `shop_listings` (`id`, `listing_id`, `shop_type`, `open_time`, `close_time`) VALUES
(7, 11, 'Jewellery', '08:00:00', '20:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tourguide_licenses`
--

CREATE TABLE `tourguide_licenses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `license_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tourguide_licenses`
--

INSERT INTO `tourguide_licenses` (`id`, `user_id`, `license_number`, `license_image`) VALUES
(1, 2, '1212121312', 'uploads/licenses/1782902941_4061de0ecf5fae6d78263fed37220143.jpg'),
(2, 13, '1243124124', 'uploads/licenses/1783699560_728606755_18379079686160101_2895611704345153244_n.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tourguide_listings`
--

CREATE TABLE `tourguide_listings` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) DEFAULT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `availability` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tourguide_listings`
--

INSERT INTO `tourguide_listings` (`id`, `listing_id`, `languages`, `specialization`, `experience_years`, `availability`) VALUES
(1, 3, 'asd', 'Historical', 111, 'Weekdays only');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT 'default.png',
  `role` enum('tourist','tour_guide','shop_owner','hotel_owner','experience_provider','admin') DEFAULT NULL,
  `is_suspended` tinyint(4) DEFAULT 0,
  `is_approved` tinyint(4) DEFAULT 0,
  `security_question` varchar(255) DEFAULT NULL,
  `security_answer` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `phone`, `profile_pic`, `role`, `is_suspended`, `is_approved`, `security_question`, `security_answer`, `created_at`) VALUES
(2, 'Tguide yasun', 'Guide', 'tourguide', 'tourguide@gmail.com', '$2y$10$U9zdWDhqF2yX7.sHjfA2veMXfA/bbGXIDuEXrozInNYyKfBw7cP/a', '0772494821', 'uploads/profiles/default.png', 'tour_guide', 0, 1, 'What is your pet\'s name?', 'kuku', '2026-07-01 10:49:01'),
(4, 'Naveen', 'admin', 'admin', 'admin@gmail.com', '$2y$10$b7rHjYFQAK0.y1ZpJy4Myui5Eb1U2nNBGuQH8YNG54HtIexA4IMpu', '0771233232', 'uploads/profiles/1783000555_4061de0ecf5fae6d78263fed37220143.jpg', 'admin', 0, 1, 'What is your registered email address on this platform?', 'admin@gmail.com', '2026-07-02 12:32:27'),
(5, 'admin2', 'admin', 'admin2', 'admin2@gmail.com', '$2y$10$tHpxGkUuisgeMei8wIrHuO12WLa8MszLK56hrqe1u.RHwkPxJI2YW', '0771291298', 'uploads/profiles/default.png', 'admin', 0, 1, 'What is your registered email address on this platform?', 'admin2@gmail.com', '2026-07-02 13:55:00'),
(6, 'shoppiyal', 'owner', 'shopowner', 'shopowner@gmail.com', '$2y$10$OvAjzlJEMN9JXAuyTazDuuT7h3c97zGlhmlZ7p5Ax4RbN3HmKR/8S', '0772494822', 'uploads/profiles/default.png', 'shop_owner', 0, 1, 'What is your pet\'s name?', 'kuku', '2026-07-03 08:00:38'),
(7, 'sunimal', 'provider', 'experienceprovider', 'experienceprovider@gmail.com', '$2y$10$4macWmzMl.nz6v2EUz5NoeQXisVt9a8.1eh8/l1/2VskLUWeRgZ/i', '0772494811', 'uploads/profiles/default.png', 'experience_provider', 0, 1, 'What is your pet\'s name?', 'kuku', '2026-07-03 08:01:41'),
(8, 'hotel kasun', 'owner', 'hotelowner', 'hotelowner@gmail.com', '$2y$10$uhYh/LkHiWy0qR/.WJhEhe.bQzh8RdEYwkBID67xqwcNw/E7ftF9e', '0772494212', 'uploads/profiles/default.png', 'hotel_owner', 0, 1, 'What is your pet\'s name?', 'kuku', '2026-07-03 08:02:22'),
(9, 'tourist sanath', 'ist', 'tourist', 'tourist@gmail.com', '$2y$10$9gEmSJJLFttu296QRsBU3ebCPrnNlqaNk6toVH6j4gLNuQQo9EZSG', '0771291212', 'uploads/profiles/default.png', 'tourist', 0, 1, 'What is your pet\'s name?', 'kuku', '2026-07-03 08:03:38'),
(10, 'shop', 'owner', 'shopowner2', 'naveen2@gmail.com', '$2y$10$CWuEP7Hx/BCmqYTKBiAJAuQMUmjhLt2ixOQmB6vQOcOdyIcsP35Hu', '0772494822', 'uploads/profiles/default.png', 'shop_owner', 0, 1, 'What is your pet\'s name?', 'kuku', '2026-07-08 10:32:59'),
(11, 'Minon', 'Yenula', 'minon', 'minon@gmail.com', '$2y$10$7dyHHizLdRRdROYmsUeNEukRl9LwRWGMVSikm1KFsq073Fhr84Z62', '0772494212', 'uploads/profiles/default.png', 'tourist', 0, 1, 'What is your pet\'s name?', 'kuku', '2026-07-10 15:13:11'),
(12, 'naveenn', 'Rashmika', 'naveen2', 'naveenrash@gmail.com', '$2y$10$kT4pdyJEPWTOG2TZKHoHEOb1UABuHwR7osjfDFFuHOLEsUPru4r72', '0771291111', 'uploads/profiles/default.png', 'shop_owner', 0, 1, 'What is your pet\'s name?', 'kuku', '2026-07-10 15:14:23'),
(13, 'tourrr', 'guide', 'tourguide2', 'tourguide2@gmail.com', '$2y$10$LLbjA9d45QVatwMI/Y.mTeXvojWunn8s4D0Y0wb/vgR1329XOxxE6', '0772491212', 'uploads/profiles/default.png', 'tour_guide', 0, 0, 'What is your pet\'s name?', 'kuku', '2026-07-10 16:06:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tourist_id` (`tourist_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD PRIMARY KEY (`booking_id`,`room_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indexes for table `experience_listings`
--
ALTER TABLE `experience_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `hotel_listings`
--
ALTER TABLE `hotel_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `listing_images`
--
ALTER TABLE `listing_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `shop_listing_id` (`shop_listing_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tourist_id` (`tourist_id`),
  ADD KEY `listing_id` (`listing_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_listing_id` (`hotel_listing_id`);

--
-- Indexes for table `shop_listings`
--
ALTER TABLE `shop_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `tourguide_licenses`
--
ALTER TABLE `tourguide_licenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tourguide_listings`
--
ALTER TABLE `tourguide_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username_2` (`username`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `commission`
--
ALTER TABLE `commission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `experience_listings`
--
ALTER TABLE `experience_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hotel_listings`
--
ALTER TABLE `hotel_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `listing_images`
--
ALTER TABLE `listing_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shop_listings`
--
ALTER TABLE `shop_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tourguide_licenses`
--
ALTER TABLE `tourguide_licenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tourguide_listings`
--
ALTER TABLE `tourguide_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tourist_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`);

--
-- Constraints for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD CONSTRAINT `booking_rooms_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `booking_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `commission`
--
ALTER TABLE `commission`
  ADD CONSTRAINT `commission_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `experience_listings`
--
ALTER TABLE `experience_listings`
  ADD CONSTRAINT `experience_listings_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`);

--
-- Constraints for table `hotel_listings`
--
ALTER TABLE `hotel_listings`
  ADD CONSTRAINT `hotel_listings_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`);

--
-- Constraints for table `listings`
--
ALTER TABLE `listings`
  ADD CONSTRAINT `listings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `listing_images`
--
ALTER TABLE `listing_images`
  ADD CONSTRAINT `listing_images_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`shop_listing_id`) REFERENCES `shop_listings` (`id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`tourist_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`hotel_listing_id`) REFERENCES `hotel_listings` (`id`);

--
-- Constraints for table `shop_listings`
--
ALTER TABLE `shop_listings`
  ADD CONSTRAINT `shop_listings_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`);

--
-- Constraints for table `tourguide_licenses`
--
ALTER TABLE `tourguide_licenses`
  ADD CONSTRAINT `tourguide_licenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tourguide_listings`
--
ALTER TABLE `tourguide_listings`
  ADD CONSTRAINT `tourguide_listings_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
