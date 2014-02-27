-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014-02-18 11:08:09
-- 服务器版本: 5.1.53-community
-- PHP 版本: 5.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `wx_sms`
--
CREATE DATABASE IF NOT EXISTS `wx_sms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `wx_sms`;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(128) NOT NULL,
  `area` varchar(128) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `addresses`
--

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(128) NOT NULL,
  `address` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `meal_list`
--

DROP TABLE IF EXISTS `meal_list`;
CREATE TABLE IF NOT EXISTS `meal_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `owner` varchar(128) NOT NULL DEFAULT 'hungjie',
  `description` text,
  `img_path` varchar(256) DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `price` DECIMAL(10,2) NOT NULL,
  `status` varchar(64) DEFAULT 'all',
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`) 
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_order`
--

DROP TABLE IF EXISTS `user_order`;
CREATE TABLE IF NOT EXISTS `user_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` int(11) NOT NULL DEFAULT 1,
  `total_count` int(10) unsigned NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `time_at` DATETIME NOT NULL,
  `user_id` varchar(128) NOT NULL,  
  `owner` varchar(128) NOT NULL DEFAULT 'hungjie',
  `order_info` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `time_at` (`time_at`, `owner`(10))  
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_order_detail`
--

DROP TABLE IF EXISTS `user_order_detail`;
CREATE TABLE IF NOT EXISTS `user_order_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time_at` DATETIME NOT NULL,
  `user_order_id` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `meal_name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `time_at` (`time_at`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_session`
--

DROP TABLE IF EXISTS `user_session`;
CREATE TABLE IF NOT EXISTS `user_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msisdn` varchar(32) NOT NULL,
  `shortcode` varchar(32) NOT NULL,
  `session_value` blob,
  PRIMARY KEY (`id`),
  KEY `msisdn_shortcode` (`msisdn`,`shortcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- --------------------------------------------------------

--
-- 表的结构 `system_config`
--

DROP TABLE IF EXISTS `system_config`;
CREATE TABLE IF NOT EXISTS `system_config` (
    `name` varchar(127) NOT NULL,
    `value` TEXT NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `system_config`
--

INSERT INTO `system_config` VALUES
('start_am', '09:00'), 
('end_am', '10:50'), 
('start_pm', '14:30'), 
('end_pm', '19:50'),
('meal_count', '200');

-- --------------------------------------------------------

--
-- 表的结构 `system_var`
--

DROP TABLE IF EXISTS `system_var`;
CREATE TABLE IF NOT EXISTS `system_var` (
    `name` varchar(127) NOT NULL,
    `value` TEXT NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
