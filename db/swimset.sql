-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 29, 2020 at 10:16 PM
-- Server version: 5.7.29-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `swimset`
--

-- --------------------------------------------------------

--
-- Table structure for table `sprites`
--

CREATE TABLE `sprites` (
  `id` int(11) NOT NULL,
  `title` varchar(60) NOT NULL,
  `description` text,
  `path` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sprites`
--

INSERT INTO `sprites` (`id`, `title`, `description`, `path`, `user_id`) VALUES
(13, 'Deep night', 'Tilemap. SpriteSheet', '5e80fc1c33bb90.81607170.png', 9),
(14, 'Bandit run', 'Mini sprite set. Bandit run', '5e80fc9e0b01f2.01805501.png', 9),
(15, 'Bandit jump', 'Mini sprite set. Bandit jump', '5e80fcb16568c1.21525679.png', 9),
(16, 'Skull icon', 'Skull icon. Good quality', '5e80fcfc3fd097.67400600.png', 9),
(17, 'FireMumu', 'Fire monster icon.', '5e80fd7707dd79.62908799.png', 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `administrator` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `administrator`, `deleted`) VALUES
(9, 'Administrator', '$2y$12$pRtzz6o1VpIftOk.gbEx.eWx7cNHi63gCOcEf7BFSXrXXZtF01eX.', 'developer', 1, 0),
(10, 'user01', '$2y$12$kxE1.s53DK3ahAM0j7yoPOvV6EvM4e45HySW4WgIyrOAv20LjhI9K', 'ilustrator', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sprites`
--
ALTER TABLE `sprites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sprites`
--
ALTER TABLE `sprites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
