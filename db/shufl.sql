-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2019 at 08:19 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shufl`
--

-- --------------------------------------------------------

--
-- Table structure for table `1wormygels`
--

CREATE TABLE `1wormygels` (
  `id` int(11) NOT NULL,
  `vidstring` text NOT NULL,
  `date` text NOT NULL,
  `title` text NOT NULL,
  `channel` text NOT NULL,
  `seed` text NOT NULL,
  `fav` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `1wormygels`
--

INSERT INTO `1wormygels` (`id`, `vidstring`, `date`, `title`, `channel`, `seed`, `fav`) VALUES
(1, 'o_4Yi5F7uzY', '20:10-January-22-2019', 'Ela Piso Sti Thesi Sou', 'Giorgos Mazonakis', 'sti', 0),
(2, 'lTyqj_JQ2OA', '20:11-January-22-2019', 'Kremlin', 'Vybz Kartel', 'jwO', 0),
(3, 'pSzSjvm-Vi4', '20:14-January-22-2019', 'Groove in the Road', 'Earthworm', 'Vi4', 0),
(4, '38UqQbQhCjk', '20:18-January-22-2019', 'Obzerve', 'Ras Nininn', 'oBZ', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `region` text NOT NULL,
  `volume` int(11) NOT NULL,
  `autoplayoff` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `region`, `volume`, `autoplayoff`) VALUES
(1, 'WormyGels', '741daee09fadea442299912f656137b17ed7c367', '04rlf', 50, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `1wormygels`
--
ALTER TABLE `1wormygels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `1wormygels`
--
ALTER TABLE `1wormygels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
