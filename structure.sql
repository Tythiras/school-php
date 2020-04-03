-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 03, 2020 at 11:59 AM
-- Server version: 10.4.12-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`rasmus`@`localhost` PROCEDURE `checkFolder` (IN `_folderUID` VARCHAR(255), IN `_userID` INT)  NO SQL
SELECT
    fo.id,
    pa.uid AS parentUID
FROM
    folders fo
LEFT JOIN folders pa ON pa.id = fo.folderID
WHERE
    fo.uid = _folderUID AND(
        fo.ownerID = _userID OR fo.id IN(
        SELECT
            itemID
        FROM
            permissions
        WHERE
            receiverID = _userID AND TYPE = "folder"
    )
    )$$

CREATE DEFINER=`rasmus`@`localhost` PROCEDURE `folderFiles` (IN `_folderID` INT)  NO SQL
SELECT name,
    uid,
    creation,
    ownerID,
    'file' AS type
FROM
    files
WHERE
    folderID = _folderID OR id IN(
    SELECT
        itemID
    FROM
        shortcuts
    WHERE
        targetFolder = _folderID AND type = 'file'
)
UNION ALL
SELECT name,
    uid,
    creation,
    ownerID,
    'folder' AS type
FROM
    folders
WHERE
    folderID = _folderID OR id IN(
    SELECT
        itemID
    FROM
        shortcuts
    WHERE
        targetFolder = _folderID AND type = 'folder'
)
ORDER BY creation DESC$$

CREATE DEFINER=`rasmus`@`localhost` PROCEDURE `rootFiles` (IN `_id` INT)  NO SQL
SELECT name,
    uid,
    creation,
    ownerID,
    'file' AS type
FROM
    files
WHERE
    (ownerID = _id AND folderID IS NULL) OR id IN(
    SELECT
        itemID
    FROM
        shortcuts
    WHERE
        targetFolder = NULL AND ownerID = _id AND type = 'file'
)
UNION ALL
SELECT name,
    uid,
    creation,
    ownerID,
    'folder' AS type
FROM
    folders
WHERE
    (ownerID = _id AND folderID IS NULL) OR id IN(
    SELECT
        itemID
    FROM
        shortcuts
    WHERE
        targetFolder = NULL AND ownerID = _id AND type = 'folders'
)
ORDER BY creation DESC$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `ownerID` int(11) NOT NULL,
  `folderID` int(11) DEFAULT NULL,
  `creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `uid` varchar(255) NOT NULL,
  `name` text NOT NULL,
  `ownerID` int(11) NOT NULL,
  `folderID` int(11) DEFAULT NULL,
  `creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `receiverID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `type` enum('folder','file') NOT NULL,
  `creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shortcuts`
--

CREATE TABLE `shortcuts` (
  `id` int(11) NOT NULL,
  `targetFolder` int(11) DEFAULT NULL,
  `itemID` int(11) NOT NULL,
  `type` enum('folder','item') NOT NULL,
  `ownerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shortcuts`
--
ALTER TABLE `shortcuts`
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
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shortcuts`
--
ALTER TABLE `shortcuts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
