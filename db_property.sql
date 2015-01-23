-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2015 at 11:38 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_property`
--

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE IF NOT EXISTS `property` (
`property_id` int(11) NOT NULL,
  `property_param_no` int(11) NOT NULL,
  `property_code` varchar(15) NOT NULL,
  `property_category_id` int(11) NOT NULL,
  `property_name` varchar(75) NOT NULL,
  `property_rent_price` double NOT NULL,
  `property_images` varchar(255) NOT NULL,
  `property_desc` text NOT NULL,
  `property_status` enum('0','1') NOT NULL DEFAULT '1',
  `property_active` enum('0','1') NOT NULL DEFAULT '1',
  `created_user` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_user` int(11) NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`property_id`, `property_param_no`, `property_code`, `property_category_id`, `property_name`, `property_rent_price`, `property_images`, `property_desc`, `property_status`, `property_active`, `created_user`, `created_time`, `updated_user`, `updated_time`) VALUES
(1, 1, 'RUA000001', 1, 'Ruangan Melati', 150000, 'a6d9b95f669e9c524d659de4a0d1f4db.jpg', 'Ruangan melati ini adalah ruangan yang sangat bagus', '1', '1', 1, '2015-01-16 11:23:58', 5, '2015-01-21 00:16:25'),
(2, 2, 'RUA000002', 1, 'Ruangan Melati 2', 200000, '0fb2cd543e0d1268fe7599f88c837e32.jpg', 'Ini merupakan ruangan melati dengan kapasitas yang lebih besar', '1', '1', 1, '2015-01-16 11:35:09', 4, '2015-01-21 04:55:42'),
(3, 1, 'BRG000001', 2, 'Patung Koala', 75000, '4feb3516260e0aa86e0c7fdef40d03c2.jpg', 'Patung ini memiliki nilai seni yang sangat tinggi.', '1', '1', 1, '2015-01-16 11:37:21', 1, '2015-01-16 11:53:01'),
(4, 3, 'RUA000003', 1, 'Ruangan Mawar', 500000, 'd41d973bfb73245840dfc850bae40bb9.jpg', 'Merupakan ruangan rapat eksekutif yang menampung 10 orang', '1', '1', 1, '2015-01-17 01:15:31', 7, '2015-01-17 10:11:41'),
(5, 4, 'RUA000004', 1, 'Ruangan Mawar 2', 750000, '0d4b4c93b3e75b69bb5daaebf6423b51.jpg', 'Merupakan ruangan rapat dengan kapasitas mencapai 15 orang', '1', '1', 1, '2015-01-17 01:20:08', 9, '2015-01-20 10:42:44'),
(6, 5, 'RUA000005', 1, 'Ruangan Merak', 1250000, '59dd5f836d98201680db7bc2700d30b4.jpg', 'Ruangan ini menampung 75 orang untuk meeting', '1', '1', 1, '2015-01-18 09:53:52', 4, '2015-01-21 04:45:56'),
(7, 6, 'RUA000006', 1, 'Ruangan Parkit', 550000, 'bfea42e23f47f5fd24bb6bf873ea3512.jpg', 'merupakan ruangan rapat dengan desain klasik', '1', '1', 1, '2015-01-18 10:05:42', 4, '2015-01-21 05:25:55'),
(8, 2, 'BRG000002', 2, 'Proyektor', 200000, 'ab51e408c584f42279300d29caec7dad.jpg', 'Proyektor merk infocus', '1', '1', 1, '2015-01-18 10:07:24', 10, '2015-01-22 00:26:07'),
(9, 3, 'BRG000003', 2, 'Kursi', 50000, '4400d1d4e0b37707d31d591978acf9f4.jpg', 'Kursi bermotif kerajaan', '1', '1', 1, '2015-01-18 10:09:20', 4, '2015-01-21 09:52:52');

-- --------------------------------------------------------

--
-- Table structure for table `property_category`
--

CREATE TABLE IF NOT EXISTS `property_category` (
`property_category_id` int(11) NOT NULL,
  `property_category_code` varchar(10) NOT NULL,
  `property_category_name` varchar(50) NOT NULL,
  `property_category_active` enum('0','1') NOT NULL DEFAULT '1',
  `created_user` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_user` int(11) NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `property_category`
--

INSERT INTO `property_category` (`property_category_id`, `property_category_code`, `property_category_name`, `property_category_active`, `created_user`, `created_time`, `updated_user`, `updated_time`) VALUES
(1, 'RUA', 'Ruangan', '1', 1, '2015-01-16 09:31:44', 0, '0000-00-00 00:00:00'),
(2, 'BRG', 'Barang', '1', 1, '2015-01-16 09:31:56', 1, '2015-01-16 09:34:41');

-- --------------------------------------------------------

--
-- Table structure for table `rent`
--

CREATE TABLE IF NOT EXISTS `rent` (
`rent_id` int(11) NOT NULL,
  `rent_param` int(11) NOT NULL,
  `rent_no` varchar(15) NOT NULL,
  `rent_user_id` int(11) NOT NULL,
  `rent_property_id` int(11) NOT NULL,
  `rent_date` datetime NOT NULL,
  `rent_date_return` datetime NOT NULL,
  `rent_days` int(11) NOT NULL,
  `rent_description` text NOT NULL,
  `rent_type` enum('1','2') NOT NULL DEFAULT '1',
  `rent_upload` varchar(255) NOT NULL,
  `rent_price` double NOT NULL,
  `rent_penalty` double NOT NULL,
  `rent_penalty_paid` enum('0','1') NOT NULL DEFAULT '1',
  `rent_penalty_upload` varchar(255) NOT NULL,
  `rent_user_approved` int(11) NOT NULL,
  `rent_status` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `rent_active` enum('0','1') NOT NULL DEFAULT '1',
  `created_user` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_user` int(11) NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `rent`
--

INSERT INTO `rent` (`rent_id`, `rent_param`, `rent_no`, `rent_user_id`, `rent_property_id`, `rent_date`, `rent_date_return`, `rent_days`, `rent_description`, `rent_type`, `rent_upload`, `rent_price`, `rent_penalty`, `rent_penalty_paid`, `rent_penalty_upload`, `rent_user_approved`, `rent_status`, `rent_active`, `created_user`, `created_time`, `updated_user`, `updated_time`) VALUES
(1, 1, 'RENT000001', 5, 1, '2015-01-24 00:00:00', '2015-01-26 00:00:00', 2, 'sewa ruangan melati rua00001 selama 2 hari dari tanggal 24/01/2015 ', '2', '', 0, 0, '1', '', 0, '0', '0', 5, '2015-01-17 08:50:34', 4, '2015-01-21 00:07:03'),
(2, 2, 'RENT000002', 5, 2, '2015-01-23 00:00:00', '2015-01-25 00:00:00', 2, 'minjem 2 hari ruang melati 2 internal', '1', 'e2cafa36113145eb40b06615db6d5fc3.docx', 0, 0, '1', '', 4, '3', '1', 5, '2015-01-17 09:07:58', 4, '2015-01-17 17:58:14'),
(3, 3, 'RENT000003', 7, 5, '2015-01-24 00:00:00', '2015-01-27 00:00:00', 3, 'Pinjam untuk meeting', '2', '', 0, 0, '1', '', 0, '0', '0', 7, '2015-01-17 10:07:55', 7, '2015-01-17 10:10:46'),
(4, 4, 'RENT000004', 7, 4, '2015-01-31 00:00:00', '2015-02-03 00:00:00', 3, 'Peminjaman ruangan mawar 3 hari, eksternal', '2', '', 1500000, 0, '1', '', 0, '0', '1', 7, '2015-01-17 10:11:41', 0, '0000-00-00 00:00:00'),
(5, 5, 'RENT000005', 8, 2, '2015-01-30 00:00:00', '2015-02-04 00:00:00', 5, 'lima hari ya..', '2', '', 1000000, 0, '1', '', 4, '3', '1', 8, '2015-01-18 09:31:51', 4, '2015-01-18 09:33:54'),
(6, 6, 'RENT000006', 9, 8, '2015-01-23 00:00:00', '2015-01-25 00:00:00', 2, 'Untuk keperluan mendadak', '2', '', 400000, 0, '1', '', 4, '1', '1', 9, '2015-01-20 10:14:27', 4, '2015-01-20 10:14:48'),
(7, 7, 'RENT000007', 9, 5, '2015-02-07 00:00:00', '2015-02-10 00:00:00', 3, 'untuk meeting diubah menjadi tanggal 7 feb dari tanggal 24 jan.', '1', '54b4bc01925b29342259ebc0b7833629.PNG', 0, 0, '1', '', 4, '1', '1', 9, '2015-01-20 10:42:44', 4, '2015-01-22 11:36:44'),
(8, 8, 'RENT000008', 5, 1, '2015-02-13 00:00:00', '2015-02-15 00:00:00', 2, 'peminjaman eksternal', '2', 'ce32f02fe9840d09ded6e16004257b11.PNG', 300000, 0, '1', '', 4, '1', '1', 5, '2015-01-21 00:16:25', 4, '2015-01-21 00:19:41'),
(9, 9, 'RENT000009', 10, 6, '2015-01-19 00:00:00', '2015-01-20 00:00:00', 1, 'test pinjam', '2', '027d9bc27ff53b141206ed0891a6c6e6.PNG', 1250000, 1250000, '1', '0451c9d23bee01b34b161951d483ffb1.PNG', 4, '3', '1', 10, '2015-01-21 00:41:48', 4, '2015-01-21 05:24:53'),
(10, 10, 'RENT0000010', 10, 2, '2015-01-19 00:00:00', '2015-01-20 00:00:00', 1, 'internal', '1', '2b8fb1fcd78f9173e061e95a1fd44c4f.jpg', 0, 0, '1', '', 4, '3', '1', 10, '2015-01-21 00:56:02', 4, '2015-01-21 04:55:41'),
(11, 11, 'RENT000011', 10, 7, '2015-02-06 00:00:00', '2015-02-08 00:00:00', 2, 'Peminjaman 2 hari', '2', '829cbd2035bc4faf9efa3595781d7503.PNG', 1100000, 0, '1', '', 4, '3', '1', 10, '2015-01-21 04:42:59', 4, '2015-01-21 05:25:55'),
(12, 12, 'RENT000012', 5, 9, '2015-01-17 00:00:00', '2015-01-20 00:00:00', 3, 'Minjem kursi untuk hajatan', '2', 'ce9bff48d5f14a3f3d9911790abff24c.PNG', 150000, 50000, '1', '2800af5fd4e1c3157f7384a0db11c79c.PNG', 4, '3', '1', 5, '2015-01-21 09:42:43', 4, '2015-01-21 09:55:37'),
(13, 13, 'RENT000013', 10, 8, '2015-01-26 00:00:00', '2015-01-28 00:00:00', 2, 'peminjaman 2 hari', '2', '', 400000, 0, '1', '', 0, '0', '1', 10, '2015-01-22 00:26:07', 0, '0000-00-00 00:00:00'),
(15, 14, 'RENT000014', 5, 8, '2015-01-29 00:00:00', '2015-01-30 00:00:00', 1, 'test', '2', '3957d4242222a6dad819e7c58575bfd1.PNG', 200000, 0, '1', '', 4, '1', '1', 5, '2015-01-22 11:12:42', 4, '2015-01-22 11:13:38'),
(16, 15, 'RENT000015', 5, 5, '2015-01-30 00:00:00', '2015-02-06 00:00:00', 7, 'Minjem 7 hari dari tanggal 30 jan - 6 feb 2015, ruangan mawar 2', '2', '', 5250000, 0, '1', '', 0, '0', '1', 5, '2015-01-22 11:29:30', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_fullname` varchar(50) NOT NULL,
  `user_address` text NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` varchar(15) NOT NULL,
  `user_active` enum('0','1') NOT NULL DEFAULT '1',
  `created_user` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_user` int(11) NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_type_id`, `user_password`, `user_fullname`, `user_address`, `user_email`, `user_phone`, `user_active`, `created_user`, `created_time`, `updated_user`, `updated_time`) VALUES
(1, 'administrator', 1, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Administrator', 'Bandung', 'admin@admin.com', '081200001122', '1', 1, '2015-01-16 00:00:00', 0, '0000-00-00 00:00:00'),
(3, 'manager', 2, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Manager', 'Bandung', 'manager@property.com', '08192881292', '1', 1, '2015-01-16 08:54:11', 0, '0000-00-00 00:00:00'),
(4, 'operator', 3, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Operator', 'Bandung', 'operator@property.com', '082726616672', '1', 1, '2015-01-16 08:54:38', 0, '0000-00-00 00:00:00'),
(5, 'member', 4, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Member', 'Bandung', 'member@property.com', '083736772937', '1', 1, '2015-01-16 08:55:00', 5, '2015-01-17 18:29:40'),
(6, 'admindua', 1, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Second Admin', 'Bandung', 'second@admin.com', '081111276612', '1', 1, '2015-01-16 09:42:51', 0, '0000-00-00 00:00:00'),
(7, 'lia', 4, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Lia Marida', 'Bandung', 'lia@gmail.com', '08817172819', '1', 1, '2015-01-17 10:05:48', 0, '0000-00-00 00:00:00'),
(8, 'iis', 4, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Iis Uus', 'Bandung', 'iis@gmail.com', '08171616521', '1', 1, '2015-01-18 09:29:45', 0, '0000-00-00 00:00:00'),
(9, 'hariono', 4, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Hariono', 'Bandung', 'hariono@gmail.com', '08715152712', '1', 0, '2015-01-19 09:51:17', 0, '0000-00-00 00:00:00'),
(10, 'kinkin', 4, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Kinkin Budiaman', 'Jalan Cihampelas Bandung', 'kinkinbudiaman@gmail.com', '089172818271', '1', 0, '2015-01-21 00:38:20', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE IF NOT EXISTS `user_type` (
`user_type_id` int(11) NOT NULL,
  `user_type_name` varchar(50) NOT NULL,
  `user_type_active` enum('0','1') NOT NULL DEFAULT '1',
  `created_user` int(11) NOT NULL,
  `created_time` datetime NOT NULL,
  `updated_user` int(11) NOT NULL,
  `updated_time` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`user_type_id`, `user_type_name`, `user_type_active`, `created_user`, `created_time`, `updated_user`, `updated_time`) VALUES
(1, 'Administrator', '1', 1, '2015-01-16 00:00:00', 1, '2015-01-16 00:00:00'),
(2, 'Manager', '1', 1, '2015-01-16 00:00:00', 1, '2015-01-16 00:00:00'),
(3, 'Operator', '1', 1, '2015-01-16 00:00:00', 1, '2015-01-16 00:00:00'),
(4, 'Member', '1', 1, '2015-01-16 00:00:00', 1, '2015-01-16 00:00:00'),
(5, 'test2', '0', 0, '2015-01-16 07:56:55', 0, '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `property`
--
ALTER TABLE `property`
 ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `property_category`
--
ALTER TABLE `property_category`
 ADD PRIMARY KEY (`property_category_id`);

--
-- Indexes for table `rent`
--
ALTER TABLE `rent`
 ADD PRIMARY KEY (`rent_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
 ADD PRIMARY KEY (`user_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `property_category`
--
ALTER TABLE `property_category`
MODIFY `property_category_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `rent`
--
ALTER TABLE `rent`
MODIFY `rent_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
