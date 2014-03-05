-- MySQL dump 10.13  Distrib 5.1.61, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: wx_sms
-- ------------------------------------------------------
-- Server version	5.1.61

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(128) COLLATE utf8_bin NOT NULL,
  `address` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,'oEXx6jlH9DxhkRtpGpNRg1JJ4gT0','[\"\\u54c8\\u54c8\\u5927\\u5bb6 , \\u7b2c\\u4e09\\u5341 , 7777777\",\"\\u5bf9\\u7b26\\u5408\\u89c4\\u8303\\u7684 , \\u8036\\u548c\\u534e , 467643268\",\"m , ufuffyu , ghkk\"]');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meal_list`
--

DROP TABLE IF EXISTS `meal_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meal_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `desc` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `price` double(7,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meal_list`
--

LOCK TABLES `meal_list` WRITE;
/*!40000 ALTER TABLE `meal_list` DISABLE KEYS */;
INSERT INTO `meal_list` VALUES (1,'çº¢çƒ§è‚‰å¥—é¤','',0,10.00),(2,'æ¢…èœæ‰£è‚‰å¥—é¤','',0,16.00);
/*!40000 ALTER TABLE `meal_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_order`
--

DROP TABLE IF EXISTS `user_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `order_info` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_order`
--

LOCK TABLES `user_order` WRITE;
/*!40000 ALTER TABLE `user_order` DISABLE KEYS */;
INSERT INTO `user_order` VALUES (11,'oEXx6jlH9DxhkRtpGpNRg1JJ4gT0','{\"address\":\"m , ufuffyu , ghkk\",\"date\":\"2014-02-12 03:58:56\"}',0);
/*!40000 ALTER TABLE `user_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_session`
--

DROP TABLE IF EXISTS `user_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_session` (
  `msisdn` varchar(32) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortcode` varchar(32) NOT NULL,
  `session_value` blob,
  PRIMARY KEY (`id`),
  KEY `msisdn_shortcode` (`msisdn`,`shortcode`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_session`
--

LOCK TABLES `user_session` WRITE;
/*!40000 ALTER TABLE `user_session` DISABLE KEYS */;
INSERT INTO `user_session` VALUES ('34567',2,'326665100','eJzVkM1Kw0AUhfeFvkOZvZCkUvTmCQSxvsGQJhEHzAzkplQphYKKoI0/C3GhFCkqLtx1I7WlD9NMrCtfwZvWQhAEV0JhmMX9ztxzzlRhHVjgCMkDX9YZVKCJYFrA9gRG3FVB4EiP2Q6UoSnAsKt/1RvQbBExSI0CPbkhdxSj52Z5MdkOlesjsm9pBZjjRkJJZuNMhbuqUW1IP9yk1bOhCUz6+xFf6GoUCGGNFlIWjlHI7C17Hkcgd/BAcvRllAlNuyXoIn/yqdURJVlzT4RupMIlq2Fl/qu5Gir0/OUq8Q9xzJ9xsjA0SEb3+nH0OeykL73p+Fwf99P+sx4f6mGbTrFQ0rdd/RBP2pfmpH1V0qfdZHCWdk50fJOHVgY/ekfvg6e5JA+NDCavb9OLOx1fEykW5sxYsdjvv/IFAz1N6A=='),('oEXx6jlH9DxhkRtpGpNRg1JJ4gT0',3,'326665100','eJw1jmEKwjAMRu+SE6wKQ9ITCOK8QaltxIJNoalsMnZ3w8r+vjzelwmNQSg1UnWJX0UAR1wFzQnhk6S5UHL2HMF6HHDd9DIgZEkS+ao+2AnN+SCPWgKJNro6IvjQUmGwslvyLvM0M9Wbpneo40xLc4f3RKP4okHir5NWwd5tfyeJ8/JjJ8Sti9sfZpQ+5w=='),('oEXx6jlH9DxhkRtpGpNRg1JJ4gT0',4,'326665101','eJzVkt1KAkEcxe8F3yHmPnB0EZt9giCyNxgmd6qBdhZm1kxE2L6uzNCLuij7EiWhqMBbbR9GZ7WrXqHZVUmKugy8/Z/fnP85M5NFKwjYhHFsU54HKI1KEsEkArtMujjn2DbhFjAJMlCJIWhmEYQISJcIFzvCouKPIwlUKmsloRdIJi2+yrccEDqkZpMN4eSolGCKphEgOZc5HJgyouSOU8gWOBVr2joa6uWc7rt4xm2ihB5ntKGOj6UrgLluTuIwiYksciwpd0MQmmWGktMGUXbMdCK5YA1S0wZ7jBYmT7BoDYxwf/TtONmmuOjkBSaWJaIUC9TkH+LA73E0ldHYsH+n2v2P3mnw1Bz7Z+qkG3Q7qucp/2j83Hxv1eKxJXV1o1rVgVeDA6++FIoPlZ9iMhSD2/aoUVH+oxaH/n1w8DKPpL6Q4LAzujzWLqp6Po8YITLqXYzf6hNENV7VtaeReGwCwWUD/H6Vn3PcsT0='),('oEXx6jlH9DxhkRtpGpNRg1JJ4gT0',5,'326665102','eJw1jlEKwjAQBe+yJ2hUirycQBDrDUJsIwbMBrJbqpTe3Vjt77xheB0OIA2ihBazwOxAzyjq+pyS54GsR4N5qUsDShJl4BPfM9kOZr+Ra8l9EKG/2oJ8rzEzWVkteeSpmziUc02v0IA4vNRt3g1NxccaDDw60UL2Yn93ojgvb3YSWL+iscsHN8E73g=='),('ovZGajhjlaTh4ZwN5uim05d3nbNE',11,'326665100','eJzVkN9KAkEUxu8F30HmPth1RWr2CYLI3mBYdzcaaGdhz4iFCEKFkNqfC+vCkJCKLoIuJAjTfBidbbvqFTqrGUsQdBUIw1zM9zvn+74p0DVKPIsL5rmiRGieVoDqWUp2OUhm+55nCYeYFs3RCqeaWfgrr9FKFRUNaeDgiHWx7RMc143Fy1bg2y4A+ULzlFi25L4gJswo2PHLhbJwgw1cPXvUKRHunmQLroiBgK7iQszCQAbE3DTncTgwC/YFA1fIGNTNKscL/dGnWAIQaM0cHtjSD5asRjb2zyVq+IHjLlsJA/2xg3Rx3zIl/4c4+s84SOVxdjq6Vrejj2EzfOhF4xN11A/792p8oIY1POlURnW66qY1qZ3pk9p5Rh13p4NG2Kyr1mVSzMbie+/wbXA3R5KiEYvhUyN6bKvOa3hRj06vVKudRLQYmT6/fCvp1FzTVgzy+8d9AutNoCk='),('oEXx6jlH9DxhkRtpGpNRg1JJ4gT0',6,'326665103','eJw1jlEKwjAQBe+yJ2hUirycQBDrDUJsIwbMBrJbqpTe3Vjt77xheB0OIA2ihBazwOxAzyjq+pyS54GsR4N5qUsDShJl4BPfM9kOZr+Ra8l9EKG/2oJ8rzEzWVkteeSpmziUc02v0IA4vNRt3g1NxccaDDw60UL2Yn93ojgvb3YSWL+iscsHN8E73g=='),('oEXx6jlH9DxhkRtpGpNRg1JJ4gT0',7,'326665104','eJw1jlEKwjAQBe+yJ2hUimxOIIj1BiGmEQNmA31bqpTe3dDa33nD8Do+MWmEErc8g82B6Z2gLpScvfRkPTc8L3VpmDISernIs5Dt2Bx3ch9KiAD91ZbJB01FyGK18CpTN0kcrjW9QsMk8aNu9x5sKj7XYJTRQQeyN7vdSXAeX3GIopu4/AA37Tvf'),('oEXx6jlH9DxhkRtpGpNRg1JJ4gT0',8,'326665105','eJzVkN9KAkEUxu8F30HmPth1RWr2CYLI3mBYdzcaaGdhz4iFCEKFkNqfC+vCkJCKLoIuJAjTfBidbbvqFTqrGUsQdBUIw1zM9zvn+74p0DVKPIsL5rmiRGieVoDqWUp2OUhm+55nCYeYFs3RCqeaWfgrr9FKFRUNaeDgiHWx7RMc143Fy1bg2y4A+ULzlFi25L4gJswo2PHLhbJwgw1cPXvUKRHunmQLroiBgK7iQszCQAbE3DTncTgwC/YFA1fIGNTNKscL/dGnWAIQaM0cHtjSD5asRjb2zyVq+IHjLlsJA/2xg3Rx3zIl/4c4+s84SOVxdjq6Vrejj2EzfOhF4xN11A/792p8oIY1POlURnW66qY1qZ3pk9p5Rh13p4NG2Kyr1mVSzMbie+/wbXA3R5KiEYvhUyN6bKvOa3hRj06vVKudRLQYmT6/fCvp1FzTVgzy+8d9AutNoCk='),('oEXx6jl49JP5YoCKu9QhDv_PJHtY',9,'326665100','eJzztzI0sFLKL0pJLYrPTU3MUbIyt6outjKzUsorzU1KLVKyzrQytC62MjSyUsrJLC6JT87PzU3MS1GyTrQysKquLQbrzy3OLE7J88xLy1ey9rcyNIaJBBTlJ6cWFytBlQJNTUwuyczPUwKZCFRVnJFf7l+el1rkAzQaLGgItDi1oiQepi4JbLsF0MDUvNL44pIikCozEyulZ00rnjVvfrFu0dMls55umPhk917VYl6uF+u3P5097+mSXiD/2dQN7/f0PF3WBCSB6oyBQiA+WNpYTwnqqczi+MTiyrz44tS8Eoh1tQCvzG+x'),('oEXx6jtVt95JKCrV_8m3QaTgrsVw',10,'326665100','eJzVkN9KAkEUxu8F30HmPth1RWr2CYLI3mBYdzcaaGdhz4iFCEKFkNqfC+vCkJCKLoIuJAjTfBidbbvqFTqrGUsQdBUIw1zM9zvn+74p0DVKPIsL5rmiRGieVoDqWUp2OUhm+55nCYeYFs3RCqeaWfgrr9FKFRUNaeDgiHWx7RMc143Fy1bg2y4A+ULzlFi25L4gJswo2PHLhbJwgw1cPXvUKRHunmQLroiBgK7iQszCQAbE3DTncTgwC/YFA1fIGNTNKscL/dGnWAIQaM0cHtjSD5asRjb2zyVq+IHjLlsJA/2xg3Rx3zIl/4c4+s84SOVxdjq6Vrejj2EzfOhF4xN11A/792p8oIY1POlURnW66qY1qZ3pk9p5Rh13p4NG2Kyr1mVSzMbie+/wbXA3R5KiEYvhUyN6bKvOa3hRj06vVKudRLQYmT6/fCvp1FzTVgzy+8d9AutNoCk='),('ovZGajt6cXdB5NjCuBkD4AHf-1hU',12,'326665100','eJzVkN9KAkEUxu8F30HmPth1RWr2CYLI3mBYdzcaaGdhz4iFCEKFkNqfC+vCkJCKLoIuJAjTfBidbbvqFTqrGUsQdBUIw1zM9zvn+74p0DVKPIsL5rmiRGieVoDqWUp2OUhm+55nCYeYFs3RCqeaWfgrr9FKFRUNaeDgiHWx7RMc143Fy1bg2y4A+ULzlFi25L4gJswo2PHLhbJwgw1cPXvUKRHunmQLroiBgK7iQszCQAbE3DTncTgwC/YFA1fIGNTNKscL/dGnWAIQaM0cHtjSD5asRjb2zyVq+IHjLlsJA/2xg3Rx3zIl/4c4+s84SOVxdjq6Vrejj2EzfOhF4xN11A/792p8oIY1POlURnW66qY1qZ3pk9p5Rh13p4NG2Kyr1mVSzMbie+/wbXA3R5KiEYvhUyN6bKvOa3hRj06vVKudRLQYmT6/fCvp1FzTVgzy+8d9AutNoCk='),('ovZGajrRhI1-j1hDIjzr2_G5F8BQ',13,'326665100','eJzVkN9KAkEUxu8F30HmPth1RWr2CYLI3mBYdzcaaGdhz4iFCEKFkNqfC+vCkJCKLoIuJAjTfBidbbvqFTqrGUsQdBUIw1zM9zvn+74p0DVKPIsL5rmiRGieVoDqWUp2OUhm+55nCYeYFs3RCqeaWfgrr9FKFRUNaeDgiHWx7RMc143Fy1bg2y4A+ULzlFi25L4gJswo2PHLhbJwgw1cPXvUKRHunmQLroiBgK7iQszCQAbE3DTncTgwC/YFA1fIGNTNKscL/dGnWAIQaM0cHtjSD5asRjb2zyVq+IHjLlsJA/2xg3Rx3zIl/4c4+s84SOVxdjq6Vrejj2EzfOhF4xN11A/792p8oIY1POlURnW66qY1qZ3pk9p5Rh13p4NG2Kyr1mVSzMbie+/wbXA3R5KiEYvhUyN6bKvOa3hRj06vVKudRLQYmT6/fCvp1FzTVgzy+8d9AutNoCk='),('oEXx6jtfgzrmrT5An5FM9dvP4fR4',15,'326665100','eJzVkt1KAkEcxe8F3yHmPnB0EZt9giCyNxgmd6qBdhZm1kxE2L6uzNCLuij7EiWhqMBbbR9GZ7WrXqHZVUmKugy8/Z/fnP85M5NFKwjYhHFsU54HKI1KEsEkArtMujjn2DbhFjAJMlCJIWhmEYQISJcIFzvCouKPIwlUKmsloRdIJi2+yrccEDqkZpMN4eSolGCKphEgOZc5HJgyouSOU8gWOBVr2joa6uWc7rt4xm2ihB5ntKGOj6UrgLluTuIwiYksciwpd0MQmmWGktMGUXbMdCK5YA1S0wZ7jBYmT7BoDYxwf/TtONmmuOjkBSaWJaIUC9TkH+LA73E0ldHYsH+n2v2P3mnw1Bz7Z+qkG3Q7qucp/2j83Hxv1eKxJXV1o1rVgVeDA6++FIoPlZ9iMhSD2/aoUVH+oxaH/n1w8DKPpL6Q4LAzujzWLqp6Po8YITLqXYzf6hNENV7VtaeReGwCwWUD/H6Vn3PcsT0='),('ovZGajsIXfEB6I9jUZk7tW83MXJI',14,'326665100','eJw1kM9Kw0AQh+8+RZknyKZNWycHD54Esb5BiMkWA2YD2UiVUtBSQUGJ0J5KK17qHxBSEInQ4NN0m3jKK7hNIsxh+OabH8N0kNQRPN+mvmF5rOs7LmAL+xy1CoN+pHMkKsKZwwMpua7JbNBNVLA/kBMFweUOt9kB63qgd4rEkhz7nkU5h0ptIphW4HgMtonS4qder9Nj1D+U0QUkCIxeBMa/d4JE4rYMpOzc4IFfWGoDIYvi7Gcsbhab4Vs6HYnZUsyv9mo7m+dEJOF6tajlyW1J8+RevI7F44NssutJ+rlKJ19ZNM+TO0lq4mWYJ9Pf9w/xNMriiLREGO83FW27tgzX3zNZsidqvaE1W+1dhUD1EYcbJr9kBqcsKG8d/AEaipNW');
/*!40000 ALTER TABLE `user_session` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-02-13  1:32:15
