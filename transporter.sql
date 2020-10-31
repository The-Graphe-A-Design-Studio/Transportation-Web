-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 31, 2020 at 05:04 PM
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
  `admin_pass` varchar(100) NOT NULL,
  `admin_toggle` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_username`, `admin_pass`, `admin_toggle`) VALUES
(1, 'Jay Barai', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);

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
  `bid_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 - Accepted by Admin; 2 - Accepted by Shipper; 3 - Accepted by Owner',
  `bid_default` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `cu_account_on` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Nothing; 1 - On Trial; 2 - On Subscription; 3 - Free Period',
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
(1, 91, 9647513679, 754001, 1, 1, 1, '2020-10-31 02:46:20', 1, '2020-11-15 02:50:48', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00', 'ebLC1WcdTselkEa7fhuvUk:APA91bFCyWGX1g-2dYVJHEkWqPSbYaUvDZD1wmd_pEWOXKWL0XRhnfuBO57kLTbguJwXncQB6n4xCpdCzsdKnPg3WYpDMKvGXUqXPbEpp9zusdJ7jX_GMm6NQf8lNfd1RrooPqBsPHkA', 1);

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
(1, 9647513679, 1, 'assets/documents/shippers/shipper_9647513679/pan_card.jpg', 1),
(2, 9647513679, 2, 'assets/documents/shippers/shipper_9647513679/address_front.jpg', 1),
(3, 9647513679, 3, 'assets/documents/shippers/shipper_9647513679/address_back.jpg', 1),
(4, 9647513679, 4, 'assets/documents/shippers/shipper_9647513679/selfie.jpg', 1),
(5, 9647513679, 5, 'Graphe', 1),
(6, 9647513679, 6, 'assets/documents/shippers/shipper_9647513679/office_address.jpg', 1);

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
  `or_admin_expected_price` tinyint(4) NOT NULL DEFAULT '0',
  `or_payment_mode` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Negotiable, 2 - Advance Pay, 3 - To Driver After Unloading',
  `or_advance_pay` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'in %',
  `or_shipper_on` tinyint(4) NOT NULL COMMENT '1 - Trial; 2 - Subscription; 3 - Free Plan',
  `or_active_on` datetime NOT NULL,
  `or_expire_on` datetime NOT NULL,
  `or_contact_person_name` varchar(100) NOT NULL,
  `or_contact_person_phone` bigint(20) NOT NULL,
  `or_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Active; 0 - Expired; 2 - Hold; 3 - Cancelled; 4 - On Going; 5 -Completed',
  `or_default` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cust_order`
--

INSERT INTO `cust_order` (`or_id`, `or_cust_id`, `or_uni_code`, `or_product`, `or_price_unit`, `or_quantity`, `or_truck_preference`, `or_expected_price`, `or_admin_expected_price`, `or_payment_mode`, `or_advance_pay`, `or_shipper_on`, `or_active_on`, `or_expire_on`, `or_contact_person_name`, `or_contact_person_phone`, `or_status`, `or_default`) VALUES
(1, 1, 'Wvye6o3', 'Engineering goods', 1, 20, 1, 50000.00, 0, 2, 35, 1, '2020-10-31 02:56:46', '2020-11-02 02:52:00', 'Graphe', 9647513679, 5, 1),
(2, 1, 'ZAWUIXm', 'Engineering goods', 2, 1, 2, 30000.00, 0, 2, 30, 1, '2020-10-31 20:45:36', '2020-11-02 20:44:00', 'Graphe', 9647513679, 1, 1);

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

--
-- Dumping data for table `cust_order_destination`
--

INSERT INTO `cust_order_destination` (`des_id`, `or_uni_code`, `or_destination`, `or_des_lat`, `or_des_lng`, `or_des_city`, `or_des_state`) VALUES
(1, 'Wvye6o3', 'Delhi Road, Bamunari, Mamunali, Mollaber Village, Bamunari, West Bengal, India', 22.71369890, 88.30336060, 'Not found', 'West Bengal'),
(2, 'Wvye6o3', 'Rajasthan State Highway 7, Gudabari, Rajasthan, India', 27.72078250, 74.72105800, 'Salasar', 'Rajasthan'),
(3, 'ZAWUIXm', 'Durgapur Bridge, Kala Bagan, Chetla, Kolkata, West Bengal, India', 22.51347730, 88.33290160, 'Kolkata', 'West Bengal');

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

--
-- Dumping data for table `cust_order_source`
--

INSERT INTO `cust_order_source` (`so_id`, `or_uni_code`, `or_source`, `or_source_lat`, `or_source_lng`, `or_source_city`, `or_source_state`) VALUES
(1, 'Wvye6o3', 'Kolkata Airport Road, Majumder Pada, Motilal Colony, Dum Dum, North Dumdum, West Bengal, India', 22.65011760, 88.43743560, 'Kolkata', 'West Bengal'),
(2, 'Wvye6o3', 'Patna Airport - Ashiana More Road, Khajpura, Patna, Bihar, India', 25.59935820, 85.08432460, 'Patna', 'Bihar'),
(3, 'ZAWUIXm', 'Kolkata Airport Road, Majumder Pada, Motilal Colony, Dum Dum, North Dumdum, West Bengal, India', 22.65011760, 88.43743560, 'Kolkata', 'West Bengal');

-- --------------------------------------------------------

--
-- Table structure for table `cust_order_truck_pref`
--

CREATE TABLE `cust_order_truck_pref` (
  `pref_id` int(11) NOT NULL,
  `or_uni_code` varchar(20) NOT NULL,
  `or_truck_pref_type` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cust_order_truck_pref`
--

INSERT INTO `cust_order_truck_pref` (`pref_id`, `or_uni_code`, `or_truck_pref_type`) VALUES
(1, 'Wvye6o3', 35),
(2, 'ZAWUIXm', 22),
(3, 'ZAWUIXm', 25),
(4, 'ZAWUIXm', 26);

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `del_id` int(11) NOT NULL,
  `or_uni_code` varchar(15) NOT NULL COMMENT 'load unique code',
  `or_id` int(11) NOT NULL COMMENT 'load id',
  `cu_id` int(11) NOT NULL COMMENT 'shipper id',
  `to_id` int(11) NOT NULL COMMENT 'truck owner id',
  `price_unit` tinyint(4) NOT NULL COMMENT '1 - Tonnage; 2 - Number of Trucks',
  `quantity` tinyint(4) NOT NULL,
  `deal_price` decimal(30,2) NOT NULL,
  `del_status` tinyint(4) NOT NULL COMMENT '0 - Set, 1 - Started, 2 - End'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`del_id`, `or_uni_code`, `or_id`, `cu_id`, `to_id`, `price_unit`, `quantity`, `deal_price`, `del_status`) VALUES
(1, 'Wvye6o3', 1, 1, 2, 1, 20, 500.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_trucks`
--

CREATE TABLE `delivery_trucks` (
  `del_trk_id` int(11) NOT NULL,
  `del_id` int(11) NOT NULL,
  `trk_id` int(11) NOT NULL,
  `lat` decimal(30,8) NOT NULL DEFAULT '22.25000000',
  `lng` decimal(30,8) NOT NULL DEFAULT '71.19000000',
  `otp` mediumint(9) NOT NULL DEFAULT '0',
  `otp_verified` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - No; 1 - Yes',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 - Started; 2 - Reached',
  `trip_start` datetime NOT NULL,
  `trip_end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `delivery_trucks`
--

INSERT INTO `delivery_trucks` (`del_trk_id`, `del_id`, `trk_id`, `lat`, `lng`, `otp`, `otp_verified`, `status`, `trip_start`, `trip_end`) VALUES
(1, 1, 1, 22.62739390, 88.40363050, 406040, 1, 2, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `load_payments`
--

CREATE TABLE `load_payments` (
  `pay_id` int(11) NOT NULL,
  `delivery_id` int(11) NOT NULL,
  `load_id` int(11) NOT NULL,
  `cu_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `razorpay_order_id` varchar(50) NOT NULL,
  `razorpay_payment_id` varchar(50) NOT NULL,
  `razorpay_signature` varchar(200) NOT NULL,
  `payment_date` datetime NOT NULL,
  `pay_mode` tinyint(4) NOT NULL COMMENT '1 - Advance, 2 - Remaining, 3 - Full',
  `pay_method` tinyint(4) NOT NULL COMMENT '1 - Online; 2 - Cash',
  `pay_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Pending; 1 - Paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `load_payments`
--

INSERT INTO `load_payments` (`pay_id`, `delivery_id`, `load_id`, `cu_id`, `to_id`, `amount`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `payment_date`, `pay_mode`, `pay_method`, `pay_status`) VALUES
(1, 1, 1, 1, 2, 3500.00, 'order_FvGLRNp4mcwbaQ', 'pay_FvGLYHVfpgXOVz', '6d0d674f6d88589511391b1be20f9b119947eebfdeb2edc320475cdeaf0a918d', '2020-10-31 03:04:17', 1, 1, 1),
(2, 1, 1, 1, 2, 6500.00, 'order_Hfl1MUhcKGKyMF9', 'pay_7LDynWXkn2luww4', '0', '2020-10-31 03:11:17', 2, 2, 1);

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
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `no_id` int(11) NOT NULL,
  `no_date_time` datetime NOT NULL,
  `no_title` varchar(100) NOT NULL,
  `no_message` varchar(255) NOT NULL,
  `id` varchar(10) NOT NULL,
  `no_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Not seen; 1 - Seen',
  `no_default` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`no_id`, `no_date_time`, `no_title`, `no_message`, `id`, `no_status`, `no_default`) VALUES
(1, '2020-10-30 19:50:21', 'New Owner Registered', 'New truck owner registered with phone number 8488888822', '8488888822', 0, 1),
(2, '2020-10-30 19:50:55', 'New Owner Registered', 'New truck owner registered with phone number 7908024082', '7908024082', 0, 1),
(3, '2020-10-30 19:51:21', 'Owner docs', 'Truck owner ID 2 uploaded PAN card', '2', 0, 1),
(4, '2020-10-30 19:51:22', 'Owner details', 'Truck owner ID 2 updated his/her bank details', '2', 0, 1),
(5, '2020-10-30 19:51:39', 'Owner docs', 'Truck owner ID 1 uploaded PAN card', '1', 0, 1),
(6, '2020-10-30 19:51:40', 'Owner details', 'Truck owner ID 1 updated his/her bank details', '1', 0, 1),
(7, '2020-10-30 19:52:35', 'Owner docs', 'Truck owner ID 1 uploaded PAN card', '1', 0, 1),
(8, '2020-10-30 19:52:36', 'Owner details', 'Truck owner ID 1 updated his/her bank details', '1', 0, 1),
(9, '2020-10-30 19:54:47', 'Owner details', 'Truck owner ID 1 updated his/her bank details', '1', 0, 1),
(10, '2020-10-30 19:56:25', 'Driver Docs', 'Truck Driver ID 1 uploaded Selfie', '1', 0, 1),
(11, '2020-10-30 19:56:27', 'Driver Docs', 'Truck Driver ID 1 uploaded Driving license', '1', 0, 1),
(12, '2020-10-30 19:57:16', 'Truck status', 'Truck ID 1 status updated', '1', 1, 1),
(13, '2020-10-30 20:02:32', 'Owner details', 'Truck owner ID 1 updated his/her bank details', '1', 0, 1),
(14, '2020-10-30 20:10:14', 'Driver Docs', 'Truck Driver ID 2 uploaded Selfie', '2', 0, 1),
(15, '2020-10-30 20:10:16', 'Driver Docs', 'Truck Driver ID 2 uploaded Driving license', '2', 0, 1),
(16, '2020-10-30 20:11:35', 'Truck status', 'Truck ID 2 status updated', '2', 0, 1),
(17, '2020-10-30 20:11:36', 'Truck status', 'Truck ID 2 status updated', '2', 0, 1),
(18, '2020-10-30 22:28:08', 'Truck status', 'Truck ID 2 status updated', '2', 0, 1),
(19, '2020-10-30 22:28:13', 'Truck status', 'Truck ID 2 status updated', '2', 0, 1),
(20, '2020-10-30 22:28:37', 'Truck status', 'Truck ID 2 status updated', '2', 0, 1),
(21, '2020-10-30 22:28:39', 'Truck status', 'Truck ID 2 status updated', '2', 0, 1),
(22, '2020-10-31 02:46:20', 'New Shipper Registered', 'New shipper registered with phone number 9647513679', '9647513679', 0, 1),
(23, '2020-10-31 02:47:03', 'Shipper Docs', 'Shipper ID 1 uploaded PAN Card', '1', 0, 1),
(24, '2020-10-31 02:47:37', 'Shipper Docs', 'Shipper ID 1 uploaded Address Docs', '1', 0, 1),
(25, '2020-10-31 02:49:18', 'Shipper Docs', 'Shipper ID 1 uploaded Selfie', '1', 0, 1),
(26, '2020-10-31 02:49:31', 'Shipper Docs', 'Shipper ID 1 uploaded Office address docs', '1', 1, 1),
(27, '2020-10-31 02:56:49', 'New Load', 'New load post created by Shipper ID 1', 'Wvye6o3', 1, 1),
(28, '2020-10-31 03:02:22', 'Bidding', 'New Bidding for Load ID 1', '1', 0, 1),
(29, '2020-10-31 03:02:49', 'Bidding', 'Shipper accepted bid of Load ID 1', '1', 0, 1),
(30, '2020-10-31 03:03:12', 'Deal accepted', 'Deal accepted by Truck owner ID 2 of Load ID 1', '1', 0, 1),
(31, '2020-10-31 03:03:31', 'Trucks assigned', 'Truck assinged by Truck owner ID 2 for Load ID 1', '1', 0, 1),
(32, '2020-10-31 03:04:17', 'Load Payment', 'Advance payment received for Load ID 1', '1', 0, 1),
(33, '2020-10-31 03:09:16', 'Trip started', 'Truck ID 1 trip started of Load ID 1', '1', 1, 1),
(34, '2020-10-31 03:11:17', 'Load Payment', 'Remaining payment received for Load ID 1', '1', 0, 1),
(35, '2020-10-31 03:12:05', 'Trip completed', 'Truck ID  trip completed of Load ID 1', '1', 0, 1),
(36, '2020-10-31 03:14:43', 'Truck docs', 'Truck ID 1 updated RC details', '1', 1, 1),
(37, '2020-10-31 03:16:04', 'Truck Subscription', 'Truck add on by Truck Owner ID 2', 'order_FvGX', 1, 1),
(38, '2020-10-31 20:45:38', 'New Load', 'New load post created by Shipper ID 1', 'ZAWUIXm', 1, 1),
(39, '2020-10-31 22:33:44', 'Truck removed', 'Truck removed by Truck Onwer ID 1', '1', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_users`
--

CREATE TABLE `subscribed_users` (
  `subs_id` int(11) NOT NULL,
  `subs_user_type` tinyint(4) NOT NULL COMMENT '1 - Shipper ; 2 - Truck Owner; 3 - Add on Truck',
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
(1, 3, 2, 900.00, 2, 'order_FvGXn8YvMUIVYE', 'pay_FvGY0GxVpv5Xu6', 'b5e473861d1ec4467467abab98c506fc97f212b4c58cc35eab0643998b40c8b3', '2020-10-31 03:16:04', '2020-12-31 03:16:04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `plan_id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `plan_type` tinyint(4) NOT NULL COMMENT '1 - Shipper Plans; 2 - Truck Owner Plans; 3 - Add on Truck',
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
(1, 'Sabse Sasta', 1, 4500.00, 2500.00, 3, 1, 1),
(2, 'Sabse Mehenga', 1, 6000.00, 4500.00, 6, 0, 1),
(3, 'Sabse Sasta', 2, 5000.00, 3500.00, 3, 1, 1),
(4, 'Sabse Mehenga', 2, 6000.00, 5000.00, 6, 0, 1),
(6, 'Add on', 3, 2000.00, 900.00, 2, 1, 1),
(7, 'Add on Truck', 3, 1000.00, 500.00, 1, 1, 1);

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
  `trk_verified` tinyint(4) NOT NULL DEFAULT '0',
  `trk_lat` decimal(30,8) NOT NULL,
  `trk_lng` decimal(30,8) NOT NULL,
  `trk_active` tinyint(4) NOT NULL DEFAULT '0',
  `trk_on_trip` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 : Not on Trip; 1 : Set for Trip; 2 - On Trip',
  `trk_dr_token` varchar(255) NOT NULL,
  `trk_on` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trucks`
--

INSERT INTO `trucks` (`trk_id`, `trk_owner`, `trk_cat`, `trk_cat_type`, `trk_num`, `trk_dr_name`, `trk_dr_phone_code`, `trk_dr_phone`, `trk_otp`, `trk_verified`, `trk_lat`, `trk_lng`, `trk_active`, `trk_on_trip`, `trk_dr_token`, `trk_on`) VALUES
(1, 2, 1, 31, 'Rtysyys', 'ramu', 91, 9647513679, 739978, 1, 22.62739390, 88.40363050, 1, 0, 'eLdIH8YLRmushKT2JKjeU2:APA91bElFSCSHKnhIgfPAvsJvfmDZHL8T0xTBsln12VDtBqn4JXnHXuOMY03Ib_rN3IMDIXWfe8yalov4N8NUV55O5EeeehdxlAsbGFpNV3bHbm4dwDPE5Ohbj2sgLHpgC8ModVAs8dz', 1),
(3, 1, 3, 64, 'GJ10TV5657', 'Jay', 91, 8488888822, 0, 0, 0.00000000, 0.00000000, 0, 0, '', 1);

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
(63, 4, '42 tons'),
(64, 3, '31 tons 14 tyres'),
(65, 3, '36 tons 16 tyres');

-- --------------------------------------------------------

--
-- Table structure for table `truck_docs`
--

CREATE TABLE `truck_docs` (
  `trk_doc_id` int(11) NOT NULL,
  `trk_doc_truck_num` varchar(20) NOT NULL,
  `trk_doc_sr_num` tinyint(4) NOT NULL COMMENT '1-DP; 2-DL; 3-RC; 4-Insurance; 5-RoadT; 6-RTO Pass',
  `trk_doc_location` varchar(200) NOT NULL,
  `trk_doc_verified` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `truck_docs`
--

INSERT INTO `truck_docs` (`trk_doc_id`, `trk_doc_truck_num`, `trk_doc_sr_num`, `trk_doc_location`, `trk_doc_verified`) VALUES
(1, 'Rtysyys', 1, 'assets/documents/truck_owners/truck_owner_id_2/Rtysyys/driver_selfie.jpg', 1),
(2, 'Rtysyys', 2, 'assets/documents/truck_owners/truck_owner_id_2/Rtysyys/license.jpg', 1),
(3, 'Rtysyys', 3, 'assets/documents/truck_owners/truck_owner_id_2/Rtysyys/rc.jpg', 1),
(4, 'Rtysyys', 4, 'assets/documents/truck_owners/truck_owner_id_2/Rtysyys/insurance.jpeg', 1),
(5, 'Rtysyys', 5, 'assets/documents/truck_owners/truck_owner_id_2/Rtysyys/road_tax.jpeg', 1),
(6, 'Rtysyys', 6, 'assets/documents/truck_owners/truck_owner_id_2/Rtysyys/rto.jpeg', 1),
(7, 'GJ10TV5657', 1, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/driver_selfie.jpg', 1),
(8, 'GJ10TV5657', 2, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/license.jpg', 1),
(9, 'GJ10TV5657', 3, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/rc.jpg', 1),
(10, 'GJ10TV5657', 4, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/insurance.jpg', 1),
(11, 'GJ10TV5657', 5, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/road_tax.jpg', 1),
(12, 'GJ10TV5657', 6, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/rto.jpg', 1),
(13, 'GJ10TV5657', 1, '', 0),
(14, 'GJ10TV5657', 2, '', 0),
(15, 'GJ10TV5657', 3, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/rc.jpg', 0),
(16, 'GJ10TV5657', 4, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/insurance.jpg', 0),
(17, 'GJ10TV5657', 5, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/road_tax.jpg', 0),
(18, 'GJ10TV5657', 6, 'assets/documents/truck_owners/truck_owner_id_1/GJ10TV5657/rto.jpg', 0);

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
  `to_bank` bigint(20) NOT NULL DEFAULT '0',
  `to_ifsc` varchar(20) NOT NULL DEFAULT '0',
  `to_verified` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - No; 1 - Yes',
  `to_account_on` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 - Nothing; 1 - Trial; 2 - Subscription',
  `to_trial_start_date` datetime NOT NULL,
  `to_trial_expire_date` datetime NOT NULL,
  `to_subscription_start_date` datetime NOT NULL,
  `to_subscription_order_id` varchar(255) NOT NULL,
  `to_subscription_expire_date` datetime NOT NULL,
  `to_registered` datetime NOT NULL,
  `to_truck_limit` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Number of trucks',
  `to_token` varchar(255) NOT NULL,
  `to_active` tinyint(4) NOT NULL DEFAULT '0',
  `to_on` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `truck_owners`
--

INSERT INTO `truck_owners` (`to_id`, `to_phone_code`, `to_phone`, `to_otp`, `to_name`, `to_bank`, `to_ifsc`, `to_verified`, `to_account_on`, `to_trial_start_date`, `to_trial_expire_date`, `to_subscription_start_date`, `to_subscription_order_id`, `to_subscription_expire_date`, `to_registered`, `to_truck_limit`, `to_token`, `to_active`, `to_on`) VALUES
(1, 91, 8488888822, 706393, 'Jay BARAI', 5912308888888, 'HDFC0001446', 1, 1, '2020-10-30 19:53:27', '2020-11-14 19:53:27', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '2020-10-30 19:50:21', 1, 'ei1kHyvMR3ubnufqGukH6J:APA91bEOWpLyD0GtwRXEAiZ-XpChrJGkUXqocHDZRsHj95AdtGaaVYFVR99L8OFgSIPFXqQ-klb6XI_3jDszbg24z3Gy4v86ngIYrVpZXfQQYCxuThiXZV5roTHvT120MhXxdMq1-sDN', 1, 1),
(2, 91, 7908024082, 830734, 'Rohit', 6452785463, 'GSYSYUS', 1, 1, '2020-10-30 19:51:44', '2020-11-14 19:51:44', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '2020-10-30 19:50:55', 3, 'fM8dhGAaSNCb3mO2Skq4Hr:APA91bEb6UOIrTOCVtcbZ48VL1A_KGc1TyVQ6Z9uKiG0D1M4_dZwA4MLKDMKa0e73UCe1zdCC4d6xFaGP9JzyRo_d8315AusYNSKsV_1jjCdryMwWfc_rVflEeGjieyx_hCzrpopm7xE', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `truck_owner_docs`
--

CREATE TABLE `truck_owner_docs` (
  `to_doc_id` int(11) NOT NULL,
  `to_doc_owner_phone` bigint(20) NOT NULL,
  `to_doc_sr_num` tinyint(4) NOT NULL,
  `to_doc_location` varchar(200) NOT NULL,
  `to_doc_verified` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `truck_owner_docs`
--

INSERT INTO `truck_owner_docs` (`to_doc_id`, `to_doc_owner_phone`, `to_doc_sr_num`, `to_doc_location`, `to_doc_verified`) VALUES
(1, 8488888822, 1, 'assets/documents/truck_owners/truck_owner_id_1/pan_card.jpeg', 1),
(2, 7908024082, 1, 'assets/documents/truck_owners/truck_owner_id_2/pan_card.jpeg', 1);

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
-- Indexes for table `delivery_trucks`
--
ALTER TABLE `delivery_trucks`
  ADD PRIMARY KEY (`del_trk_id`);

--
-- Indexes for table `load_payments`
--
ALTER TABLE `load_payments`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `material_types`
--
ALTER TABLE `material_types`
  ADD PRIMARY KEY (`mat_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`no_id`);

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
-- Indexes for table `truck_docs`
--
ALTER TABLE `truck_docs`
  ADD PRIMARY KEY (`trk_doc_id`);

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
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `or_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cust_order_destination`
--
ALTER TABLE `cust_order_destination`
  MODIFY `des_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cust_order_source`
--
ALTER TABLE `cust_order_source`
  MODIFY `so_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cust_order_truck_pref`
--
ALTER TABLE `cust_order_truck_pref`
  MODIFY `pref_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `del_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_trucks`
--
ALTER TABLE `delivery_trucks`
  MODIFY `del_trk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `load_payments`
--
ALTER TABLE `load_payments`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `material_types`
--
ALTER TABLE `material_types`
  MODIFY `mat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `no_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `subscribed_users`
--
ALTER TABLE `subscribed_users`
  MODIFY `subs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trucks`
--
ALTER TABLE `trucks`
  MODIFY `trk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `truck_cat`
--
ALTER TABLE `truck_cat`
  MODIFY `trk_cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `truck_cat_type`
--
ALTER TABLE `truck_cat_type`
  MODIFY `ty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `truck_docs`
--
ALTER TABLE `truck_docs`
  MODIFY `trk_doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `truck_owners`
--
ALTER TABLE `truck_owners`
  MODIFY `to_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `truck_owner_docs`
--
ALTER TABLE `truck_owner_docs`
  MODIFY `to_doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
