-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2020 at 08:09 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iot_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `avatars`
--

CREATE TABLE `avatars` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `size` float DEFAULT 0,
  `extension` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(15) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` tinyint(4) DEFAULT 0,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `code`, `name`, `type`, `description`) VALUES
(1, 'S1234567', 'DHT11 Temperature Sensor', 1, NULL),
(2, 'A1234567', 'Buzzer', 2, 'Buzzer (Alarm)');

-- --------------------------------------------------------

--
-- Table structure for table `device_alarms`
--

CREATE TABLE `device_alarms` (
  `id` int(10) UNSIGNED NOT NULL,
  `device_reading_id` int(10) UNSIGNED DEFAULT NULL,
  `device_calibration_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `device_calibrations`
--

CREATE TABLE `device_calibrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_device_id` int(10) UNSIGNED DEFAULT NULL,
  `value` float DEFAULT 0,
  `message` text NOT NULL,
  `operator_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `device_readings`
--

CREATE TABLE `device_readings` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_device_id` int(10) UNSIGNED DEFAULT NULL,
  `reading` float UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `device_rules`
--

CREATE TABLE `device_rules` (
  `id` int(10) UNSIGNED NOT NULL,
  `sensor_id` int(10) UNSIGNED DEFAULT NULL,
  `actuator_id` int(10) UNSIGNED DEFAULT NULL,
  `value` float DEFAULT 0,
  `state` tinyint(1) DEFAULT 0,
  `operator_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `name`) VALUES
(2, 'Female'),
(1, 'Male');

-- --------------------------------------------------------

--
-- Table structure for table `operators`
--

CREATE TABLE `operators` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `operators`
--

INSERT INTO `operators` (`id`, `name`) VALUES
(5, 'Equal'),
(1, 'Less Than'),
(2, 'Less Than or Equal'),
(3, 'More Than'),
(4, 'More Than or Equal');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `username` varchar(125) NOT NULL,
  `email` varchar(125) NOT NULL,
  `password` varchar(125) NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender_id` int(10) UNSIGNED DEFAULT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `device_id` int(10) UNSIGNED DEFAULT NULL,
  `is_on` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `token` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `website_information`
--

CREATE TABLE `website_information` (
  `id` int(10) UNSIGNED NOT NULL,
  `info_key` varchar(50) NOT NULL,
  `info_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `website_information`
--

INSERT INTO `website_information` (`id`, `info_key`, `info_value`) VALUES
(1, 'name', 'IoT Management System'),
(2, 'logo', '/uploads/logos/logo.png'),
(3, 'description', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam commodi consequuntur dolor, dolorum eos esse\r\n          ex exercitationem labore laudantium libero praesentium quam qui ratione rem sunt, temporibus voluptates.\r\n          Beatae minus nisi, perferendis ratione repellendus similique. Adipisci aspernatur debitis laudantium molestias\r\n          reiciendis? A, atque ea, facere hic illum ipsum laborum natus pariatur praesentium quis quos sit veniam?\r\n          Aperiam assumenda aut laboriosam natus, quos voluptatibus? Ad architecto asperiores assumenda cupiditate\r\n          dignissimos distinctio dolores ea explicabo illo minus molestiae non odit officiis possimus repellat rerum\r\n          sapiente, voluptatem! Cumque, dolorem ea eos molestiae mollitia omnis provident sapiente sequi! Aliquam amet\r\n          doloremque eos porro voluptatum.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `avatars`
--
ALTER TABLE `avatars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `device_alarms`
--
ALTER TABLE `device_alarms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_reading_id` (`device_reading_id`),
  ADD KEY `device_calibration_id` (`device_calibration_id`);

--
-- Indexes for table `device_calibrations`
--
ALTER TABLE `device_calibrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_device_id` (`user_device_id`),
  ADD KEY `operator_id` (`operator_id`);

--
-- Indexes for table `device_readings`
--
ALTER TABLE `device_readings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_device_id` (`user_device_id`);

--
-- Indexes for table `device_rules`
--
ALTER TABLE `device_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sensor_id` (`sensor_id`),
  ADD KEY `actuator_id` (`actuator_id`),
  ADD KEY `operator_id` (`operator_id`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `gender_id` (`gender_id`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `device_id` (`device_id`);

--
-- Indexes for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `website_information`
--
ALTER TABLE `website_information`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `info_key` (`info_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `avatars`
--
ALTER TABLE `avatars`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `device_alarms`
--
ALTER TABLE `device_alarms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `device_calibrations`
--
ALTER TABLE `device_calibrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `device_readings`
--
ALTER TABLE `device_readings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1684;

--
-- AUTO_INCREMENT for table `device_rules`
--
ALTER TABLE `device_rules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `operators`
--
ALTER TABLE `operators`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `website_information`
--
ALTER TABLE `website_information`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `avatars`
--
ALTER TABLE `avatars`
  ADD CONSTRAINT `avatars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `device_alarms`
--
ALTER TABLE `device_alarms`
  ADD CONSTRAINT `device_alarms_ibfk_1` FOREIGN KEY (`device_reading_id`) REFERENCES `device_readings` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `device_alarms_ibfk_2` FOREIGN KEY (`device_calibration_id`) REFERENCES `device_calibrations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `device_calibrations`
--
ALTER TABLE `device_calibrations`
  ADD CONSTRAINT `device_calibrations_ibfk_1` FOREIGN KEY (`user_device_id`) REFERENCES `user_devices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `device_calibrations_ibfk_2` FOREIGN KEY (`operator_id`) REFERENCES `operators` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `device_readings`
--
ALTER TABLE `device_readings`
  ADD CONSTRAINT `device_readings_ibfk_1` FOREIGN KEY (`user_device_id`) REFERENCES `user_devices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `device_rules`
--
ALTER TABLE `device_rules`
  ADD CONSTRAINT `device_rules_ibfk_1` FOREIGN KEY (`sensor_id`) REFERENCES `user_devices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `device_rules_ibfk_2` FOREIGN KEY (`actuator_id`) REFERENCES `user_devices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `device_rules_ibfk_3` FOREIGN KEY (`operator_id`) REFERENCES `operators` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD CONSTRAINT `user_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user_devices_ibfk_2` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
