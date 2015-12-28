-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 18, 2012 at 04:29 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ezord`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_sessions`
--

DROP TABLE IF EXISTS `api_sessions`;
CREATE TABLE IF NOT EXISTS `api_sessions` (
  `id` char(36) NOT NULL,
  `value` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

DROP TABLE IF EXISTS `bills`;
CREATE TABLE IF NOT EXISTS `bills` (
  `id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `items` text,
  `orders` text,
  `bill_tables` text,
  `created` datetime DEFAULT NULL,
  `created_by` char(36) DEFAULT NULL,
  `total_price` float DEFAULT NULL,
  `bill_number` char(5) DEFAULT NULL,
  `bill_status` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_images`
--

DROP TABLE IF EXISTS `item_images`;
CREATE TABLE IF NOT EXISTS `item_images` (
  `id` char(36) NOT NULL,
  `menu_item_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `caption` tinytext,
  `type` varchar(10) NOT NULL,
  `size` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` char(36) NOT NULL,
  `menu_item_group_id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` float NOT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `active` tinyint(3) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items_orders`
--

DROP TABLE IF EXISTS `menu_items_orders`;
CREATE TABLE IF NOT EXISTS `menu_items_orders` (
  `id` char(36) NOT NULL,
  `menu_item_id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `menu_item_groups`
--

DROP TABLE IF EXISTS `menu_item_groups`;
CREATE TABLE IF NOT EXISTS `menu_item_groups` (
  `id` char(36) NOT NULL,
  `place_id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `active` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `merged_tables`
--

DROP TABLE IF EXISTS `merged_tables`;
CREATE TABLE IF NOT EXISTS `merged_tables` (
  `id` char(36) NOT NULL,
  `table_id` char(36) NOT NULL,
  `merged_id` char(36) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` char(36) NOT NULL,
  `place_id` char(36) NOT NULL,
  `order_status` tinyint(3) unsigned NOT NULL COMMENT '1: created, 2: checkout, 3: delete',
  `note_on_delete` text,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `confirm_by` varchar(50) DEFAULT NULL,
  `deleted_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

DROP TABLE IF EXISTS `places`;
CREATE TABLE IF NOT EXISTS `places` (
  `id` char(36) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slogan` varchar(255) NOT NULL,
  `description` text,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `places_users`
--

DROP TABLE IF EXISTS `places_users`;
CREATE TABLE IF NOT EXISTS `places_users` (
  `place_id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  PRIMARY KEY (`place_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `place_payments`
--

DROP TABLE IF EXISTS `place_payments`;
CREATE TABLE IF NOT EXISTS `place_payments` (
  `id` char(1) NOT NULL,
  `place_id` char(1) NOT NULL,
  `payment_date` datetime NOT NULL,
  `expired_date` datetime DEFAULT NULL,
  `remain_orders` bigint(20) DEFAULT NULL,
  `payment_id` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `place_profiles`
--

DROP TABLE IF EXISTS `place_profiles`;
CREATE TABLE IF NOT EXISTS `place_profiles` (
  `place_id` char(36) NOT NULL,
  `max_item_sum` tinyint(4) unsigned DEFAULT NULL,
  `theme` varchar(10) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `slogan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`place_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reserves`
--

DROP TABLE IF EXISTS `reserves`;
CREATE TABLE IF NOT EXISTS `reserves` (
  `id` char(36) NOT NULL,
  `table_id` char(36) NOT NULL,
  `from` datetime NOT NULL,
  `to` datetime DEFAULT NULL,
  `memo` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` char(36) NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `setting_key` varchar(50) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  `setting_type` varchar(10) DEFAULT NULL,
  `options` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
CREATE TABLE IF NOT EXISTS `tables` (
  `id` char(36) NOT NULL,
  `place_id` char(36) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `active` tinyint(3) unsigned DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `order` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `tables_orders`
--

DROP TABLE IF EXISTS `tables_orders`;
CREATE TABLE IF NOT EXISTS `tables_orders` (
  `id` char(36) NOT NULL,
  `table_id` char(36) NOT NULL,
  `order_id` char(36) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` char(36) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `user_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_members`
--

DROP TABLE IF EXISTS `user_members`;
CREATE TABLE IF NOT EXISTS `user_members` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `user_member_id` char(36) NOT NULL,
  `member_status` tinyint(4) NOT NULL DEFAULT '1',
  `confirm_date` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
