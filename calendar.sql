-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 17, 2021 at 02:42 PM
-- Server version: 10.2.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calendar`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` smallint(5) UNSIGNED NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `month` tinyint(3) UNSIGNED NOT NULL,
  `year` smallint(5) UNSIGNED NOT NULL,
  `day` smallint(5) UNSIGNED NOT NULL,
  `title` tinytext NOT NULL,
  `tag_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `dur` varchar(9) DEFAULT NULL,
  `time_event` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `user_id`, `month`, `year`, `day`, `title`, `tag_id`, `dur`, `time_event`) VALUES
(1, 'test', 2, 2021, 1, 't', 1, NULL, '00:00:00'),
(2, 'laura', 2, 2021, 13, 'pi day', 4, 'once', '00:00:03'),
(3, 'laura', 2, 2021, 21, 'test', 0, 'once', '12:12:00'),
(4, 'laura', 1, 2021, 21, 'test', 2, 'once', '00:12:00'),
(6, 'test', 2, 2021, 21, 'event 2', 3, 'once', '15:32:00'),
(45, 'laura', 2, 2021, 1, 'weekly repeating x5', 4, 'weekly', '04:23:00'),
(46, 'laura', 2, 2021, 8, 'weekly repeating x5', 4, 'weekly', '04:23:00'),
(47, 'laura', 2, 2021, 15, 'weekly repeating x5', 4, 'weekly', '04:23:00'),
(48, 'laura', 2, 2021, 22, 'weekly repeating x5', 4, 'weekly', '04:23:00'),
(49, 'laura', 2, 2021, 29, 'weekly repeating x5', 4, 'weekly', '04:23:00'),
(50, 'laura', 3, 2021, 2, 'montly repeating x3', 2, 'monthly', '04:23:00'),
(51, 'laura', 4, 2021, 2, 'montly repeating x3', 2, 'monthly', '04:23:00'),
(52, 'laura', 5, 2021, 2, 'montly repeating x3', 2, 'monthly', '04:23:00'),
(53, 'laura', 3, 2021, 3, 'yearly repeating x2', 1, 'monthly', '04:23:00'),
(54, 'laura', 4, 2021, 3, 'yearly repeating x2', 1, 'monthly', '04:23:00'),
(55, 'laura', 2, 2022, 6, 'yearly repeating x2', 1, 'yearly', '04:23:00'),
(56, 'laura', 2, 2023, 6, 'yearly repeating x2', 1, 'yearly', '04:23:00'),
(57, 'laura', 2, 2021, 16, 'month x3', 4, 'monthly', '06:06:00'),
(58, 'laura', 3, 2021, 16, 'month x3', 4, 'monthly', '06:06:00'),
(59, 'laura', 4, 2021, 16, 'month x3', 4, 'monthly', '06:06:00'),
(60, 'laura', 2, 2021, 25, 'year x2', 4, 'yearly', '06:06:00'),
(61, 'laura', 2, 2022, 25, 'year x2', 4, 'yearly', '06:06:00'),
(62, 'eva', 2, 2021, 3, 'sprint', 4, 'once', '00:00:00'),
(63, 'test', 2, 2021, 3, 'testrepeat', 1, 'weekly', '16:14:00'),
(64, 'test', 2, 2021, 10, 'testrepeat', 1, 'weekly', '16:14:00'),
(65, 'test', 2, 2021, 17, 'testrepeat', 1, 'weekly', '16:14:00'),
(66, 'test', 2, 2021, 24, 'testrepeat', 1, 'weekly', '16:14:00'),
(67, 'laura', 2, 2021, 3, 'time?', 4, 'once', '02:45:00'),
(68, 'laura', 2, 2021, 3, '2nd event', 4, 'once', '02:45:00'),
(69, 'laura', 2, 2021, 23, '', 2, 'once', '14:22:00'),
(70, 'laura', 2, 2021, 0, 't', 2, 'once', '14:22:00'),
(71, 'test', 2, 2021, 5, 'test', 1, 'once', '03:45:00'),
(72, 'test', 2, 2021, 5, 'test', 1, 'weekly', '03:45:00'),
(73, 'test', 2, 2021, 12, 'test', 1, 'weekly', '03:45:00'),
(74, 'test', 2, 2021, 5, 'test', 2, 'once', '03:45:00'),
(75, 'test', 2, 2021, 5, 'test', 2, 'once', '03:45:00'),
(76, 'test', 2, 2021, 2, 'Really Important Stuff', 2, 'once', '05:15:00'),
(77, 'eva', 1, 2, 0, 'asdf', 0, '', '14:02:00'),
(78, 'eva', 11, 2, 2, 'd', 0, '', '14:22:00'),
(79, 'eva', 2, 2021, 2, 't', 3, 'once', '14:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `share`
--

CREATE TABLE `share` (
  `share_id` tinyint(4) NOT NULL,
  `from_id` varchar(10) NOT NULL,
  `to_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `share`
--

INSERT INTO `share` (`share_id`, `from_id`, `to_id`) VALUES
(3, 'eva', 'test'),
(2, 'test', 'eva');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(10) NOT NULL,
  `hash_pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `hash_pass`) VALUES
('eva', '$2y$10$owO.f1M0IrCAp2cXQIpnTeLwZMFGNVPlD9qB6vLM/MKhV8.TsgVSK'),
('george', '$2y$10$eSy057J1grFL5a619G4SDOF79zMrHw31advNX7Q7BwWS7GvVALJby'),
('laura', '$2y$10$6XGhdUXi28/PXOc0zF4aHu8WyBAp9Cpnz0ilceGjM7ld/02.A8Bf2'),
('new', '$2y$10$G.r00D6OZnZxbXcfGIlkJeiDAMtnz6vOhXtALW5rsXYUk82woU6L2'),
('test', '$2y$10$irPynBV.pGCGDl4hz5TO3.pk0064LxAXDQPKVzh29dtQGUjFwUXYS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `fk3` (`user_id`);

--
-- Indexes for table `share`
--
ALTER TABLE `share`
  ADD PRIMARY KEY (`share_id`),
  ADD UNIQUE KEY `from_id` (`from_id`,`to_id`),
  ADD KEY `fk2` (`to_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `share`
--
ALTER TABLE `share`
  MODIFY `share_id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `share`
--
ALTER TABLE `share`
  ADD CONSTRAINT `fk` FOREIGN KEY (`from_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk2` FOREIGN KEY (`to_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
