-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: 192.168.0.80    Database: processloader_db
-- ------------------------------------------------------
-- Server version	5.7.19

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
  `id` int(11) NOT NULL,
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
  `Description` varchar(250) DEFAULT NULL,
  `Info` varchar(250) DEFAULT NULL,
  `latitudes` varchar(45) DEFAULT NULL,
  `longitudes` varchar(45) DEFAULT NULL,
  `datetime_of_insert` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DashboardWizard`

--
-- Table structure for table `Help_manager`
--

DROP TABLE IF EXISTS `Help_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Help_manager` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(250) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `accesses` int(11) DEFAULT '0',
  `type` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Help_manager`
--

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
INSERT INTO `MainMenu` VALUES (16,'KPI_editor.php','KPI Editor','fa fa-edit','KPI Editor','{RootAdmin}','any','no','samePage','#F98E35','KPI Editor'),(2,'file_archive.php','file_archive','fa fa-archive','Uploaded Resources','{RootAdmin,AreaManager,ToolAdmin}','any','no','samePage','#ff66ff','Process Loader: Uploaded Resources'),(3,'job_type.php','ProcessModels','fa fa-edit','Process Models','{RootAdmin,AreaManager,ToolAdmin}','any','no','samePage','#d84141','Process Loader: Process Models'),(4,'Process_loader.php','ProcessesExecution','fa fa-gear','Processes In Execution','{RootAdmin,AreaManager,ToolAdmin}','any','no','samePage','#71B2CF','Process Loader: Processes In Execution'),(5,'archive.php','ProcessArchive','fa fa-calendar','Process Execution Archive','{RootAdmin,AreaManager,ToolAdmin}','any','no','samePage','#59C0B9','Process Loader: Process Execution Archive'),(6,'upload_process_production.php','ProductionVsTest','fa fa-upload','Process: Test vs Production','{RootAdmin,ToolAdmin}','any','no','samePage','#82E0FF','Process Loader: Process: Test vs Production'),(7,'transfer_file_property.php','ManageOwnership','fa fa-gears','Manage Resources Ownership','{RootAdmin,ToolAdmin}','any','no','samePage','#9999FF','Process Loader: Manage Resources Ownership'),(8,'schedulers_mng.php','Schedulers','fa fa-database','Schedulers','{RootAdmin,ToolAdmin}','any','no','samePage','#FFCC00','Process Loader: Schedulers'),(9,'registrazione.php','UserRegister','fa fa-user-plus','Local Users Register','{RootAdmin,ToolAdmin}','any','no','samePage','#82C168','Process Loader: Local Users Register'),(10,'mapping_data.php','MappingData','fa fa-sitemap','Mapping Data','{RootAdmin,ToolAdmin}','any','no','samePage','#F6AD2D','Process Loader: Mapping Data'),(1,'page.php','page','fa fa-eye','View Resources','{RootAdmin,AreaManager,ToolAdmin, Manager,Public}','any','no','samePage','#ff9933','Process Loader: View Resources'),(11,'dataTypes_Users.php','DataTypevsUsers','fa fa-bar-chart','Data Types vs Users','{RootAdmin}','any','no','samePage','#AA3939','Data Types vs Users'),(12,'auditPersonalData.php','AuditPersonalData','fa fa-bar-chart','Audit Personal Data','{RootAdmin}','any','no','samePage','#2D518A','Audit Personal Data'),(13,'audit_violation.php','AuditViolation','fa fa-bar-chart','Audit Personal Data Violation','{RootAdmin}','any','no','samePage','#2A7E43','Audit Personal Data Violation'),(14,'help_manager.php','Help Manager','fa fa-bar-chart','Help Manager','{RootAdmin}','any','no','samePage','#ff66ff','Help Manager'),(15,'photo_service.php','External Services','fa fa-camera','Photo Service','{RootAdmin}','any','no','samePage','#59C0B9','Photo Service'),(17,'MicroService_editor.php','MicroService Editor','fa fa-gears','MicroService Editor','{AreaManager,ToolAdmin,RootAdmin}','any','no','samePage','#C36279','Micro Service Editor'),(18,'dataAnalyticMicroService_editor.php','Data Analitics Editor','fa fa-gears','Data Analitics Editor','{AreaManager,ToolAdmin,RootAdmin}','any','no','samePage','#7D9F35','Data Analitics Editor'),(19,'heatmap.php','Heatmap Manager','fa fa-bar-chart','Heatmap Manager','{AreaManager,RootAdmin}','any','no','samePage','#FFCB2F','Heatmap Manager'),(20,'colorMap.php','Color Map Editor','fa fa-gears','Color Map Editor','{RootAdmin}','any','no','samePage','#DCA7C8','Color Map Editor');
/*!40000 ALTER TABLE `MainMenu` ENABLE KEYS */;
UNLOCK TABLES;

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
  `RootAdmin` varchar(45) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `functionalities`
--

LOCK TABLES `functionalities` WRITE;
/*!40000 ALTER TABLE `functionalities` DISABLE KEYS */;
INSERT INTO `functionalities` VALUES (1,'View Public resources',1,1,1,1,'page.php','view','.mainContentCellCnt','1'),(2,'View Public resource details',1,1,1,1,'page.php','popoup','.dashboardsListCardClick2EditDiv','1'),(3,'Search a public resource by search label',1,1,1,1,'page.php','view','#dashboardListsSearchFilter','1'),(4,'Filter public resources by facet menu',1,1,1,1,'page.php','view','#facet-menu','1'),(5,'Download public resource by link',1,1,1,1,'page.php','view','.link-file','1'),(6,'Vote a resource',1,1,1,1,'page.php','view','.raty','1'),(7,'View own resources',1,1,0,0,'file_archive.php','view','#mainContentCnt','1'),(8,'View all resources',1,1,0,0,'file_archive.php','view','#mainContentCnt','1'),(9,'Upload a new resource',1,1,0,0,'file_archive.php','popup','.upload-resource','1'),(10,'Publish a resource',1,1,0,0,'file_archive.php','popup','.publish_jt','1'),(11,'Add metadata to resource',1,1,0,0,'file_archive.php','popup','.publish_jt','1'),(12,'Modify resource metadata',1,1,0,0,'file_archive.php','popup','.modify_jt','1'),(13,'Delete resource',1,1,0,0,'file_archive.php','popup','.delete_file','1'),(14,'Create a new Process model',1,1,0,0,'file_archive.php','popup','.crea_jt','1'),(15,'View own process models',1,1,0,0,'file_archive.php','view','.mostra_jt','1'),(16,'View all process models',1,1,0,0,'job_type.php','view','#mainContentCnt','1'),(17,'Create new instance',1,1,0,0,'job_type.php','popup','.crea_j','1'),(18,'View own instances',1,1,0,0,'job_type.php','view','.mostra_j','1'),(19,'View all instances',1,1,0,0,'Process_loader.php','view','#mainContentCnt','1'),(20,'View instances execution details',1,1,0,0,'Process_loader.php','popup','.view_det','1'),(21,'Start/Pause instance execution',1,1,0,0,'Process_loader.php','popup','.start_pause','1'),(22,'Delete instance',1,1,0,0,'Process_loader.php','popup','.rem_proc','1'),(23,'View own Process Execution Archive',1,1,0,0,'archive.php','view','#mainContentCnt','1'),(24,'View all Process Execution Archive',1,1,0,0,'archive.php','view','#mainContentCnt','1'),(25,'Move instance from test scheduler to production scheduler',1,0,0,0,'upload_process_production.php','popup','.load_menu','1'),(26,'Manage Resources Ownership',1,0,0,0,'transfer_file_property.php','popup','.transfer','1'),(27,'Add new scheduler ',1,0,0,0,'schedulers_mng.php','popup','#add_sch_btn','1'),(28,'Register new local user',1,0,0,0,'registrazione.php','view','#mainContentCnt','1'),(29,'Add new Data Mapping',1,0,0,0,'mapping_data.php','popup','#add_button','1'),(30,'edit Data Mapping Data',1,0,0,0,'mapping_data.php','popup','.update','1'),(31,'delete data mapping',1,0,0,0,'mapping_data.php','popup','.delete','1'),(32,'show username filter',1,0,0,0,'page.php','popup','.drop-Username','1'),(33,'view resource username (popup)',1,0,0,0,'page.php','popup','#itemUsername','1'),(34,'view resource username',1,0,0,0,'page.php','view','.dashboardsListUsernameDiv','1'),(35,'view status Published',1,1,0,0,'page.php','popup','#status_pub','1'),(36,'view column user file archive',1,0,0,0,'file_archive.php','view','.username_td','1'),(37,'view user Column',1,0,0,0,'Process_loader.php','view','.user_creator','1'),(38,'getAllResult',1,0,0,0,'getData.php','view',NULL,'1'),(39,'view all resources from solr',1,0,0,0,'page.php','view',NULL,'1'),(40,'edit metadata of resource',1,1,1,0,'page.php','popup','.editDashBtnCard_modify','1'),(41,'change resource status',1,1,1,0,'page.php','popup','.editDashBtnCard_publish','1'),(42,'view user publish status',1,0,0,0,'page.php','view','.user_status','1'),(43,'view status',0,1,1,1,'page.php','view','.myOwn_status','0'),(44,'change resource ownership',0,0,0,0,'page.php','popup','.editDashBtnCard_owner','1'),(45,'help_manager',0,0,0,0,'help_manager.php','view','#mainContentCnt','1'),(46,'data types',0,0,0,0,'dataTypes_Users.php','view',NULL,'1'),(47,'personal_data',0,0,0,0,'auditPersonalData.php','view',NULL,'1'),(48,'violations',0,0,0,0,'audit_violation.php','view',NULL,'1'),(49,'KPI_editor',0,0,0,0,'KPI_editor.php','view',NULL,'1'),(50,'Micro Service Editor',1,1,0,0,'MicroService_editor.php','view',NULL,'1'),(51,'Data Analityis Editor',1,1,0,0,'dataAnalyticMicroService_editor.php','view',NULL,'1'),(52,'Photo Service',0,0,0,0,'photo_service.php','view',NULL,'1'),(53,'Heatmap',0,1,0,0,'heatmap.php','view',NULL,'1'),(54,'ColorMap Server',0,0,0,0,'colorMap.php','view',NULL,'1');
/*!40000 ALTER TABLE `functionalities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `heatmap`
--

DROP TABLE IF EXISTS `heatmap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `heatmap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `unit` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `comment` text,
  `x1y1` varchar(200) DEFAULT NULL,
  `x2y2` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `heatmap`
--
--
-- Table structure for table `heatmap_values`
--

DROP TABLE IF EXISTS `heatmap_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `heatmap_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `heatmap_id` varchar(45) DEFAULT NULL,
  `value` varchar(45) DEFAULT NULL,
  `lat` varchar(200) DEFAULT NULL,
  `lng` varchar(200) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `heatmap_values`
--

--
-- Table structure for table `job_type`
--

DROP TABLE IF EXISTS `job_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_type` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `job_type_name` varchar(200) NOT NULL,
  `job_type_group` varchar(200) NOT NULL,
  `file_zip` varchar(200) NOT NULL,
  `file_name` varchar(50) NOT NULL,
  `type` varchar(200) NOT NULL,
  `start_time` varchar(50) NOT NULL,
  `end_time` varchar(50) NOT NULL,
  `Time_interval` varchar(50) NOT NULL,
  `creation_date` datetime NOT NULL,
  `job_type_description` varchar(200) NOT NULL,
  `url` varchar(100) NOT NULL,
  `path` varchar(200) NOT NULL,
  `e-mail` varchar(200) NOT NULL,
  `storeDurably` tinyint(1) NOT NULL,
  `non_concurrent` tinyint(1) NOT NULL,
  `requestRecovery` tinyint(1) NOT NULL,
  `Trigger_name` varchar(50) NOT NULL,
  `Trigger_group` varchar(50) NOT NULL,
  `Trigger_description` varchar(200) NOT NULL,
  `Priority` int(11) NOT NULL,
  `RepeatCount` int(11) NOT NULL,
  `ProcessParameter` text NOT NULL,
  `MisfireInstruction` varchar(200) NOT NULL,
  `Time_out` varchar(11) DEFAULT NULL,
  `DataMap` longtext NOT NULL,
  `NextJob` text NOT NULL,
  `JobConstraint` text NOT NULL,
  `file_position` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_type`
--
-- Table structure for table `kpi_values`
--

DROP TABLE IF EXISTS `kpi_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kpi_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kpi` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `value` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kpi_values`
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mappingtable`
--
--
-- Table structure for table `nature`
--

DROP TABLE IF EXISTS `nature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nature` (
  `id` int(11) NOT NULL,
  `nature` varchar(100) DEFAULT NULL,
  `sub_nature` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nature`
--

LOCK TABLES `nature` WRITE;
/*!40000 ALTER TABLE `nature` DISABLE KEYS */;
INSERT INTO `nature` VALUES (1,'Accommodation','Accomodation'),(2,'Accommodation','Agritourism'),(3,'Accommodation','Beach_resort'),(4,'Accommodation','Bed_and_breakfast'),(5,'Accommodation','Boarding_house'),(6,'Accommodation','Camping'),(7,'Accommodation','Day_care_centre'),(8,'Accommodation','Farm_house'),(9,'Accommodation','Historic_residence'),(10,'Accommodation','Holiday_village'),(11,'Accommodation','Hostel'),(12,'Accommodation','Hotel'),(13,'Accommodation','Mountain_shelter'),(14,'Accommodation','Other_accommodation'),(15,'Accommodation','Religiuos_guest_house'),(16,'Accommodation','Residence'),(17,'Accommodation','Rest_home'),(18,'Accommodation','Summer_camp'),(19,'Accommodation','Summer_residence'),(20,'Accommodation','Vacation_resort'),(21,'Advertising','Advertising_and_promotion'),(22,'Advertising','Market_polling'),(23,'AgricultureAndLivestock','Agricultural'),(24,'AgricultureAndLivestock','Animal_production'),(25,'AgricultureAndLivestock','Crop_animal_production_hunting'),(26,'AgricultureAndLivestock','Crop_production'),(27,'AgricultureAndLivestock','Fishing_and_aquaculture'),(28,'AgricultureAndLivestock','Hunting_trapping_and_services'),(29,'AgricultureAndLivestock','Support_animal_production'),(30,'AgricultureAndLivestock','Veterinary'),(31,'Assisstance','Discover the city'),(32,'Assisstance','Suggestion Near You'),(33,'check refresh',''),(34,'check refresh ADV',''),(35,'CivilAndEdilEngineering','Architectural_consulting'),(36,'CivilAndEdilEngineering','Building_construction'),(37,'CivilAndEdilEngineering','Cartographers'),(38,'CivilAndEdilEngineering','Civil Engineering'),(39,'CivilAndEdilEngineering','Civil_engineering'),(40,'CivilAndEdilEngineering','Engineering_consulting'),(41,'CivilAndEdilEngineering','Other_specialized_construction'),(42,'CivilAndEdilEngineering','Specialized_construction'),(43,'CivilAndEdilEngineering','Surveyor'),(44,'CivilAndEdilEngineering','Technical_consultants'),(45,'CulturalActivity','Archaeological_site'),(46,'CulturalActivity','Auditorium'),(47,'CulturalActivity','Botanical_and_zoological_gardens'),(48,'CulturalActivity','Castle'),(49,'CulturalActivity','Churches'),(50,'CulturalActivity','Cultural Activity'),(51,'CulturalActivity','Cultural_centre'),(52,'CulturalActivity','Cultural_sites'),(53,'CulturalActivity','Historical_buildings'),(54,'CulturalActivity','Journalist'),(55,'CulturalActivity','Leasing_of_intellectual_property'),(56,'CulturalActivity','Library'),(57,'CulturalActivity','Monument_location'),(58,'CulturalActivity','Motion_picture_and_television_programme_activities'),(59,'CulturalActivity','Museum'),(60,'CulturalActivity','News_agency'),(61,'CulturalActivity','Other_broadcasting'),(62,'CulturalActivity','Photographic_activities'),(63,'CulturalActivity','Printing_and_recorded_media'),(64,'CulturalActivity','Printing_and_services'),(65,'CulturalActivity','Publishing_activities'),(66,'CulturalActivity','Radio_broadcasting'),(67,'CulturalActivity','Reproduction_recorded_media'),(68,'CulturalActivity','Roman_bridge'),(69,'CulturalActivity','Sound_recording_and_music_publishing'),(70,'CulturalActivity','Squares'),(71,'CulturalActivity','Television_broadcasting'),(72,'CulturalActivity','Theatre'),(73,'CulturalActivity','Translation_and_interpreting'),(74,'EducationAndResearch','Automobile_driving_and_flying_schools'),(75,'EducationAndResearch','Conservatory'),(76,'EducationAndResearch','Cultural_education'),(77,'EducationAndResearch','Dance_schools'),(78,'EducationAndResearch','Diving_school'),(79,'EducationAndResearch ','Education and Research '),(80,'EducationAndResearch','Educational_support_activities'),(81,'EducationAndResearch','Higher_education'),(82,'EducationAndResearch','Language_courses'),(83,'EducationAndResearch','Performing_arts_schools'),(84,'EducationAndResearch','Post_secondary_education'),(85,'EducationAndResearch','Pre_primary_education'),(86,'EducationAndResearch','Primary_education'),(87,'EducationAndResearch','Private_high_school'),(88,'EducationAndResearch','Private_infant_school'),(89,'EducationAndResearch','Private_junior_high_school'),(90,'EducationAndResearch','Private_junior_school'),(91,'EducationAndResearch','Private_polytechnic_school'),(92,'EducationAndResearch','Private_preschool'),(93,'EducationAndResearch','Private_professional_institute'),(94,'EducationAndResearch','Public_high_school'),(95,'EducationAndResearch','Public_infant_school'),(96,'EducationAndResearch','Public_junior_high_school'),(97,'EducationAndResearch','Public_junior_school'),(98,'EducationAndResearch','Public_polytechnic_school'),(99,'EducationAndResearch','Public_professional_institute'),(100,'EducationAndResearch','Public_university'),(101,'EducationAndResearch','Research_and_development'),(102,'EducationAndResearch','Sailing_school'),(103,'EducationAndResearch','School_canteen'),(104,'EducationAndResearch','Secondary_education'),(105,'EducationAndResearch','Ski_school'),(106,'EducationAndResearch','Sports_and_recreation_education'),(107,'EducationAndResearch','Training_school'),(108,'EducationAndResearch','Training_school_for_teachers'),(109,'Emergency','Carabinieri'),(110,'Emergency','Civil Protection Alerts'),(111,'Emergency','Civil_protection'),(112,'Emergency','Coast_guard_harbormaster'),(113,'Emergency','Commissariat_of_public_safety'),(114,'Emergency','Corps_of_forest_rangers'),(115,'Emergency','Emergency'),(116,'Emergency','Emergency_medical_care'),(117,'Emergency','Emergency_services'),(118,'Emergency','Fire_brigade'),(119,'Emergency','First_aid'),(120,'Emergency','Italian_finance_police'),(121,'Emergency','Local_police'),(122,'Emergency','Rescuers'),(123,'Emergency','Shelter_area'),(124,'Emergency','Towing_and_roadside_assistance'),(125,'Emergency','Traffic_corps'),(126,'Emergency','Useful_numbers'),(127,'Entertainment','Amusement_activities'),(128,'Entertainment','Amusement_and_theme_parks'),(129,'Entertainment','Aquarium'),(130,'Entertainment','Beach'),(131,'Entertainment','Bench'),(132,'Entertainment','Betting_shops'),(133,'Entertainment','Boxoffice'),(134,'Entertainment','Cinema'),(135,'Entertainment','Climbing'),(136,'Entertainment','Discotheque'),(137,'Entertainment','Dog_area'),(138,'Entertainment','Entertainment'),(139,'Entertainment','Entertainment Locations'),(140,'Entertainment','Events of Entertainment'),(141,'Entertainment','Fishing_reserve'),(142,'Entertainment','Forest'),(143,'Entertainment','Gambling_and_betting'),(144,'Entertainment','Game_reserve'),(145,'Entertainment','Game_room'),(146,'Entertainment','Gardens'),(147,'Entertainment','Golf'),(148,'Entertainment','Green_areas'),(149,'Entertainment','Gym_fitness'),(150,'Entertainment','Hippodrome'),(151,'Entertainment','Marina'),(152,'Entertainment','National_park'),(153,'Entertainment','Natural_reserve'),(154,'Entertainment','Operation_of_casinos'),(155,'Entertainment','Pool'),(156,'Entertainment','Rafting_kayak'),(157,'Entertainment','Recreation_room'),(158,'Entertainment','Riding_stables'),(159,'Entertainment','Skiing_facility'),(160,'Entertainment','Smart_bench'),(161,'Entertainment','Social_centre'),(162,'Entertainment','Sports_clubs'),(163,'Entertainment','Sports_facility'),(164,'Entertainment','Sport_event_promoters'),(165,'Entertainment','Viewpoint'),(166,'Environment','Air_quality_monitoring_station'),(167,'Environment','Building_and_industrial_cleaning_activities'),(168,'Environment','Cleaning_activities'),(169,'Environment','Disinfecting_and_exterminating_activities'),(170,'Environment','Environment'),(171,'Environment','Forestry'),(172,'Environment','Geologists'),(173,'Environment','Green Areas'),(174,'Environment','Irrigator'),(175,'Environment','Landscape_care'),(176,'Environment','Materials_recovery'),(177,'Environment','Photovoltaic_system'),(178,'Environment','Pollen data '),(179,'Environment','Pollen_monitoring_station'),(180,'Environment','Pollution data'),(181,'Environment','Sewerage'),(182,'Environment','Smart_irrigator'),(183,'Environment','Smart_street_light'),(184,'Environment','Smart_waste_container'),(185,'Environment','Street_light'),(186,'Environment','Street_sweeping'),(187,'Environment','Waste_collection_and_treatment'),(188,'Environment','Water_resource'),(189,'Environment','Weather Forecast'),(190,'Environment','Weather_sensor'),(191,'Financial','Financial Service'),(192,'FinancialService','Accountants'),(193,'FinancialService','Atm'),(194,'FinancialService','Auditing_activities'),(195,'FinancialService','Bank'),(196,'FinancialService','Financial_institute'),(197,'FinancialService','Insurance'),(198,'FinancialService','Insurance_and_financial'),(199,'FinancialService','Labour_consultant'),(200,'FinancialService','Legal_office'),(201,'FinancialService','Tax_advice'),(202,'From Dashboard to IOT App','AgainTest'),(203,'From Dashboard to IOT App','ancoraprovadashboard'),(204,'From Dashboard to IOT App','AngeloApp'),(205,'From Dashboard to IOT App','AngeloApplication'),(206,'From Dashboard to IOT App','ChangeDashActTest'),(207,'From Dashboard to IOT App','ChangeTest2'),(208,'From Dashboard to IOT App','ClaudioApp'),(209,'From Dashboard to IOT App','ClaudioApplication'),(210,'From Dashboard to IOT App','DelTestDash'),(211,'From Dashboard to IOT App','DimmerTest'),(212,'From Dashboard to IOT App','Multiple IOT Apps'),(213,'From Dashboard to IOT App','NewDashboardTest'),(214,'From Dashboard to IOT App','NicolaApp'),(215,'From Dashboard to IOT App','NicolaApplication'),(216,'From Dashboard to IOT App','On road app dashboard'),(217,'From Dashboard to IOT App','On road operator app'),(218,'From Dashboard to IOT App','PaoloApp'),(219,'From Dashboard to IOT App','PaoloApplication'),(220,'From Dashboard to IOT App','PieroApp'),(221,'From Dashboard to IOT App','PieroApplication'),(222,'From Dashboard to IOT App','ProvaSettingsNew'),(223,'From Dashboard to IOT App','Snap4City - App dashboard'),(224,'From Dashboard to IOT App','Snap4City - App dashboard - Cloned'),(225,'From Dashboard to IOT App','Snap4City - City app'),(226,'From Dashboard to IOT App','Snap4CityApplication'),(227,'From Dashboard to IOT App','SwitchTest'),(228,'From Dashboard to IOT App','Z - Custom Private Dashboard'),(229,'From Dashboard to IOT App','zzzzzdrt'),(230,'From Dashboard to IOT App','zzzzzz22223452345'),(231,'From Dashboard to IOT Device','IoTSensor-Actuator'),(232,'From IOT App to Dashboard','0 - marazzinirootVisTest'),(233,'From IOT App to Dashboard','aaaa'),(234,'From IOT App to Dashboard','AaaTestWs'),(235,'From IOT App to Dashboard','AaaTestWs2'),(236,'From IOT App to Dashboard','AaaTestWs3'),(237,'From IOT App to Dashboard','aadd'),(238,'From IOT App to Dashboard','AddDashTest'),(239,'From IOT App to Dashboard','AngeloApp'),(240,'From IOT App to Dashboard','AngeloApplication'),(241,'From IOT App to Dashboard','ChangeDash1'),(242,'From IOT App to Dashboard','ChangeDashActTest'),(243,'From IOT App to Dashboard','ChangeDashboardTest'),(244,'From IOT App to Dashboard','ChangeDashTest'),(245,'From IOT App to Dashboard','ChangeTest1'),(246,'From IOT App to Dashboard','ClaudioApp'),(247,'From IOT App to Dashboard','ClaudioApplication'),(248,'From IOT App to Dashboard','ClaudioTest'),(249,'From IOT App to Dashboard','Dashboard - tester14'),(250,'From IOT App to Dashboard','DashboardTestNumer1'),(251,'From IOT App to Dashboard','ddddddd'),(252,'From IOT App to Dashboard','First Dashbaord fdgfgdfgor IOT '),(253,'From IOT App to Dashboard','First Dashbaord for IOT '),(254,'From IOT App to Dashboard','Multiple IOT Apps'),(255,'From IOT App to Dashboard','my test'),(256,'From IOT App to Dashboard','mysnap'),(257,'From IOT App to Dashboard','NewDashboardTest'),(258,'From IOT App to Dashboard','newdashdaricreated'),(259,'From IOT App to Dashboard','NewTest'),(260,'From IOT App to Dashboard','NicolaApp'),(261,'From IOT App to Dashboard','NicolaApplication'),(262,'From IOT App to Dashboard','NomeDiProvaSeLoApre'),(263,'From IOT App to Dashboard','NuovaDashBoardTest'),(264,'From IOT App to Dashboard','PaoloApp'),(265,'From IOT App to Dashboard','PaoloApplication'),(266,'From IOT App to Dashboard','pb3 dashboard'),(267,'From IOT App to Dashboard','pb3 dashboards'),(268,'From IOT App to Dashboard','PersonalDeviceGraph'),(269,'From IOT App to Dashboard','PieroApp'),(270,'From IOT App to Dashboard','PieroApplication'),(271,'From IOT App to Dashboard','provaDashboard'),(272,'From IOT App to Dashboard','ProvaSettingsNew'),(273,'From IOT App to Dashboard','RaspberryPersonalDash'),(274,'From IOT App to Dashboard','RaspberryPiPersonalSensorDash'),(275,'From IOT App to Dashboard','ReadDevice27Bis'),(276,'From IOT App to Dashboard','ReadDevice27Dash'),(277,'From IOT App to Dashboard','ReadDevice29Bis'),(278,'From IOT App to Dashboard','ReadDevice29Dash'),(279,'From IOT App to Dashboard','ReadDeviceDashboard'),(280,'From IOT App to Dashboard','ReadDeviceDashboard - Cloned'),(281,'From IOT App to Dashboard','ReadPersonalDeviceDash'),(282,'From IOT App to Dashboard','sadasdasd'),(283,'From IOT App to Dashboard','Second Dashboard for IOT'),(284,'From IOT App to Dashboard','Snap4CityApplication'),(285,'From IOT App to Dashboard','TemperaturaDiProva'),(286,'From IOT App to Dashboard','TemperaturaProva'),(287,'From IOT App to Dashboard','test                                                                                                                            '),(288,'From IOT App to Dashboard','test-dashboard-angelo'),(289,'From IOT App to Dashboard','tester3dash'),(290,'From IOT App to Dashboard','testOfName'),(291,'From IOT App to Dashboard','testOfName2'),(292,'From IOT App to Dashboard','Titolo'),(293,'From IOT App to Dashboard','Titolo a piacimento'),(294,'From IOT App to Dashboard','Titolo2'),(295,'From IOT App to Dashboard','Z - AccessToken'),(296,'From IOT App to Dashboard','Z - AddNewDash2'),(297,'From IOT App to Dashboard','Z - DashInternalTest'),(298,'From IOT App to Dashboard','Z - DisitInternalTest'),(299,'From IOT App to Dashboard','Z - DisitTest'),(300,'From IOT App to Dashboard','Z - IotAppsTest'),(301,'From IOT App to Dashboard','Z - IotAppsTest2'),(302,'From IOT App to Dashboard','Z - IotAppsViewer3'),(303,'From IOT App to Dashboard','Z - Snap4cityCountTestWs'),(304,'From IOT App to Dashboard','Z - Test add dash'),(305,'From IOT App to Dashboard','Z-Test'),(306,'From IOT App to Dashboard','zzsd'),(307,'From IOT App to Dashboard','ZZZ'),(308,'From IOT App to Dashboard','zzzzzwdfasdf'),(309,'From IOT Device to KB','IoTSensor'),(310,'Generic','Decision Support'),(311,'Government and Security','Critical city events'),(312,'Government and Security','Evacuation Plans'),(313,'Government and Security','Firenze WiFi Menu'),(314,'Government and Security','Firenze: Clusters of City User behaviors, WiFi data'),(315,'Government and Security','Firenze: Origin Destination Matrix Spider'),(316,'Government and Security','Firenze: Recency and Frequency from WiFi data'),(317,'Government and Security','Firenze: Tracking rescue teams'),(318,'Government and Security','Firenze: Trajectories from Mobile App'),(319,'Government and Security','Governmental Offices'),(320,'GovernmentOffice','Airport_lost_property_office'),(321,'GovernmentOffice','Cemetery'),(322,'GovernmentOffice','Civil_registry'),(323,'GovernmentOffice','Consulate'),(324,'GovernmentOffice','Department_of_motor_vehicles'),(325,'GovernmentOffice','District'),(326,'GovernmentOffice','Employment_exchange'),(327,'GovernmentOffice','Income_revenue_authority'),(328,'GovernmentOffice','Other_office'),(329,'GovernmentOffice','Police_headquarters'),(330,'GovernmentOffice','Postal_office'),(331,'GovernmentOffice','Prefecture'),(332,'GovernmentOffice','Public_relations_office'),(333,'GovernmentOffice','Social_security_service_office'),(334,'GovernmentOffice','Train_lost_property_office'),(335,'GovernmentOffice','Welfare_worker_office'),(336,'GovernmentOffice','Youth_information_centre'),(337,'HealthCare','Addiction_recovery_centre'),(338,'HealthCare','Aestestic_medicine_centre'),(339,'HealthCare','Assistance'),(340,'HealthCare','Community_centre'),(341,'HealthCare','Dentist'),(342,'HealthCare','Doctor_office'),(343,'HealthCare','Family_counselling'),(344,'HealthCare','First Aid data'),(345,'HealthCare','Group_practice'),(346,'HealthCare','Haircare_centres'),(347,'HealthCare','HealthCare'),(348,'HealthCare','Healthcare_centre'),(349,'HealthCare','Health_district'),(350,'HealthCare','Health_reservations_centre'),(351,'HealthCare','Human_health_activities'),(352,'HealthCare','Local_health_authority'),(353,'HealthCare','Medical_analysis_laboratories'),(354,'HealthCare','Mental_health_centre'),(355,'HealthCare','Paramedical_activities'),(356,'HealthCare','Pharmacy'),(357,'HealthCare','Physical_therapy_centre'),(358,'HealthCare','Poison_control_centre'),(359,'HealthCare','Private_clinic'),(360,'HealthCare','Psychologists'),(361,'HealthCare','Public_hospital'),(362,'HealthCare','Radiotherapy_centre'),(363,'HealthCare','Red_cross'),(364,'HealthCare','Residential_care_activities'),(365,'HealthCare','Senior_centre'),(366,'HealthCare','Social_work'),(367,'HealthCare','Surgical_activities'),(368,'HealthCare','Youth_assistance'),(369,'IndustryAndManufacturing','Animal_feeds_manufacture'),(370,'IndustryAndManufacturing','Beverage_manufacture'),(371,'IndustryAndManufacturing','Building_materials_manufacture'),(372,'IndustryAndManufacturing','Coke_and_petroleum_derivatives'),(373,'IndustryAndManufacturing','Computer_data_processing'),(374,'IndustryAndManufacturing','Computer_programming_and_consultancy'),(375,'IndustryAndManufacturing','Food_manufacture'),(376,'IndustryAndManufacturing','Footwear_manufacture'),(377,'IndustryAndManufacturing','Ict_service'),(378,'IndustryAndManufacturing','Industry and Manufacturing'),(379,'IndustryAndManufacturing','Installation_of_industrial_machinery'),(380,'IndustryAndManufacturing','Knitted_manufacture'),(381,'IndustryAndManufacturing','Leather_manufacture'),(382,'IndustryAndManufacturing','Machinery_repair_and_installation'),(383,'IndustryAndManufacturing','Manufacture_of_basic_metals'),(384,'IndustryAndManufacturing','Manufacture_of_chemicals_products'),(385,'IndustryAndManufacturing','Manufacture_of_clay_and_ceramic'),(386,'IndustryAndManufacturing','Manufacture_of_electrical_equipment'),(387,'IndustryAndManufacturing','Manufacture_of_electronic_products'),(388,'IndustryAndManufacturing','Manufacture_of_furniture'),(389,'IndustryAndManufacturing','Manufacture_of_glass'),(390,'IndustryAndManufacturing','Manufacture_of_jewellery_bijouterie'),(391,'IndustryAndManufacturing','Manufacture_of_machinery_and_equipment'),(392,'IndustryAndManufacturing','Manufacture_of_motor_vehicles'),(393,'IndustryAndManufacturing','Manufacture_of_musical_instruments'),(394,'IndustryAndManufacturing','Manufacture_of_non_metallic_mineral_products'),(395,'IndustryAndManufacturing','Manufacture_of_paper'),(396,'IndustryAndManufacturing','Manufacture_of_paper_products'),(397,'IndustryAndManufacturing','Manufacture_of_pharmaceutical_products'),(398,'IndustryAndManufacturing','Manufacture_of_plastics_products'),(399,'IndustryAndManufacturing','Manufacture_of_refined_petroleum_products'),(400,'IndustryAndManufacturing','Manufacture_of_refractory_products'),(401,'IndustryAndManufacturing','Manufacture_of_rubber_and_plastics_products'),(402,'IndustryAndManufacturing','Manufacture_of_rubber_products'),(403,'IndustryAndManufacturing','Manufacture_of_sports_goods'),(404,'IndustryAndManufacturing','Manufacture_of_structural_metal_products'),(405,'IndustryAndManufacturing','Manufacture_of_textiles'),(406,'IndustryAndManufacturing','Manufacture_of_toys_and_game'),(407,'IndustryAndManufacturing','Manufacture_of_transport_equipment'),(408,'IndustryAndManufacturing','Manufacture_of_travel_articles'),(409,'IndustryAndManufacturing','Manufacture_of_wearing_apparel'),(410,'IndustryAndManufacturing','Manufacture_of_wood'),(411,'IndustryAndManufacturing','Manufacture_of_wood_products'),(412,'IndustryAndManufacturing','Mining_support_services'),(413,'IndustryAndManufacturing','Other_manufacturing'),(414,'IndustryAndManufacturing','Quality_control_and_certification'),(415,'IndustryAndManufacturing','Sawmilling'),(416,'IndustryAndManufacturing','Software_publishing'),(417,'IndustryAndManufacturing','Specialized_design'),(418,'IndustryAndManufacturing','Stone_processing'),(419,'IndustryAndManufacturing','Tannery'),(420,'IndustryAndManufacturing','Technical_testing'),(421,'IndustryAndManufacturing','Textile_manufacturing'),(422,'IndustryAndManufacturing','Tobacco_industry'),(423,'IndustryAndManufacturing','Web_and_internet_provider'),(424,'Infrastructure',''),(425,'Infrastructure','DISCES'),(426,'Infrastructure','Engaged'),(427,'Infrastructure','Km4City User Statistics'),(428,'Infrastructure','Mobile App'),(429,'Infrastructure','Notifications'),(430,'Infrastructure','Smart CIty API'),(431,'Km4City Application','Km4City Web Application'),(432,'MarazziniTest5',''),(433,'MiningAndQuarrying','Extraction_of_salt'),(434,'MiningAndQuarrying','Mining and Quarrying'),(435,'MiningAndQuarrying','Mining_of_metal_ores'),(436,'MiningAndQuarrying','Other_mining_and_quarrying'),(437,'MiningAndQuarrying','Petroleum_and_natural_gas_extraction'),(438,'MiningAndQuarrying','Quarrying_of_stone_sand_and_clay'),(439,'Mobility and Transport','Airfields'),(440,'Mobility and Transport','Airplanes_rental'),(441,'Mobility and Transport','Bike_rack'),(442,'Mobility and Transport','Bike_rental'),(443,'Mobility and Transport','Bike_sharing_rack'),(444,'Mobility and Transport','Boats_and_ships_rental'),(445,'Mobility and Transport','Bollard'),(446,'Mobility and Transport','Bus Position Map'),(447,'Mobility and Transport','BusStop'),(448,'Mobility and Transport','Bus_tickets_retail'),(449,'Mobility and Transport','Cargo_handling'),(450,'Mobility and Transport','Car_park'),(451,'Mobility and Transport','Car_rental_with_driver'),(452,'Mobility and Transport','Charging_stations'),(453,'Mobility and Transport','Charter_airlines'),(454,'Mobility and Transport','Civil_airport'),(455,'Mobility and Transport','Controlled_parking_zone'),(456,'Mobility and Transport','Courier'),(457,'Mobility and Transport','Cycle_paths'),(458,'Mobility and Transport','Cycling paths'),(459,'Mobility and Transport','Disabled_parking_area'),(460,'Mobility and Transport','Fast_charging_station'),(461,'Mobility and Transport','Ferry_stop'),(462,'Mobility and Transport','Flight_companies'),(463,'Mobility and Transport','Freight_transport_and_furniture_removal'),(464,'Mobility and Transport','Fuel_station'),(465,'Mobility and Transport','Helipads'),(466,'Mobility and Transport','Land_transport'),(467,'Mobility and Transport','Land_transport_rental'),(468,'Mobility and Transport','Lifting_and_handling_equipment_rental'),(469,'Mobility and Transport','Logistics_activities'),(470,'Mobility and Transport','Monitoring_camera'),(471,'Mobility and Transport','Network Analysis'),(472,'Mobility and Transport','Operator events'),(473,'Mobility and Transport','Parking'),(474,'Mobility and Transport','Parking Free Spaces'),(475,'Mobility and Transport','Passenger_air_transport'),(476,'Mobility and Transport','Path Finder'),(477,'Mobility and Transport','Port'),(478,'Mobility and Transport','Postal_and_courier_activities'),(479,'Mobility and Transport','Public Transport Search'),(480,'Mobility and Transport','Public Transportation'),(481,'Mobility and Transport','PublicTransportLine'),(482,'Mobility and Transport','Quality of Service Public Transport'),(483,'Mobility and Transport','Reserved_lane'),(484,'Mobility and Transport','RTZgate'),(485,'Mobility and Transport','Rtz_daily'),(486,'Mobility and Transport','Rtz_nightly'),(487,'Mobility and Transport','SensorSite'),(488,'Mobility and Transport','Speed_camera'),(489,'Mobility and Transport','Support_activities_for_transportation'),(490,'Mobility and Transport','Taxi_company'),(491,'Mobility and Transport','Taxi_park'),(492,'Mobility and Transport','Ticket'),(493,'Mobility and Transport','TimeTable'),(494,'Mobility and Transport','Traffic city events'),(495,'Mobility and Transport','Traffic Flow Reconstruction'),(496,'Mobility and Transport','Traffic_light'),(497,'Mobility and Transport','Train_station'),(498,'Mobility and Transport','Tramline'),(499,'Mobility and Transport','Tramline Position'),(500,'Mobility and Transport','Tram_stops'),(501,'Mobility and Transport','Transfer Services and renting'),(502,'Mobility and Transport','Underpass'),(503,'Mobility and Transport','Urban_bus'),(504,'Mobility and Transport','Variable_message_sign'),(505,'Mobility and Transport','Vehicle_rental'),(506,'Mobility and Transport','Warehousing_and_storage'),(507,'Mobility and Transport','Water_transport'),(508,'myapp',''),(509,'nr1',''),(510,'nr21',''),(511,'nr22',''),(512,'nr25',''),(513,'nr26',''),(514,'nr27',''),(515,'nr28',''),(516,'nr29',''),(517,'nr30',''),(518,'nrklnn9',''),(519,'nrXX',''),(520,'personal data',''),(521,'prova-send',''),(522,'prova-sending',''),(523,'prova-sending-ADV',''),(524,'prova-subscribe',''),(525,'Services POI and IOT','Antwerp Overview (a part)'),(526,'Services POI and IOT','Garda Lake Overview (a part)'),(527,'Services POI and IOT','Google Map'),(528,'Services POI and IOT','Helsinki Overview (a part)'),(529,'Services POI and IOT','PISA view'),(530,'Services POI and IOT','Sardegna Overview (a part)'),(531,'Services POI and IOT','ServiceMAP'),(532,'Services POI and IOT','ServiceMAP3D'),(533,'Services POI and IOT','Toscana Overview (a part)'),(534,'Services POI and IOT','Venezia Overview (a part)'),(535,'Shopping','Shopping Services'),(536,'ShoppingAndService','Adult_clothing'),(537,'ShoppingAndService','Antiques'),(538,'ShoppingAndService','Artisan_shop'),(539,'ShoppingAndService','Art_galleries'),(540,'ShoppingAndService','Auctioning_houses'),(541,'ShoppingAndService','Audio_and_video'),(542,'ShoppingAndService','Beauty_centre'),(543,'ShoppingAndService','Boat_equipment'),(544,'ShoppingAndService','Bookshop'),(545,'ShoppingAndService','Building_material'),(546,'ShoppingAndService','Carpentry'),(547,'ShoppingAndService','Carpets'),(548,'ShoppingAndService','Carpets_and_curtains'),(549,'ShoppingAndService','Car_washing'),(550,'ShoppingAndService','Cleaning_materials'),(551,'ShoppingAndService','Clothing'),(552,'ShoppingAndService','Clothing_accessories'),(553,'ShoppingAndService','Clothing_and_linen'),(554,'ShoppingAndService','Clothing_children_and_infants'),(555,'ShoppingAndService','Clothing_factory_outlet'),(556,'ShoppingAndService','Coffee_rosters'),(557,'ShoppingAndService','Computer_systems'),(558,'ShoppingAndService','Computer_technician'),(559,'ShoppingAndService','Cultural_and_recreation_goods'),(560,'ShoppingAndService','Curtains_and_net_curtains'),(561,'ShoppingAndService','Dairy_products'),(562,'ShoppingAndService','Dating_service'),(563,'ShoppingAndService','Diet_products'),(564,'ShoppingAndService','Discount'),(565,'ShoppingAndService','Door_to_door'),(566,'ShoppingAndService','Estate_activities'),(567,'ShoppingAndService','Fine_arts_articles'),(568,'ShoppingAndService','Fish_and_seafood'),(569,'ShoppingAndService','Flower_shop'),(570,'ShoppingAndService','Food_and_tobacconist'),(571,'ShoppingAndService','Footwear_and_accessories'),(572,'ShoppingAndService','Footwear_and_leather_goods'),(573,'ShoppingAndService','Footwear_factory_outlet'),(574,'ShoppingAndService','Frozen_food'),(575,'ShoppingAndService','Fruit_and_vegetables'),(576,'ShoppingAndService','Funeral'),(577,'ShoppingAndService','Funeral_and_cemetery_articles'),(578,'ShoppingAndService','Fur_and_leather_clothing'),(579,'ShoppingAndService','Games_and_toys'),(580,'ShoppingAndService','Garden_and_agriculture'),(581,'ShoppingAndService','Gifts_and_smoking_articles'),(582,'ShoppingAndService','Haberdashery'),(583,'ShoppingAndService','Hairdressing'),(584,'ShoppingAndService','Hairdressing_and_beauty_treatment'),(585,'ShoppingAndService','Hardware_electrical_plumbing_and_heating'),(586,'ShoppingAndService','Hardware_paints_and_glass'),(587,'ShoppingAndService','Herbalists_shop'),(588,'ShoppingAndService','Household_appliances_shop'),(589,'ShoppingAndService','Household_articles'),(590,'ShoppingAndService','Household_fuel'),(591,'ShoppingAndService','Household_furniture'),(592,'ShoppingAndService','Household_products'),(593,'ShoppingAndService','Household_utensils'),(594,'ShoppingAndService','Hypermarket'),(595,'ShoppingAndService','Industrial_laundries'),(596,'ShoppingAndService','Jeweller'),(597,'ShoppingAndService','Jewellery'),(598,'ShoppingAndService','Laundries_and_dry_cleaners'),(599,'ShoppingAndService','Lighting'),(600,'ShoppingAndService','Maintenance_repair_of_motorcycles'),(601,'ShoppingAndService','Maintenance_repair_of_motor_vehicles'),(602,'ShoppingAndService','Manicure_and_pedicure'),(603,'ShoppingAndService','Meat_and_poultry'),(604,'ShoppingAndService','Mechanic_workshop'),(605,'ShoppingAndService','Medical_and_orthopaedic_goods'),(606,'ShoppingAndService','Minimarket'),(607,'ShoppingAndService','Motorcycles_parts_wholesale_and_retail'),(608,'ShoppingAndService','Motorcycles_wholesale_and_retail'),(609,'ShoppingAndService','Motor_vehicles_wholesale_and_retail'),(610,'ShoppingAndService','Musical_instruments_and_scores'),(611,'ShoppingAndService','Music_and_video_recordings'),(612,'ShoppingAndService','Newspapers_and_stationery'),(613,'ShoppingAndService','Non_food_large_retailers'),(614,'ShoppingAndService','Non_food_products'),(615,'ShoppingAndService','Office_furniture'),(616,'ShoppingAndService','Optics_and_photography'),(617,'ShoppingAndService','Other_goods'),(618,'ShoppingAndService','Other_retail_sale'),(619,'ShoppingAndService','Parties_and_ceremonies'),(620,'ShoppingAndService','Perfumery_and_cosmetic_articles'),(621,'ShoppingAndService','Personal_service_activities'),(622,'ShoppingAndService','Pet_care_services'),(623,'ShoppingAndService','Pet_shop'),(624,'ShoppingAndService','Pharmaceuticals'),(625,'ShoppingAndService','Pharmacy'),(626,'ShoppingAndService','Repair'),(627,'ShoppingAndService','Repair_musical_instruments'),(628,'ShoppingAndService','Repair_of_communication_equipment'),(629,'ShoppingAndService','Repair_of_consumer_electronics'),(630,'ShoppingAndService','Repair_of_footwear_and_leather_goods'),(631,'ShoppingAndService','Repair_of_garden_equipment'),(632,'ShoppingAndService','Repair_of_home_equipment'),(633,'ShoppingAndService','Repair_of_household_appliances'),(634,'ShoppingAndService','Repair_of_sporting_goods'),(635,'ShoppingAndService','Restorers'),(636,'ShoppingAndService','Retail_motor_vehicles_parts'),(637,'ShoppingAndService','Retail_sale_non_specialized_stores'),(638,'ShoppingAndService','Retail_trade'),(639,'ShoppingAndService','Rope_cord_and_twine'),(640,'ShoppingAndService','Sale_motor_vehicles_parts'),(641,'ShoppingAndService','Sale_of_motorcycles'),(642,'ShoppingAndService','Sale_of_motor_vehicles'),(643,'ShoppingAndService','Sale_of_motor_vehicles_and_motorcycles'),(644,'ShoppingAndService','Sale_via_mail_order_houses_or_via_internet'),(645,'ShoppingAndService','Sanitary_equipment'),(646,'ShoppingAndService','Second_hand_books'),(647,'ShoppingAndService','Second_hand_goods'),(648,'ShoppingAndService','Security_systems'),(649,'ShoppingAndService','Sexy_shop'),(650,'ShoppingAndService','Shopping_centre'),(651,'ShoppingAndService','Single_brand_store'),(652,'ShoppingAndService','Small_household_appliances'),(653,'ShoppingAndService','Souvenirs_craftwork_and_religious_articles'),(654,'ShoppingAndService','Sporting_equipment'),(655,'ShoppingAndService','Stalls_and_markets'),(656,'ShoppingAndService','Stalls_and_markets_of_clothing_and_footwear'),(657,'ShoppingAndService','Stalls_and_markets_of_food'),(658,'ShoppingAndService','Stalls_and_markets_other_goods'),(659,'ShoppingAndService','Stamps_and_coins'),(660,'ShoppingAndService','Supermarket'),(661,'ShoppingAndService','Tattoo_and_piercing'),(662,'ShoppingAndService','Telecommunications'),(663,'ShoppingAndService','Textiles_products'),(664,'ShoppingAndService','Tobacco_shop'),(665,'ShoppingAndService','Travel_goods'),(666,'ShoppingAndService','Trinkets'),(667,'ShoppingAndService','Underwear_knitwear_and_shirts'),(668,'ShoppingAndService','Upholsterer'),(669,'ShoppingAndService','Vacating_service'),(670,'ShoppingAndService','Vehicle_trade'),(671,'ShoppingAndService','Vending_machines'),(672,'ShoppingAndService','Wallpaper_and_floor_coverings'),(673,'ShoppingAndService','Weapons_and_ammunition'),(674,'ShoppingAndService','Wedding_favors'),(675,'ShoppingAndService','Wellness_centre'),(676,'Social','Firenze WiFi Clustering'),(677,'Social','Firenze WiFi HeatMap'),(678,'Social','Twitter Citations'),(679,'Social','Twitter Hashtags'),(680,'Social','Twitter Vigilance'),(681,'Social','Twitter Vigilance ads'),(682,'Social','Twitter Vigilance allertameteo'),(683,'Social','Twitter Vigilance apretoscana'),(684,'Social','Twitter Vigilance auereoporto'),(685,'Social','Twitter Vigilance CALDO'),(686,'Social','Twitter Vigilance cambiamenti climatici'),(687,'Social','Twitter Vigilance Cardboard'),(688,'Social','Twitter Vigilance Codified '),(689,'Social','Twitter Vigilance ComfortUS'),(690,'Social','Twitter Vigilance Consumo Suolo'),(691,'Social','Twitter Vigilance Emergenza Acqua'),(692,'Social','Twitter Vigilance Europeana'),(693,'Social','Twitter Vigilance EXPO2015'),(694,'Social','Twitter Vigilance Expo2015Toscana'),(695,'Social','Twitter Vigilance Firenze'),(696,'Social','Twitter Vigilance Firenze Fuochi'),(697,'Social','Twitter Vigilance Firenze ICT'),(698,'Social','Twitter Vigilance Firenze Nuova'),(699,'Social','Twitter Vigilance GeoComuni TOS'),(700,'Social','Twitter Vigilance Giubileo'),(701,'Social','Twitter Vigilance IENE'),(702,'Social','Twitter Vigilance Influenza'),(703,'Social','Twitter Vigilance iononrischio'),(704,'Social','Twitter Vigilance LAMMA'),(705,'Social','Twitter Vigilance LaudatoSI'),(706,'Social','Twitter Vigilance MajorCities2016'),(707,'Social','Twitter Vigilance Maltempo'),(708,'Social','Twitter Vigilance MeteoUSER'),(709,'Social','Twitter Vigilance Missione Rosetta'),(710,'Social','Twitter Vigilance MonitAllergie'),(711,'Social','Twitter Vigilance MRM'),(712,'Social','Twitter Vigilance Mugnone 2016'),(713,'Social','Twitter Vigilance MyMeteo'),(714,'Social','Twitter Vigilance NASA'),(715,'Social','Twitter Vigilance Nutella'),(716,'Social','Twitter Vigilance PA Environment'),(717,'Social','Twitter Vigilance PA Meteo news'),(718,'Social','Twitter Vigilance PA Meteo News Stud'),(719,'Social','Twitter Vigilance PA Prot Civile'),(720,'Social','Twitter Vigilance PA Social PA'),(721,'Social','Twitter Vigilance PAAlert'),(722,'Social','Twitter Vigilance PapaFrancesco'),(723,'Social','Twitter Vigilance Pisa'),(724,'Social','Twitter Vigilance Protezione Civile Toscana'),(725,'Social','Twitter Vigilance Real Time Fir'),(726,'Social','Twitter Vigilance Real Time Firenze'),(727,'Social','Twitter Vigilance Real Time maojrCities2016'),(728,'Social','Twitter Vigilance Real Time Mugnone 2016'),(729,'Social','Twitter Vigilance Real Time Mugnone Focus'),(730,'Social','Twitter Vigilance Resilience'),(731,'Social','Twitter Vigilance Rossano'),(732,'Social','Twitter Vigilance Siena'),(733,'Social','Twitter Vigilance SmartCity'),(734,'Social','Twitter Vigilance SmartCityBigData'),(735,'Social','Twitter Vigilance SOLO AllertaToscana'),(736,'Social','Twitter Vigilance Univ Firenze'),(737,'Social','Twitter Vigilance VarGoups'),(738,'Social','Twitter Vigilance VDA'),(739,'Social','Twitter Vigilance Venezia'),(740,'Social','Twitter Vigilance XFACTOR'),(741,'Social','Twitter Vigilance Zanzara'),(742,'Social','Twitter VigilanceTech'),(743,'Social','Twitter VigilanceTPL'),(744,'Social','Twitter VigilanceUBER'),(745,'Social','Wi-Fi'),(746,'testing-adv',''),(747,'testing-basic',''),(748,'TestSave',''),(749,'testsavebasic',''),(750,'Time','Current Time'),(751,'Tourism','Tourism Services'),(752,'TourismService','Beacon'),(753,'TourismService','Camper_service'),(754,'TourismService','Fresh_place'),(755,'TourismService','Pedestrian_zone'),(756,'TourismService','Ticket_sale'),(757,'TourismService','Toilet'),(758,'TourismService','Tourist_complaints_office'),(759,'TourismService','Tourist_guides'),(760,'TourismService','Tourist_information_office'),(761,'TourismService','Tourist_trail'),(762,'TourismService','Tour_operator'),(763,'TourismService','Travel_agency'),(764,'TourismService','Travel_bureau'),(765,'TourismService','Travel_information'),(766,'TourismService','Wifi'),(767,'TransferServiceAndRenting','Bike_sharing_rack'),(768,'TransferServiceAndRenting','Car_park'),(769,'TransferServiceAndRenting','Charging_stations'),(770,'TransferServiceAndRenting','SensorSite'),(771,'UtilitiesAndSupply','Accommodation_or_office_containers_rental'),(772,'UtilitiesAndSupply','Agents'),(773,'UtilitiesAndSupply','Associations'),(774,'UtilitiesAndSupply','Business_support'),(775,'UtilitiesAndSupply','Call_center'),(776,'UtilitiesAndSupply','Combined_facilities_support_activities'),(777,'UtilitiesAndSupply','Consulting_services'),(778,'UtilitiesAndSupply','Credit_collection_agencies'),(779,'UtilitiesAndSupply','Energy_supply'),(780,'UtilitiesAndSupply','Equipment_for_events_and_shows_rental'),(781,'UtilitiesAndSupply','Extraction_of_natural_gas'),(782,'UtilitiesAndSupply','Internet_point_and_public_telephone'),(783,'UtilitiesAndSupply','Internet_service_provider'),(784,'UtilitiesAndSupply','Investigation_activities'),(785,'UtilitiesAndSupply','Machinery_and_equipment_rental'),(786,'UtilitiesAndSupply','Management_consultancy'),(787,'UtilitiesAndSupply','Office_administrative_and_support_activities'),(788,'UtilitiesAndSupply','Organization_of_conventions_and_trade_shows'),(789,'UtilitiesAndSupply','Other_telecommunications_activities'),(790,'UtilitiesAndSupply','Packaging_activities'),(791,'UtilitiesAndSupply','Personal_and_household_goods_rental'),(792,'UtilitiesAndSupply','Private_security'),(793,'UtilitiesAndSupply','Recreational_and_sports_goods_rental'),(794,'UtilitiesAndSupply','Recruitment'),(795,'UtilitiesAndSupply','Reporting_agencies'),(796,'UtilitiesAndSupply','Secretarial_support_services'),(797,'UtilitiesAndSupply','Security_systems_service'),(798,'UtilitiesAndSupply','Temp_agency'),(799,'UtilitiesAndSupply','Utility and Supply'),(800,'UtilitiesAndSupply','Video_tapes_disks_rental'),(801,'UtilitiesAndSupply','Water_collection_treatment_and_supply'),(802,'Wholesale','Non_specialized_wholesale_trade'),(803,'Wholesale','Other_specialized_wholesale'),(804,'Wholesale','Wholesale'),(805,'Wholesale','Wholesale_agricultural_raw_materials_live_animals'),(806,'Wholesale','Wholesale_commission_trade'),(807,'Wholesale','Wholesale_food_beverages_tobacco'),(808,'Wholesale','Wholesale_household_goods'),(809,'Wholesale','Wholesale_ict_equipment'),(810,'Wholesale','Wholesale_machinery_equipmentent_supplies'),(811,'Wholesale','Wholesale_motor_vehicles_parts'),(812,'Wholesale','Wholesale_trade'),(813,'WineAndFood','Bakery'),(814,'WineAndFood','Bar'),(815,'WineAndFood','Canteens_and_food_service'),(816,'WineAndFood','Catering'),(817,'WineAndFood','Dining_hall'),(818,'WineAndFood','Drinking_fountain'),(819,'WineAndFood','Food_and_ice_cream_truck'),(820,'WineAndFood','Food_trade'),(821,'WineAndFood','Grill'),(822,'WineAndFood','Highway_stop'),(823,'WineAndFood','Ice_cream_parlour'),(824,'WineAndFood','Literary_cafe'),(825,'WineAndFood','Pastry_shop'),(826,'WineAndFood','Pizzeria'),(827,'WineAndFood','Restaurant'),(828,'WineAndFood','Sandwich_shop_pub'),(829,'WineAndFood','Small_shop'),(830,'WineAndFood','Sushi_bar'),(831,'WineAndFood','Take_away'),(832,'WineAndFood','Trattoria'),(833,'WineAndFood','Wine and Food'),(834,'WineAndFood','Wine_shop_and_wine_bar');
/*!40000 ALTER TABLE `nature` ENABLE KEYS */;
UNLOCK TABLES;

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
  `Process_name` varchar(200) NOT NULL,
  `Process_group` varchar(200) NOT NULL,
  `Description_activity` varchar(250) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=344 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `process_archive`
--
--
-- Table structure for table `processes`
--

DROP TABLE IF EXISTS `processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `processes` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Process_name` varchar(200) NOT NULL,
  `Process_group` varchar(200) NOT NULL,
  `job_type` varchar(200) NOT NULL,
  `Start_time` varchar(50) NOT NULL,
  `End_time` varchar(50) NOT NULL,
  `Time_interval` varchar(50) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Process_Type` varchar(100) NOT NULL,
  `Creation_date` datetime NOT NULL,
  `non_concurrent` tinyint(1) NOT NULL,
  `StoreDurably` tinyint(1) NOT NULL,
  `RequestRecovery` tinyint(1) NOT NULL,
  `Process_description` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `process_path` varchar(200) NOT NULL,
  `MisfireInstruction` varchar(200) NOT NULL,
  `Email` varchar(200) NOT NULL,
  `id_disces` varchar(200) NOT NULL,
  `trigger_name` varchar(50) NOT NULL,
  `trigger_group` varchar(50) NOT NULL,
  `trigger_description` varchar(250) NOT NULL,
  `priority` int(11) NOT NULL,
  `repeat_count` int(11) NOT NULL,
  `time_out` int(11) DEFAULT NULL,
  `dataMap` text NOT NULL,
  `nextJob` text NOT NULL,
  `JobConstraint` text NOT NULL,
  `ProcessParameter` text NOT NULL,
  `file_position` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=267 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `processes`
--
--
-- Table structure for table `schedulers`
--

DROP TABLE IF EXISTS `schedulers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedulers` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Ip_address` varchar(100) DEFAULT NULL,
  `repository` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `Description` varchar(200) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `data_integration_path` mediumtext,
  `process_path` varchar(250) DEFAULT NULL,
  `DDI_HOME` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedulers`
--
--
-- Table structure for table `uploaded_files`
--

DROP TABLE IF EXISTS `uploaded_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uploaded_files` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `File_name` varchar(150) NOT NULL,
  `Description` varchar(250) NOT NULL,
  `User` int(11) NOT NULL,
  `Creation_date` datetime NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `status` varchar(200) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Resource_input` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `Img` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  `Category` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `Format` varchar(45) DEFAULT 'ToBeDefined',
  `Protocol` varchar(500) DEFAULT 'ToBeDefined',
  `Realtime` tinyint(1) NOT NULL DEFAULT '0',
  `Periodic` tinyint(1) NOT NULL DEFAULT '0',
  `Public` tinyint(1) NOT NULL DEFAULT '0',
  `Date_of_publication` datetime DEFAULT NULL,
  `License` varchar(300) DEFAULT 'Private',
  `Download_number` int(11) NOT NULL DEFAULT '0',
  `Votes` int(11) NOT NULL,
  `Average_stars` float NOT NULL DEFAULT '0',
  `Total_stars` int(11) NOT NULL DEFAULT '0',
  `Url` varchar(500) DEFAULT NULL,
  `OS` varchar(100) DEFAULT NULL,
  `OpenSource` tinyint(1) DEFAULT '0',
  `Method` varchar(500) DEFAULT NULL,
  `Help` varchar(5000) DEFAULT NULL,
  `Html` longtext,
  `Js` longtext,
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM AUTO_INCREMENT=215 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploaded_files`
--
--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Role` varchar(50) NOT NULL,
  `Email` varchar(300) NOT NULL DEFAULT 'User mail',
  `Notes` varchar(500) NOT NULL DEFAULT 'Notes',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-11-06 10:33:15
