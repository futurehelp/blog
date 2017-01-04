-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jan 03, 2017 at 10:32 PM
-- Server version: 5.6.34
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `drewrain_blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `request_state` int(1) NOT NULL,
  `request_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`user_id`, `friend_id`, `request_state`, `request_ts`) VALUES
(7, 8, 0, '2013-04-23 11:04:03'),
(7, 6, 0, '2013-04-23 11:04:32'),
(7, 10, 0, '2013-04-24 12:04:14'),
(7, 9, 0, '2013-04-24 03:04:57'),
(11, 8, 0, '2013-04-24 05:04:18'),
(7, 11, 0, '2013-04-24 05:04:36'),
(8, 7, 0, '2013-05-06 09:05:06'),
(16, 8, 0, '2013-05-10 04:05:24'),
(7, 16, 0, '2013-05-10 04:05:17'),
(10, 7, 0, '2013-05-17 04:05:56'),
(19, 14, 0, '2014-03-08 01:03:40');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
