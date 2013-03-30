-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 30, 2013 at 07:58 AM
-- Server version: 5.1.63-community
-- PHP Version: 5.4.0-ZS5.6.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `matula`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_audit_log`
--

DROP TABLE IF EXISTS `app_audit_log`;
CREATE TABLE IF NOT EXISTS `app_audit_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_context` varchar(30) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `action` enum('Add','Update','Delete') NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) NOT NULL,
  `data_packet` mediumtext,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `app_audit_log`
--

INSERT INTO `app_audit_log` (`id`, `customer_context`, `customer_id`, `action`, `table_name`, `record_id`, `data_packet`, `created`) VALUES
(1, 'Seller', 2, 'Add', 'lib_authentication_log', 3, 'a:3:{s:5:"email";s:20:"jack.v@jackscars.com";s:10:"ip_address";s:9:"127.0.0.1";s:10:"profile_id";s:1:"2";}', '2013-02-06 17:15:52'),
(2, 'Seller', 2, 'Add', 'lib_authentication_log', 4, 'a:3:{s:5:"email";s:20:"jack.v@jackscars.com";s:10:"ip_address";s:9:"127.0.0.1";s:10:"profile_id";s:1:"2";}', '2013-02-07 08:01:59'),
(3, 'User', 2, 'Add', 'lib_authentication_log', 1, 'a:3:{s:5:"email";s:20:"jack.v@jackscars.com";s:10:"ip_address";s:9:"127.0.0.1";s:10:"profile_id";s:1:"2";}', '2013-02-07 08:07:58'),
(4, 'User', 1, 'Add', 'lib_authentication_log', 2, 'a:3:{s:5:"email";s:23:"greg@simmons-cars.co.za";s:10:"ip_address";s:9:"127.0.0.1";s:10:"profile_id";s:1:"1";}', '2013-03-16 19:34:45');

-- --------------------------------------------------------

--
-- Table structure for table `app_link_request`
--

DROP TABLE IF EXISTS `app_link_request`;
CREATE TABLE IF NOT EXISTS `app_link_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `device_os` varchar(100) NOT NULL,
  `device_cpu` varchar(100) NOT NULL,
  `ip_address` varchar(23) NOT NULL,
  `is_blacklisted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_disabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` enum('Requested','Active','Disabled','BlackListed','Archived') NOT NULL DEFAULT 'Requested',
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `asset`
--

DROP TABLE IF EXISTS `asset`;
CREATE TABLE IF NOT EXISTS `asset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(10) unsigned NOT NULL,
  `town_id` int(10) unsigned NOT NULL,
  `street_id` int(10) unsigned DEFAULT NULL,
  `building_id` int(10) unsigned DEFAULT NULL,
  `floor_id` int(10) unsigned DEFAULT NULL,
  `room_id` int(10) unsigned DEFAULT NULL,
  `owner_id` int(10) unsigned NOT NULL,
  `gps_relative` tinyint(3) unsigned DEFAULT NULL,
  `gps_lat` varchar(50) DEFAULT NULL,
  `gps_long` varchar(50) DEFAULT NULL,
  `identifier` varchar(100) DEFAULT NULL,
  `asset_type_id` int(10) unsigned NOT NULL,
  `asset_sub_type_id` int(10) unsigned NOT NULL,
  `asset_description_id` int(10) unsigned NOT NULL,
  `asset_sub_description_id` int(10) unsigned DEFAULT NULL,
  `material_id` int(10) unsigned DEFAULT NULL,
  `pole_length_id` int(10) unsigned DEFAULT NULL,
  `street_light_type_id` int(10) unsigned DEFAULT NULL,
  `condition_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `asset_description`
--

DROP TABLE IF EXISTS `asset_description`;
CREATE TABLE IF NOT EXISTS `asset_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_sub_type_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `asset_description`
--

INSERT INTO `asset_description` (`id`, `asset_sub_type_id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 1, 'RESERVOIR', '2013-03-30 08:15:00', NULL, 0),
(2, 1, 'WATERTANK', '2013-03-30 08:15:00', NULL, 0),
(3, 1, 'WATERTANK ON STAND', '2013-03-30 08:15:00', NULL, 0),
(4, 1, 'WOODEN POLE', '2013-03-30 08:15:00', NULL, 0),
(5, 1, 'PALLISADE FENCE', '2013-03-30 08:15:00', NULL, 0),
(6, 2, 'POLE NO LIGHT', '2013-03-30 08:15:00', NULL, 0),
(7, 2, 'STREET LIGHT', '2013-03-30 08:15:00', NULL, 0),
(8, 3, 'REGULATORY SIGN', '2013-03-30 08:15:00', NULL, 0),
(9, 3, 'WARNING SIGN', '2013-03-30 08:15:00', NULL, 0),
(10, 3, 'INFORMATION SIGN', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `asset_sub_description`
--

DROP TABLE IF EXISTS `asset_sub_description`;
CREATE TABLE IF NOT EXISTS `asset_sub_description` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_description_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `asset_sub_type`
--

DROP TABLE IF EXISTS `asset_sub_type`;
CREATE TABLE IF NOT EXISTS `asset_sub_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_type_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `asset_sub_type`
--

INSERT INTO `asset_sub_type` (`id`, `asset_type_id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 1, 'WATER', '2013-03-30 08:15:00', NULL, 0),
(2, 1, 'ELECTRICITY', '2013-03-30 08:15:00', NULL, 0),
(3, 1, 'ROAD SIGNS', '2013-03-30 08:15:00', NULL, 0),
(4, 2, 'FURNITURE & FITTNGS', '2013-03-30 08:15:00', NULL, 0),
(5, 2, 'MOTOR VEHICLES', '2013-03-30 08:15:00', NULL, 0),
(6, 2, 'OFFICE EQUIPMENT', '2013-03-30 08:15:00', NULL, 0),
(7, 2, 'COMPUTER EQUIPMENT', '2013-03-30 08:15:00', NULL, 0),
(8, 2, 'PLANT & MACHINERY', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `asset_type`
--

DROP TABLE IF EXISTS `asset_type`;
CREATE TABLE IF NOT EXISTS `asset_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `asset_type`
--

INSERT INTO `asset_type` (`id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 'INFRASTRUCTURE', '2013-03-30 08:15:00', NULL, 0),
(2, 'OTHER ASSETS', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bill_invoice`
--

DROP TABLE IF EXISTS `bill_invoice`;
CREATE TABLE IF NOT EXISTS `bill_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_nr` varchar(30) NOT NULL,
  `type` enum('Prorata Invoice','Tax Invoice','Credit Note') NOT NULL DEFAULT 'Prorata Invoice',
  `profile_id` int(10) unsigned DEFAULT NULL,
  `lib_currency_id` int(10) unsigned NOT NULL DEFAULT '1',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(12,2) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bill_invoice_line_item`
--

DROP TABLE IF EXISTS `bill_invoice_line_item`;
CREATE TABLE IF NOT EXISTS `bill_invoice_line_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_invoice_id` int(10) unsigned NOT NULL,
  `lib_service_id` int(10) unsigned DEFAULT NULL,
  `description` varchar(50) NOT NULL,
  `units` decimal(6,2) NOT NULL DEFAULT '1.00',
  `months` int(10) unsigned DEFAULT NULL,
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `building`
--

DROP TABLE IF EXISTS `building`;
CREATE TABLE IF NOT EXISTS `building` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `building`
--

INSERT INTO `building` (`id`, `location_id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 1, 'COMMUNITY HALL', '2013-03-30 08:15:00', NULL, 0),
(2, 1, 'MUNICIPAL BUILDING', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `condition`
--

DROP TABLE IF EXISTS `condition`;
CREATE TABLE IF NOT EXISTS `condition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `condition`
--

INSERT INTO `condition` (`id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 'EXCELLENT', '2013-03-30 08:15:00', NULL, 0),
(2, 'GOOD', '2013-03-30 08:15:00', NULL, 0),
(3, 'FAIR', '2013-03-30 08:15:00', NULL, 0),
(4, 'POOR', '2013-03-30 08:15:00', NULL, 0),
(5, 'BROKEN', '2013-03-30 08:15:00', NULL, 0),
(6, 'MISSING', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(10) unsigned NOT NULL,
  `date_format` varchar(20) NOT NULL,
  `time_format` varchar(20) NOT NULL,
  `lib_currency_id` int(10) unsigned NOT NULL DEFAULT '1',
  `currency_prefix` varchar(5) NOT NULL,
  `vat_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `notification_source_email` varchar(255) NOT NULL,
  `notification_source_number` varchar(20) NOT NULL,
  `administrative_email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `country_id`, `date_format`, `time_format`, `lib_currency_id`, `currency_prefix`, `vat_percentage`, `notification_source_email`, `notification_source_number`, `administrative_email`) VALUES
(1, 1, 'Y-m-d', 'H:i:s', 1, 'R', '14.00', 'no-reply@nirph.com', '27844801653', 'andre@nirph.com');

-- --------------------------------------------------------

--
-- Table structure for table `contact_request`
--

DROP TABLE IF EXISTS `contact_request`;
CREATE TABLE IF NOT EXISTS `contact_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_name` varchar(100) NOT NULL,
  `trading_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  `name` varchar(100) NOT NULL,
  `html` text,
  `js` text,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `type`, `name`, `html`, `js`, `created`, `updated`, `archived`) VALUES
(1, 'page', 'home', 'Hi, this is the home page!', 'alert("home page loaded");', '2013-03-16 19:30:00', '2013-03-16 19:30:00', 0),
(2, 'page', 'settings', 'Hi! Setting page here :)', 'alert("settings page loaded");', '2013-03-16 19:30:00', '2013-03-16 19:30:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `floor`
--

DROP TABLE IF EXISTS `floor`;
CREATE TABLE IF NOT EXISTS `floor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `building_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `floor`
--

INSERT INTO `floor` (`id`, `building_id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 1, 'A1234', '2013-03-30 08:15:00', NULL, 0),
(2, 1, 'A4321', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lib_action_reference`
--

DROP TABLE IF EXISTS `lib_action_reference`;
CREATE TABLE IF NOT EXISTS `lib_action_reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash_code` varchar(40) NOT NULL,
  `component` varchar(250) NOT NULL,
  `action` varchar(250) NOT NULL,
  `params` varchar(250) DEFAULT NULL,
  `data_packet` text,
  `created` datetime NOT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_address`
--

DROP TABLE IF EXISTS `lib_address`;
CREATE TABLE IF NOT EXISTS `lib_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `lib_city_id` int(10) unsigned NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `latitude` int(10) unsigned DEFAULT NULL,
  `longitude` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lib_address`
--

INSERT INTO `lib_address` (`id`, `name`, `lib_city_id`, `address`, `postal_code`, `latitude`, `longitude`, `created`, `updated`, `archived`) VALUES
(1, 'Business Address', 1, 'Some place, some where, else where', '123', NULL, NULL, '2012-08-30 09:50:50', NULL, 0),
(2, 'Auction Address', 2, 'Some place, some where, else where', '234', NULL, NULL, '2012-08-30 09:50:50', NULL, 0),
(3, 'Business Address', 3, 'Between, nowhere, neverwhere', '345', NULL, NULL, '2012-08-30 09:50:50', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lib_attachment`
--

DROP TABLE IF EXISTS `lib_attachment`;
CREATE TABLE IF NOT EXISTS `lib_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(100) NOT NULL,
  `document` longblob,
  `mime_type` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_authentication_log`
--

DROP TABLE IF EXISTS `lib_authentication_log`;
CREATE TABLE IF NOT EXISTS `lib_authentication_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) NOT NULL,
  `profile_id` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_city`
--

DROP TABLE IF EXISTS `lib_city`;
CREATE TABLE IF NOT EXISTS `lib_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lib_region_id` int(10) unsigned NOT NULL,
  `lib_timezone_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `latitude` int(10) unsigned DEFAULT NULL,
  `longitude` int(10) unsigned DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lib_city`
--

INSERT INTO `lib_city` (`id`, `lib_region_id`, `lib_timezone_id`, `name`, `latitude`, `longitude`, `archived`) VALUES
(1, 1, NULL, 'Pretoria', NULL, NULL, 0),
(2, 1, NULL, 'Johannesburg', NULL, NULL, 0),
(3, 1, NULL, 'Centurion', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lib_contact`
--

DROP TABLE IF EXISTS `lib_contact`;
CREATE TABLE IF NOT EXISTS `lib_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `family_name` varchar(50) NOT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `office` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `lib_address_id` int(10) unsigned DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lib_contact`
--

INSERT INTO `lib_contact` (`id`, `first_name`, `family_name`, `mobile`, `office`, `fax`, `email`, `lib_address_id`, `archived`) VALUES
(1, 'Greggory', 'Simmons', '0840840840', '0120840840', '0120840841', 'greg@simmons-cars.co.za', 1, 0),
(2, 'Jack', 'Vredenweld', '0820820820', '0110820820', '0110820821', 'jack.v@jackscars.com', NULL, 0),
(3, 'Lilith', 'Warchild', '790790790', '210210210', '210210211', 'warchild@rebel-saleshouse.co.za', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lib_country`
--

DROP TABLE IF EXISTS `lib_country`;
CREATE TABLE IF NOT EXISTS `lib_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `country_code_iso` varchar(2) DEFAULT NULL,
  `country_code_fips` varchar(2) DEFAULT NULL,
  `dialing_code` tinyint(3) unsigned DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lib_country`
--

INSERT INTO `lib_country` (`id`, `name`, `country_code_iso`, `country_code_fips`, `dialing_code`, `archived`) VALUES
(1, 'South Africa', NULL, NULL, NULL, 0),
(2, 'Australia', NULL, NULL, NULL, 0),
(3, 'United Kingdom', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lib_currency`
--

DROP TABLE IF EXISTS `lib_currency`;
CREATE TABLE IF NOT EXISTS `lib_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prefix` varchar(3) NOT NULL,
  `code` varchar(3) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `lib_currency`
--

INSERT INTO `lib_currency` (`id`, `prefix`, `code`, `name`) VALUES
(1, 'R', 'ZAR', 'South African Rand');

-- --------------------------------------------------------

--
-- Table structure for table `lib_document`
--

DROP TABLE IF EXISTS `lib_document`;
CREATE TABLE IF NOT EXISTS `lib_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document` longblob,
  `mime_type` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_ip_country`
--

DROP TABLE IF EXISTS `lib_ip_country`;
CREATE TABLE IF NOT EXISTS `lib_ip_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_from` bigint(20) unsigned NOT NULL,
  `ip_to` bigint(20) unsigned NOT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `assigned` varchar(12) DEFAULT NULL,
  `country_code` varchar(2) NOT NULL,
  `country_short` varchar(3) NOT NULL,
  `country_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_newsletter`
--

DROP TABLE IF EXISTS `lib_newsletter`;
CREATE TABLE IF NOT EXISTS `lib_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lib_newsletter_template_id` int(10) unsigned NOT NULL,
  `subject` varchar(100) NOT NULL,
  `content` text,
  `lib_attachment_id` int(10) unsigned DEFAULT NULL,
  `status` enum('Draft','Sent','Test') NOT NULL,
  `sent_to` mediumint(8) unsigned DEFAULT '0',
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_newsletter_template`
--

DROP TABLE IF EXISTS `lib_newsletter_template`;
CREATE TABLE IF NOT EXISTS `lib_newsletter_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `header_lib_photo_id` int(10) unsigned NOT NULL,
  `footer_lib_photo_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `lib_newsletter_template`
--

INSERT INTO `lib_newsletter_template` (`id`, `name`, `header_lib_photo_id`, `footer_lib_photo_id`) VALUES
(1, 'Default', 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `lib_notification_log`
--

DROP TABLE IF EXISTS `lib_notification_log`;
CREATE TABLE IF NOT EXISTS `lib_notification_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_to` varchar(250) DEFAULT NULL,
  `email_subject` varchar(250) DEFAULT NULL,
  `email_body` text,
  `sms_to` varchar(20) DEFAULT NULL,
  `sms_body` text,
  `api_msg_id` varchar(32) DEFAULT NULL,
  `sms_status` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_person`
--

DROP TABLE IF EXISTS `lib_person`;
CREATE TABLE IF NOT EXISTS `lib_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `family_name` varchar(50) NOT NULL,
  `id_number` varchar(13) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_photo`
--

DROP TABLE IF EXISTS `lib_photo`;
CREATE TABLE IF NOT EXISTS `lib_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo` mediumblob,
  `thumbnail` mediumblob,
  `mime_type` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_region`
--

DROP TABLE IF EXISTS `lib_region`;
CREATE TABLE IF NOT EXISTS `lib_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lib_country_id` int(10) unsigned NOT NULL,
  `name` varchar(75) NOT NULL,
  `iso3166_2_code` varchar(2) DEFAULT NULL,
  `fips10_4_code` varchar(2) DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `lib_region`
--

INSERT INTO `lib_region` (`id`, `lib_country_id`, `name`, `iso3166_2_code`, `fips10_4_code`, `archived`) VALUES
(1, 1, 'Gauteng', NULL, NULL, 0),
(2, 1, 'Western Cape', NULL, NULL, 0),
(3, 1, 'Freestate', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lib_repeater_template`
--

DROP TABLE IF EXISTS `lib_repeater_template`;
CREATE TABLE IF NOT EXISTS `lib_repeater_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lib_template_id` int(10) unsigned NOT NULL,
  `group_field` varchar(50) DEFAULT NULL,
  `group_repeater` text,
  `row_repeater_odd` text,
  `row_repeater_even` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_service`
--

DROP TABLE IF EXISTS `lib_service`;
CREATE TABLE IF NOT EXISTS `lib_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `lib_service`
--

INSERT INTO `lib_service` (`id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 'Auctioning Service', '2012-08-30 09:50:50', NULL, 0),
(2, 'Arbitration', '2012-08-30 09:50:50', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lib_template`
--

DROP TABLE IF EXISTS `lib_template`;
CREATE TABLE IF NOT EXISTS `lib_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `tags` text,
  `email_template` text,
  `sms_template` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `lib_template`
--

INSERT INTO `lib_template` (`id`, `name`, `subject`, `tags`, `email_template`, `sms_template`) VALUES
(1, 'Contact Request', 'Contact Request', 'person_name,trading_name,email,mobile,telephone,subject,message', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"\r\n    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">\r\n<head>\r\n	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />\r\n	<title></title>\r\n</head>\r\n<body style="padding:0;margin:0;">\r\n	<!-- Begin 100% Wrapping Table -->\r\n	<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center" style="margin:0; padding:0;border-collapse:collapse; border-spacing:0;width:100%;">\r\n		<tr>\r\n			<td style="padding:20px 0;">\r\n				<!-- Begin Main Template Table -->					\r\n				<table border="0" cellspacing="0" cellpadding="0" width="800" align="center" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:800px; font-family: Arial, Sans-serif; font-size:12px; margin:0 auto; line-height:1.33;">\r\n					<tr>\r\n						<td>\r\n						<img src="http://{APP_HOST}/images/EmailHeader.png" width="800" height="100" alt="Please set images to always display for our emails" style="display:block;" />\r\n						</td>\r\n					</tr>				\r\n					<tr>\r\n						<td style="background:#f6f6f6;padding:20px 0">\r\n							<!-- Main Content table -->\r\n							<table border="0" cellspacing="0" cellpadding="0" width="800" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:800px; font-family: Arial, Sans-serif; font-size:12px;">\r\n								<tr>\r\n									<!-- Spacing Column -->\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\r\n									<!-- Main Column -->\r\n									<td width="420" valign="top" style="width:420px; font-family: Arial, Sans-serif; font-size:12px;">\r\n										<h1 style="font-size:18px;margin:0 0 0.5em;padding:0;font-weight:400;">Action Required</h1>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											You have a new contact request from the website. Details are as follows:\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											<table border="0" cellspacing="0" cellpadding="3px" width="420" style="border-collapse:collapse; border-spacing:0; margin:0; padding:3px; width:420px; font-family: Arial, Sans-serif; font-size:12px;">\r\n												<tr>\r\n													<td width="150px"><b>Person:</b></td>\r\n													<td>[person_name]</td>\r\n												</tr>\r\n												<tr>\r\n													<td><b>Trading Name:</b></td>\r\n													<td>[trading_name]</td>\r\n												</tr>\r\n												<tr>\r\n													<td><b>Email Address:</b></td>\r\n													<td>[email]</td>\r\n												</tr>\r\n												<tr>\r\n													<td><b>Mobile Number:</b></td>\r\n													<td>[mobile]</td>\r\n												</tr>\r\n												<tr>\r\n													<td><b>Telephone Number:</b></td>\r\n													<td>[telephone]</td>\r\n												</tr>\r\n												<tr>\r\n													<td><b>Subject:</b></td>\r\n													<td>[subject]</td>\r\n												</tr>\r\n												<tr>\r\n													<td colspan="2"><b>Message:</b></td>\r\n												</tr>\r\n												<tr>\r\n													<td colspan="2">&nbsp;[message]</td>\r\n												</tr>\r\n											</table>\r\n										</p>\r\n									</td>\r\n									<!-- Spacing Column -->\r\n									<td width="40" valign="top" style="width:40px;">&nbsp;</td>\r\n									<!-- Side Column -->\r\n									<td width="300" valign="top" style="width:300px; font-family: Arial, Sans-serif; font-size:12px;">\r\n										<img src="http://{APP_HOST}/images/contact.jpg" width="300px" alt="Content Image" align="left" style="padding:5px;" />\r\n										<h2 style="font-size:18px;margin:20px 3px 0px;padding:0;font-weight:400;">Contact Request</h2>\r\n										<p style="margin:0 0 1em;padding:5px;">\r\n											A user filled in the contact form on the Bid4Cars website. \r\n											To change the email addres that this notification is sent to \r\n											please login as an Administrator and change the setting under\r\n											General Settings &gt; Site Configuration.\r\n										</p>			\r\n									</td>\r\n									<!-- Spacing Column -->\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\r\n								</tr>\r\n							</table>\r\n							<!-- /Main Content Table -->			\r\n						</td>		\r\n					</tr>\r\n					<tr>\r\n						<td style="background:#ececec;padding:10px 0 0;">\r\n							<!-- Begin Footer table -->\r\n							<table border="0" cellspacing="0" cellpadding="0" width="600" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:600px; font-family: Arial, Sans-serif; font-size:12px;">\r\n								<tr>\r\n									<!-- Spacing Column -->\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\r\n									<!-- Main Column -->\r\n									<td width="560" valign="top" style="width:560px; font-family: Arial, Sans-serif; font-size:12px;">\r\n										<p style="margin:0 0 1em;padding:0;"><b>demo.bid4cars.co.za</b></p>\r\n									</td>\r\n									<!-- Spacing Column -->								\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;&nbsp;</td>\r\n								</tr>\r\n							</table>\r\n							<!-- /End Footer Table -->	\r\n						</td>\r\n					</tr>				\r\n				</table>\r\n				<!-- /End Main Template Table -->\r\n			</td>\r\n		</tr>\r\n	</table>\r\n<!-- /End 100% Wrapping Table  -->	\r\n</body>\r\n</html>\r\n', ''),
(2, 'Profile Registered', 'Profile Registered', 'first_name,family_name,email,pin,user', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"\r\n    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">\r\n<head>\r\n	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />\r\n	<title></title>\r\n</head>\r\n<body style="padding:0;margin:0;">\r\n	<!-- Begin 100% Wrapping Table -->\r\n	<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center" style="margin:0; padding:0;border-collapse:collapse; border-spacing:0;width:100%;">\r\n		<tr>\r\n			<td style="padding:20px 0;">\r\n				<!-- Begin Main Template Table -->					\r\n				<table border="0" cellspacing="0" cellpadding="0" width="800" align="center" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:800px; font-family: Arial, Sans-serif; font-size:12px; margin:0 auto; line-height:1.33;">\r\n					<tr>\r\n						<td>\r\n						<img src="http://{APP_HOST}/images/EmailHeader.png" width="800" height="100" alt="Please set images to always display for our emails" style="display:block;" />\r\n						</td>\r\n					</tr>				\r\n					<tr>\r\n						<td style="background:#f6f6f6;padding:20px 0">\r\n							<!-- Main Content table -->\r\n							<table border="0" cellspacing="0" cellpadding="0" width="800" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:800px; font-family: Arial, Sans-serif; font-size:12px;">\r\n								<tr>\r\n									<!-- Spacing Column -->\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\r\n									<!-- Main Column -->\r\n									<td width="420" valign="top" style="width:420px; font-family: Arial, Sans-serif; font-size:12px;">\r\n										<h1 style="font-size:18px;margin:0 0 0.5em;padding:0;font-weight:400;">Profile created/updated</h1>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											&nbsp;\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											Dear [first_name] [family_name],\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n										  A profile was registered/updated for you by [user] on the Bid4Cars website.\r\n											Here are your login details:\r\n											<table border="0" cellspacing="0" cellpadding="3px" width="420" style="border-collapse:collapse; border-spacing:0; margin:0; padding:3px; width:420px; font-family: Arial, Sans-serif; font-size:12px;">\r\n												<tr>\r\n													<td width="80px"><b>Username:</b></td>\r\n													<td><b>[email]</b></td>\r\n												</tr>\r\n												<tr>\r\n													<td><b>Pin:</b></td>\r\n													<td><b>[pin]</b></td>\r\n												</tr>\r\n											</table>\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											&nbsp;\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											To login please go to <i><a href="http://{APP_HOST}/login">{APP_HOST}/login</a></i>\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											To reset your pin please go to <i><a href="http://{APP_HOST}/forgot">{APP_HOST}/forgot</a></i>\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											And to help you get going please have a look at the FAQ''s <i><a href="http://{APP_HOST}/index/faq">{APP_HOST}/index/faq</a></i>\r\n										</p>\r\n                    <p style="margin:0 0 1em;padding:0;">\r\n                      Regards,<br/>\r\n                      The Bid4Cars Team\r\n                    </p>\r\n									</td>\r\n									<!-- Spacing Column -->\r\n									<td width="40" valign="top" style="width:40px;">&nbsp;</td>\r\n									<!-- Side Column -->\r\n									<td width="300" valign="top" style="width:300px; font-family: Arial, Sans-serif; font-size:12px;">\r\n										<img src="http://{APP_HOST}/images/handshake.png" width="300px" alt="Content Image" align="left" style="padding:5px;" />\r\n										<h2 style="font-size:18px;margin:20px 3px 0px;padding:0;font-weight:400;">Welcome</h2>\r\n										<p style="margin:0 0 1em;padding:5px;">\r\n											Online technology bringing buyers and sellers\r\n											together through the Dealer Network.\r\n										</p>			\r\n									</td>\r\n									<!-- Spacing Column -->\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\r\n								</tr>\r\n							</table>\r\n							<!-- /Main Content Table -->			\r\n						</td>		\r\n					</tr>\r\n					<tr>\r\n						<td style="background:#ececec;padding:10px 0 0;">\r\n							<!-- Begin Footer table -->\r\n							<table border="0" cellspacing="0" cellpadding="0" width="600" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:600px; font-family: Arial, Sans-serif; font-size:12px;">\r\n								<tr>\r\n									<!-- Spacing Column -->\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\r\n									<!-- Main Column -->\r\n									<td width="560" valign="top" style="width:560px; font-family: Arial, Sans-serif; font-size:12px;">\r\n										<p style="margin:0 0 1em;padding:0;"><b>demo.bid4cars.co.za</b></p>\r\n									</td>\r\n									<!-- Spacing Column -->								\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;&nbsp;</td>\r\n								</tr>\r\n							</table>\r\n							<!-- /End Footer Table -->	\r\n						</td>\r\n					</tr>				\r\n				</table>\r\n				<!-- /End Main Template Table -->\r\n			</td>\r\n		</tr>\r\n	</table>\r\n<!-- /End 100% Wrapping Table  -->	\r\n</body>\r\n</html>\r\n', ''),
(3, 'Forgot Pin', 'Your new pin', 'first_name,family_name,email,pin', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"\r\n    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">\r\n<head>\r\n	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />\r\n	<title></title>\r\n</head>\r\n<body style="padding:0;margin:0;">\r\n	<!-- Begin 100% Wrapping Table -->\r\n	<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center" style="margin:0; padding:0;border-collapse:collapse; border-spacing:0;width:100%;">\r\n		<tr>\r\n			<td style="padding:20px 0;">\r\n				<!-- Begin Main Template Table -->					\r\n				<table border="0" cellspacing="0" cellpadding="0" width="800" align="center" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:800px; font-family: Arial, Sans-serif; font-size:12px; margin:0 auto; line-height:1.33;">\r\n					<tr>\r\n						<td>\r\n						<img src="http://{APP_HOST}/images/EmailHeader.png" width="800" height="100" alt="Please set images to always display for our emails" style="display:block;" />\r\n						</td>\r\n					</tr>				\r\n					<tr>\r\n						<td style="background:#f6f6f6;padding:20px 0">\r\n							<!-- Main Content table -->\r\n							<table border="0" cellspacing="0" cellpadding="0" width="800" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:800px; font-family: Arial, Sans-serif; font-size:12px;">\r\n								<tr>\r\n									<!-- Spacing Column -->\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\r\n									<!-- Main Column -->\r\n									<td width="420" valign="top" style="width:420px; font-family: Arial, Sans-serif; font-size:12px;">\r\n										<h1 style="font-size:18px;margin:0 0 0.5em;padding:0;font-weight:400;">Pin Reset</h1>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											&nbsp;\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											Dear [first_name] [family_name],\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											Your pin was reset on the Bid 4 Cars website.\r\n											Here is your new login details:\r\n											<table border="0" cellspacing="0" cellpadding="3px" width="420" style="border-collapse:collapse; border-spacing:0; margin:0; padding:3px; width:420px; font-family: Arial, Sans-serif; font-size:12px;">\r\n												<tr>\r\n													<td width="80px"><b>Username:</b></td>\r\n													<td><b>[email]</b></td>\r\n												</tr>\r\n												<tr>\r\n													<td><b>Pin:</b></td>\r\n													<td><b>[pin]</b></td>\r\n												</tr>\r\n											</table>\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											&nbsp;\r\n										</p>\r\n										<p style="margin:0 0 1em;padding:0;">\r\n											To login please go to <i><a href="http://{APP_HOST}/login">{APP_HOST}/login</a></i>\r\n										</p>\r\n                    <p style="margin:0 0 1em;padding:0;">\r\n                      Regards,<br/>\r\n                      The Bid4Cars Team\r\n                    </p>\r\n									</td>\r\n									<!-- Spacing Column -->\r\n									<td width="40" valign="top" style="width:40px;">&nbsp;</td>\r\n									<!-- Side Column -->\r\n									<td width="300" valign="top" style="width:300px; font-family: Arial, Sans-serif; font-size:12px;">\r\n										<img src="http://{APP_HOST}/images/reset-button.jpg" width="300px" alt="Content Image" align="left" style="padding:5px;" />\r\n										<h2 style="font-size:18px;margin:20px 3px 0px;padding:0;font-weight:400;">Reset</h2>\r\n										<p style="margin:0 0 1em;padding:5px;">\r\n											Please remember to change your pin once logged in.\r\n										</p>			\r\n									</td>\r\n									<!-- Spacing Column -->\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\r\n								</tr>\r\n							</table>\r\n							<!-- /Main Content Table -->			\r\n						</td>		\r\n					</tr>\r\n					<tr>\r\n						<td style="background:#ececec;padding:10px 0 0;">\r\n							<!-- Begin Footer table -->\r\n							<table border="0" cellspacing="0" cellpadding="0" width="600" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:600px; font-family: Arial, Sans-serif; font-size:12px;">\r\n								<tr>\r\n									<!-- Spacing Column -->\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\r\n									<!-- Main Column -->\r\n									<td width="560" valign="top" style="width:560px; font-family: Arial, Sans-serif; font-size:12px;">\r\n										<p style="margin:0 0 1em;padding:0;"><b>demo.bid4cars.co.za</b></p>\r\n									</td>\r\n									<!-- Spacing Column -->								\r\n									<td width="20" valign="top" style="width:20px;">&nbsp;&nbsp;</td>\r\n								</tr>\r\n							</table>\r\n							<!-- /End Footer Table -->	\r\n						</td>\r\n					</tr>				\r\n				</table>\r\n				<!-- /End Main Template Table -->\r\n			</td>\r\n		</tr>\r\n	</table>\r\n<!-- /End 100% Wrapping Table  -->	\r\n</body>\r\n</html>\r\n', 'Your new pin is [pin]. Details also sent via email.'),
(4, 'Newsletter - Basic', '', 'HeaderImageSource,Body,FooterImageSource', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"\n    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\n<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">\n<head>\n	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />\n	<title></title>\n</head>\n<body style="padding:0;margin:0;">\n	<!-- Begin 100% Wrapping Table -->\n	<table border="0" cellspacing="0" cellpadding="0" width="100%" align="center" style="margin:0; padding:0;border-collapse:collapse; border-spacing:0;width:100%;">\n		<tr>\n			<td style="padding:20px 0;">\n				<!-- Begin Main Template Table -->					\n				<table border="0" cellspacing="0" cellpadding="0" width="800" align="center" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:800px; font-family: Arial, Sans-serif; font-size:12px; margin:0 auto; line-height:1.33;">\n					<tr>\n						<td>\n							<img src="[HeaderImageSource]" width="800" height="100" alt="Please set images to always display for our emails" style="display:block;" />\n						</td>\n					</tr>				\n					<tr>\n						<td style="background:#f6f6f6;padding:20px">\n							[Body]\n						</td>		\n					</tr>\n					<tr>\n						<td style="background:#ececec;padding:10px 0 0;">\n							<!-- Begin Footer table -->\n							<table border="0" cellspacing="0" cellpadding="0" width="800" style="border-collapse:collapse; border-spacing:0; margin:0; padding:0; width:800px; font-family: Arial, Sans-serif; font-size:12px;">\n								<tr>\n									<!-- Spacing Column -->\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\n									<!-- Main Column -->\n									<td width="380" valign="top" style="width:380px; font-family: Arial, Sans-serif; font-size:12px;">\n										&nbsp;\n									</td>\n									<!-- Spacing Column -->								\n									<td width="380" valign="top" align="right" style="width:380px;">\n										Click <a href="http://{APP_HOST}/unsubscribe/newsletter">here</a> to unsubscribe from newsletters.\n									</td>\n									<!-- Spacing Column -->\n									<td width="20" valign="top" style="width:20px;">&nbsp;</td>\n								</tr>\n							</table>\n							<!-- /End Footer Table -->\n						</td>\n					</tr>\n					<tr>\n						<td>\n							<img src="[FooterImageSource]" width="800" height="50" alt="Please set images to always display for our emails" style="display:block;" />\n						</td>\n					</tr>\n				</table>\n				<!-- /End Main Template Table -->\n			</td>\n		</tr>\n	</table>\n<!-- /End 100% Wrapping Table  -->	\n</body>\n</html>', '');

-- --------------------------------------------------------

--
-- Table structure for table `lib_video`
--

DROP TABLE IF EXISTS `lib_video`;
CREATE TABLE IF NOT EXISTS `lib_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video` longblob,
  `mime_type` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lib_xmlrpc_profile`
--

DROP TABLE IF EXISTS `lib_xmlrpc_profile`;
CREATE TABLE IF NOT EXISTS `lib_xmlrpc_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `auth_token` varchar(40) NOT NULL,
  `requests` smallint(5) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 'BOSHOF', '2013-03-30 08:15:00', NULL, 0),
(2, 'DEALESVILLE', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

DROP TABLE IF EXISTS `material`;
CREATE TABLE IF NOT EXISTS `material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 'STEEL', '2013-03-30 08:15:00', NULL, 0),
(2, 'CEMENT', '2013-03-30 08:15:00', NULL, 0),
(3, 'WOOD', '2013-03-30 08:15:00', NULL, 0),
(4, 'ASBESTOS', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `meta_table`
--

DROP TABLE IF EXISTS `meta_table`;
CREATE TABLE IF NOT EXISTS `meta_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `hash` varchar(32) NOT NULL,
  `version` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`,`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `meta_table`
--

INSERT INTO `meta_table` (`id`, `name`, `created`, `updated`, `hash`, `version`) VALUES
(1, 'contact_request', '2013-02-06 00:00:00', NULL, 'c6281d8d77e2fb13fd1b1b908e69fce7', 1),
(2, 'profile', '2013-02-06 00:00:00', '2013-03-30 00:00:00', '74f6870f6740f14743f1648e51d26b70', 16),
(3, 'app_link_request', '2013-02-06 00:00:00', NULL, 'f43bdcb651541b245f2655558fb3d18d', 1),
(4, 'config', '2013-02-06 00:00:00', '2013-03-30 00:00:00', 'e03531a6324decfbb1141c4f2892a0c5', 16),
(5, 'lib_photo', '2013-02-06 00:00:00', NULL, '7bde09f24c7977b4721ca39fe327af08', 1),
(6, 'lib_video', '2013-02-06 00:00:00', NULL, 'a69102d29787306f26ec5d6d0ee0d794', 1),
(7, 'lib_document', '2013-02-06 00:00:00', NULL, 'c2a035d14c50dacbf0a8c67a14bd2851', 1),
(8, 'lib_attachment', '2013-02-06 00:00:00', NULL, '38ab8b5d858df97a2456f2e624905b3a', 1),
(9, 'lib_address', '2013-02-06 00:00:00', '2013-03-30 00:00:00', 'eda206b2bd3dcaf4822a4edfc279299e', 16),
(10, 'lib_person', '2013-02-06 00:00:00', NULL, '33692ed654c410d64c3a1213d8333f73', 1),
(11, 'lib_contact', '2013-02-06 00:00:00', '2013-03-30 00:00:00', '8d6f12942a2322c96b1f80a61cfea1ad', 16),
(12, 'lib_newsletter_template', '2013-02-06 00:00:00', '2013-03-30 00:00:00', '71485ae092f3cffdc0950a6a78b1f3ae', 16),
(13, 'lib_newsletter', '2013-02-06 00:00:00', NULL, '5acc042b8308cf92971bbfc35cdab562', 1),
(14, 'lib_ip_country', '2013-02-06 00:00:00', NULL, '6e7553d3a5c89cad46393eebc24fafa5', 1),
(15, 'lib_country', '2013-02-06 00:00:00', '2013-03-30 00:00:00', 'e76a17da9d1aa21dcb272b7fcd5cfb11', 16),
(16, 'lib_region', '2013-02-06 00:00:00', '2013-03-30 00:00:00', '30d5d303e8665973611450aeecf03b90', 16),
(17, 'lib_city', '2013-02-06 00:00:00', '2013-03-30 00:00:00', '6c5b2f2c7b8858933b4048bfdb603dba', 16),
(18, 'lib_template', '2013-02-06 00:00:00', NULL, '1730e156a4631a9c34dd8867d1b62c79', 1),
(19, 'lib_repeater_template', '2013-02-06 00:00:00', NULL, '6808b6e0d1d25cd9206da938a62b1085', 1),
(20, 'lib_authentication_log', '2013-02-06 00:00:00', '2013-03-27 00:00:00', 'd656bfcd19186809dded6a3be9d5844e', 2),
(21, 'lib_notification_log', '2013-02-06 00:00:00', NULL, 'ed4aa2a527e0e94a55b9023ae3ef9825', 1),
(22, 'lib_service', '2013-02-06 00:00:00', '2013-03-30 00:00:00', 'ce8a58e087c7d5537c65a524032cd3e1', 16),
(23, 'lib_currency', '2013-02-06 00:00:00', '2013-03-30 00:00:00', '66490a63a4b2802f902d2980426f8e4e', 16),
(24, 'lib_xmlrpc_profile', '2013-02-06 00:00:00', NULL, '6137d78bc404d61f7894a5a7b01ab705', 1),
(25, 'lib_action_reference', '2013-02-06 00:00:00', NULL, '7bd37aa43130e0ffa2466306f74725c7', 1),
(26, 'bill_invoice', '2013-02-06 00:00:00', NULL, '37f95e7ed7065a5b6a3d533723692166', 1),
(27, 'bill_invoice_line_item', '2013-02-06 00:00:00', NULL, 'b85a696bb89838b55ffae31d25ac5cc4', 1),
(28, 'mobile_content', '2013-03-06 00:00:00', NULL, 'cb88ed57b97a6c99904f87e70dc13365', 1),
(29, 'content', '2013-03-16 00:00:00', '2013-03-30 00:00:00', 'bf47045e2f05006d8d8bc9b0c1151f00', 12),
(30, 'location', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '961a1c8649c0994e82fb150f28f17cff', 3),
(31, 'asset_type', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '75bc2e41d75fb2f38097ef483d03d2e5', 8),
(32, 'asset_sub_type', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '8b9edf987516b70ebf2e3786a0e8e05b', 8),
(33, 'asset_description', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '4ffe133b31b990d12461c5dc6b7744da', 8),
(34, 'asset_sub_description', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '6795cadd1217c01c589adbb04e876323', 2),
(35, 'material', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '28be51e227ff429cac73d8db61be656e', 8),
(36, 'pole_length', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '549ccbe022f0fd5ea5e5f4b567110d61', 6),
(37, 'town', '2013-03-30 00:00:00', '2013-03-30 00:00:00', 'bb651138df6717a8515a0f9840e29b2c', 2),
(38, 'street', '2013-03-30 00:00:00', '2013-03-30 00:00:00', 'ab7d1b24a3851d5bc97abba16bcdf4a7', 2),
(39, 'building', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '910ddb9c7f191325d5b9a60e4cbaf4df', 2),
(40, 'floor', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '47c294ae1a59df083783e15a9260eda9', 2),
(41, 'room', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '19c81626777ecc44a7972e2f544d750b', 2),
(42, 'street_light_type', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '61075483e8516992cd36ab38378d47d4', 2),
(43, 'condition', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '37664e54b5f9fba4a4389c89dee7b8f0', 2),
(44, 'owner', '2013-03-30 00:00:00', '2013-03-30 00:00:00', '6d349af96d1ce958d029d56093f15224', 2),
(45, 'asset', '2013-03-30 00:00:00', NULL, 'de69ada670a58e031d2c58fd871e7ec9', 1);

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

DROP TABLE IF EXISTS `owner`;
CREATE TABLE IF NOT EXISTS `owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `owner`
--

INSERT INTO `owner` (`id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 'TOKOLOGO LOCAL MUNICIPALITY', '2013-03-30 08:15:00', NULL, 0),
(2, 'TECHNICAL SERVICES', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pole_length`
--

DROP TABLE IF EXISTS `pole_length`;
CREATE TABLE IF NOT EXISTS `pole_length` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `pole_length`
--

INSERT INTO `pole_length` (`id`, `name`, `created`, `updated`, `archived`) VALUES
(1, '6M AND LESS', '2013-03-30 08:15:00', NULL, 0),
(2, '8M', '2013-03-30 08:15:00', NULL, 0),
(3, '10M', '2013-03-30 08:15:00', NULL, 0),
(4, '14M', '2013-03-30 08:15:00', NULL, 0),
(5, '25M', '2013-03-30 08:15:00', NULL, 0),
(6, 'MORE THAN 25M', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `family_name` varchar(100) NOT NULL,
  `id_number` varchar(13) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `password_salt` varchar(40) NOT NULL,
  `user_type` enum('User','Administrator') DEFAULT NULL,
  `status` enum('Active','Suspended') DEFAULT 'Active',
  `subscribe_newsletter` tinyint(3) unsigned DEFAULT '1',
  `subscribe_reminders` tinyint(3) unsigned DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `first_name`, `family_name`, `id_number`, `date_of_birth`, `mobile`, `email`, `username`, `password`, `password_salt`, `user_type`, `status`, `subscribe_newsletter`, `subscribe_reminders`, `last_login`, `created`, `archived`) VALUES
(1, 'Greggory', 'Simmons', '7711225544000', '1977-11-22', '0840840840', 'greg@simmons-cars.co.za', 'greg', '787ca4d10a31f0e66d4d792a0a8c3975dd61c697', '8deba4865385c926b093b676ee39da57853a5c22', 'User', 'Active', 1, 1, '2012-09-24 12:00:00', '2012-09-24 12:00:00', 0),
(2, 'Jack', 'Vredenweld', '7202029977333', '1972-02-02', '0820820820', 'jack.v@jackscars.com', 'jack', '787ca4d10a31f0e66d4d792a0a8c3975dd61c697', '8deba4865385c926b093b676ee39da57853a5c22', 'User', 'Active', 1, 1, '2012-09-24 12:00:00', '2012-09-24 12:00:00', 0),
(3, 'Shannon', 'Bramson', '7703155544111', '1977-03-15', '0830830830', 'shannon@simmons-cars.co.za', 'shannon', '787ca4d10a31f0e66d4d792a0a8c3975dd61c697', '8deba4865385c926b093b676ee39da57853a5c22', 'Administrator', 'Active', 1, 1, '2012-09-24 12:00:00', '2012-09-24 12:00:00', 0),
(4, 'Lilith', 'Warchild', '7703155544111', '1977-03-15', '0730730730', 'lilith@rebel-traders.co.za', 'lilith', '787ca4d10a31f0e66d4d792a0a8c3975dd61c697', '8deba4865385c926b093b676ee39da57853a5c22', 'User', 'Active', 1, 1, '2012-09-24 12:00:00', '2012-09-24 12:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
CREATE TABLE IF NOT EXISTS `room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `floor_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `floor_id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 1, 'A234', '2013-03-30 08:15:00', NULL, 0),
(2, 1, 'A432', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `street`
--

DROP TABLE IF EXISTS `street`;
CREATE TABLE IF NOT EXISTS `street` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `town_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `street`
--

INSERT INTO `street` (`id`, `town_id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 1, 'A RE YENG STREET', '2013-03-30 08:15:00', NULL, 0),
(2, 1, 'WESSELS STREET', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `street_light_type`
--

DROP TABLE IF EXISTS `street_light_type`;
CREATE TABLE IF NOT EXISTS `street_light_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `street_light_type`
--

INSERT INTO `street_light_type` (`id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 'NO LIGHT', '2013-03-30 08:15:00', NULL, 0),
(2, 'SINGLE LIGHT', '2013-03-30 08:15:00', NULL, 0),
(3, 'DOUBLE LIGHT', '2013-03-30 08:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `town`
--

DROP TABLE IF EXISTS `town`;
CREATE TABLE IF NOT EXISTS `town` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `archived` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `town`
--

INSERT INTO `town` (`id`, `location_id`, `name`, `created`, `updated`, `archived`) VALUES
(1, 1, 'SERETSE', '2013-03-30 08:15:00', NULL, 0),
(2, 1, 'DONKERHOEK', '2013-03-30 08:15:00', NULL, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
