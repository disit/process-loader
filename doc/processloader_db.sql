-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Creato il: Set 15, 2017 alle 10:52
-- Versione del server: 5.7.11
-- Versione PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `processloader_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `job_type`
--

CREATE TABLE `job_type` (
  `Id` int(11) NOT NULL,
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
  `file_position` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `processes`
--

CREATE TABLE `processes` (
  `Id` int(11) NOT NULL,
  `Process_name` varchar(200) NOT NULL,
  `Process_group` varchar(200) NOT NULL,
  `job_type` varchar(200) NOT NULL,
  `Start_time` varchar(50) DEFAULT NULL,
  `End_time` varchar(50) DEFAULT NULL,
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
  `time_out` varchar(11) DEFAULT NULL,
  `dataMap` text NOT NULL,
  `nextJob` text NOT NULL,
  `JobConstraint` text NOT NULL,
  `ProcessParameter` text NOT NULL,
  `file_position` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `process_archive`
--

CREATE TABLE `process_archive` (
  `Id` int(11) NOT NULL,
  `Activity_date` datetime NOT NULL,
  `Process_id` int(11) NOT NULL,
  `Process_name` varchar(200) NOT NULL,
  `Process_group` varchar(200) NOT NULL,
  `Description_activity` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `schedulers`
--

CREATE TABLE `schedulers` (
  `Id` int(11) NOT NULL,
  `Ip_address` varchar(100) NOT NULL,
  `repository` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `Description` varchar(250) NOT NULL,
  `name` varchar(100) NOT NULL,
  `data_integration_path` text NOT NULL,
  `process_path` varchar(250) NOT NULL,
  `DDI_HOME` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `uploaded_files`
--

CREATE TABLE `uploaded_files` (
  `Id` int(11) NOT NULL,
  `File_name` varchar(50) NOT NULL,
  `Description` varchar(250) NOT NULL,
  `User` int(11) NOT NULL,
  `Creation_date` datetime NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `status` varchar(200) NOT NULL,
  `Username` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Username` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Role` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `job_type`
--
ALTER TABLE `job_type`
  ADD PRIMARY KEY (`Id`);

--
-- Indici per le tabelle `processes`
--
ALTER TABLE `processes`
  ADD PRIMARY KEY (`Id`);

--
-- Indici per le tabelle `process_archive`
--
ALTER TABLE `process_archive`
  ADD PRIMARY KEY (`Id`);

--
-- Indici per le tabelle `schedulers`
--
ALTER TABLE `schedulers`
  ADD PRIMARY KEY (`Id`);

--
-- Indici per le tabelle `uploaded_files`
--
ALTER TABLE `uploaded_files`
  ADD PRIMARY KEY (`Id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `job_type`
--
ALTER TABLE `job_type`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT per la tabella `processes`
--
ALTER TABLE `processes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=455;
--
-- AUTO_INCREMENT per la tabella `process_archive`
--
ALTER TABLE `process_archive`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=704;
--
-- AUTO_INCREMENT per la tabella `schedulers`
--
ALTER TABLE `schedulers`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT per la tabella `uploaded_files`
--
ALTER TABLE `uploaded_files`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;
--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
