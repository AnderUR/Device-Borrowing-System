-- MySQL dump 10.13  Distrib 8.0.13, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: libservices
-- ------------------------------------------------------
-- Server version	5.7.14

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `libservices`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `libservices` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `libservices`;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
INSERT INTO `groups` VALUES (1,'admin','Administrator'),(2,'supervisor','Supervisor'),(3,'staff','Faculty/Staff'),(4,'guest','Guest');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `login_attempts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `barcode` varchar(15) NOT NULL,
  `emergencyContact` text,
  `barcodeLogin` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `barcode_UNIQUE` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=9608 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (9604,_binary 'na','admin','R8LJQg2MJsa93c172659bbfe84c4a84d025bc73e',NULL,'dbs@dbs.com',NULL,NULL,NULL,NULL,1559534400,1562274882,1,'adminFirst','adminLast','ESS','000000000','12345','ess contact street',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_groups`
--

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` VALUES (29,9604,1);
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'libservices'
--

--
-- Dumping routines for database 'libservices'
--

--
-- Current Database: `inventory`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `inventory` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `inventory`;

--
-- Table structure for table `accessories`
--

DROP TABLE IF EXISTS `accessories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `accessories` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Quantity` int(11) NOT NULL COMMENT 'Quantity of any given accessory.',
  `ScanRequired` tinyint(1) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accessories`
--

LOCK TABLES `accessories` WRITE;
/*!40000 ALTER TABLE `accessories` DISABLE KEYS */;
/*!40000 ALTER TABLE `accessories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device`
--

DROP TABLE IF EXISTS `device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `device` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Barcode` varchar(14) NOT NULL,
  `Type` varchar(45) NOT NULL COMMENT 'Refers to which device will be loaned',
  `Notes` text,
  `TechnicalAvailability` tinyint(4) NOT NULL DEFAULT '0',
  `LoanAvailability` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Barcode_UNIQUE` (`Barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device`
--

LOCK TABLES `device` WRITE;
/*!40000 ALTER TABLE `device` DISABLE KEYS */;
INSERT INTO `device` VALUES (236,'iPad 1','1','iPad',NULL,1,1);
/*!40000 ALTER TABLE `device` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_borrower`
--

DROP TABLE IF EXISTS `device_borrower`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `device_borrower` (
  `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The primary key Id is the patron''s barcode',
  `Email` varchar(320) NOT NULL,
  `Phone` varchar(10) NOT NULL,
  `Barcode` varchar(14) NOT NULL,
  `FirstName` varchar(45) NOT NULL DEFAULT 'FirstName',
  `LastName` varchar(75) NOT NULL DEFAULT 'LastName',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Barcode_UNIQUE` (`Barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_borrower`
--

LOCK TABLES `device_borrower` WRITE;
/*!40000 ALTER TABLE `device_borrower` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_borrower` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_has_accessories`
--

DROP TABLE IF EXISTS `device_has_accessories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `device_has_accessories` (
  `device_Id` int(11) NOT NULL,
  `accessories_Id` int(11) NOT NULL,
  PRIMARY KEY (`device_Id`,`accessories_Id`),
  KEY `fk_device_has_accessories_accessories1_idx` (`accessories_Id`),
  KEY `fk_device_has_accessories_device1_idx` (`device_Id`),
  CONSTRAINT `fk_device_has_accessories_accessories1` FOREIGN KEY (`accessories_Id`) REFERENCES `accessories` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_device_has_accessories_device1` FOREIGN KEY (`device_Id`) REFERENCES `device` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_has_accessories`
--

LOCK TABLES `device_has_accessories` WRITE;
/*!40000 ALTER TABLE `device_has_accessories` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_has_accessories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_loan`
--

DROP TABLE IF EXISTS `device_loan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `device_loan` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `DueDate` datetime NOT NULL,
  `Date_Out` datetime NOT NULL,
  `Date_In` datetime DEFAULT NULL,
  `ConditionOut_Front` varchar(150) NOT NULL,
  `ConditionOut_Back` varchar(150) NOT NULL,
  `ConditionOut_On` varchar(150) NOT NULL,
  `ConditionIn_Front` varchar(150) DEFAULT NULL,
  `ConditionIn_Back` varchar(150) DEFAULT NULL,
  `ConditionIn_On` varchar(150) DEFAULT NULL,
  `Accessories_Out` text,
  `Accessories_In` text,
  `EmployeeBarcode_Out` varchar(14) NOT NULL COMMENT 'Employee who assisted with the loaning process',
  `EmployeeBarcode_In` varchar(14) DEFAULT NULL,
  `Notes_Out` text,
  `Notes_In` text,
  `Signature` blob NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Device_Id` int(11) NOT NULL,
  `device_borrower_Id` int(11) NOT NULL,
  PRIMARY KEY (`Id`,`Device_Id`,`device_borrower_Id`),
  KEY `fk_Device_Loan_Device1_idx` (`Device_Id`),
  KEY `fk_device_loan_device_borrower1_idx` (`device_borrower_Id`),
  CONSTRAINT `fk_Device_Loan_Device1` FOREIGN KEY (`Device_Id`) REFERENCES `device` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_device_loan_device_borrower1` FOREIGN KEY (`device_borrower_Id`) REFERENCES `device_borrower` (`Id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_loan`
--

LOCK TABLES `device_loan` WRITE;
/*!40000 ALTER TABLE `device_loan` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_loan` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `inventory`.`device_loan_AFTER_INSERT`
AFTER INSERT ON `inventory`.`device_loan`
FOR EACH ROW
BEGIN
	if new.Date_In is null then
		update inventory.device set LoanAvailability = 0 where new.Device_Id = device.Id;
	end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `inventory`.`device_loan_AFTER_UPDATE`
AFTER UPDATE ON `inventory`.`device_loan`
FOR EACH ROW
BEGIN
	if old.Date_In is null and new.Date_In is not null then 
    update inventory.device set LoanAvailability = 1 where new.Device_Id = device.Id; 
    elseif old.Date_In is not null and new.Date_In is null then
    update inventory.device set LoanAvailability = 0 where new.Device_Id = device.Id;
    end if;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Dumping events for database 'inventory'
--

--
-- Dumping routines for database 'inventory'
--
/*!50003 DROP PROCEDURE IF EXISTS `delete_accessory` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_accessory`(IN IdArray VARCHAR(100))
BEGIN
        	DECLARE exit handler FOR SQLEXCEPTION, SQLWARNING
            BEGIN
        		ROLLBACK;
                RESIGNAL;
        	END;
            
        	SET SQL_SAFE_UPDATES=0; /*Required because otherwise error 'tried to update a table without WHERE that uses a KEY column*/
            
            START TRANSACTION;
        
        	delete dha
            from device_has_accessories AS dha
            where find_in_set(accessories_Id, IdArray);
            
            delete a
            from accessories AS a
            where find_in_set(Id, IdArray);
            
            COMMIT;
            
            SET SQL_SAFE_UPDATES=1;
        END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-07-05 22:19:30
