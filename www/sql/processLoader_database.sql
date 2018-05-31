-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: 192.168.56.200    Database: processloader_db
-- ------------------------------------------------------
-- Server version	5.6.36

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
-- Table structure for table `DashboardWizard`
--

DROP TABLE IF EXISTS `DashboardWizard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DashboardWizard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nature` varchar(128) DEFAULT NULL,
  `high_level_type` varchar(128) DEFAULT NULL,
  `sub_nature` varchar(128) DEFAULT NULL,
  `low_level_type` varchar(128) DEFAULT NULL,
  `unique_name_id` varchar(128) DEFAULT NULL,
  `instance_uri` varchar(256) DEFAULT NULL,
  `get_instances` varchar(128) DEFAULT NULL,
  `last_date` datetime DEFAULT NULL,
  `last_value` varchar(128) DEFAULT NULL,
  `unit` varchar(128) DEFAULT NULL,
  `metric` varchar(128) DEFAULT NULL,
  `saved_direct` varchar(128) DEFAULT NULL,
  `kb_based` varchar(128) DEFAULT NULL,
  `sm_based` varchar(128) DEFAULT NULL,
  `user` varchar(128) DEFAULT NULL,
  `widgets` varchar(128) DEFAULT NULL,
  `parameters` varchar(512) DEFAULT NULL,
  `healthiness` varchar(128) NOT NULL,
  `microAppExtServIcon` varchar(100) DEFAULT NULL,
  `lastCheck` datetime DEFAULT NULL,
  `ownership` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueKey` (`high_level_type`,`sub_nature`,`low_level_type`,`unique_name_id`,`instance_uri`)
) ENGINE=InnoDB AUTO_INCREMENT=2695 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MainMenu`
--

DROP TABLE IF EXISTS `MainMenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MainMenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `linkUrl` varchar(200) NOT NULL,
  `linkId` varchar(200) DEFAULT NULL,
  `icon` varchar(200) DEFAULT NULL,
  `text` varchar(200) DEFAULT NULL,
  `privileges` text,
  `userType` varchar(45) DEFAULT 'any',
  `externalApp` varchar(3) DEFAULT 'no',
  `openMode` varchar(45) DEFAULT 'newTab',
  `iconColor` varchar(45) DEFAULT '#FFFFFF',
  `pageTitle` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=314 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `functionalities`
--

DROP TABLE IF EXISTS `functionalities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `functionalities` (
  `id` int(11) NOT NULL,
  `functionality` varchar(200) DEFAULT '0',
  `ToolAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `AreaManager` tinyint(1) NOT NULL DEFAULT '0',
  `Manager` tinyint(1) NOT NULL DEFAULT '0',
  `Public` tinyint(1) NOT NULL DEFAULT '0',
  `link` varchar(200) DEFAULT NULL,
  `view` varchar(40) DEFAULT NULL,
  `class` varchar(200) DEFAULT NULL,
  `RootAdmin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `job_type`
--

DROP TABLE IF EXISTS `job_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_type` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `job_type_name` varchar(200) CHARACTER SET latin1 NOT NULL,
  `job_type_group` varchar(200) CHARACTER SET latin1 NOT NULL,
  `file_zip` varchar(200) CHARACTER SET latin1 NOT NULL,
  `file_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `type` varchar(200) CHARACTER SET latin1 NOT NULL,
  `start_time` varchar(50) CHARACTER SET latin1 NOT NULL,
  `end_time` varchar(50) CHARACTER SET latin1 NOT NULL,
  `Time_interval` varchar(50) CHARACTER SET latin1 NOT NULL,
  `creation_date` datetime NOT NULL,
  `job_type_description` varchar(200) CHARACTER SET latin1 NOT NULL,
  `url` varchar(100) CHARACTER SET latin1 NOT NULL,
  `path` varchar(200) CHARACTER SET latin1 NOT NULL,
  `e-mail` varchar(200) CHARACTER SET latin1 NOT NULL,
  `storeDurably` tinyint(1) NOT NULL,
  `non_concurrent` tinyint(1) NOT NULL,
  `requestRecovery` tinyint(1) NOT NULL,
  `Trigger_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `Trigger_group` varchar(50) CHARACTER SET latin1 NOT NULL,
  `Trigger_description` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Priority` int(11) NOT NULL,
  `RepeatCount` int(11) NOT NULL,
  `ProcessParameter` text CHARACTER SET latin1 NOT NULL,
  `MisfireInstruction` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Time_out` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
  `DataMap` longtext CHARACTER SET latin1 NOT NULL,
  `NextJob` text CHARACTER SET latin1 NOT NULL,
  `JobConstraint` text CHARACTER SET latin1 NOT NULL,
  `file_position` varchar(500) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mappingtable`
--

DROP TABLE IF EXISTS `mappingtable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mappingtable` (
  `source` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `source_UNIQUE` (`source`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `process_archive`
--

DROP TABLE IF EXISTS `process_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `process_archive` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Activity_date` datetime NOT NULL,
  `Process_id` int(11) NOT NULL,
  `Process_name` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Process_group` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Description_activity` varchar(250) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=794 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `processes`
--

DROP TABLE IF EXISTS `processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `processes` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Process_name` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Process_group` varchar(200) CHARACTER SET latin1 NOT NULL,
  `job_type` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Start_time` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `End_time` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  `Time_interval` varchar(50) CHARACTER SET latin1 NOT NULL,
  `Status` varchar(50) CHARACTER SET latin1 NOT NULL,
  `Process_Type` varchar(100) CHARACTER SET latin1 NOT NULL,
  `Creation_date` datetime NOT NULL,
  `non_concurrent` tinyint(1) NOT NULL,
  `StoreDurably` tinyint(1) NOT NULL,
  `RequestRecovery` tinyint(1) NOT NULL,
  `Process_description` varchar(200) CHARACTER SET latin1 NOT NULL,
  `url` varchar(200) CHARACTER SET latin1 NOT NULL,
  `process_path` varchar(200) CHARACTER SET latin1 NOT NULL,
  `MisfireInstruction` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Email` varchar(200) CHARACTER SET latin1 NOT NULL,
  `id_disces` varchar(200) CHARACTER SET latin1 NOT NULL,
  `trigger_name` varchar(50) CHARACTER SET latin1 NOT NULL,
  `trigger_group` varchar(50) CHARACTER SET latin1 NOT NULL,
  `trigger_description` varchar(250) CHARACTER SET latin1 NOT NULL,
  `priority` int(11) NOT NULL,
  `repeat_count` int(11) NOT NULL,
  `time_out` varchar(11) CHARACTER SET latin1 DEFAULT NULL,
  `dataMap` text CHARACTER SET latin1 NOT NULL,
  `nextJob` text CHARACTER SET latin1 NOT NULL,
  `JobConstraint` text CHARACTER SET latin1 NOT NULL,
  `ProcessParameter` text CHARACTER SET latin1 NOT NULL,
  `file_position` varchar(500) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=492 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `schedulers`
--

DROP TABLE IF EXISTS `schedulers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedulers` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Ip_address` varchar(100) CHARACTER SET latin1 NOT NULL,
  `repository` varchar(100) CHARACTER SET latin1 NOT NULL,
  `type` varchar(50) CHARACTER SET latin1 NOT NULL,
  `Description` varchar(250) CHARACTER SET latin1 NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `data_integration_path` text CHARACTER SET latin1 NOT NULL,
  `process_path` varchar(250) CHARACTER SET latin1 NOT NULL,
  `DDI_HOME` varchar(200) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `uploaded_files`
--

DROP TABLE IF EXISTS `uploaded_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uploaded_files` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `File_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `Description` varchar(250) CHARACTER SET latin1 NOT NULL,
  `User` int(11) NOT NULL,
  `Creation_date` datetime NOT NULL,
  `file_type` varchar(100) CHARACTER SET latin1 NOT NULL,
  `status` varchar(200) CHARACTER SET latin1 NOT NULL,
  `Username` varchar(100) CHARACTER SET latin1 NOT NULL,
  `Resource_input` varchar(300) CHARACTER SET latin1 DEFAULT 'ToBeDefined',
  `Img` varchar(60) CHARACTER SET latin1 DEFAULT NULL,
  `Category` varchar(300) CHARACTER SET latin1 DEFAULT 'ToBeDefined',
  `Format` varchar(45) CHARACTER SET latin1 DEFAULT 'ToBeDefined',
  `Protocol` varchar(500) CHARACTER SET latin1 DEFAULT 'ToBeDefined',
  `Realtime` tinyint(1) DEFAULT '0',
  `Periodic` tinyint(1) DEFAULT '0',
  `Public` tinyint(1) NOT NULL DEFAULT '0',
  `Date_of_publication` datetime DEFAULT '0000-00-00 00:00:00',
  `License` varchar(300) CHARACTER SET latin1 DEFAULT 'Private',
  `Download_number` int(11) DEFAULT '0',
  `Votes` int(11) DEFAULT NULL,
  `Average_stars` float DEFAULT '0',
  `Total_stars` int(11) DEFAULT '0',
  `Url` varchar(500) DEFAULT NULL,
  `OS` varchar(100) DEFAULT NULL,
  `OpenSource` tinyint(1) DEFAULT '0',
  `Method` varchar(500) DEFAULT NULL,
  `Help` varchar(5000) DEFAULT NULL,
  `Html` longtext,
  `Js` longtext,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=629 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(20) CHARACTER SET latin1 NOT NULL,
  `Password` varchar(50) CHARACTER SET latin1 NOT NULL,
  `Role` varchar(50) CHARACTER SET latin1 NOT NULL,
  `Email` varchar(300) CHARACTER SET latin1 NOT NULL DEFAULT 'mail utente',
  `Notes` varchar(500) CHARACTER SET latin1 NOT NULL DEFAULT 'Note riguardanti utente',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-31 14:20:46
