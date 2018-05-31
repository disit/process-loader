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
-- Dumping data for table `MainMenu`
--

LOCK TABLES `MainMenu` WRITE;
/*!40000 ALTER TABLE `MainMenu` DISABLE KEYS */;
INSERT INTO `MainMenu` VALUES (1,'page.php','page','fa fa-eye','View Resources','{Public}','any','no','samePage','#ff9933','Process Loader: View Resources'),(2,'file_archive.php','file_archive','fa fa-archive','Uploaded Resources','{AreaManager,Manager,ToolAdmin}','any','no','samePage','#ff66ff','Process Loader: Uploaded Resources'),(3,'job_type.php','ProcessModels','fa fa-edit','Process Models','{AreaManager,Manager,ToolAdmin}','any','no','samePage','#d84141','Process Loader: Process Models'),(4,'Process_loader.php','ProcessesExecution','fa fa-gear','Processes In Execution','{AreaManager,Manager,ToolAdmin}','any','no','samePage','#71B2CF','Process Loader: Processes In Execution'),(5,'archive.php','ProcessArchive','fa fa-calendar','Process Execution Archive','{AreaManager,Manager,ToolAdmin}','any','no','samePage','#59C0B9','Process Loader: Process Execution Archive'),(6,'upload_process_production.php','ProductionVsTest','fa fa-upload','Process: Test vs Production','{ToolAdmin}','any','no','samePage','#82E0FF','Process Loader: Process: Test vs Production'),(7,'transfer_file_property.php','ManageOwnership','fa fa-gears','Manage Resources Ownership','{ToolAdmin}','any','no','samePage','#9999FF','Process Loader: Manage Resources Ownership'),(8,'schedulers_mng.php','Schedulers','fa fa-database','Schedulers','{ToolAdmin}','any','no','samePage','#FFCC00','Process Loader: Schedulers'),(9,'registrazione.php','UserRegister','fa fa-user-plus','Local Users Register','{ToolAdmin}','any','no','samePage','#82C168','Process Loader: Local Users Register');
/*!40000 ALTER TABLE `MainMenu` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-10 11:18:06
