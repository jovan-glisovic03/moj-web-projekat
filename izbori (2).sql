-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 23, 2026 at 03:33 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `izbori`
--
CREATE DATABASE IF NOT EXISTS `izbori` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `izbori`;

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

DROP TABLE IF EXISTS `korisnici`;
CREATE TABLE IF NOT EXISTS `korisnici` (
  `IDKorisnika` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `Ime` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Prezime` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Telefon` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Sifra` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Adresa` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/',
  `Slika` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Tip` int NOT NULL COMMENT '0-admin, 1-analitičar, 2-kontrolor',
  PRIMARY KEY (`IDKorisnika`),
  UNIQUE KEY `Telefon` (`Telefon`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `Sifra` (`Sifra`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`IDKorisnika`, `Ime`, `Prezime`, `Telefon`, `Email`, `Sifra`, `Adresa`, `Slika`, `Tip`) VALUES
(1, 'Jovan', 'Glišović', NULL, 'jovan@gmail.com', 'jovan123', '/', NULL, 0),
(13, 'Petar', 'Popovic', NULL, 'popovic@gmail.com', 'petar123', '/', NULL, 2),
(12, 'Marko', 'Jankovic', NULL, 'marko@gmail.com', 'marko123', '/', NULL, 2),
(14, 'Ana', 'Antic', NULL, 'ana@gmail.com', 'ana123', '/', NULL, 1),
(22, 'Veljko', 'Matic', NULL, 'veljko@gmail.com', 'veljko123', '/', NULL, 2),
(16, 'Nikola', 'Petrovic', NULL, 'nikola@gmail.com', 'nikola123', '/', NULL, 1),
(17, 'Milica', 'Jovanovic', NULL, 'milica@gmail.com', 'milica123', '/', NULL, 1),
(18, 'Stefan', 'Pantic', NULL, 'stefan@gmail.com', 'stefan123', '/', NULL, 1),
(20, 'Ilija', 'Rakic', NULL, 'ilija@gmail.com', 'ilija123', '/', NULL, 2),
(23, 'Katarina', 'Petrovic', NULL, 'katarina@gmail.com', 'katarina123', '/', NULL, 2),
(29, 'Lana', 'Radic', NULL, 'lana@gmail.com', 'lana123', '/', NULL, 2),
(28, 'Marina', 'Markovic', NULL, 'marina@gmail.com', 'marina123', '/', NULL, 2),
(30, 'Milica', 'Đurđević Stamenkovski', NULL, 'zavetnici@gmail.com', 'zavetnici123', '/', NULL, 1),
(31, 'Slavica', 'Đukic Dejanovic', NULL, 'slavica@gmail.com', 'slavica123', '/', '', 1),
(33, 'Aleksandar', 'Sapic', NULL, 'sapic@gmail.com', 'sapic123', '/', NULL, 2),
(34, 'Brna', 'Anabic', NULL, 'anabic@gmail.com', 'anabic123', '/', NULL, 2),
(35, 'Gasic', '', NULL, 'gasic@gmail.com', 'gasic123', '/', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `opstinegradovi`
--

DROP TABLE IF EXISTS `opstinegradovi`;
CREATE TABLE IF NOT EXISTS `opstinegradovi` (
  `IDOpstinaGrad` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `Naziv` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BrojGlasaca` int UNSIGNED DEFAULT '0',
  `BrojIzaslih` int UNSIGNED DEFAULT '0',
  `BrojNevazecihListica` int UNSIGNED DEFAULT '0',
  `BrojGlasovaPoPartijama` text COLLATE utf8mb4_unicode_ci,
  `PrebrojaniGlasaci` decimal(5,2) DEFAULT '0.00',
  `PrebrojanaIzbornaMesta` decimal(5,2) DEFAULT '0.00',
  PRIMARY KEY (`IDOpstinaGrad`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `opstinegradovi`
--

INSERT INTO `opstinegradovi` (`IDOpstinaGrad`, `Naziv`, `BrojGlasaca`, `BrojIzaslih`, `BrojNevazecihListica`, `BrojGlasovaPoPartijama`, `PrebrojaniGlasaci`, `PrebrojanaIzbornaMesta`) VALUES
(1, 'Beograd', 0, 0, 0, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 0.00, 0.00),
(2, 'Kragujevac', 0, 0, 0, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 0.00, 0.00),
(3, 'Cacak', 0, 0, 0, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 0.00, 0.00),
(4, 'Zrenjanin', 0, 0, 0, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 0.00, 0.00),
(8, 'Knjazevac', 0, 0, 0, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 0.00, 0.00),
(7, 'Valjevo', 0, 0, 0, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 0.00, 0.00),
(10, 'Novi Sad', 0, 0, 0, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 0.00, 0.00),
(11, 'Krusevac', 0, 0, 0, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 0.00, 0.00);

-- --------------------------------------------------------

--
-- Stand-in structure for view `pregled_provera_analiticara`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `pregled_provera_analiticara`;
CREATE TABLE IF NOT EXISTS `pregled_provera_analiticara` (
`IDProvere` int unsigned
,`IDRezultata` int unsigned
,`IDIzbornoMesto` int unsigned
,`NazivMesta` varchar(255)
,`NazivOpstine` varchar(30)
,`IDAnaliticara` int unsigned
,`ImeAnaliticara` varchar(30)
,`PrezimeAnaliticara` varchar(30)
,`BrojGlasaca` int unsigned
,`BrojIzaslih` int unsigned
,`BrojNevazecihListica` int unsigned
,`BrojGlasovaPoPartijama` text
,`Komentar` text
,`StatusProvere` varchar(20)
,`DatumProvere` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `provere_analiticara`
--

DROP TABLE IF EXISTS `provere_analiticara`;
CREATE TABLE IF NOT EXISTS `provere_analiticara` (
  `IDProvere` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `IDRezultata` int UNSIGNED NOT NULL,
  `IDAnaliticara` int UNSIGNED NOT NULL,
  `IDIzbornoMesto` int UNSIGNED DEFAULT NULL,
  `BrojGlasaca` int UNSIGNED DEFAULT '0',
  `BrojIzaslih` int UNSIGNED DEFAULT '0',
  `BrojNevazecihListica` int UNSIGNED DEFAULT '0',
  `BrojGlasovaPoPartijama` text COLLATE utf8mb4_unicode_ci,
  `Komentar` text COLLATE utf8mb4_unicode_ci,
  `StatusProvere` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dodeljeno',
  `DatumProvere` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IDProvere`),
  KEY `IDRezultata` (`IDRezultata`,`IDAnaliticara`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provere_analiticara`
--

INSERT INTO `provere_analiticara` (`IDProvere`, `IDRezultata`, `IDAnaliticara`, `IDIzbornoMesto`, `BrojGlasaca`, `BrojIzaslih`, `BrojNevazecihListica`, `BrojGlasovaPoPartijama`, `Komentar`, `StatusProvere`, `DatumProvere`) VALUES
(28, 42, 14, 3, 65, 62, 2, 'SPS:14,DSS:15,Zeleno-levi:16,Radikalna:15', '', 'zavrseno', '2026-03-13 15:40:54'),
(29, 42, 16, 3, 65, 62, 2, 'SPS:18,DSS:15,Zeleno-levi:16,Radikalna:10', '', 'zavrseno', '2026-03-13 15:40:54'),
(30, 42, 18, 3, 65, 62, 2, 'SPS:17,DSS:15,Zeleno-levi:10,Radikalna:15', '', 'zavrseno', '2026-03-13 15:40:54'),
(31, 40, 14, 1, 45, 41, 1, 'SPS:13,DSS:17,Zeleno-levi:5,Radikalna:5', '', 'zavrseno', '2026-03-13 15:41:53'),
(32, 40, 16, 1, 45, 41, 1, 'SPS:13,DSS:17,Zeleno-levi:5,Radikalna:5', '', 'dodeljeno', '2026-03-13 15:41:53'),
(33, 40, 17, 1, 45, 41, 1, 'SPS:13,DSS:17,Zeleno-levi:5,Radikalna:5', '', 'zavrseno', '2026-03-13 15:41:53'),
(34, 39, 18, 1, 45, 41, 1, 'SPS:13,DSS:17,Zeleno-levi:5,Radikalna:5', '', 'zavrseno', '2026-03-13 15:43:15'),
(35, 39, 14, 1, 45, 41, 1, 'SPS:13,DSS:17,Zeleno-levi:5,Radikalna:5', '', 'zavrseno', '2026-03-13 15:43:15'),
(36, 39, 16, 1, 45, 41, 1, 'SPS:13,DSS:17,Zeleno-levi:5,Radikalna:5', '', 'dodeljeno', '2026-03-13 15:43:15'),
(37, 47, 17, 5, 90, 80, 10, 'SPS:10,DSS:25,Zeleno-levi:25,Radikalna:10', '', 'zavrseno', '2026-03-13 16:45:36'),
(38, 47, 18, 5, 90, 80, 10, 'SPS:10,DSS:25,Zeleno-levi:25,Radikalna:10', '', 'zavrseno', '2026-03-13 16:45:36'),
(39, 47, 14, 5, 90, 80, 10, 'SPS:10,DSS:25,Zeleno-levi:25,Radikalna:10', '', 'zavrseno', '2026-03-13 16:45:36'),
(49, 64, 14, 6, 55, 50, 5, 'SPS:10,DSS:15,Zeleno-levi:15,Radikalna:5', '', 'zavrseno', '2026-03-16 16:51:09'),
(50, 64, 17, 6, 55, 50, 5, 'SPS:12,DSS:13,Zeleno-levi:15,Radikalna:5', '', 'zavrseno', '2026-03-16 16:51:09'),
(51, 64, 18, 6, 55, 50, 5, 'SPS:8,DSS:17,Zeleno-levi:15,Radikalna:5', '', 'zavrseno', '2026-03-16 16:51:09'),
(52, 63, 30, 7, 36, 33, 3, 'SPS:2,DSS:15,Zeleno-levi:5,Radikalna:8', '', 'zavrseno', '2026-03-16 16:55:23'),
(53, 63, 31, 7, 36, 33, 3, 'SPS:10,DSS:10,Zeleno-levi:10,Radikalna:10', '', 'zavrseno', '2026-03-16 16:55:23'),
(54, 63, 14, 7, 36, 33, 3, 'SPS:2,DSS:15,Zeleno-levi:5,Radikalna:8', '', 'zavrseno', '2026-03-16 16:55:23'),
(63, 66, 18, 8, 100, 80, 8, 'SPS:15,DSS:25,Zeleno-levi:20,Radikalna:10', '', 'zavrseno', '2026-03-17 11:11:54'),
(61, 66, 14, 8, 100, 80, 2, 'SPS:20,DSS:25,Zeleno-levi:23,Radikalna:12', '', 'zavrseno', '2026-03-17 11:11:54'),
(62, 66, 17, 8, 100, 80, 1, 'SPS:24,DSS:25,Zeleno-levi:21,Radikalna:10', '', 'zavrseno', '2026-03-17 11:11:54'),
(94, 75, 14, 9, 140, 100, 10, 'SPS:22,DSS:22,Zeleno-levi:24,Radikalna:22', '', 'zavrseno', '2026-03-17 13:28:12'),
(95, 75, 17, 9, 140, 100, 10, 'SPS:22,DSS:22,Zeleno-levi:24,Radikalna:22', '', 'zavrseno', '2026-03-17 13:28:12'),
(96, 75, 18, 9, 140, 100, 10, 'SPS:22,DSS:22,Zeleno-levi:24,Radikalna:22', '', 'dodeljeno', '2026-03-17 13:28:12');

-- --------------------------------------------------------

--
-- Table structure for table `rezultati`
--

DROP TABLE IF EXISTS `rezultati`;
CREATE TABLE IF NOT EXISTS `rezultati` (
  `IDRezultata` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `IDIzbornoMesto` int UNSIGNED NOT NULL,
  `IDOpstinaGrad` int UNSIGNED NOT NULL,
  `Naziv` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `BrojGlasaca` int UNSIGNED DEFAULT '0',
  `BrojIzaslih` int UNSIGNED DEFAULT '0',
  `BrojNevazecihListica` int UNSIGNED DEFAULT '0',
  `BrojGlasovaPoPartijama` text COLLATE utf8mb4_unicode_ci,
  `Zapisnik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Prilozi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Regularnost` tinyint(1) DEFAULT '0' COMMENT '1 ako je potpisan',
  `Neregularnost` text COLLATE utf8mb4_unicode_ci,
  `IDKontrolora` int UNSIGNED NOT NULL,
  `IDAnaliticara` int UNSIGNED DEFAULT '0',
  `StatusRezultata` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'na_proveri',
  PRIMARY KEY (`IDRezultata`),
  KEY `IDOpstinaGrad` (`IDOpstinaGrad`),
  KEY `IDKontrolora` (`IDKontrolora`),
  KEY `IDAnaliticara` (`IDAnaliticara`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rezultati`
--

INSERT INTO `rezultati` (`IDRezultata`, `IDIzbornoMesto`, `IDOpstinaGrad`, `Naziv`, `BrojGlasaca`, `BrojIzaslih`, `BrojNevazecihListica`, `BrojGlasovaPoPartijama`, `Zapisnik`, `Prilozi`, `Regularnost`, `Neregularnost`, `IDKontrolora`, `IDAnaliticara`, `StatusRezultata`) VALUES
(56, 4, 7, 'Ub', 0, 0, 0, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', NULL, NULL, 0, NULL, 23, 0, 'na_proveri'),
(64, 6, 10, 'Veternik', 55, 50, 5, 'SPS:10,DSS:15,Zeleno-levi:15,Radikalna:5', 'No file', 'No file', 1, '', 29, 0, 'sumnjiv'),
(39, 1, 3, 'Tanasko Rajic', 45, 41, 1, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 'No file', 'No file', 0, '', 13, 0, 'potvrdjen'),
(40, 1, 3, 'Tanasko Rajic', 45, 41, 1, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 'No file', 'No file', 1, '', 12, 0, 'potvrdjen'),
(47, 5, 1, 'Vozdovac', 90, 80, 10, 'SPS:10,DSS:25,Zeleno-levi:25,Radikalna:10', 'No file', 'No file', 1, '', 22, 0, 'potvrdjen'),
(42, 3, 2, 'Osnovna skola ', 65, 62, 2, 'SPS:0,DSS:0,Zeleno-levi:0,Radikalna:0', 'No file', 'No file', 1, '', 20, 0, 'nevazeci'),
(66, 8, 4, 'Dom kulture', 100, 80, 5, 'SPS:20,DSS:25,Zeleno-levi:20,Radikalna:10', 'No file', 'No file', 1, '', 34, 0, 'nevazeci'),
(63, 7, 10, 'Liman', 36, 33, 3, 'SPS:2,DSS:15,Zeleno-levi:5,Radikalna:8', 'No file', 'No file', 1, '', 28, 0, 'potvrdjen'),
(75, 9, 11, 'Jasika', 140, 100, 10, 'SPS:22,DSS:22,Zeleno-levi:24,Radikalna:22', 'uploads/zapisnik_1773750492_prilozi_1772542848_slike-za-pozadinu-1-edited.jpg', 'uploads/prilozi_1773750492_prilozi_1772542848_slike-za-pozadinu-1-edited.jpg', 1, '', 35, 0, 'potvrdjen');

-- --------------------------------------------------------

--
-- Structure for view `pregled_provera_analiticara`
--
DROP TABLE IF EXISTS `pregled_provera_analiticara`;

DROP VIEW IF EXISTS `pregled_provera_analiticara`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pregled_provera_analiticara`  AS SELECT `p`.`IDProvere` AS `IDProvere`, `p`.`IDRezultata` AS `IDRezultata`, `p`.`IDIzbornoMesto` AS `IDIzbornoMesto`, `r`.`Naziv` AS `NazivMesta`, `o`.`Naziv` AS `NazivOpstine`, `p`.`IDAnaliticara` AS `IDAnaliticara`, `k`.`Ime` AS `ImeAnaliticara`, `k`.`Prezime` AS `PrezimeAnaliticara`, `p`.`BrojGlasaca` AS `BrojGlasaca`, `p`.`BrojIzaslih` AS `BrojIzaslih`, `p`.`BrojNevazecihListica` AS `BrojNevazecihListica`, `p`.`BrojGlasovaPoPartijama` AS `BrojGlasovaPoPartijama`, `p`.`Komentar` AS `Komentar`, `p`.`StatusProvere` AS `StatusProvere`, `p`.`DatumProvere` AS `DatumProvere` FROM (((`provere_analiticara` `p` left join `rezultati` `r` on((`p`.`IDRezultata` = `r`.`IDRezultata`))) left join `opstinegradovi` `o` on((`r`.`IDOpstinaGrad` = `o`.`IDOpstinaGrad`))) left join `korisnici` `k` on((`p`.`IDAnaliticara` = `k`.`IDKorisnika`))) ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
