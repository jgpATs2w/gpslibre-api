-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 14, 2018 at 06:05 PM
-- Server version: 5.7.20-0ubuntu0.16.04.1-log
-- PHP Version: 5.6.36-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `who` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `lat` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `lon` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `batt` varchar(256) COLLATE utf8_spanish_ci NOT NULL,
  `ts_remote` timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logger`
--

CREATE TABLE `logger` (
  `id` int(11) NOT NULL,
  `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tag` char(16) COLLATE utf8_spanish_ci NOT NULL,
  `level` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `message` TEXT COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `metrics`
--

CREATE TABLE `metrics` (
  `id` bigint(20) NOT NULL,
  `name` varchar(232) COLLATE utf8_spanish_ci NOT NULL,
  `value` bigint(20) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

  --
  -- Indexes
  --
  ALTER TABLE `logger`
    ADD PRIMARY KEY (`id`);

  ALTER TABLE `metrics`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `metrics_name` (`name`);

--
-- AUTO_INCREMENT
--
ALTER TABLE `logger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `metrics`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;


INSERT INTO `logger` (`id`, `logtime`, `tag`, `level`, `message`) VALUES (NULL, CURRENT_TIMESTAMP, 'system', 'info', 'creación base de datos');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
