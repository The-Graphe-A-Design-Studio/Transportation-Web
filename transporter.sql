-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2020 at 02:03 PM
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
  `bid_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 - Accepted by Admin; 2 - Accepted by Shipper; 3 - Accepted by Owner',
  `bid_default` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bidding`
--

INSERT INTO `bidding` (`bid_id`, `bid_user_type`, `bid_user_id`, `load_id`, `bid_expected_price`, `bid_status`, `bid_default`) VALUES
(1, 1, 1, 1, '18000.00', 3, 1),
(2, 1, 1, 2, '280.00', 3, 1),
(3, 1, 4, 3, '1500.00', 3, 1),
(4, 1, 4, 4, '2100.00', 2, 1),
(6, 1, 6, 5, '320.00', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `co_id` int(11) NOT NULL,
  `co_name` varchar(50) NOT NULL,
  `co_users` tinyint(4) NOT NULL,
  `co_discount` tinyint(4) NOT NULL,
  `co_start_date` datetime NOT NULL,
  `co_expire_date` datetime NOT NULL,
  `co_status` tinyint(4) NOT NULL DEFAULT 0,
  `co_default` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`co_id`, `co_name`, `co_users`, `co_discount`, `co_start_date`, `co_expire_date`, `co_status`, `co_default`) VALUES
(1, 'VSDFVSD', 1, 43, '2020-11-21 13:36:34', '2020-11-25 23:59:59', 1, 1),
(2, 'CFDVDS', 1, 45, '2020-11-21 13:36:46', '2020-11-27 23:59:59', 1, 1),
(3, 'SFSBG', 2, 44, '2020-11-21 13:37:10', '2020-11-27 23:59:59', 1, 1),
(4, 'SVSRTBVTR', 3, 43, '2020-11-21 13:37:24', '2020-11-24 23:59:59', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `coupon_users`
--

CREATE TABLE `coupon_users` (
  `cu_id` int(11) NOT NULL,
  `cu_coupon` varchar(50) NOT NULL,
  `cu_user_type` tinyint(4) NOT NULL,
  `cu_user_id` int(11) NOT NULL,
  `cu_date` datetime NOT NULL
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
  `cu_account_on` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Nothing; 1 - On Trial; 2 - On Subscription; 3 - Free Period',
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
(1, 91, 9647513679, 274647, 1, 1, 1, '2020-11-07 10:27:04', 2, '2020-11-22 10:45:29', '2020-11-21 16:29:13', 'order_hgvu243', '2021-02-20 16:29:13', 'clic2oILTeSCZu_Pk2p9n9:APA91bHI0oyOOfJMzymwyqpQgwFdSwk3k1fsSyHnRFfdBcykGt9zjLCbOa3-_MEW26EZ-PE9rFtcQ4kNYPCsk44TRfay9wB44oF0KQaXl60oTYi6DD8Y6pnHxU4tEEaq0XiQAaLtHHLn', 1),
(2, 91, 8488888822, 843311, 1, 1, 1, '2020-11-07 16:40:43', 3, '2020-11-23 16:51:46', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00', 'c59as2p_RfKWlGN3E0-4qx:APA91bEGck5pNTDVtaCon8AYiuGTMlcIkXIGkFR6cTKW7ZTnQ8KPFZO0xG78MUMnJWw05NOzO8ganQBGRc5VNUQQKT1uchS3siZ9hIkyJV9bUhLSbIf5pbRnFPxCNHITcZdyjGpapm58', 1),
(3, 91, 7033584816, 119999, 2, 0, 0, '2020-11-08 01:42:19', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0', '0000-00-00 00:00:00', 'd2Ric5agRlCdDANTtuf6nT:APA91bG_idTGdTwDNMF2-27dlg2po3iGPluTTGkiz9mdZPdQB21Q0QiN6vHnRDWMX63Smlx0l9BRagKqJpaycg-wTjR0hxRilb-tLSYaaTTAacYwif6I_Pr4luI7mIj2WSra0IHTfWj0', 1);

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
(1, 9647513679, 1, 'assets/documents/shippers/shipper_9647513679/pan_card.jpg', 1),
(2, 9647513679, 2, 'assets/documents/shippers/shipper_9647513679/address_front.jpg', 1),
(3, 9647513679, 3, 'assets/documents/shippers/shipper_9647513679/address_back.jpg', 1),
(4, 9647513679, 4, 'assets/documents/shippers/shipper_9647513679/selfie.jpg', 1),
(5, 9647513679, 5, 'Graphe', 1),
(6, 9647513679, 6, 'assets/documents/shippers/shipper_9647513679/office_address.jpg', 1),
(7, 8488888822, 1, 'assets/documents/shippers/shipper_8488888822/pan_card.jpg', 1),
(8, 8488888822, 2, 'assets/documents/shippers/shipper_8488888822/address_front.jpg', 1),
(9, 8488888822, 3, 'assets/documents/shippers/shipper_8488888822/address_back.jpg', 1),
(10, 8488888822, 4, 'assets/documents/shippers/shipper_8488888822/selfie.jpg', 1),
(11, 8488888822, 5, 'Westend Roadlines', 1),
(12, 8488888822, 6, 'assets/documents/shippers/shipper_8488888822/office_address.jpg', 1),
(13, 7033584816, 1, 'assets/documents/shippers/shipper_7033584816/pan_card.jpg', 0),
(14, 7033584816, 2, 'assets/documents/shippers/shipper_7033584816/address_front.jpg', 0),
(15, 7033584816, 3, 'assets/documents/shippers/shipper_7033584816/address_back.jpg', 0),
(16, 7033584816, 4, 'assets/documents/shippers/shipper_7033584816/selfie.jpg', 0),
(17, 7033584816, 5, '', 0),
(18, 7033584816, 6, '', 0);

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
  `or_admin_expected_price` tinyint(4) NOT NULL DEFAULT 0,
  `or_payment_mode` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 - Negotiable, 2 - Advance Pay, 3 - To Driver After Unloading',
  `or_advance_pay` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'in %',
  `or_shipper_on` tinyint(4) NOT NULL COMMENT '1 - Trial; 2 - Subscription; 3 - Free Plan',
  `or_active_on` datetime NOT NULL,
  `or_expire_on` datetime NOT NULL,
  `or_contact_person_name` varchar(100) NOT NULL,
  `or_contact_person_phone` bigint(20) NOT NULL,
  `or_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 - Active; 0 - Expired; 2 - Hold; 3 - Cancelled; 4 - On Going; 5 -Completed',
  `or_default` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cust_order`
--

INSERT INTO `cust_order` (`or_id`, `or_cust_id`, `or_uni_code`, `or_product`, `or_price_unit`, `or_quantity`, `or_truck_preference`, `or_expected_price`, `or_admin_expected_price`, `or_payment_mode`, `or_advance_pay`, `or_shipper_on`, `or_active_on`, `or_expire_on`, `or_contact_person_name`, `or_contact_person_phone`, `or_status`, `or_default`) VALUES
(1, 1, 'sdn1Ml6', 'Engineering goods', 2, 2, 2, '20000.00', 0, 2, 60, 2, '2020-11-10 21:46:40', '2020-11-11 02:45:00', 'Graphe', 9647513679, 4, 1),
(2, 1, 'TLPqHJh', 'Engineering goods', 1, 28, 2, '300.00', 0, 3, 0, 2, '2020-11-10 22:07:11', '2020-11-11 03:06:00', 'Graphe', 9647513679, 4, 1),
(3, 1, 'bXG3JdT', 'Marble Slab / Marble Block', 2, 1, 4, '1800.00', 0, 2, 20, 2, '2020-11-11 01:19:48', '2020-11-28 06:18:00', 'Graphe', 9647513679, 4, 1),
(4, 1, 'gS190i7', 'Fruits and Vegetables', 1, 1, 2, '2300.00', 0, 3, 0, 2, '2020-11-11 02:16:00', '2020-11-11 07:15:00', 'Graphe', 9647513679, 0, 1),
(5, 2, '494NWVh', 'Others', 1, 31, 3, '320.00', 0, 3, 0, 1, '2020-11-17 19:12:09', '2020-11-19 00:00:00', 'Westend Roadlines', 8488888822, 4, 1);

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
(1, 'sdn1Ml6', 'punjabi pura, Delhi Road, New Punjabi Pura, Brahmpuri, Meerut, Uttar Pradesh, India', '28.96454480', '77.68652770', 'Meerut', 'Uttar Pradesh'),
(2, 'TLPqHJh', 'Kolkata - Mumbai Highway, Naora, Shibpur, Howrah, West Bengal, India', '22.56644090', '88.31399580', 'Howrah', 'West Bengal'),
(3, 'bXG3JdT', 'New Kalimati Road, Sakchi, Jamshedpur, Jharkhand, India', '22.80106780', '86.20722780', 'Jamshedpur', 'Jharkhand'),
(4, 'gS190i7', 'Mirzapur - Varanasi Road, Ram Nagar Industrial Area, Sultanpur, Salhupur, Uttar Pradesh, India', '25.24434320', '83.03739580', 'Milkipur', 'Uttar Pradesh'),
(5, '494NWVh', 'Jamnagar Bypass Road, GIDC Phase III, GIDC Phase-2, Dared, Jamnagar, Gujarat, India', '22.42221530', '70.05172570', 'Jamnagar', 'Gujarat');

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
(1, 'sdn1Ml6', 'Kolkata - Mumbai Highway, Naora, Shibpur, Howrah, West Bengal, India', '22.56644090', '88.31399580', 'Howrah', 'West Bengal'),
(2, 'TLPqHJh', 'Bihar University Campus, Khabra Road, Gannipur, Muzaffarpur, Bihar, India', '26.10993930', '85.37692640', 'Khabra urf Kiratpur Gurdas', 'Bihar'),
(3, 'bXG3JdT', 'Goa - Mumbai Highway, Nandgaon, Maharashtra, India', '17.28067800', '73.53946910', 'Harekarwadi', 'Maharashtra'),
(4, 'gS190i7', 'Kedarnath Road, Phata, Uttarakhand, India', '30.57878380', '79.03901740', 'Phata', 'Uttarakhand'),
(5, '494NWVh', 'Gujarat State Highway 29, Kuranga, Gujarat, India', '22.07651740', '69.17469380', 'Bamanasa', 'Gujarat');

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
(1, 'sdn1Ml6', 22),
(2, 'sdn1Ml6', 23),
(3, 'sdn1Ml6', 26),
(4, 'TLPqHJh', 20),
(5, 'TLPqHJh', 22),
(6, 'bXG3JdT', 50),
(7, 'bXG3JdT', 51),
(8, 'bXG3JdT', 52),
(9, 'bXG3JdT', 53),
(10, 'bXG3JdT', 54),
(11, 'bXG3JdT', 55),
(12, 'bXG3JdT', 56),
(13, 'bXG3JdT', 57),
(14, 'bXG3JdT', 58),
(15, 'gS190i7', 21),
(16, 'gS190i7', 22),
(17, 'gS190i7', 23),
(18, 'gS190i7', 24),
(19, '494NWVh', 64);

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
(1, 'sdn1Ml6', 1, 1, 1, 2, 2, '18000.00', 1),
(2, 'TLPqHJh', 2, 1, 1, 1, 28, '280.00', 1),
(3, 'bXG3JdT', 3, 1, 4, 2, 1, '1500.00', 1),
(4, '494NWVh', 5, 2, 6, 1, 31, '320.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_trucks`
--

CREATE TABLE `delivery_trucks` (
  `del_trk_id` int(11) NOT NULL,
  `del_id` int(11) NOT NULL,
  `trk_id` int(11) NOT NULL,
  `lat` decimal(30,16) NOT NULL DEFAULT 22.4705670000000000,
  `lng` decimal(30,16) NOT NULL DEFAULT 69.0756230000000000,
  `otp` mediumint(9) NOT NULL DEFAULT 0,
  `otp_verified` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - No; 1 - Yes',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 - Started; 2 - Reached',
  `trip_start` datetime NOT NULL,
  `trip_end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `delivery_trucks`
--

INSERT INTO `delivery_trucks` (`del_trk_id`, `del_id`, `trk_id`, `lat`, `lng`, `otp`, `otp_verified`, `status`, `trip_start`, `trip_end`) VALUES
(1, 1, 1, '22.4705670000000000', '69.3456230000000000', 927490, 1, 2, '2020-11-11 12:36:06', '2020-11-11 12:49:20'),
(2, 1, 3, '22.4705670000000000', '69.0756230000000000', 171438, 1, 1, '2020-11-18 13:57:34', '0000-00-00 00:00:00'),
(3, 2, 5, '22.4705670000000000', '69.0756230000000000', 300399, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 3, 6, '22.7740641000000000', '86.1595660000000000', 502915, 1, 2, '2020-11-11 01:38:46', '2020-11-11 01:42:47'),
(5, 4, 4, '22.4233558000000000', '69.0266046000000000', 766241, 1, 1, '2020-11-17 19:29:46', '0000-00-00 00:00:00');

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
  `pay_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Pending; 1 - Paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `load_payments`
--

INSERT INTO `load_payments` (`pay_id`, `delivery_id`, `load_id`, `cu_id`, `to_id`, `amount`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `payment_date`, `pay_mode`, `pay_method`, `pay_status`) VALUES
(1, 1, 1, 1, 1, '21600.00', 'order_FzXPHBlpIwCb7S', 'pay_FzXPWUn9DLXPcC', '89e8e2237e04505ddd6ce30a78c6e36c5b24c0c0479c774f9925c5f0247723c8', '2020-11-10 22:21:51', 1, 1, 1),
(2, 1, 1, 1, 1, '14400.00', 'order_cdFwTANxN43sjW0', 'pay_0IY3T3zYLpHqe0g', '0', '2020-11-11 02:49:23', 2, 2, 1),
(3, 2, 2, 1, 1, '7840.00', 'order_BkR3uYLxO8KxK5v', 'pay_k8V2elBjTP4Rhfb', '0', '2020-11-11 02:49:07', 3, 2, 1),
(4, 3, 3, 1, 4, '300.00', 'order_Fzak2Pkq8DXs1Z', 'pay_FzakEFiojjUVrY', '70742117d0a47ca752d95cf4cf57f8fa20a0a02f328a5a54dea152b680b368d0', '2020-11-11 01:37:31', 1, 1, 1),
(5, 3, 3, 1, 4, '1200.00', 'order_FzapI1AssZmxJA', 'pay_FzapQAxRCxVwRg', 'e101c08ca06143a4fa0a58fa454538cf383fad05ae063678e6f7fad6cd2c5cfe', '2020-11-11 01:42:26', 2, 1, 1),
(6, 4, 5, 2, 6, '9920.00', '', '', '', '0000-00-00 00:00:00', 3, 0, 0);

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
  `no_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Not seen; 1 - Seen',
  `no_default` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`no_id`, `no_date_time`, `no_title`, `no_message`, `id`, `no_status`, `no_default`) VALUES
(45, '2020-11-21 16:29:13', 'Shipper Subscription', 'New subscription by Shipper ID 1', 'order_hgvu', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_users`
--

CREATE TABLE `subscribed_users` (
  `subs_id` int(11) NOT NULL,
  `subs_user_type` tinyint(4) NOT NULL COMMENT '1 - Shipper ; 2 - Truck Owner; 3 - Add on Truck',
  `subs_user_id` int(11) NOT NULL,
  `coupon` varchar(50) NOT NULL,
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

INSERT INTO `subscribed_users` (`subs_id`, `subs_user_type`, `subs_user_id`, `coupon`, `subs_amount`, `subs_duration`, `razorpay_order_id`, `razorpay_payment_id`, `razorpay_signature`, `payment_datetime`, `expire_datetime`, `subs_default`) VALUES
(1, 3, 1, '', '500.00', 1, 'order_FyoA6dBVmCZP2h', 'pay_FyoAFN4FYNAIHJ', '4dccdebd4dd2d77565745f655967597077b24c91520488818b3e23ef1bfa08e9', '2020-07-15 02:06:10', '2020-12-09 02:06:10', 1),
(2, 2, 1, '', '3500.00', 3, 'order_FyoHVcHxqUY1Z2', 'pay_FyoHcTIG9YzakM', 'ee08cb3cf19247398800141d42aea17414770c0a926d86e081193cc74cedc2d9', '2020-06-17 02:13:10', '2021-02-08 02:13:10', 1),
(3, 1, 1, '', '2500.00', 3, 'order_FyoIYkhk9N4rBw', 'pay_FyoIgwQjPnx20Y', 'd272863ff0804d6bc111b0c92b8fa99426c0cb4591b4bca8da2264e393265d92', '2020-08-19 02:14:10', '2021-02-08 02:14:10', 1),
(4, 2, 4, '', '500.00', 6, 'order_FzZxNozdDIC8ZK', 'pay_FzZxmRkZyjXFVX', '6b509337d3307443b3e83d89f2814105b05aced2a3cb241d08396efbf938d141', '2020-09-16 00:51:40', '2021-05-13 00:51:40', 1),
(5, 3, 1, '', '500.00', 1, 'order_Fzc0a8onWjl5uR', 'pay_Fzc0uVsccXPvIW', 'bbc082e2d5b2b053f533565c8d6b6cf3cac6bb7ef4e3745855c0d534dca64019', '2020-09-18 02:52:00', '2020-12-11 02:52:00', 1),
(6, 1, 1, '', '400.00', 3, 'order_hgvu243', '234rfwvw4542', 'fvsrf435t45vg43', '2020-11-21 16:29:13', '2021-02-20 16:29:13', 1);

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
  `plan_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Inactive ; 1 - Active',
  `plan_reg` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`plan_id`, `plan_name`, `plan_type`, `plan_original_price`, `plan_selling_price`, `plan_duration`, `plan_status`, `plan_reg`) VALUES
(1, 'Sabse Sasta', 1, '4500.00', '2500.00', 3, 1, 1),
(2, 'Sabse Mehenga', 1, '6000.00', '4500.00', 6, 1, 1),
(3, 'Sabse Sasta', 2, '5000.00', '3500.00', 3, 1, 1),
(4, 'Sabse Mehenga', 2, '6000.00', '5000.00', 6, 0, 1),
(6, 'Add on', 3, '2000.00', '900.00', 2, 1, 1),
(7, 'Add on Truck', 3, '1000.00', '500.00', 1, 1, 1);

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
  `trk_verified` tinyint(4) NOT NULL DEFAULT 0,
  `trk_lat` decimal(30,16) NOT NULL DEFAULT 22.4705670000000000,
  `trk_lng` decimal(30,16) NOT NULL DEFAULT 69.0756230000000000,
  `trk_active` tinyint(4) NOT NULL DEFAULT 0,
  `trk_on_trip` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 : Not on Trip; 1 : Set for Trip; 2 - On Trip',
  `trk_dr_token` varchar(255) NOT NULL,
  `trk_on` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trucks`
--

INSERT INTO `trucks` (`trk_id`, `trk_owner`, `trk_cat`, `trk_cat_type`, `trk_num`, `trk_dr_name`, `trk_dr_phone_code`, `trk_dr_phone`, `trk_otp`, `trk_verified`, `trk_lat`, `trk_lng`, `trk_active`, `trk_on_trip`, `trk_dr_token`, `trk_on`) VALUES
(1, 1, 3, 38, 'WB324', 'Rohit Singh', 91, 9647513679, 635263, 1, '22.6273964000000000', '88.4036331000000000', 1, 0, 'cHYxhQD7REGDo4DERIOb2c:APA91bFxgLtCehW4s4boEDniIOY0lIvzZdBrjR2FYoBZnpjrO_KYsW8c0vLU5JrjO-WIsS78JLphHV3sCtYrpF4FU2QFsgwdtDWdciaHdzQMbcs4SOAf2s687MIX3ch3DAfrUq94Vnoj', 1),
(2, 3, 3, 64, 'GJ10TV5657', 'Hitesh', 91, 8866165988, 356828, 1, '22.4705337000000000', '69.0721991000000000', 1, 0, 'evTOHmERR52HyZnoL1AXaP:APA91bFDPmwfYlceK-ut2ZYudTwltZ-juzgjHFkzIeabgEodeYOCI9wAntIPauXmBZa8PN6ObyGXLy6dWj8VLvdmqs93URSsDPtv6gEfZpoZLW9jlgBf3cE1q-Rfs3lUM5b8r05ZvEyU', 1),
(3, 1, 4, 59, 'TE465TFG', 'Raj', 91, 7908024082, 579603, 1, '22.6273983000000000', '88.4036462000000000', 1, 2, 'eAaQrO0iSGO-nmZxrtATST:APA91bG2Dd0x6Chs54wy84vLHzoP-rruV8Dz1gF8KMH89n7g4yaLRvIwTkLi83fHo_jYzhmlgYknXDUisyytbPPJyGPdC9EG41bK69u3MQn3-H3VOaVXDZnZUU6zsfJX0sN31CQ_ep-b', 1),
(4, 6, 3, 64, 'GJ 10 TV 6566', 'Anwar', 91, 8488888822, 792026, 1, '22.4233558000000000', '69.0266046000000000', 1, 2, 'c3jrBpUqTtm5sIyDQ42H_e:APA91bHxpNFVYejtwmtF1JYHtfMqsVrv1e_B5XWdL_AxbTmw5jWiSaicJM_AcD0ebgjBsdrVkwoSQirBCXZGLfllfN7h_gZ2heITOYBrDTQWrQ_QP8DccglOEQT420uvtg4BvR2w3Xyq', 1),
(5, 1, 2, 22, 'Geyy6367e', 'teyey', 91, 9647513678, 0, 1, '22.4705670000000000', '69.0756230000000000', 1, 1, '', 1),
(6, 4, 4, 57, 'Jh04as1234', 'Happy Diwali', 91, 7033584816, 477231, 1, '22.7740920000000000', '86.1592917000000000', 1, 0, 'eiPjZKqF5OxwrDW-PfwooS:APA91bFHIRY71XRE2vUIYY4okRnTDKg12EeoiHeWhYtT5R7DzEZBTFFw9ZNgo6zY0mJY-fCzeXn2QbH02eXeElx910_yNSCLGF2YUV8MPEkOfr7OORBThazpWvn9-774U6AcaM5xiIbc', 1);

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
  `trk_doc_verified` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `truck_docs`
--

INSERT INTO `truck_docs` (`trk_doc_id`, `trk_doc_truck_num`, `trk_doc_sr_num`, `trk_doc_location`, `trk_doc_verified`) VALUES
(1, 'WB324', 1, 'assets/documents/truck_owners/truck_owner_id_1/WB324/driver_selfie.jpg', 1),
(2, 'WB324', 2, 'assets/documents/truck_owners/truck_owner_id_1/WB324/license.jpg', 1),
(3, 'WB324', 3, 'assets/documents/truck_owners/truck_owner_id_1/WB324/rc.png', 1),
(4, 'WB324', 4, 'assets/documents/truck_owners/truck_owner_id_1/WB324/insurance.jpg', 1),
(5, 'WB324', 5, 'assets/documents/truck_owners/truck_owner_id_1/WB324/road_tax.jpg', 1),
(6, 'WB324', 6, 'assets/documents/truck_owners/truck_owner_id_1/WB324/rto.jpg', 1),
(7, 'GJ10TV5657', 1, 'assets/documents/truck_owners/truck_owner_id_3/GJ10TV5657/driver_selfie.jpg', 1),
(8, 'GJ10TV5657', 2, 'assets/documents/truck_owners/truck_owner_id_3/GJ10TV5657/license.jpg', 1),
(9, 'GJ10TV5657', 3, 'assets/documents/truck_owners/truck_owner_id_3/GJ10TV5657/rc.jpg', 1),
(10, 'GJ10TV5657', 4, 'assets/documents/truck_owners/truck_owner_id_3/GJ10TV5657/insurance.jpg', 1),
(11, 'GJ10TV5657', 5, 'assets/documents/truck_owners/truck_owner_id_3/GJ10TV5657/road_tax.jpg', 1),
(12, 'GJ10TV5657', 6, 'assets/documents/truck_owners/truck_owner_id_3/GJ10TV5657/rto.jpg', 1),
(13, 'TE465TFG', 1, '', 1),
(14, 'TE465TFG', 2, '', 1),
(15, 'TE465TFG', 3, 'assets/documents/truck_owners/truck_owner_id_1/TE465TFG/rc.jpeg', 1),
(16, 'TE465TFG', 4, 'assets/documents/truck_owners/truck_owner_id_1/TE465TFG/insurance.jpeg', 1),
(17, 'TE465TFG', 5, 'assets/documents/truck_owners/truck_owner_id_1/TE465TFG/road_tax.jpeg', 1),
(18, 'TE465TFG', 6, 'assets/documents/truck_owners/truck_owner_id_1/TE465TFG/rto.jpeg', 1),
(19, 'GJ 10 TV 6566', 1, 'assets/documents/truck_owners/truck_owner_id_6/GJ 10 TV 6566/rc.jpg', 1),
(20, 'GJ 10 TV 6566', 2, 'assets/documents/truck_owners/truck_owner_id_6/GJ 10 TV 6566/rc.jpg', 1),
(21, 'GJ 10 TV 6566', 3, 'assets/documents/truck_owners/truck_owner_id_6/GJ 10 TV 6566/rc.jpg', 1),
(22, 'GJ 10 TV 6566', 4, 'assets/documents/truck_owners/truck_owner_id_6/GJ 10 TV 6566/insurance.jpg', 1),
(23, 'GJ 10 TV 6566', 5, 'assets/documents/truck_owners/truck_owner_id_6/GJ 10 TV 6566/road_tax.jpg', 1),
(24, 'GJ 10 TV 6566', 6, 'assets/documents/truck_owners/truck_owner_id_6/GJ 10 TV 6566/rto.jpg', 1),
(25, 'Geyy6367e', 1, '', 1),
(26, 'Geyy6367e', 2, '', 1),
(27, 'Geyy6367e', 3, 'assets/documents/truck_owners/truck_owner_id_1/Geyy6367e/rc.jpeg', 1),
(28, 'Geyy6367e', 4, 'assets/documents/truck_owners/truck_owner_id_1/Geyy6367e/insurance.jpeg', 1),
(29, 'Geyy6367e', 5, 'assets/documents/truck_owners/truck_owner_id_1/Geyy6367e/road_tax.jpeg', 1),
(30, 'Geyy6367e', 6, 'assets/documents/truck_owners/truck_owner_id_1/Geyy6367e/rto.jpeg', 1),
(31, 'Jh04as1234', 1, 'assets/documents/truck_owners/truck_owner_id_4/Jh04as1234/driver_selfie.jpg', 1),
(32, 'Jh04as1234', 2, 'assets/documents/truck_owners/truck_owner_id_4/Jh04as1234/license.jpg', 1),
(33, 'Jh04as1234', 3, 'assets/documents/truck_owners/truck_owner_id_4/Jh04as1234/rc.jpg', 1),
(34, 'Jh04as1234', 4, 'assets/documents/truck_owners/truck_owner_id_4/Jh04as1234/insurance.jpg', 1),
(35, 'Jh04as1234', 5, 'assets/documents/truck_owners/truck_owner_id_4/Jh04as1234/road_tax.jpg', 1),
(36, 'Jh04as1234', 6, 'assets/documents/truck_owners/truck_owner_id_4/Jh04as1234/rto.jpg', 1);

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
  `to_verified` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - No; 1 - Yes',
  `to_account_on` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Nothing; 1 - Trial; 2 - Subscription',
  `to_trial_start_date` datetime NOT NULL,
  `to_trial_expire_date` datetime NOT NULL,
  `to_subscription_start_date` datetime NOT NULL,
  `to_subscription_order_id` varchar(255) NOT NULL,
  `to_subscription_expire_date` datetime NOT NULL,
  `to_registered` datetime NOT NULL,
  `to_truck_limit` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Number of trucks',
  `to_token` varchar(255) NOT NULL,
  `to_active` tinyint(4) NOT NULL DEFAULT 0,
  `to_on` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `truck_owners`
--

INSERT INTO `truck_owners` (`to_id`, `to_phone_code`, `to_phone`, `to_otp`, `to_name`, `to_bank`, `to_ifsc`, `to_verified`, `to_account_on`, `to_trial_start_date`, `to_trial_expire_date`, `to_subscription_start_date`, `to_subscription_order_id`, `to_subscription_expire_date`, `to_registered`, `to_truck_limit`, `to_token`, `to_active`, `to_on`) VALUES
(1, 91, 7908024082, 311628, 'Rohit Singh', 23545896523, 'RYGT566', 1, 2, '2020-11-07 10:52:09', '2020-11-22 10:52:09', '2020-11-09 02:13:10', 'order_FyoHVcHxqUY1Z2', '2021-02-08 02:13:10', '2020-11-07 10:47:42', 4, 'eWU-EO-tT5yhbqMHCCFShZ:APA91bGtDrsdEy9RFt-xzuPXw54ASU9agfrwcY9WTvbdXv_YwFAYQIRBBJ8KvJwAhAe7eQI7SIaBJR9gMKE7uQz3P_zqC2J05Ex6881bAnahNWZTfdvI8c2Xwc5DhpM7vqgNxzk77N4x', 1, 1),
(2, 91, 8488888855, 940905, '', 0, '0', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '2020-11-07 15:14:42', 1, 'fxNRjpA5SUWsu54wz6uEil:APA91bFnij-Ju_WQ7rVRfO_RwtHcRhi4EBmQ9f2uLR7L8tvRRlZviWP4OaeoTpRk7OtZg2CtXSnQYk41M41aRqsbsDnlVwZxlX52dAPg_0qJpX-0AHyYsgIo_L-1Z4IpA1Jp6pvGiuVk', 0, 1),
(3, 91, 8488888822, 289624, 'Jay Barai', 32759453231, 'SBIN0000457', 1, 0, '2020-11-07 15:21:05', '2020-11-22 15:21:05', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '2020-11-07 15:15:52', 1, 'edVPakIuQYW0QFln3m4N-D:APA91bEKSiBCkRmDZLof2EgAxF3I5QPt5aQVsQhMPlfes9g7l6lZFPgWwEpnLsIBZisuCsz_ALwIdEM6IUcxPXDVkLz-3tbz6vxSVqAzb9-OjRuK8Wxsl26f5ftEq1UMMUmKzSGrwUEI', 0, 1),
(4, 91, 7033584816, 694223, 'Nitish Kumar', 1234567890, 'SBIN12345', 1, 2, '2020-11-07 21:57:59', '2020-11-22 21:57:59', '2020-11-11 00:51:40', 'order_FzZxNozdDIC8ZK', '2021-05-13 00:51:40', '2020-11-07 21:54:55', 1, 'dEv2g81HTtqy01SNB73jBu:APA91bGvNHVd1hnHIE2psNLDXZWYzyk7wacLK4tmvwSPKgzNDt572cPlHEnTDO5D7UDR3NMDKC9CEfS4Gf1028Sb4xUAQLaEnd5gYPjjzYMgIukSWKBu_JnmjtNo271hBYg5UIp5m2qQ', 1, 1),
(5, 91, 9558552786, 907501, '', 0, '0', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '2020-11-08 11:59:55', 1, 'fDzKIGBeQpePkKBZP0oXNC:APA91bEifEU-Rkp1bYwwlatbTDN-OddG_7rYgalijTlKlWDuN7XpDRmpwTF2SysiljXbbdYyMOCGdi6sdDu1rREaul5NbCK_Mzuls8TzVgn5lm-zvAbOaq3FddmADguw_P3VMBYYdSNh', 1, 1),
(6, 91, 9867435788, 208329, 'Jay Barai', 32759153231, 'SBIN0000457', 1, 1, '2020-11-10 16:22:04', '2020-11-25 16:22:04', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '2020-11-10 16:18:54', 1, 'fIQ5caIIReaPO--CvKGtkY:APA91bFrWRMITiJ0fXZWNF1uaJ-rVeKtvMemRVG-yHi4T9arZcWyFA_l2m6QoZvNwqlPOUql7a-gMevUZTplKTJMfXnxCpYSLjZ-U6WHpQRm7eCdYNibuTcprbwg98fwOqS3-fu4rkY2', 1, 1),
(7, 91, 9879610848, 839703, '', 0, '0', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '2020-11-12 12:45:23', 1, 'e09QLhOkSgGrm8rlLD9ofK:APA91bEpx3YLqFK2mtNBG7SaqFWdxkrhPUkt30d7egiKwl0Dy9INqHNDRUxTnRKTfulDdzEFbGtor9FnJrjU38twkth4x-UvaShmNS0NXWKbZldCyeqt_F6uuoNw3UsfnRTyQxT6s9We', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `truck_owner_docs`
--

CREATE TABLE `truck_owner_docs` (
  `to_doc_id` int(11) NOT NULL,
  `to_doc_owner_phone` bigint(20) NOT NULL,
  `to_doc_sr_num` tinyint(4) NOT NULL,
  `to_doc_location` varchar(200) NOT NULL,
  `to_doc_verified` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `truck_owner_docs`
--

INSERT INTO `truck_owner_docs` (`to_doc_id`, `to_doc_owner_phone`, `to_doc_sr_num`, `to_doc_location`, `to_doc_verified`) VALUES
(1, 7908024082, 1, 'assets/documents/truck_owners/truck_owner_id_1/pan_card.jpeg', 1),
(2, 8488888855, 1, '', 0),
(3, 8488888822, 1, 'assets/documents/truck_owners/truck_owner_id_3/pan_card.jpeg', 1),
(4, 7033584816, 1, 'assets/documents/truck_owners/truck_owner_id_4/pan_card.jpeg', 1),
(5, 9558552786, 1, '', 0),
(6, 9867435788, 1, 'assets/documents/truck_owners/truck_owner_id_6/pan_card.jpeg', 1),
(7, 9879610848, 1, '', 0);

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
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`co_id`);

--
-- Indexes for table `coupon_users`
--
ALTER TABLE `coupon_users`
  ADD PRIMARY KEY (`cu_id`);

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
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `co_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `coupon_users`
--
ALTER TABLE `coupon_users`
  MODIFY `cu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `cu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customer_docs`
--
ALTER TABLE `customer_docs`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `cust_order`
--
ALTER TABLE `cust_order`
  MODIFY `or_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `cust_order_destination`
--
ALTER TABLE `cust_order_destination`
  MODIFY `des_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cust_order_source`
--
ALTER TABLE `cust_order_source`
  MODIFY `so_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cust_order_truck_pref`
--
ALTER TABLE `cust_order_truck_pref`
  MODIFY `pref_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `del_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `delivery_trucks`
--
ALTER TABLE `delivery_trucks`
  MODIFY `del_trk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `load_payments`
--
ALTER TABLE `load_payments`
  MODIFY `pay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `material_types`
--
ALTER TABLE `material_types`
  MODIFY `mat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `no_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `subscribed_users`
--
ALTER TABLE `subscribed_users`
  MODIFY `subs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `trucks`
--
ALTER TABLE `trucks`
  MODIFY `trk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  MODIFY `trk_doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `truck_owners`
--
ALTER TABLE `truck_owners`
  MODIFY `to_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `truck_owner_docs`
--
ALTER TABLE `truck_owner_docs`
  MODIFY `to_doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
