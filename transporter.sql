-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2020 at 05:43 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `transporter`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_username` varchar(100) NOT NULL,
  `admin_pass` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_username`, `admin_pass`) VALUES
(1, 'Jason Statham', 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `bidding`
--

CREATE TABLE `bidding` (
  `bid_id` int(11) NOT NULL,
  `bid_user_type` int(11) NOT NULL COMMENT '1 - Truck Owner ; 2 - Driver',
  `bid_user_id` int(11) NOT NULL,
  `load_id` int(11) NOT NULL,
  `bid_expected_price` decimal(15,2) NOT NULL,
  `bid_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 - Accepted by Admin; 2 - Accepted by Shipper',
  `bid_default` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `cu_id` int(11) NOT NULL,
  `cu_phone_code` tinyint(4) NOT NULL,
  `cu_phone` bigint(20) NOT NULL,
  `cu_otp` int(11) NOT NULL DEFAULT 0,
  `cu_address_type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 - Aadhar, 2 - Voter, 3 - Passport, 4 - DL',
  `cu_verified` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Default; 1 - Verified; 2 - Rejected',
  `cu_active` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Logged out; 1 - Logged in',
  `cu_registered` datetime NOT NULL,
  `cu_account_on` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Nothing; 1 - On Trial; 2 - On Subscription',
  `cu_trial_expire_date` datetime NOT NULL,
  `cu_subscription_start_date` datetime NOT NULL,
  `cu_subscription_order_id` varchar(255) NOT NULL DEFAULT '0',
  `cu_subscription_expire_date` datetime NOT NULL,
  `cu_token` varchar(255) NOT NULL,
  `cu_default` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`cu_id`, `cu_phone_code`, `cu_phone`, `cu_otp`, `cu_address_type`, `cu_verified`, `cu_active`, `cu_registered`, `cu_account_on`, `cu_trial_expire_date`, `cu_subscription_start_date`, `cu_subscription_order_id`, `cu_subscription_expire_date`, `cu_token`, `cu_default`) VALUES
(1, 91, 7908024082, 688264, 2, 1, 1, '2020-09-18 10:54:55', 2, '2020-09-25 11:09:05', '2020-09-18 16:02:47', 'order_FeSdIWh54gWuSm', '2020-12-18 16:02:47', 'doCWIRkvT5CdsqieHNW9Il:APA91bFXhkuNMNMv5ikG5an3UIR1FJydLhRHN3I9fZ3K5Z0y0Jelr_eYDuJsIOUUUeWNCCKQZCu1hPy5lyaGDcRfcGG19orr3qIN0wgCRis9unx6MCNhItqch1MWhPiaq-5H-FeZ7-gl', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_docs`
--

CREATE TABLE `customer_docs` (
  `doc_id` int(11) NOT NULL,
  `doc_owner_phone` bigint(20) NOT NULL,
  `doc_sr_num` tinyint(4) NOT NULL COMMENT '1 - Pan Card, 2 - Address Front, 3 - Address Back, 4 - Selfie, 5 - Company Name, 6 - Office Address',
  `doc_location` varchar(200) NOT NULL DEFAULT '',
  `doc_verified` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - No; 1 - Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_docs`
--

INSERT INTO `customer_docs` (`doc_id`, `doc_owner_phone`, `doc_sr_num`, `doc_location`, `doc_verified`) VALUES
(1, 7908024082, 1, 'assets/documents/shippers/shipper_7908024082/pan_card.jpg', 1),
(2, 7908024082, 2, 'assets/documents/shippers/shipper_7908024082/address_front.jpg', 1),
(3, 7908024082, 3, 'assets/documents/shippers/shipper_7908024082/address_back.jpg', 1),
(4, 7908024082, 4, 'assets/documents/shippers/shipper_7908024082/selfie.jpg', 1),
(5, 7908024082, 5, 'The Graphe', 1),
(6, 7908024082, 6, 'assets/documents/shippers/shipper_7908024082/office_address.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cust_order`
--

CREATE TABLE `cust_order` (
  `or_id` int(11) NOT NULL,
  `or_cust_id` int(11) NOT NULL,
  `or_uni_code` varchar(20) NOT NULL,
  `or_product` varchar(100) NOT NULL,
  `or_price_unit` tinyint(4) NOT NULL COMMENT '1 - Tonnage; 2 - Truck',
  `or_quantity` smallint(6) NOT NULL,
  `or_truck_preference` tinyint(4) NOT NULL,
  `or_expected_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `or_admin_expected_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `or_payment_mode` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 - Negotiable, 2 - Advance Pay, 3 - To Driver After Unloading',
  `or_advance_pay` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'in %',
  `or_shipper_on` tinyint(4) NOT NULL COMMENT '1 - Trial; 2 - Subscription',
  `or_active_on` datetime NOT NULL,
  `or_expire_on` datetime NOT NULL,
  `or_contact_person_name` varchar(100) NOT NULL,
  `or_contact_person_phone` bigint(20) NOT NULL,
  `or_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 - Active; 0 - Expired; 2 - Hold; 3 - Cancelled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cust_order_destination`
--

CREATE TABLE `cust_order_destination` (
  `des_id` int(11) NOT NULL,
  `or_uni_code` varchar(20) NOT NULL,
  `or_destination` varchar(200) NOT NULL,
  `or_des_lat` decimal(30,8) NOT NULL,
  `or_des_lng` decimal(30,8) NOT NULL,
  `or_des_city` varchar(100) NOT NULL,
  `or_des_state` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cust_order_source`
--

CREATE TABLE `cust_order_source` (
  `so_id` int(11) NOT NULL,
  `or_uni_code` varchar(20) NOT NULL,
  `or_source` varchar(250) NOT NULL,
  `or_source_lat` decimal(30,8) NOT NULL,
  `or_source_lng` decimal(30,8) NOT NULL,
  `or_source_city` varchar(100) NOT NULL,
  `or_source_state` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cust_order_truck_pref`
--

CREATE TABLE `cust_order_truck_pref` (
  `pref_id` int(11) NOT NULL,
  `or_uni_code` varchar(20) NOT NULL,
  `or_truck_pref_type` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `del_id` int(11) NOT NULL,
  `load_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `del_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `material_types`
--

CREATE TABLE `material_types` (
  `mat_id` int(11) NOT NULL,
  `mat_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `material_types`
--

INSERT INTO `material_types` (`mat_id`, `mat_name`) VALUES
(5, 'Building Materials'),
(6, 'Granites / Marbel'),
(7, 'Cement'),
(8, 'Chemicals'),
(9, 'Coal and Ash'),
(10, 'Container'),
(11, 'Engineering goods'),
(12, 'Electronics or Consumer Durable'),
(13, 'Fertilizer'),
(14, 'Fruits and Vegetables'),
(15, 'Furniture and Wood Products'),
(16, 'Household Goods'),
(17, 'Industrial Equipments'),
(18, 'Iron or Steel Material'),
(19, 'Liquids or Oil Drums'),
(20, 'Machineries'),
(21, 'Marble Slab / Marble Block'),
(22, 'Medicines'),
(23, 'Packed Food'),
(24, 'Plastic Pipes'),
(25, 'Books or Paper Rolls'),
(26, 'Plastic Granules / Plastic Industrial Goods'),
(27, 'Refrigerated Goods'),
(28, 'Crop or Agriculture Products'),
(29, 'Scrap'),
(30, 'Stones / Tiles'),
(31, 'Textiles'),
(32, 'Tyres and Rubber Product'),
(33, 'Vehicles / Automobiles'),
(34, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_users`
--

CREATE TABLE `subscribed_users` (
  `subs_id` int(11) NOT NULL,
  `subs_user_type` tinyint(4) NOT NULL COMMENT '1 - Shipper ; 2 - Truck Owner',
  `subs_user_id` int(11) NOT NULL,
  `subs_amount` decimal(15,2) NOT NULL,
  `subs_duration` tinyint(4) NOT NULL COMMENT 'in months',
  `razorpay_order_id` varchar(255) NOT NULL,
  `razorpay_payment_id` varchar(255) NOT NULL,
  `razorpay_signature` varchar(255) NOT NULL,
  `payment_datetime` datetime NOT NULL,
  `expire_datetime` datetime NOT NULL,
  `subs_default` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subscribed_users`
--

INSERT INTO `subscribed_users` (`subs_id`, `subs_user_type`, `subs_user_id`, `subs_amount`, `subs_duration`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `payment_datetime`, `expire_datetime`, `subs_default`) VALUES
(1, 1, 1, '4000.00', 3, 'order_FeSdIWh54gWuSm', 'pay_FeSdg2wvpJyPLt', '5ceda7415be81001f377b977426f8de2690314117dbfb4a2baa187c4554d6871', '2020-09-18 16:02:47', '2020-12-18 16:02:47', 1),
(2, 2, 4, '15000.00', 1, 'order_FetTRuX15Stx54', 'pay_FetTxJ3wZOpYT2', '5cfa4da84d46c460a18d4ecf20860bbf1ed580479ad978e2c546bdffbb46b5b7', '2020-09-19 18:18:23', '2020-10-19 18:18:23', 1),
(3, 2, 4, '15000.00', 1, 'order_FetXCQMuugIJYM', 'pay_FetXaByN1R04P1', '808f090fd747268d69371ecf6ce790304f13f41792667445376fcd0b09abf0db', '2020-09-19 18:21:42', '2020-10-19 18:21:42', 1),
(4, 1, 22, '4000.00', 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-09-19 18:22:50', '2020-12-19 18:22:50', 1),
(5, 2, 4, '2200.00', 3, 'order_FetaXrYpeivGiw', 'pay_Fetaj11mJcaW0m', '6756e5eee62dcdbd9d81f8b2571730ebb7664a1c66742ce49c328e490e729d9a', '2020-09-19 18:24:41', '2020-12-19 18:24:41', 1),
(6, 2, 4, '15000.00', 1, 'order_FetcXFvpeY0P0p', 'pay_Fetcj6VzuUDLRk', 'dcb0261693750280ebf71922b93f1955b4cdda65d25d14a0fcbe22a84db7867e', '2020-09-19 18:26:35', '2020-10-19 18:26:35', 1),
(7, 2, 4, '15000.00', 12, 'order_Feth3Vp0Vt8oph', 'pay_FethFHaE0k7GYc', 'c5894a17009477f2b0bc519611771f483b00fb7f3ecdc21615b21a9a124b4104', '2020-09-19 18:30:51', '2021-09-19 18:30:51', 1),
(8, 2, 4, '15000.00', 12, 'order_FetideHnp3g0xA', 'pay_Fetir7NbtnWjcr', 'bb9abc114501490c00e04e87b80917b45f154fc407a5503969f244eb06bc05ed', '2020-09-19 18:32:23', '2021-09-19 18:32:23', 1),
(9, 1, 22, '4000.00', 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-09-19 18:34:06', '2020-12-19 18:34:06', 1),
(10, 2, 4, '15000.00', 12, 'order_FetnAQKQAoUXa2', 'pay_FetnLNbSwuoujm', 'd59c08217e7928a742e2210b041c15db52dc2bb6d52d94281636b4e65e9b391c', '2020-09-19 18:36:37', '2021-09-19 18:36:37', 1),
(11, 1, 22, '4000.00', 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-09-19 18:37:00', '2020-12-19 18:37:00', 1),
(12, 1, 22, '4000.00', 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-09-19 18:37:02', '2020-12-19 18:37:02', 1),
(13, 2, 4, '2200.00', 3, 'order_FetqsX1IswfmMK', 'pay_Fetr4KbaJrI6dd', 'd6312abdbee86322d8c51b7038c83cd52bf16a48d7f6e4f69b6287965b54526a', '2020-09-19 18:40:11', '2020-12-19 18:40:11', 1),
(14, 2, 4, '6000.00', 6, 'order_Fets2dnqpAYZYH', 'pay_FetsD5fCuB5y6f', '7d00dadc34b1979a57c38aadcfe1022ea54a1a3880fc0e5ca1d3d1a363df0ae2', '2020-09-19 18:41:13', '2021-03-21 18:41:13', 1),
(15, 2, 4, '6000.00', 6, 'order_Fetu2Au6EIORY1', 'pay_FetuDsMFoAthZx', '5afe589c36cb1974cc01a6b8adf1a0c6ca312c1c839da8c931039c687362f2f6', '2020-09-19 18:43:08', '2021-03-21 18:43:08', 1),
(16, 2, 4, '2200.00', 3, 'order_Fetuef6zvM0dNs', 'pay_FetupouaekFMtx', '0fc045d11efebed63e326bc99e0a7d8bc68b0226798b4d24d808b6188fb17c55', '2020-09-19 18:43:42', '2020-12-19 18:43:42', 1),
(17, 2, 22, '4000.00', 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-09-19 18:44:11', '2020-12-19 18:44:11', 1),
(18, 2, 6, '4000.00', 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-09-19 19:02:14', '2020-12-19 19:02:14', 1),
(19, 2, 6, '4000.00', 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-09-19 19:03:47', '2020-12-19 19:03:47', 1),
(20, 2, 22, '4000.00', 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-09-19 19:09:15', '2020-12-19 19:09:15', 1),
(21, 2, 4, '15000.00', 12, 'order_FeuS6DWYyEoc26', 'pay_FeuSIcel04MoLB', '492881082fe25b9ac9a593115029650fdc8d0a2e191af27593d10dcbaec7913a', '2020-09-19 19:15:28', '2021-09-19 19:15:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `plan_id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `plan_type` tinyint(4) NOT NULL COMMENT '1 - Shipper Plans; 2 - Truck Owner Plans',
  `plan_original_price` decimal(15,2) NOT NULL,
  `plan_selling_price` decimal(15,2) NOT NULL,
  `plan_duration` tinyint(4) NOT NULL COMMENT 'in Months',
  `plan_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Inactive ; 1 - Active',
  `plan_reg` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`plan_id`, `plan_name`, `plan_type`, `plan_original_price`, `plan_selling_price`, `plan_duration`, `plan_status`, `plan_reg`) VALUES
(4, 'Basic', 1, '6000.00', '4000.00', 3, 1, 1),
(5, 'Basic', 2, '5000.00', '2200.00', 3, 1, 1),
(6, 'Medium', 1, '12000.00', '6000.00', 6, 1, 1),
(7, 'Hard', 1, '50000.00', '30000.00', 12, 1, 1),
(8, 'Medium', 2, '10000.00', '6000.00', 6, 1, 1),
(9, 'Hard', 2, '20000.00', '15000.00', 12, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `trucks`
--

CREATE TABLE `trucks` (
  `trk_id` int(11) NOT NULL,
  `trk_owner` int(11) NOT NULL,
  `trk_cat` tinyint(4) NOT NULL,
  `trk_cat_type` tinyint(4) NOT NULL,
  `trk_num` varchar(50) NOT NULL,
  `trk_dr_name` varchar(150) NOT NULL,
  `trk_dr_phone_code` mediumint(9) NOT NULL,
  `trk_dr_phone` bigint(20) NOT NULL,
  `trk_otp` int(11) NOT NULL,
  `trk_dr_pic` varchar(200) DEFAULT 'Nil',
  `trk_dr_license` varchar(200) DEFAULT 'Nil',
  `trk_rc` varchar(200) DEFAULT '',
  `trk_insurance` varchar(200) DEFAULT '',
  `trk_road_tax` varchar(200) DEFAULT '',
  `trk_rto` varchar(200) DEFAULT '',
  `trk_active` tinyint(4) NOT NULL DEFAULT 0,
  `trk_on_trip` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 : On Trip; 0 : Not on Trip',
  `trk_dr_token` varchar(250) DEFAULT 'Nil',
  `trk_on` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `truck_cat`
--

CREATE TABLE `truck_cat` (
  `trk_cat_id` int(11) NOT NULL,
  `trk_cat_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `truck_cat`
--

INSERT INTO `truck_cat` (`trk_cat_id`, `trk_cat_name`) VALUES
(1, 'Flat Bed'),
(2, 'Container'),
(3, 'Open Body'),
(4, 'Trailer Dala Body');

-- --------------------------------------------------------

--
-- Table structure for table `truck_cat_type`
--

CREATE TABLE `truck_cat_type` (
  `ty_id` int(11) NOT NULL,
  `ty_cat` int(11) NOT NULL,
  `ty_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `truck_cat_type`
--

INSERT INTO `truck_cat_type` (`ty_id`, `ty_cat`, `ty_name`) VALUES
(19, 2, 'SXL 20 feet 6 tons'),
(20, 2, 'SXL 32 feet 7.5 tons HQ'),
(21, 2, 'SXL 32 feet 7.5 tons'),
(22, 2, 'SXL 32 feet 9 tons'),
(23, 2, 'SXL 32 feet 9 tons HQ'),
(24, 2, 'MXL 32 feet 15 tons HQ'),
(25, 2, 'MXL 32 feet 15 tons'),
(26, 2, 'MXL 32 feet 18 tons'),
(27, 2, 'MXL 32 feet 18 tons HQ'),
(28, 1, '20 feet 16 tons'),
(29, 1, '20 feet 21 tons'),
(30, 1, '40 feet 24 tons'),
(31, 1, '40 feet 30 tons'),
(32, 1, '40 feet 32 tons'),
(33, 1, '40 feet 33 tons'),
(34, 1, '40 feet 34 tons'),
(35, 1, '40 feet 40 tons'),
(36, 1, '20 feet 16 tons'),
(37, 3, '15 tons 10 tyres'),
(38, 3, '16 tons 10 tyres'),
(39, 3, '18 tons 10 tyres'),
(40, 3, '19 tons 10 tyres'),
(41, 3, '20 tons 12 tyres'),
(42, 3, '21 tons 12 tyres'),
(43, 3, '24 tons 12 tyres'),
(44, 3, '25 tons 12 tyres'),
(45, 3, '7 tons 6 tyres'),
(46, 3, '8 tons 6 tyres'),
(47, 3, '9 tons 6 tyres'),
(48, 4, '27 tons'),
(49, 4, '28 tons'),
(50, 4, '29 tons'),
(51, 4, '30 tons'),
(52, 4, '31 tons'),
(53, 4, '32 tons'),
(54, 4, '33 tons'),
(55, 4, '34 tons'),
(56, 4, '35 tons'),
(57, 4, '36 tons'),
(58, 4, '37 tons'),
(59, 4, '38 tons'),
(60, 4, '39 tons'),
(61, 4, '40 tons'),
(62, 4, '41 tons'),
(63, 4, '42 tons');

-- --------------------------------------------------------

--
-- Table structure for table `truck_owners`
--

CREATE TABLE `truck_owners` (
  `to_id` int(11) NOT NULL,
  `to_phone_code` smallint(6) NOT NULL,
  `to_phone` bigint(20) NOT NULL,
  `to_otp` mediumint(9) NOT NULL,
  `to_name` varchar(150) NOT NULL DEFAULT '',
  `to_bank` bigint(20) NOT NULL DEFAULT 0,
  `to_ifsc` varchar(20) NOT NULL DEFAULT '0',
  `to_account_on` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Nothing; 1 - Subscription',
  `to_subscription_start_date` datetime NOT NULL,
  `to_subscription_order_id` varchar(255) NOT NULL,
  `to_subscription_expire_date` datetime NOT NULL,
  `to_registered` datetime NOT NULL,
  `to_token` varchar(255) NOT NULL,
  `to_active` tinyint(4) NOT NULL DEFAULT 0,
  `to_on` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `truck_owner_docs`
--

CREATE TABLE `truck_owner_docs` (
  `to_doc_id` int(11) NOT NULL,
  `to_doc_owner_phone` bigint(20) NOT NULL,
  `to_doc_sr_num` tinyint(4) NOT NULL,
  `to_doc_location` varchar(200) NOT NULL,
  `to_doc_verified` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `bidding`
--
ALTER TABLE `bidding`
  ADD PRIMARY KEY (`bid_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`cu_id`);

--
-- Indexes for table `customer_docs`
--
ALTER TABLE `customer_docs`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `cust_order`
--
ALTER TABLE `cust_order`
  ADD PRIMARY KEY (`or_id`);

--
-- Indexes for table `cust_order_destination`
--
ALTER TABLE `cust_order_destination`
  ADD PRIMARY KEY (`des_id`);

--
-- Indexes for table `cust_order_source`
--
ALTER TABLE `cust_order_source`
  ADD PRIMARY KEY (`so_id`);

--
-- Indexes for table `cust_order_truck_pref`
--
ALTER TABLE `cust_order_truck_pref`
  ADD PRIMARY KEY (`pref_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`del_id`);

--
-- Indexes for table `material_types`
--
ALTER TABLE `material_types`
  ADD PRIMARY KEY (`mat_id`);

--
-- Indexes for table `subscribed_users`
--
ALTER TABLE `subscribed_users`
  ADD PRIMARY KEY (`subs_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `trucks`
--
ALTER TABLE `trucks`
  ADD PRIMARY KEY (`trk_id`);

--
-- Indexes for table `truck_cat`
--
ALTER TABLE `truck_cat`
  ADD PRIMARY KEY (`trk_cat_id`);

--
-- Indexes for table `truck_cat_type`
--
ALTER TABLE `truck_cat_type`
  ADD PRIMARY KEY (`ty_id`);

--
-- Indexes for table `truck_owners`
--
ALTER TABLE `truck_owners`
  ADD PRIMARY KEY (`to_id`);

--
-- Indexes for table `truck_owner_docs`
--
ALTER TABLE `truck_owner_docs`
  ADD PRIMARY KEY (`to_doc_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bidding`
--
ALTER TABLE `bidding`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_docs`
--
ALTER TABLE `customer_docs`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cust_order`
--
ALTER TABLE `cust_order`
  MODIFY `or_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cust_order_destination`
--
ALTER TABLE `cust_order_destination`
  MODIFY `des_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cust_order_source`
--
ALTER TABLE `cust_order_source`
  MODIFY `so_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cust_order_truck_pref`
--
ALTER TABLE `cust_order_truck_pref`
  MODIFY `pref_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `del_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_types`
--
ALTER TABLE `material_types`
  MODIFY `mat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `subscribed_users`
--
ALTER TABLE `subscribed_users`
  MODIFY `subs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `trucks`
--
ALTER TABLE `trucks`
  MODIFY `trk_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `truck_cat`
--
ALTER TABLE `truck_cat`
  MODIFY `trk_cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `truck_cat_type`
--
ALTER TABLE `truck_cat_type`
  MODIFY `ty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `truck_owners`
--
ALTER TABLE `truck_owners`
  MODIFY `to_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `truck_owner_docs`
--
ALTER TABLE `truck_owner_docs`
  MODIFY `to_doc_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
