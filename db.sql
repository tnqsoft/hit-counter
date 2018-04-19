/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.7.21-0ubuntu0.17.10.1 : Database - hit_counter
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`hit_counter` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `hit_counter`;

/*Table structure for table `visitor_summary` */

DROP TABLE IF EXISTS `visitor_summary`;

CREATE TABLE `visitor_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visit_date` date DEFAULT NULL,
  `total_view` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `visitor_summary` */

insert  into `visitor_summary`(`id`,`visit_date`,`total_view`) values (1,'2018-04-18',6);

/*Table structure for table `visitors` */

DROP TABLE IF EXISTS `visitors`;

CREATE TABLE `visitors` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `last_visit` int(11) NOT NULL,
  `os` varchar(100) DEFAULT NULL,
  `device` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `visitors` */

insert  into `visitors`(`id`,`session_id`,`ip`,`last_visit`,`os`,`device`,`language`,`created_at`) values (1,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524119590,'Linux','Chrome','','2018-04-19 13:33:10'),(2,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524120212,'Linux','Chrome','','2018-04-19 13:43:32'),(3,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524120574,'Linux','Chrome','en','2018-04-19 13:49:34'),(4,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524121363,'Linux','Chrome','en-US','2018-04-19 14:02:43'),(5,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524125061,'Linux','Chrome','en-US','2018-04-19 15:04:21'),(6,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524128630,'Linux','Chrome','en-US','2018-04-19 16:03:50'),(7,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524129649,'Linux','Chrome','en-US','2018-04-19 16:20:49'),(8,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524130303,'Linux','Chrome','en-US','2018-04-19 16:31:43'),(9,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524131440,'Linux','Chrome','en-US','2018-04-19 16:50:40'),(10,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524131759,'Linux','Chrome','en-US','2018-04-19 16:55:59'),(11,'jdl5rc5dc1d7htab2g61vo94g9','::1',1524132096,'Linux','Chrome','en-US','2018-04-19 17:01:36');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
