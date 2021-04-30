-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2021 at 09:20 AM
-- Server version: 8.0.22
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bloodbank`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `findDonatedDonors` ()  BEGIN SELECT * FROM donor D WHERE EXISTS(SELECT * FROM donated_at DA WHERE DA.D_ID = D.D_ID); END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `findDonatedDonorsInBranch` (IN `branchID` BIGINT)  BEGIN SELECT * FROM donor D WHERE EXISTS(SELECT * FROM donated_at DA WHERE DA.D_ID = D.D_ID AND DA.B_ID = branchID); END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `findRegisteredDonors` ()  BEGIN SELECT * FROM donor D WHERE NOT EXISTS(SELECT * FROM donated_at DA WHERE DA.D_ID = D.D_ID); END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `findRegisteredDonorsInBranch` (IN `branchID` BIGINT)  BEGIN SELECT * FROM donor D WHERE NOT EXISTS(SELECT * FROM donated_at DA WHERE DA.D_ID = D.D_ID AND DA.B_ID = branchID); END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `blood`
--

CREATE TABLE `blood` (
  `BloodGroup` varchar(3) COLLATE utf8mb4_general_ci NOT NULL,
  `Br_ID` bigint NOT NULL,
  `Amount` int NOT NULL DEFAULT '0'
) ;

--
-- Dumping data for table `blood`
--

INSERT INTO `blood` (`BloodGroup`, `Br_ID`, `Amount`) VALUES
('A+', 50410, 0),
('A+', 50411, 0),
('A+', 50412, 0),
('A+', 50413, 0),
('A+', 50414, 0),
('A+', 50415, 1),
('A-', 50410, 0),
('A-', 50411, 0),
('A-', 50412, 0),
('A-', 50413, 0),
('A-', 50414, 0),
('A-', 50415, 0),
('AB+', 50410, 0),
('AB+', 50411, 0),
('AB+', 50412, 0),
('AB+', 50413, 0),
('AB+', 50414, 0),
('AB+', 50415, 2),
('AB-', 50410, 0),
('AB-', 50411, 1),
('AB-', 50412, 0),
('AB-', 50413, 0),
('AB-', 50414, 0),
('AB-', 50415, 1),
('B+', 50410, 0),
('B+', 50411, 2),
('B+', 50412, 0),
('B+', 50413, 0),
('B+', 50414, 0),
('B+', 50415, 0),
('B-', 50410, 0),
('B-', 50411, 0),
('B-', 50412, 0),
('B-', 50413, 0),
('B-', 50414, 0),
('B-', 50415, 0),
('O+', 50410, 0),
('O+', 50411, 1),
('O+', 50412, 1),
('O+', 50413, 3),
('O+', 50414, 0),
('O+', 50415, 0),
('O-', 50410, 0),
('O-', 50411, 0),
('O-', 50412, 0),
('O-', 50413, 0),
('O-', 50414, 0),
('O-', 50415, 0);

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `Br_ID` bigint NOT NULL,
  `Name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Phone` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Address` varchar(150) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`Br_ID`, `Name`, `Email`, `Phone`, `Address`) VALUES
(50410, 'Union Blood Bank Center', 'ubbc@bbnet.com', '3853212938', 'L-5, Abhilash Complex, Ambawadi, Ahmedabad, Gujarat'),
(50411, 'White Petal Blood Center', 'wpbc@gmail.com', '3851362374', '46/C, Rajdeep, Nr Malhaar, Naupada, Mumbai, Maharashtra'),
(50412, 'Hemocare', 'donorreq@hemocare.com', '3854298430', '41, Virupaksha Plaza, Shivaji Road, Panvel, Mumbai, Maharashtra'),
(50413, 'Newon Blood Bank', 'inquire@newonbb.net', '3854250062', '1-9-642/6, Vidyanagar,Hyderabad, Andhra Pradesh'),
(50414, 'HollyReds', 'hollyred@bbnet.com', '3853193561', '321, Parklane, Hyderabad, Andhra Pradesh'),
(50415, 'Etisson Blood Bank', 'request@etisson.com', '3856469450', '61, Sembudoss Street, George Town, Chennai, Tamil Nadu');

--
-- Triggers `branch`
--
DELIMITER $$
CREATE TRIGGER `addStock` AFTER INSERT ON `branch` FOR EACH ROW INSERT INTO Blood(BloodGroup,Br_ID,Amount) VALUES('A+', NEW.Br_ID, 0),('B+', NEW.Br_ID, 0),('O+', NEW.Br_ID, 0),('AB+', NEW.Br_ID, 0),('A-', NEW.Br_ID, 0),('B-', NEW.Br_ID, 0),('O-', NEW.Br_ID, 0),('AB-', NEW.Br_ID, 0)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `donated_at`
--

CREATE TABLE `donated_at` (
  `B_ID` bigint NOT NULL,
  `D_ID` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `DonatedOn` date NOT NULL,
  `Volume` int NOT NULL DEFAULT '0'
) ;

--
-- Dumping data for table `donated_at`
--

INSERT INTO `donated_at` (`B_ID`, `D_ID`, `DonatedOn`, `Volume`) VALUES
(50411, '69910', '2020-10-09', 1),
(50411, '69911', '2020-11-08', 2),
(50411, '69914', '2020-09-07', 1),
(50412, '69910', '2020-10-29', 1),
(50413, '69919', '2020-08-12', 3),
(50415, '69914', '2020-05-01', 1),
(50415, '69915', '2020-09-21', 1),
(50415, '69920', '2020-10-28', 2);

--
-- Triggers `donated_at`
--
DELIMITER $$
CREATE TRIGGER `addAmount` AFTER INSERT ON `donated_at` FOR EACH ROW UPDATE Blood SET Amount = Amount + NEW.Volume
WHERE Br_ID = NEW.B_ID AND BloodGroup =
(SELECT Bloodtype FROM Donor WHERE D_ID = NEW.D_ID)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `deleteAmount` BEFORE DELETE ON `donated_at` FOR EACH ROW UPDATE Blood SET Amount = Amount - OLD.Volume
WHERE Br_ID = OLD.B_ID AND BloodGroup =
(SELECT Bloodtype FROM Donor WHERE D_ID = OLD.D_ID)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateAmount` AFTER UPDATE ON `donated_at` FOR EACH ROW UPDATE Blood SET Amount = Amount + (NEW.Volume - OLD.Volume)
WHERE Br_ID = NEW.B_ID AND BloodGroup = 
(SELECT Bloodtype FROM Donor WHERE D_ID = NEW.D_ID)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `donation_record`
-- (See below for the actual view)
--
CREATE TABLE `donation_record` (
`D_ID` varchar(5)
,`Name` varchar(50)
,`Bloodtype` varchar(3)
,`B_ID` bigint
,`DonatedOn` date
,`Volume` int
);

-- --------------------------------------------------------

--
-- Table structure for table `donor`
--

CREATE TABLE `donor` (
  `D_ID` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `Name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Bloodtype` varchar(3) COLLATE utf8mb4_general_ci NOT NULL,
  `Age` int NOT NULL,
  `Gender` char(1) COLLATE utf8mb4_general_ci NOT NULL,
  `Phone` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Weight` decimal(4,2) DEFAULT NULL
) ;

--
-- Dumping data for table `donor`
--

INSERT INTO `donor` (`D_ID`, `Name`, `Bloodtype`, `Age`, `Gender`, `Phone`, `Email`, `Weight`) VALUES
('69910', 'Ramya Sen', 'O+', 36, 'F', '3855666118', 'ramya85@gmail.com', '60.00'),
('69911', 'Jatin Dasgupta', 'B+', 46, 'M', '3466905476', 'jatipta@gmail.com', '92.00'),
('69912', 'Palash Saha', 'B+', 31, 'M', '3854102234', 'saha90@gmail.com', '72.56'),
('69913', 'Komala Patkar', 'A+', 27, 'F', '3856145030', 'komala.patkar@soundleads.fr', '70.00'),
('69914', 'Amritha Shroff', 'AB-', 36, 'F', '3854343210', 'amrithashroff@capcams.hu', '78.19'),
('69915', 'Iswara Sathe', 'A+', 39, 'M', '3854143233', 'iswaras@cencov.in', '96.00'),
('69916', 'Div Mathai', 'B+', 35, 'F', '3854397427', 'dmathai@gmail.com', '76.00'),
('69917', 'Mohan Chatterjee', 'O-', 44, 'M', '3855387187', 'mohanch@cameopro.com', '67.00'),
('69918', 'Mira Dhavan', 'A+', 39, 'F', '3857861067', 'mdhav@lifepro.net', '81.56'),
('69919', 'Barid Agarwal', 'O+', 49, 'M', '3857861067', 'bagar@gmail.com', '80.73'),
('69920', 'Ram Agarwal', 'AB+', 36, 'M', '3853798886', 'agarwalr87@dezeste.com', '72.63');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Emp_ID` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `Name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Branch` bigint NOT NULL,
  `Phone` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Salary` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Emp_ID`, `Name`, `Branch`, `Phone`, `Email`, `Salary`) VALUES
('28710', 'Mandar Khandke', 50411, '3853487523', 'mkhandke@gmail.com', 50000),
('28711', 'Vivek Ghosh', 50415, '3851590592', 'vivekg@etission.com', 60000),
('28712', 'Sangita Dhavan', 50410, '3852829332', 'olon@gmail.com', 50000),
('28713', 'Ajay Sidhu', 50413, '3856412449', 'ajay.sidhu@newonbb.net', 55000),
('28714', 'Tanika Bansal', 50411, '3855558601', 'tanikab@outlook.com', 50000),
('28715', 'Rishi Das', 50412, '3856412449', 'rdas@hemocare.com', 55000),
('28716', 'Shetan Peri', 50414, '3857382322', 'shetanp@hollyred.bbnet.com', 57500);

-- --------------------------------------------------------

--
-- Table structure for table `employeelogin`
--

CREATE TABLE `employeelogin` (
  `Emp_ID` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `Username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeelogin`
--

INSERT INTO `employeelogin` (`Emp_ID`, `Username`, `Password`) VALUES
('28710', 'mkhandke', '12345'),
('28711', 'vivekg', '456'),
('28712', 'sangita', '147963'),
('28713', 'ajay.sidhu', '789321'),
('28714', 'tanikab', '123456'),
('28715', 'rishi_das', '159753'),
('28716', 'peri_shetan', '357951');

-- --------------------------------------------------------

--
-- Structure for view `donation_record`
--
DROP TABLE IF EXISTS `donation_record`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `donation_record`  AS SELECT `donor`.`D_ID` AS `D_ID`, `donor`.`Name` AS `Name`, `donor`.`Bloodtype` AS `Bloodtype`, `donated_at`.`B_ID` AS `B_ID`, `donated_at`.`DonatedOn` AS `DonatedOn`, `donated_at`.`Volume` AS `Volume` FROM (`donor` join `donated_at` on((`donor`.`D_ID` = `donated_at`.`D_ID`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood`
--
ALTER TABLE `blood`
  ADD PRIMARY KEY (`BloodGroup`,`Br_ID`),
  ADD KEY `Blood_Branch_FK` (`Br_ID`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`Br_ID`),
  ADD UNIQUE KEY `Br_ID` (`Br_ID`);

--
-- Indexes for table `donated_at`
--
ALTER TABLE `donated_at`
  ADD PRIMARY KEY (`B_ID`,`D_ID`),
  ADD KEY `Donated_At_Donor_FK` (`D_ID`);

--
-- Indexes for table `donor`
--
ALTER TABLE `donor`
  ADD PRIMARY KEY (`D_ID`),
  ADD UNIQUE KEY `D_ID` (`D_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Emp_ID`),
  ADD UNIQUE KEY `Emp_ID` (`Emp_ID`),
  ADD KEY `Employee_Branch_FK` (`Branch`);

--
-- Indexes for table `employeelogin`
--
ALTER TABLE `employeelogin`
  ADD PRIMARY KEY (`Emp_ID`),
  ADD UNIQUE KEY `Emp_ID` (`Emp_ID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood`
--
ALTER TABLE `blood`
  ADD CONSTRAINT `Blood_Branch_FK` FOREIGN KEY (`Br_ID`) REFERENCES `branch` (`Br_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `donated_at`
--
ALTER TABLE `donated_at`
  ADD CONSTRAINT `Donated_At_Branch_FK` FOREIGN KEY (`B_ID`) REFERENCES `branch` (`Br_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Donated_At_Donor_FK` FOREIGN KEY (`D_ID`) REFERENCES `donor` (`D_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `Employee_Branch_FK` FOREIGN KEY (`Branch`) REFERENCES `branch` (`Br_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employeelogin`
--
ALTER TABLE `employeelogin`
  ADD CONSTRAINT `EmpLogin_Emp_FK` FOREIGN KEY (`Emp_ID`) REFERENCES `employee` (`Emp_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
