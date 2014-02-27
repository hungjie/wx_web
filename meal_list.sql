-- phpMyAdmin SQL Dump
-- version 4.0.10
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014-02-27 02:24:35
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

-- --------------------------------------------------------

--
-- 表的结构 `meal_list`
--

DROP TABLE IF EXISTS `meal_list`;
CREATE TABLE IF NOT EXISTS `meal_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `owner` varchar(64) NOT NULL DEFAULT 'hungjie',
  `description` text,
  `img_path` varchar(256) DEFAULT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `price` double(7,2) NOT NULL,
  `status` varchar(64) DEFAULT 'all',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
