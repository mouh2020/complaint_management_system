-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 16, 2025 at 10:03 PM
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
-- Database: `ComplaintsSystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `adminId` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`adminId`, `username`, `password`) VALUES
(1, 'admin', 'admin'),
(2, 'admin2', 'adminsecure');

-- --------------------------------------------------------

--
-- Table structure for table `Complaint`
--

CREATE TABLE `Complaint` (
  `complaintId` int NOT NULL,
  `userId` int NOT NULL,
  `title` varchar(100) NOT NULL,
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
(6, 6, 'Power Outage', 'Power outage in Building B', 'Pending', '2025-01-16', NULL),
(7, 6, 'Broken Chair', 'The chair in my office is broken', 'Pending', '2025-01-16', NULL),
(8, 6, 'AC Not Working', 'The air conditioner is not cooling properly', 'Resolved', '2025-01-16', '2025-01-16'),
(9, 6, 'first complaint', 'Yes my first complaint', 'Resolved', '2025-01-16', '2025-01-16'),
(10, 6, 'first complaint', 'Yes my first complaint', 'Resolved', '2025-01-16', '2025-01-16'),
(11, 6, 'hi there', 'Wow description', 'Pending', '2025-01-16', NULL),
(12, 6, 'The second complaint', 'Hi there', 'Pending', '2025-01-16', NULL),
(13, 6, 'The second complaint', 'Hi there', 'Resolved', '2025-01-16', '2025-01-16');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `userId` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`userId`, `username`, `password`) VALUES
(1, 'user1', 'password123'),
(2, 'user2', 'password456'),
(3, 'test', '$2y$10$8G1SSfEiwVPiZHFWJxRyye4hhj3fX.nk/k6.w/U/L1Cwlf8QkdHAW'),
(4, 'user', '$2y$10$i6nlwPsL72QefC1zgjFrkOuNu7J9W/Kcz37YzYDD1UJLEEt7EG94q'),
(5, 'sa', '$2y$10$aiO9UKMg.NPFzh5c1XzXYOnsjG9OMGAi5W/ibKRUldUGZlwiN6v9u'),
(6, 'med', '$2y$10$x5yVFkT8lRbmtkQxMkHwR.STHeOe3yE5TXyOvwemTlPm5iBMGQH4K'),
(7, 'medmed', '$2y$10$EFdXI1zbCxX/H4IUZHoAtOinecgkSf0WY0uzM/7m1wEqxQQxkY/K6');

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
  MODIFY `complaintId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `userId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
