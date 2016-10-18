-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 24, 2016 at 11:59 PM
-- Server version: 5.6.28
-- PHP Version: 7.0.10


CREATE DATABASE `HighSide`;
USE `HighSide`;
--
-- Database: `HighSide`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--
DROP TABLE IF EXISTS `ANSWERS`;

CREATE TABLE `ANSWERS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `QUEST_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `ANSWER` VARCHAR(500) NOT NULL,
  `POINTS` int(11) NOT NULL DEFAULT '0',
  `DATE_ANSWERED` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--
DROP TABLE IF EXISTS `QUESTIONS`;

CREATE TABLE `QUESTIONS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ASKER_ID` int(11) NOT NULL,  
  `QUESTION_TITLE` VARCHAR(60) NOT NULL,
  `QUESTION_PHRASE` VARCHAR(500) NOT NULL,
  `DATE_ASKED` datetime NOT NULL ,
  `TAG1` VARCHAR(20) NOT NULL,
  `TAG2` VARCHAR(20),
  `TAG3` VARCHAR(20),
  `ANSWER_ID` int(11) NOT NULL DEFAULT '0',
  `POINTS` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `USERS`;

CREATE TABLE `USERS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` VARCHAR(20) NOT NULL,
  `PASSWORD` VARCHAR(100) NOT NULL,
  `KARMA_POINTS` int(11) NOT NULL DEFAULT '0',
  `LAST_ACTIVE` datetime NOT NULL,
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--
LOCK TABLES `USERS` WRITE;

INSERT INTO `USERS` (`ID`, `USERNAME`, `PASSWORD`, `KARMA_POINTS`, `LAST_ACTIVE`) VALUES
(1, 'admin', 'cs518pa$$', 99, '2016-09-24 16:44:13'),
(2, 'jbrunelle', 'M0n@rch$', 2, '2016-09-24 16:45:15'),
(3, 'pvenkman', 'imadoctor', 0, '2016-09-24 16:45:44'),
(4, 'rstantz', '"; INSERT INTO Customers (CustomerName,Address,City) Values(@0,@1,@2); --', 0, '2016-09-24 16:46:20'),
(5, 'dbarrett', 'fr1ed3GGS', 0, '2016-09-24 16:46:55'),
(6, 'ltully', '<!--<i>', 0, '2016-09-24 16:47:13'),
(7, 'espengler', 'don\'t cross the streams', 0, '2016-09-24 16:48:15'),
(8, 'janine', '--!drop tables;', 4, '2016-09-24 16:48:15'),
(9, 'winston', 'zeddM0r3', 12, '2016-09-24 16:48:57'),
(10, 'gozer', 'd3$truct0R', 43, '2016-09-24 16:48:57'),
(11, 'slimer', 'f33dM3', 55, '2016-09-24 16:49:32'),
(12, 'zuul', '105"; DROP TABLE', 7, '2016-09-24 16:49:32'),
(13, 'keymaster', 'n0D@na', 87, '2016-09-24 16:50:15'),
(14, 'gatekeeper', '$l0r', 1, '2016-09-24 16:50:15'),
(15, 'staypuft', 'm@r$hM@ll0w', 39, '2016-09-24 16:50:34'),
(16, 'okrand', '666777', 20, '2016-10-01 02:00:00');

UNLOCK TABLES;

ALTER TABLE USERS AUTO_INCREMENT = 17;

--
-- Dumping data for table `questions`
--
LOCK TABLES `QUESTIONS` WRITE;

INSERT INTO `QUESTIONS` (`ID`, `ASKER_ID`, `QUESTION_TITLE`, `QUESTION_PHRASE`, `TAG1`, `TAG2`, `TAG3`, `POINTS`, `DATE_ASKED`) VALUES 
(1, 2, 'Why does my bike not run?', 'So I tried to start my bike this morning and it just wouldnt start. Turned the key on and nothing. Why is this?', 'start', 'would not', 'motorcycle', 5, NOW()),
(2, 16, 'Mod recommendations for a long road trip?', 'I ride an FZ1 and I am trying to get it ready for a road trip. What mods do you guys recommend for a long road trip(2-3 months)?', 'road trip', 'modifications', 'fz1', 12, NOW());


UNLOCK TABLES;

ALTER TABLE QUESTIONS AUTO_INCREMENT = 3;
