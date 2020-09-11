-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 11, 2020 at 06:55 AM
-- Server version: 5.6.41-84.1
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thegrhmw_transpoter`
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
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `cu_id` int(11) NOT NULL,
  `cu_phone_code` tinyint(4) NOT NULL,
  `cu_phone` bigint(20) NOT NULL,
  `cu_otp` int(11) NOT NULL DEFAULT '0',
  `cu_address_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 - Aadhar, 2 - Voter, 3 - Passport, 4 - DL',
  `cu_verified` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Default; 1 - Verified; 2 - Rejected',
  `cu_active` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Logged out; 1 - Logged in',
  `cu_registered` datetime NOT NULL,
  `cu_account_on` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Nothing; 1 - On Trial; 2 - On Subscription',
  `cu_trial_expire_date` datetime NOT NULL,
  `cu_subscription_start_date` datetime NOT NULL,
  `cu_subscription_order_id` varchar(255) NOT NULL DEFAULT '0',
  `cu_subscription_expire_date` datetime NOT NULL,
  `cu_token` varchar(255) NOT NULL,
  `cu_default` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`cu_id`, `cu_phone_code`, `cu_phone`, `cu_otp`, `cu_address_type`, `cu_verified`, `cu_active`, `cu_registered`, `cu_account_on`, `cu_trial_expire_date`, `cu_subscription_start_date`, `cu_subscription_order_id`, `cu_subscription_expire_date`, `cu_token`, `cu_default`) VALUES
(1, 91, 7908024082, 710343, 4, 1, 1, '2020-09-09 17:38:13', 2, '2020-09-16 17:44:04', '2020-09-10 02:57:33', 'order_Fb4zD2ejeURujj', '2021-03-12 02:57:33', 'cxqsIcJURRK79c6xrrv-Nb:APA91bGtJrr1Qia_wodrfeZi9ffh186ANO2he3vLZyk_MCdrapjwAICRPmIx4RhQjK1tvaORUOMcC7WI6KuKhJ3JJNoSdUJ6HVEY_mRq_A7x5VSnuvsUTFG6XOxeMR_hwycqoYVSek5I', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer_docs`
--

CREATE TABLE `customer_docs` (
  `doc_id` int(11) NOT NULL,
  `doc_owner_phone` bigint(20) NOT NULL,
  `doc_sr_num` tinyint(4) NOT NULL COMMENT '1 - Pan Card, 2 - Address Front, 3 - Address Back, 4 - Selfie, 5 - Company Name, 6 - Office Address',
  `doc_location` varchar(200) NOT NULL DEFAULT '',
  `doc_verified` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - No; 1 - Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customer_docs`
--

INSERT INTO `customer_docs` (`doc_id`, `doc_owner_phone`, `doc_sr_num`, `doc_location`, `doc_verified`) VALUES
(64, 7908024082, 1, 'assets/documents/shippers/shipper_7908024082/pan_card.jpg', 1),
(65, 7908024082, 2, 'assets/documents/shippers/shipper_7908024082/address_front.jpg', 1),
(66, 7908024082, 3, 'assets/documents/shippers/shipper_7908024082/address_back.jpg', 1),
(67, 7908024082, 4, 'assets/documents/shippers/shipper_7908024082/selfie.jpg', 1),
(68, 7908024082, 5, 'The Graphe', 1),
(69, 7908024082, 6, 'assets/documents/shippers/shipper_7908024082/office_address.jpg', 1);

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
  `or_expected_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `or_payment_mode` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Negotiable, 2 - Advance Pay, 3 - To Driver After Unloading',
  `or_advance_pay` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'in %',
  `or_active_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `or_expire_on` datetime NOT NULL,
  `or_contact_person_name` varchar(100) NOT NULL,
  `or_contact_person_phone` bigint(20) NOT NULL,
  `or_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Active; 0 - Expired'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cust_order_destination`
--

CREATE TABLE `cust_order_destination` (
  `des_id` int(11) NOT NULL,
  `or_uni_code` varchar(20) NOT NULL,
  `or_destination` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cust_order_source`
--

CREATE TABLE `cust_order_source` (
  `so_id` int(11) NOT NULL,
  `or_uni_code` varchar(20) NOT NULL,
  `or_source` varchar(250) NOT NULL
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
  `subs_default` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subscribed_users`
--

INSERT INTO `subscribed_users` (`subs_id`, `subs_user_type`, `subs_user_id`, `subs_amount`, `subs_duration`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `payment_datetime`, `expire_datetime`, `subs_default`) VALUES
(1, 1, 22, 4000.00, 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-09-10 02:42:06', '2020-12-10 02:42:06', 1),
(2, 1, 1, 6000.00, 6, 'order_Fb4zD2ejeURujj', 'pay_Fb4zI6kpIQAw8N', '3a32987ec17334ad48aa82852516255d5090e36eb6ae8ef008e79d168d71cd4e', '2020-09-10 02:57:33', '2021-03-12 02:57:33', 1);

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
  `plan_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Inactive ; 1 - Active',
  `plan_reg` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`plan_id`, `plan_name`, `plan_type`, `plan_original_price`, `plan_selling_price`, `plan_duration`, `plan_status`, `plan_reg`) VALUES
(4, 'Basic', 1, 6000.00, 4000.00, 3, 1, 1),
(5, 'Basic', 2, 50000.00, 22000.00, 12, 1, 1),
(6, 'Medium', 1, 12000.00, 6000.00, 6, 1, 1),
(7, 'Hard', 1, 50000.00, 30000.00, 12, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `temp_register`
--

CREATE TABLE `temp_register` (
  `t_id` int(11) NOT NULL,
  `t_to_name` varchar(100) NOT NULL,
  `t_to_email` varchar(100) NOT NULL,
  `t_to_phone_code` smallint(6) NOT NULL,
  `t_to_phone` bigint(20) NOT NULL,
  `t_to_password` varchar(200) NOT NULL,
  `t_to_city` varchar(100) NOT NULL,
  `t_to_address` text NOT NULL,
  `t_to_routes` varchar(200) NOT NULL,
  `t_to_permits` varchar(200) NOT NULL,
  `t_to_pan` varchar(20) NOT NULL,
  `t_to_bank` bigint(20) NOT NULL,
  `t_to_ifsc` varchar(20) NOT NULL,
  `t_otp` mediumint(9) NOT NULL,
  `t_reg_user` tinyint(4) NOT NULL DEFAULT '0',
  `t_verified` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `trucks`
--

CREATE TABLE `trucks` (
  `trk_id` int(11) NOT NULL,
  `trk_owner` int(11) NOT NULL,
  `trk_cat` tinyint(4) NOT NULL,
  `trk_num` varchar(50) NOT NULL,
  `trk_load` mediumint(9) NOT NULL,
  `trk_dr_name` varchar(150) NOT NULL,
  `trk_dr_phone_code` smallint(6) NOT NULL,
  `trk_dr_phone` bigint(20) NOT NULL,
  `trk_dr_license` varchar(200) NOT NULL,
  `trk_rc` varchar(200) NOT NULL,
  `trk_insurance` varchar(200) NOT NULL,
  `trk_road_tax` varchar(200) NOT NULL,
  `trk_rto` varchar(200) NOT NULL,
  `trk_active` tinyint(4) NOT NULL,
  `trk_on_trip` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 : On Trip; 0 : Not on Trip',
  `trk_on` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trucks`
--

INSERT INTO `trucks` (`trk_id`, `trk_owner`, `trk_cat`, `trk_num`, `trk_load`, `trk_dr_name`, `trk_dr_phone_code`, `trk_dr_phone`, `trk_dr_license`, `trk_rc`, `trk_insurance`, `trk_road_tax`, `trk_rto`, `trk_active`, `trk_on_trip`, `trk_on`) VALUES
(35, 4, 3, 'OP044', 1000, 'Rohit Singh', 91, 9647513679, 'assets/documents/truck_owners/truck_owners_id_4/OP044/rc.png', 'assets/documents/truck_owners/truck_owners_id_4/OP044/license.png', 'assets/documents/truck_owners/truck_owners_id_4/OP044/insurance.jpg', 'assets/documents/truck_owners/truck_owners_id_4/OP044/road_tax.jpg', 'assets/documents/truck_owners/truck_owners_id_4/OP044/rto.jpg', 1, 0, 1),
(36, 4, 3, 'OP0441', 1000, 'Rohit Singh', 91, 98757547, 'assets/documents/truck_owners/truck_owners_id_4/OP0441/rc.png', 'assets/documents/truck_owners/truck_owners_id_4/OP0441/license.png', 'assets/documents/truck_owners/truck_owners_id_4/OP0441/insurance.jpg', 'assets/documents/truck_owners/truck_owners_id_4/OP0441/road_tax.jpg', 'assets/documents/truck_owners/truck_owners_id_4/OP0441/rto.jpg', 0, 0, 1),
(37, 5, 3, 'OP', 1000, 'Rohit Singh', 91, 9878769, 'assets/documents/truck_owners/truck_owners_id_5/OP/rc.png', 'assets/documents/truck_owners/truck_owners_id_5/OP/license.png', 'assets/documents/truck_owners/truck_owners_id_5/OP/insurance.jpg', 'assets/documents/truck_owners/truck_owners_id_5/OP/road_tax.jpg', 'assets/documents/truck_owners/truck_owners_id_5/OP/rto.jpg', 0, 0, 1);

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
  `to_name` varchar(150) NOT NULL,
  `to_email` varchar(150) NOT NULL,
  `to_phone_code` smallint(6) NOT NULL,
  `to_phone` bigint(20) NOT NULL,
  `to_password` varchar(200) NOT NULL,
  `to_city` varchar(100) NOT NULL,
  `to_address` text NOT NULL,
  `to_routes` varchar(200) NOT NULL,
  `to_permits` varchar(200) NOT NULL,
  `to_pan` varchar(20) NOT NULL,
  `to_bank` bigint(20) NOT NULL,
  `to_ifsc` varchar(20) NOT NULL,
  `to_verified` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 - Accepted; 2 - Rejected',
  `to_registered` date NOT NULL,
  `to_on` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `truck_owners`
--

INSERT INTO `truck_owners` (`to_id`, `to_name`, `to_email`, `to_phone_code`, `to_phone`, `to_password`, `to_city`, `to_address`, `to_routes`, `to_permits`, `to_pan`, `to_bank`, `to_ifsc`, `to_verified`, `to_registered`, `to_on`) VALUES
(4, 'dfdfd', 'sjhd@g.com', 91, 7908024082, 'e10adc3949ba59abbe56e057f20f883e', 'Kolkata', 'Kolkata', 'Delhi, Mumbai', 'All India', '547dfr', 12345678945, 'sbin0007503', 2, '2020-08-01', 1),
(5, 'wedecw', 'bsbs@g.com', 91, 9647513679, 'e10adc3949ba59abbe56e057f20f883e', 'Durgapur', '11/1, Edison Road (B-Zone), Durgapur, Paschim Burdwan, West benagl, 713205', 'Delhi, Mumbai', 'All India', '547dfr', 12345678945, 'sbin0007503', 1, '2020-08-02', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

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
-- Indexes for table `temp_register`
--
ALTER TABLE `temp_register`
  ADD PRIMARY KEY (`t_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer_docs`
--
ALTER TABLE `customer_docs`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

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
-- AUTO_INCREMENT for table `material_types`
--
ALTER TABLE `material_types`
  MODIFY `mat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `subscribed_users`
--
ALTER TABLE `subscribed_users`
  MODIFY `subs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `temp_register`
--
ALTER TABLE `temp_register`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trucks`
--
ALTER TABLE `trucks`
  MODIFY `trk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
  MODIFY `to_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
