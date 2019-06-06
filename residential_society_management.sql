-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 06, 2019 at 06:37 PM
-- Server version: 5.7.26-0ubuntu0.16.04.1-log
-- PHP Version: 7.0.33-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `residential_society_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(5) NOT NULL,
  `event_type` varchar(20) DEFAULT NULL,
  `event_title` varchar(50) DEFAULT NULL,
  `event_date` varchar(20) DEFAULT NULL,
  `event_time` varchar(20) DEFAULT NULL,
  `event_venue` varchar(50) DEFAULT NULL,
  `event_description` varchar(1000) DEFAULT NULL,
  `created_by` int(5) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  `deleted_at` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `event_type`, `event_title`, `event_date`, `event_time`, `event_venue`, `event_description`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Invitation', 'Birthday party', '2019-06-08', '8:00 pm', 'Park', 'Helo \nmy sons bday\nthanks', 1, '2019-06-05 18:34:44', '2019-06-05 19:26:46', NULL),
(2, 'Announcement', 'Electricity Bill', '', '', '', 'Hello \nPlease submit th ebill ASAP', 1, '2019-06-05 19:27:48', NULL, NULL),
(3, 'Invitation', 'weding', '2019-06-09', '8:00 pm', 'bandra', 'wedding invitation', 24, '2019-06-06 11:26:20', '2019-06-06 17:16:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `flat`
--

CREATE TABLE `flat` (
  `id` int(5) NOT NULL,
  `flat_no` int(5) NOT NULL,
  `wing_id` int(5) NOT NULL,
  `wing_name` varchar(20) NOT NULL,
  `remark` varchar(1000) NOT NULL,
  `created_at` varchar(20) NOT NULL,
  `update_at` varchar(20) NOT NULL,
  `deleted_at` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id` int(5) NOT NULL,
  `society_id` int(5) DEFAULT NULL,
  `society_short_name` varchar(20) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  `deleted_at` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `maintenance`
--

INSERT INTO `maintenance` (`id`, `society_id`, `society_short_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'ramchet', NULL, '2019-06-06 00:23:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_transaction`
--

CREATE TABLE `payment_transaction` (
  `id` int(5) NOT NULL,
  `subscriptions_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `amout_paid` int(10) DEFAULT NULL,
  `mode_payment` varchar(50) DEFAULT NULL,
  `reference_id` varchar(50) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  `deleted_at` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(5) NOT NULL,
  `service_name` varchar(50) DEFAULT NULL,
  `monthly_charge` int(10) DEFAULT NULL,
  `remark` varchar(1000) DEFAULT NULL,
  `maintenance_id` int(5) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  `deleted_at` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `service_name`, `monthly_charge`, `remark`, `maintenance_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(5, 'swiming pool', 3000, 'pool charges', 1, '2019-06-05 21:01:50', '2019-06-05 21:32:57', NULL),
(6, 'gym', 500, '', 1, '2019-06-05 21:32:57', '2019-06-06 00:23:11', NULL),
(7, 'park', 2000, '', 1, '2019-06-05 21:32:57', NULL, '2019-06-05 21:44:47'),
(8, 'house keeping', 2000, '', 1, '2019-06-05 21:32:57', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `society`
--

CREATE TABLE `society` (
  `id` int(5) NOT NULL,
  `society_name` varchar(50) DEFAULT NULL,
  `society_short_name` varchar(20) DEFAULT NULL,
  `Address` varchar(500) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `pincode` int(10) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  `deleted_at` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `society`
--

INSERT INTO `society` (`id`, `society_name`, `society_short_name`, `Address`, `city`, `state`, `country`, `pincode`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ramchet  Co Operative Housing Society', 'ramchet', 'Western Express Highway', 'Mumbai', 'Maharashtra', 'India', 400069, NULL, NULL, '2019-06-06 00:28:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(5) NOT NULL,
  `subscription_name` varchar(20) DEFAULT NULL,
  `maintenance_id` int(5) DEFAULT NULL,
  `services` varchar(100) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `total_amt_to_paid` int(10) DEFAULT '0',
  `paid_amt` int(10) DEFAULT '0',
  `completed_at` varchar(20) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  `deleted_at` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `subscription_name`, `maintenance_id`, `services`, `user_id`, `total_amt_to_paid`, `paid_amt`, `completed_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(9, 'June-2019', 1, ',5,6,8', 1, 5500, 0, NULL, '2019-06-06 18:31:10', NULL, NULL),
(10, 'June-2019', 1, ',5,6,8', 24, 5500, 0, NULL, '2019-06-06 18:31:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) NOT NULL,
  `user_type` varchar(20) DEFAULT NULL,
  `user_name` varchar(30) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `wing_id` int(5) DEFAULT NULL,
  `wing_name` varchar(20) DEFAULT NULL,
  `flat_id` int(5) DEFAULT NULL,
  `flat_no` int(5) DEFAULT NULL,
  `wallet_amt` int(10) DEFAULT '0',
  `created_at` varchar(20) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  `deleted_at` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_type`, `user_name`, `password`, `first_name`, `last_name`, `mobile`, `email`, `wing_id`, `wing_name`, `flat_id`, `flat_no`, `wallet_amt`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin', '12345', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'member', 'mangesh', '12345', 'Mangesh', 'Trimbake', '9930703396', 'mangeshtrimbake00786.94@gmail.com', NULL, 'ramchet-B', NULL, 201, 400, '2019-06-06 10:20:46', '2019-06-06 16:23:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wing`
--

CREATE TABLE `wing` (
  `id` int(5) NOT NULL,
  `wing_name` varchar(20) DEFAULT NULL,
  `society_id` int(5) DEFAULT NULL,
  `society_short_name` varchar(20) DEFAULT NULL,
  `remark` varchar(1000) DEFAULT NULL,
  `created_at` varchar(20) DEFAULT NULL,
  `updated_at` varchar(20) DEFAULT NULL,
  `deleted_at` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wing`
--

INSERT INTO `wing` (`id`, `wing_name`, `society_id`, `society_short_name`, `remark`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'ramchet-A', 1, NULL, 'A wing of ramchet', '2019-06-05 22:00:18', '2019-06-06 00:26:09', '2019-06-06 00:26:09'),
(3, 'ramchet-A', 1, NULL, 'A wing', '2019-06-06 00:28:03', NULL, NULL),
(4, 'ramchet-B', 1, NULL, 'B wing', '2019-06-06 00:28:03', NULL, NULL),
(5, 'ramchet-C', 1, NULL, 'C wing', '2019-06-06 00:28:03', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `society`
--
ALTER TABLE `society`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `wing`
--
ALTER TABLE `wing`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `payment_transaction`
--
ALTER TABLE `payment_transaction`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `society`
--
ALTER TABLE `society`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `wing`
--
ALTER TABLE `wing`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
