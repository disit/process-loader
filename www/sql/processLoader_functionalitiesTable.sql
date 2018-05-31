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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `functionalities`
--

LOCK TABLES `functionalities` WRITE;
/*!40000 ALTER TABLE `functionalities` DISABLE KEYS */;
INSERT INTO `functionalities` VALUES (1,'View Public resources',1,1,1,1,'page.php','view','.mainContentCellCnt'),(2,'View Public resource details',1,1,1,1,'page.php','popoup','.dashboardsListCardClick2EditDiv'),(3,'Search a public resource by search label',1,1,1,1,'page.php','view','#dashboardListsSearchFilter'),(4,'Filter public resources by facet menu',1,1,1,1,'page.php','view','#facet-menu'),(5,'Download public resource by link',1,1,1,1,'page.php','view','.link-file'),(6,'Vote a resource',1,1,1,1,'page.php','view','.raty'),(7,'View own resources',1,1,1,0,'file_archive.php','view','#mainContentCnt'),(8,'View all resources',1,1,1,0,'file_archive.php','view','#mainContentCnt'),(9,'Upload a new resource',1,1,1,0,'file_archive.php','popup','.upload-resource'),(10,'Publish a resource',1,1,1,0,'file_archive.php','popup','.publish_jt'),(11,'Add metadata to resource',1,1,1,0,'file_archive.php','popup','.publish_jt'),(12,'Modify resource metadata',1,1,1,0,'file_archive.php','popup','.modify_jt'),(13,'Delete resource',1,1,1,0,'file_archive.php','popup','.delete_file'),(14,'Create a new Process model',1,1,1,0,'file_archive.php','popup','.crea_jt'),(15,'View own process models',1,1,1,0,'file_archive.php','view','.mostra_jt'),(16,'View all process models',1,1,1,0,'job_type.php','view','#mainContentCnt'),(17,'Create new instance',1,1,1,0,'job_type.php','popup','.crea_j'),(18,'View own instances',1,1,1,0,'job_type.php','view','.mostra_j'),(19,'View all instances',1,1,1,0,'Process_loader.php','view','#mainContentCnt'),(20,'View instances execution details',1,1,1,0,'Process_loader.php','popup','.view_det'),(21,'Start/Pause instance execution',1,1,1,0,'Process_loader.php','popup','.start_pause'),(22,'Delete instance',1,1,1,0,'Process_loader.php','popup','.rem_proc'),(23,'View own Process Execution Archive',1,1,1,0,'archive.php','view','#mainContentCnt'),(24,'View all Process Execution Archive',1,1,1,0,'archive.php','view','#mainContentCnt'),(25,'Move instance from test scheduler to production scheduler',1,0,0,0,'upload_process_production.php','popup','.load_menu'),(26,'Manage Resources Ownership',1,0,0,0,'transfer_file_property.php','popup','.transfer'),(27,'Add new scheduler ',1,0,0,0,'schedulers_mng.php','popup','#add_sch_btn'),(28,'Register new local user',1,0,0,0,'registrazione.php','view','#mainContentCnt');
/*!40000 ALTER TABLE `functionalities` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-05-10 12:28:23
