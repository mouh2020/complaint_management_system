-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 19, 2025 at 08:29 AM
-- Server version: 8.0.40-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `complaints_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `adminId` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`adminId`, `username`, `password`, `email`) VALUES
(1, 'admin1', 'pass1', 'admin1@example.com'),
(2, 'admin2', 'pass2', 'admin2@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `Complaint`
--

CREATE TABLE `Complaint` (
  `complaintId` int NOT NULL,
  `userId` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `dateSubmitted` date NOT NULL,
  `resolvedDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Complaint`
--

INSERT INTO `Complaint` (`complaintId`, `userId`, `title`, `description`, `status`, `dateSubmitted`, `resolvedDate`) VALUES
(1, 1, 'Broken AC', 'The AC in the main hall is not working.', 'Resolved', '2025-01-01', '2025-01-16'),
(2, 2, 'Noisy Neighbors', 'The neighbors are making too much noise at night.', 'Resolved', '2025-01-05', '2025-01-16'),
(3, 1, 'Leaking Roof', 'The roof is leaking during rain.', 'Resolved', '2025-01-10', '2025-01-16'),
(4, 6, 'Network Issue', 'Internet is not working in Room 101', 'Resolved', '2025-01-16', '2025-01-16'),
(5, 6, 'Leaking Tap', 'The tap in the kitchen is leaking', 'Resolved', '2025-01-16', '2025-01-16'),
(6, 6, 'Power Outage', 'Power outage in Building B', 'Resolved', '2025-01-16', '2025-01-19'),
(7, 6, 'Broken Chair', 'The chair in my office is broken', 'Pending', '2025-01-16', NULL),
(8, 6, 'AC Not Working', 'The air conditioner is not cooling properly', 'Resolved', '2025-01-16', '2025-01-16'),
(9, 6, 'first complaint', 'Yes my first complaint', 'Resolved', '2025-01-16', '2025-01-16'),
(10, 6, 'first complaint', 'Yes my first complaint', 'Resolved', '2025-01-16', '2025-01-16'),
(11, 6, 'hi there', 'Wow description', 'Pending', '2025-01-16', NULL),
(12, 6, 'The second complaint', 'Hi there', 'Resolved', '2025-01-16', '2025-01-18'),
(13, 6, 'The second complaint', 'Hi there', 'Resolved', '2025-01-16', '2025-01-16'),
(14, 6, 'Test', 'Test', 'Resolved', '2025-01-19', '2025-01-19');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `userId` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`userId`, `username`, `password`, `email`) VALUES
(1, 'user1', 'pass1', 'user1@example.com'),
(2, 'user2', 'pass2', 'user2@example.com'),
(3, 'user3', 'pass3', 'user3@example.com'),
(4, 'user', '123456', 'user4@example.com'),
(5, 'sa', '123456', 'user5@example.com'),
(6, 'med', '123456', 'user6@example.com'),
(7, 'medmed', '123456', 'user7@example.com'),
(8, 'testgmail', '123456', 'test@gmail.com'),
(9, '2025', '2025', 't2025@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Indexes for table `Complaint`
--
ALTER TABLE `Complaint`
  ADD PRIMARY KEY (`complaintId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `adminId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Complaint`
--
ALTER TABLE `Complaint`
  MODIFY `complaintId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Complaint`
--
ALTER TABLE `Complaint`
  ADD CONSTRAINT `Complaint_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
