-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 15, 2017 at 08:03 AM
-- Server version: 5.7.16
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `deletedemployee`
--

CREATE TABLE `deletedemployee` (
  `emplNum` int(13) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `jobTitle` varchar(45) NOT NULL,
  `extension` varchar(11) DEFAULT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `level` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emplNum` int(13) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `jobTitle` varchar(45) NOT NULL,
  `extension` varchar(11) DEFAULT NULL,
  `reportsTo` int(13) DEFAULT NULL,
  `tmp_reportsTo` int(13) NOT NULL DEFAULT '0',
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `is_manager` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emplNum`, `name`, `email`, `jobTitle`, `extension`, `reportsTo`, `tmp_reportsTo`, `username`, `password`, `is_manager`, `is_admin`) VALUES
(1001, 'Tando Kili', 'tandokili@gmail.com', 'journalist', '1234', 1004, 0, 'tando', 'b11cc6e320a7bf4880c1d18714962f2d', 0, 0),
(1002, 'Ean Boyce', 'hlophe73@gmail.com', 'Photographer', '3214', 1004, 0, 'ean', 'boyce', 0, 0),
(1003, 'Nolufefe Samalenge', 'jimmysamalenge@gmail.com', 'snr manager', '9986', NULL, 0, 'nolufefe', 'samalenge', 1, 0),
(1004, 'Jimmy Samalenge', 'jsamalenge@matityah.co.za', 'Manager', '7789', 1003, 0, 'jimmy', 'b3c9e179e5e600db1b21b0fda2f46bce', 1, 1),
(1005, 'Freddy Kapako', 'fkapako@gmail.com', 'tester', '8828', 1004, 0, 'freddy', 'kapako', 0, 0),
(1007, 'Indi Madikida', 'indiphilemadikida@gmail.com', 'Journalist', '13215', 1004, 0, 'indi', 'madikida', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `employee_leaves`
--

CREATE TABLE `employee_leaves` (
  `usedDays` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hours` decimal(4,2) NOT NULL DEFAULT '0.00',
  `am_pm` varchar(10) NOT NULL DEFAULT 'n/a',
  `leaves_id` int(11) NOT NULL,
  `employee_emplNum` int(13) NOT NULL,
  `leaveapplstatus_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee_leaves`
--

INSERT INTO `employee_leaves` (`usedDays`, `hours`, `am_pm`, `leaves_id`, `employee_emplNum`, `leaveapplstatus_id`) VALUES
('10.00', '90.00', '', 2, 1002, 13);

-- --------------------------------------------------------

--
-- Table structure for table `hours`
--

CREATE TABLE `hours` (
  `id` int(11) NOT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `fromTime` time DEFAULT NULL,
  `toTime` time DEFAULT NULL,
  `hours` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hours`
--

INSERT INTO `hours` (`id`, `duration`, `fromTime`, `toTime`, `hours`) VALUES
(0, NULL, NULL, NULL, '9.00'),
(1, 'FullDay', '07:30:00', '16:30:00', '9.00'),
(2, 'HaflDay - Mornig', '07:30:00', '11:45:00', '4.50'),
(3, 'HaflDay - Afternoon', '11:45:00', '16:30:00', '4.50');

-- --------------------------------------------------------

--
-- Table structure for table `leaveappl`
--

CREATE TABLE `leaveappl` (
  `id` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `numDays` decimal(4,2) NOT NULL DEFAULT '0.00',
  `hours` decimal(4,2) NOT NULL,
  `am_pm` varchar(10) NOT NULL DEFAULT 'n/a',
  `comments` varchar(45) DEFAULT NULL,
  `dateApplied` datetime NOT NULL,
  `med_cert` varchar(3) DEFAULT 'N/A',
  `leaves_id` int(11) NOT NULL,
  `employee_emplNum` int(13) NOT NULL,
  `hours_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leaveappl`
--

INSERT INTO `leaveappl` (`id`, `startDate`, `endDate`, `startTime`, `endTime`, `numDays`, `hours`, `am_pm`, `comments`, `dateApplied`, `med_cert`, `leaves_id`, `employee_emplNum`, `hours_id`) VALUES
(1, '2015-03-02', '2015-03-02', NULL, NULL, '1.00', '9.00', 'full day', 'head ache', '2015-03-29 18:48:42', 'Yes', 1, 1004, 1),
(2, '2015-03-30', '2015-03-30', NULL, NULL, '0.50', '4.50', 'PM', 'rest', '2015-03-29 19:01:21', 'N/A', 2, 1004, 3),
(3, '2015-04-09', '2015-04-10', NULL, NULL, '2.00', '18.00', '', 'Rest', '2015-04-09 12:11:12', 'N/A', 2, 1004, 0),
(4, '2015-04-10', '2015-04-10', NULL, NULL, '1.00', '9.00', 'full day', 'Fever', '2015-04-09 12:54:55', 'No', 1, 1004, 1),
(5, '2015-04-09', '2015-04-10', NULL, NULL, '2.00', '18.00', '', 'New Born Baby', '2015-04-09 13:01:33', 'N/A', 3, 1004, 0),
(6, '2015-04-09', '2015-04-10', NULL, NULL, '2.00', '18.00', '', 'Head Ache', '2015-04-09 16:01:12', 'Yes', 1, 1004, 0),
(7, '2015-04-09', '2015-04-10', NULL, NULL, '2.00', '18.00', '', 'Rest', '2015-04-09 17:39:13', 'N/A', 2, 1004, 0),
(8, '2015-04-09', '2015-04-10', NULL, NULL, '2.00', '18.00', '', 'Rest', '2015-04-09 18:11:58', 'N/A', 2, 1004, 0),
(9, '2015-04-09', '2015-04-10', NULL, NULL, '2.00', '18.00', '', 'Rest', '2015-04-09 18:11:59', 'N/A', 2, 1004, 0),
(10, '2015-04-09', '2015-04-09', NULL, NULL, '1.00', '9.00', 'full day', 'head ache', '2015-04-09 19:17:23', 'Yes', 1, 1004, 1),
(11, '2015-04-18', '2015-04-25', NULL, NULL, '4.00', '36.00', '', 'Rest', '2015-04-18 21:20:53', 'N/A', 2, 1004, 0),
(13, '2015-05-08', '2015-05-08', NULL, NULL, '1.00', '9.00', 'full day', 'headache', '2015-05-08 18:34:37', 'Yes', 1, 1004, 1),
(14, '2015-06-29', '2015-07-10', NULL, NULL, '10.00', '90.00', '', '', '2015-06-24 07:56:45', '', 2, 1002, 0),
(15, '2015-08-27', '2015-08-28', NULL, NULL, '2.00', '18.00', '', 'Rest', '2015-08-26 12:18:50', 'N/A', 1, 1004, 0),
(16, '2016-09-15', '2016-09-15', NULL, NULL, '1.00', '9.00', 'full day', 'ZFDSGDSGDS', '2016-09-15 09:23:31', 'N/A', 1, 1004, 1),
(17, '2016-09-16', '2016-09-19', NULL, NULL, '2.00', '18.00', '', 'i76f686', '2016-09-15 09:43:34', 'N/A', 3, 1004, 0),
(18, '2017-02-14', '2017-02-14', NULL, NULL, '0.50', '4.50', 'AM', 'ewweqqewr', '2017-02-14 11:38:21', 'N/A', 1, 1004, 2);

-- --------------------------------------------------------

--
-- Table structure for table `leaveapplstatus`
--

CREATE TABLE `leaveapplstatus` (
  `id` int(11) NOT NULL,
  `status` varchar(9) NOT NULL DEFAULT 'pending',
  `approved_by` int(13) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `declined_by` int(13) DEFAULT NULL,
  `declined_date` timestamp NULL DEFAULT NULL,
  `reason_for_decline` text,
  `request_cancel` varchar(3) NOT NULL DEFAULT 'no',
  `request_cancel_date` datetime DEFAULT NULL,
  `reasons_for_cancel` varchar(255) DEFAULT NULL,
  `leaveAppl_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leaveapplstatus`
--

INSERT INTO `leaveapplstatus` (`id`, `status`, `approved_by`, `approved_date`, `declined_by`, `declined_date`, `reason_for_decline`, `request_cancel`, `request_cancel_date`, `reasons_for_cancel`, `leaveAppl_id`) VALUES
(1, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 16:07:50', 'N/A', 1),
(2, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 17:29:12', 'N/A', 2),
(3, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 17:35:43', 'N/A', 3),
(4, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 16:08:05', 'N/A', 4),
(5, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 17:31:59', 'N/A', 5),
(6, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 17:39:34', 'N/A', 6),
(7, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 18:10:00', 'N/A', 7),
(8, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 19:11:19', 'N/A', 8),
(9, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 19:16:09', 'N/A', 9),
(10, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-09 19:22:51', 'N/A', 10),
(11, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-04-18 21:21:54', 'N/A', 11),
(12, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-05-08 18:35:59', 'N/A', 13),
(13, 'approved', 1004, '2015-12-06 13:34:53', NULL, NULL, NULL, 'no', NULL, NULL, 14),
(14, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2015-08-26 12:20:10', 'N/A', 15),
(15, 'pending', NULL, NULL, NULL, NULL, NULL, 'no', NULL, NULL, 16),
(16, 'pending', NULL, NULL, NULL, NULL, NULL, 'no', NULL, NULL, 17),
(17, 'pending', NULL, NULL, NULL, NULL, NULL, 'yes', '2017-02-14 11:39:57', 'asfdasasfa', 18);

-- --------------------------------------------------------

--
-- Table structure for table `leavecancelstatus`
--

CREATE TABLE `leavecancelstatus` (
  `id` int(11) NOT NULL,
  `approved_by` int(13) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `declined_by` int(13) DEFAULT NULL,
  `declined_date` timestamp NULL DEFAULT NULL,
  `reasons_for_decline` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL,
  `type` varchar(45) NOT NULL,
  `available` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `type`, `available`) VALUES
(1, 'Sick', '36.00'),
(2, 'Annual', '21.00'),
(3, 'Family Responsibility', '8.00');

-- --------------------------------------------------------

--
-- Table structure for table `tmp_leavesummary`
--

CREATE TABLE `tmp_leavesummary` (
  `emplNum` varchar(10) NOT NULL,
  `type` varchar(10) NOT NULL,
  `used` double(4,2) NOT NULL,
  `available` double(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tmp_leavesummary`
--

INSERT INTO `tmp_leavesummary` (`emplNum`, `type`, `used`, `available`) VALUES
('1002', 'Sick', 0.00, 36.00),
('1002', 'Annual', 0.00, 21.00),
('1002', 'Family Res', 0.00, 8.00),
('', 'Sick', 0.00, 36.00),
('', 'Annual', 0.00, 21.00),
('', 'Family Res', 0.00, 8.00),
('1001', 'Sick', 0.00, 36.00),
('1001', 'Annual', 0.00, 21.00),
('1001', 'Family Res', 0.00, 8.00),
('1004', 'Sick', 0.00, 36.00),
('1004', 'Annual', 0.00, 21.00),
('1004', 'Family Res', 0.00, 8.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `deletedemployee`
--
ALTER TABLE `deletedemployee`
  ADD PRIMARY KEY (`emplNum`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emplNum`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `reportsTo_idx` (`reportsTo`);

--
-- Indexes for table `employee_leaves`
--
ALTER TABLE `employee_leaves`
  ADD KEY `fk_employee_leaves_leaves1_idx` (`leaves_id`),
  ADD KEY `fk_employee_leaves_employee1_idx` (`employee_emplNum`),
  ADD KEY `fk_employee_leaves_leaveapplstatus1_idx` (`leaveapplstatus_id`);

--
-- Indexes for table `hours`
--
ALTER TABLE `hours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaveappl`
--
ALTER TABLE `leaveappl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Leave_appl_leaves_idx` (`leaves_id`),
  ADD KEY `fk_leave_appl_employee1_idx` (`employee_emplNum`),
  ADD KEY `fk_leaveappl_hours1_idx` (`hours_id`);

--
-- Indexes for table `leaveapplstatus`
--
ALTER TABLE `leaveapplstatus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_leaveApplStatus_leaveAppl1_idx` (`leaveAppl_id`);

--
-- Indexes for table `leavecancelstatus`
--
ALTER TABLE `leavecancelstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leaveappl`
--
ALTER TABLE `leaveappl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `leaveapplstatus`
--
ALTER TABLE `leaveapplstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `leavecancelstatus`
--
ALTER TABLE `leavecancelstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `reportsTo` FOREIGN KEY (`reportsTo`) REFERENCES `employee` (`emplNum`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employee_leaves`
--
ALTER TABLE `employee_leaves`
  ADD CONSTRAINT `fk_employee_leaves_employee1` FOREIGN KEY (`employee_emplNum`) REFERENCES `employee` (`emplNum`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_employee_leaves_leaveapplstatus1` FOREIGN KEY (`leaveapplstatus_id`) REFERENCES `leaveapplstatus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_employee_leaves_leaves1` FOREIGN KEY (`leaves_id`) REFERENCES `leaves` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `leaveappl`
--
ALTER TABLE `leaveappl`
  ADD CONSTRAINT `fk_Leave_appl_leaves` FOREIGN KEY (`leaves_id`) REFERENCES `leaves` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_leave_appl_employee1` FOREIGN KEY (`employee_emplNum`) REFERENCES `employee` (`emplNum`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_leaveappl_hours1` FOREIGN KEY (`hours_id`) REFERENCES `hours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `leaveapplstatus`
--
ALTER TABLE `leaveapplstatus`
  ADD CONSTRAINT `fk_leaveApplStatus_leaveAppl1` FOREIGN KEY (`leaveAppl_id`) REFERENCES `leaveappl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
