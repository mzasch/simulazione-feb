-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2019 at 10:38 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simulazione`
--

CREATE DATABASE IF NOT EXISTS `simulazione`;
USE `simulazione`;

-- --------------------------------------------------------

--
-- Table structure for table `biciclette`
--

CREATE TABLE `biciclette` (
  `TagRFID` int(11) NOT NULL,
  `stato` enum('available','unavailable') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `biciclette`
--

INSERT INTO `biciclette` (`TagRFID`, `stato`) VALUES
(1, 'unavailable'),
(2, 'available'),
(3, 'available'),
(4, 'available'),
(5, 'available'),
(6, 'unavailable'),
(7, 'unavailable'),
(8, 'available'),
(9, 'available'),
(10, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `operazioni`
--

CREATE TABLE `operazioni` (
  `idOperazione` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idStazioneRitiro` int(11) NOT NULL,
  `idStazioneConsegna` int(11) DEFAULT NULL,
  `tagRFID` int(11) NOT NULL,
  `Data_Ora_Ritiro` datetime NOT NULL,
  `Data_Ora_Consegna` datetime DEFAULT NULL,
  `Costo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `operazioni`
--

INSERT INTO `operazioni` (`idOperazione`, `idUtente`, `idStazioneRitiro`, `idStazioneConsegna`, `tagRFID`, `Data_Ora_Ritiro`, `Data_Ora_Consegna`, `Costo`) VALUES
(1, 1, 1, 1, 1, '2019-02-28 09:15:00', '2019-02-28 10:20:00', 1),
(2, 1, 2, 2, 2, '2019-02-28 19:15:00', '2019-03-01 09:10:00', 7),
(3, 2, 1, 1, 3, '2019-02-28 08:30:00', '2019-02-28 18:25:00', 5),
(4, 2, 2, 2, 4, '2019-02-28 09:25:00', '2019-02-28 13:08:00', 2),
(5, 3, 1, 2, 5, '2019-02-28 10:15:00', '2019-02-28 10:55:00', 0.5),
(6, 3, 2, 1, 6, '2019-02-28 10:05:00', '2019-02-28 12:30:00', 1.5),
(7, 4, 1, NULL, 7, '2019-02-28 09:20:00', NULL, 0),
(8, 4, 2, 1, 8, '2019-02-28 09:15:00', '2019-02-28 18:25:00', 5),
(9, 5, 1, 1, 9, '2019-02-28 13:30:00', '2019-02-28 22:10:00', 4.5),
(10, 5, 2, 2, 10, '2019-02-28 11:40:00', '2019-02-28 19:35:00', 4),
(11, 5, 1, NULL, 1, '2019-03-01 07:15:00', NULL, 0),
(12, 2, 2, NULL, 6, '2019-03-02 09:00:00', NULL, 0),
(13, 1, 1, NULL, 3, '2019-03-04 13:15:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stazioni`
--

CREATE TABLE `stazioni` (
  `idStazione` int(11) NOT NULL,
  `Nome` varchar(20) NOT NULL,
  `Latitudine` double NOT NULL,
  `Longitudine` double NOT NULL,
  `Indirizzo` varchar(100) NOT NULL,
  `BiciDisponibili` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stazioni`
--

INSERT INTO `stazioni` (`idStazione`, `Nome`, `Latitudine`, `Longitudine`, `Indirizzo`, `BiciDisponibili`) VALUES
(1, 'Leonardo', 2.4578, 7.9812, 'via Porta Nuova, 3', 49),
(2, 'Michelangelo', 10.4637, 42.7865, 'via Centrale, 16', 49);

-- --------------------------------------------------------

--
-- Table structure for table `utenti`
--

CREATE TABLE `utenti` (
  `idUtente` int(11) NOT NULL,
  `Nome` varchar(20) NOT NULL,
  `Cognome` varchar(20) NOT NULL,
  `Indirizzo` varchar(100) NOT NULL,
  `NCartaCredito` char(16) NOT NULL,
  `CadenzaReport` enum('7','30','90','180','365') NOT NULL DEFAULT '30'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utenti`
--

INSERT INTO `utenti` (`idUtente`, `Nome`, `Cognome`, `Indirizzo`, `NCartaCredito`, `CadenzaReport`) VALUES
(1, 'Michele', 'Caceffo', 'via Taldeitali 32', '0123456789', '30'),
(2, 'Guido', 'Frigo', 'via dei Talaltri, 49', '9876543210', '30'),
(3, 'Adriana', 'Ghisellini', 'via Tizio, 24', '13579', '30'),
(4, 'Lucia', 'Carli', 'via Caio, 21', '246810', '30'),
(5, 'Alessandra', 'Chilese', 'via Sempronio, 18', '11235813', '30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biciclette`
--
ALTER TABLE `biciclette`
  ADD PRIMARY KEY (`TagRFID`);

--
-- Indexes for table `operazioni`
--
ALTER TABLE `operazioni`
  ADD PRIMARY KEY (`idOperazione`),
  ADD KEY `FK_Utente` (`idUtente`),
  ADD KEY `FK_Bicicletta` (`tagRFID`),
  ADD KEY `FK_Stazione_Ritiro` (`idStazioneRitiro`),
  ADD KEY `FK_Stazione_Consegna` (`idStazioneConsegna`);

--
-- Indexes for table `stazioni`
--
ALTER TABLE `stazioni`
  ADD PRIMARY KEY (`idStazione`);

--
-- Indexes for table `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`idUtente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `operazioni`
--
ALTER TABLE `operazioni`
  MODIFY `idOperazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `stazioni`
--
ALTER TABLE `stazioni`
  MODIFY `idStazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `utenti`
--
ALTER TABLE `utenti`
  MODIFY `idUtente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `operazioni`
--
ALTER TABLE `operazioni`
  ADD CONSTRAINT `FK_Bicicletta` FOREIGN KEY (`tagRFID`) REFERENCES `biciclette` (`TagRFID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Stazione_Consegna` FOREIGN KEY (`idStazioneConsegna`) REFERENCES `stazioni` (`idStazione`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Stazione_Ritiro` FOREIGN KEY (`idStazioneRitiro`) REFERENCES `stazioni` (`idStazione`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Utente` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`idUtente`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
