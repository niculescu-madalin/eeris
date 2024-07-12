-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gazdă: 127.0.0.1
-- Timp de generare: iul. 08, 2024 la 07:43 AM
-- Versiune server: 10.4.32-MariaDB
-- Versiune PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Bază de date: `eeris`
--

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `cadru_medical`
--

CREATE TABLE `cadru_medical` (
  `id` int(11) NOT NULL,
  `nume` varchar(50) DEFAULT NULL,
  `prenume` varchar(50) DEFAULT NULL,
  `data_nasterii` date DEFAULT NULL,
  `specialitate` varchar(255) DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `cadru_medical`
--

INSERT INTO `cadru_medical` (`id`, `nume`, `prenume`, `data_nasterii`, `specialitate`, `telefon`, `user_id`) VALUES
(6, 'Alex', 'Boss', '2002-01-02', 'ortoped', '4614363416', 12),
(7, 'Dunarintu', 'Dragos', '2002-06-22', 'ortoped', '078256345', 14);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `consultations`
--

CREATE TABLE `consultations` (
  `id` int(11) NOT NULL,
  `data_programarii` datetime NOT NULL,
  `pacient_id` int(11) NOT NULL,
  `cadru_medical_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `consultations`
--

INSERT INTO `consultations` (`id`, `data_programarii`, `pacient_id`, `cadru_medical_id`, `status`) VALUES
(6, '2024-07-07 11:00:00', 4, 7, 'completed'),
(9, '2024-07-08 11:02:00', 5, 6, 'accepted'),
(13, '2024-07-08 12:14:00', 4, 7, 'accepted'),
(14, '2024-07-19 10:08:00', 5, 7, 'accepted');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `diagnostic`
--

CREATE TABLE `diagnostic` (
  `id` int(11) NOT NULL,
  `pacient_id` int(11) DEFAULT NULL,
  `scor_miscare_umar` int(11) DEFAULT NULL,
  `scor_antebrat` int(11) DEFAULT NULL,
  `scor_durere` int(11) DEFAULT NULL,
  `scor_mobilitate_sold` int(11) DEFAULT NULL,
  `scor_genunchi` int(11) DEFAULT NULL,
  `scor_glezna` int(11) DEFAULT NULL,
  `alte_scori_genunchi` int(11) DEFAULT NULL,
  `flex_musculatura_genunchi` varchar(255) DEFAULT NULL,
  `scor_flexori` int(11) DEFAULT NULL,
  `scor_extensori` int(11) DEFAULT NULL,
  `scor_glezna_flexie_dorsala` int(11) DEFAULT NULL,
  `scor_glezna_flexie_plantara` int(11) DEFAULT NULL,
  `scor_durere_integrat` int(11) DEFAULT NULL,
  `consultation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `diagnostic`
--

INSERT INTO `diagnostic` (`id`, `pacient_id`, `scor_miscare_umar`, `scor_antebrat`, `scor_durere`, `scor_mobilitate_sold`, `scor_genunchi`, `scor_glezna`, `alte_scori_genunchi`, `flex_musculatura_genunchi`, `scor_flexori`, `scor_extensori`, `scor_glezna_flexie_dorsala`, `scor_glezna_flexie_plantara`, `scor_durere_integrat`, `consultation_id`) VALUES
(1, 4, 10, 10, 10, 10, 10, 10, 10, 'foarte buna', 10, 10, 10, 10, 10, 6);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `imc`
--

CREATE TABLE `imc` (
  `id` int(11) NOT NULL,
  `value` float DEFAULT NULL,
  `pacient_id` int(11) DEFAULT NULL,
  `inaltime` int(11) DEFAULT NULL,
  `greutate` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `consultation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `imc`
--

INSERT INTO `imc` (`id`, `value`, `pacient_id`, `inaltime`, `greutate`, `created_at`, `consultation_id`) VALUES
(9, 28.0584, 4, 198, 110, '2024-07-04 10:31:55', 6);

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `istoricexercitii`
--

CREATE TABLE `istoricexercitii` (
  `id` int(11) NOT NULL,
  `pacient_id` int(11) DEFAULT NULL,
  `nr_repetitii` int(11) DEFAULT NULL,
  `muschi` varchar(50) DEFAULT NULL,
  `viteza` varchar(50) DEFAULT NULL,
  `durata` time DEFAULT NULL,
  `data_start_exercitiu` date DEFAULT NULL,
  `nume_exercitiu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `istoricexercitii`
--

INSERT INTO `istoricexercitii` (`id`, `pacient_id`, `nr_repetitii`, `muschi`, `viteza`, `durata`, `data_start_exercitiu`, `nume_exercitiu`) VALUES
(4, 4, 20, 'Abs', '2s', '00:05:00', '2024-07-10', 'Crunches');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `pacient`
--

CREATE TABLE `pacient` (
  `id` int(11) NOT NULL,
  `nume` varchar(50) DEFAULT NULL,
  `prenume` varchar(50) DEFAULT NULL,
  `varsta` int(11) DEFAULT NULL,
  `gen` enum('M','F') DEFAULT NULL,
  `inaltime` int(11) DEFAULT NULL,
  `greutate` int(11) DEFAULT NULL,
  `diagnostic` varchar(255) DEFAULT NULL,
  `patologie_asociata` varchar(255) DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `data_nasterii` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `pacient`
--

INSERT INTO `pacient` (`id`, `nume`, `prenume`, `varsta`, `gen`, `inaltime`, `greutate`, `diagnostic`, `patologie_asociata`, `telefon`, `user_id`, `data_nasterii`) VALUES
(4, 'Penoiu', 'Cristian', 21, 'M', 198, 110, 'niciunul', 'niciuna', '143564163', 17, '2002-07-07'),
(5, 'Nazario', 'Ronaldo', 47, 'M', 182, 78, 'Ruptura de ligamente', 'Obezitate', '0727586125', 19, '1976-09-18');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `scor_adl`
--

CREATE TABLE `scor_adl` (
  `id` int(11) NOT NULL,
  `pacient_id` int(11) DEFAULT NULL,
  `scor_adl` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `descriere` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `scor_adl`
--

INSERT INTO `scor_adl` (`id`, `pacient_id`, `scor_adl`, `data`, `descriere`) VALUES
(1, 4, 200, '2024-07-07', 'Bun rau');

-- --------------------------------------------------------

--
-- Structură tabel pentru tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tip_utilizator` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Eliminarea datelor din tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `tip_utilizator`) VALUES
(12, 'Sefu', 'Sefu1234', 'sefu3@gmail.com', 'cadru_medical'),
(14, 'Dragos', 'Dragos1234', 'dg@gmail.com', 'cadru_medical'),
(17, 'Mesy', 'Mesy1234', 'Mesy@gmail.com', 'pacient'),
(18, 'Sef', 'Sef12345', 'sef@gmail.com', 'admin'),
(19, 'ofenomeno', 'ofenomeno1234', 'of@gmail.com', 'pacient');

--
-- Indexuri pentru tabele eliminate
--

--
-- Indexuri pentru tabele `cadru_medical`
--
ALTER TABLE `cadru_medical`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cadru_medical_user_id` (`user_id`);

--
-- Indexuri pentru tabele `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pacient_id` (`pacient_id`),
  ADD KEY `fk_cadru_medical_id` (`cadru_medical_id`);

--
-- Indexuri pentru tabele `diagnostic`
--
ALTER TABLE `diagnostic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `diagnostic_ibfk_2` (`consultation_id`);

--
-- Indexuri pentru tabele `imc`
--
ALTER TABLE `imc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imc_ibfk_1` (`pacient_id`),
  ADD KEY `fk_consultation_imc` (`consultation_id`);

--
-- Indexuri pentru tabele `istoricexercitii`
--
ALTER TABLE `istoricexercitii`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pacient_id` (`pacient_id`);

--
-- Indexuri pentru tabele `pacient`
--
ALTER TABLE `pacient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pacient_user_id` (`user_id`);

--
-- Indexuri pentru tabele `scor_adl`
--
ALTER TABLE `scor_adl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pacient_id` (`pacient_id`);

--
-- Indexuri pentru tabele `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pentru tabele eliminate
--

--
-- AUTO_INCREMENT pentru tabele `cadru_medical`
--
ALTER TABLE `cadru_medical`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pentru tabele `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pentru tabele `diagnostic`
--
ALTER TABLE `diagnostic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pentru tabele `imc`
--
ALTER TABLE `imc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pentru tabele `istoricexercitii`
--
ALTER TABLE `istoricexercitii`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pentru tabele `pacient`
--
ALTER TABLE `pacient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pentru tabele `scor_adl`
--
ALTER TABLE `scor_adl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pentru tabele `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constrângeri pentru tabele eliminate
--

--
-- Constrângeri pentru tabele `cadru_medical`
--
ALTER TABLE `cadru_medical`
  ADD CONSTRAINT `fk_cadru_medical_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constrângeri pentru tabele `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `fk_cadru_medical_id` FOREIGN KEY (`cadru_medical_id`) REFERENCES `cadru_medical` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pacient_id` FOREIGN KEY (`pacient_id`) REFERENCES `pacient` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constrângeri pentru tabele `diagnostic`
--
ALTER TABLE `diagnostic`
  ADD CONSTRAINT `diagnostic_ibfk_2` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`);

--
-- Constrângeri pentru tabele `imc`
--
ALTER TABLE `imc`
  ADD CONSTRAINT `fk_consultation_imc` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `imc_ibfk_1` FOREIGN KEY (`pacient_id`) REFERENCES `pacient` (`id`);

--
-- Constrângeri pentru tabele `istoricexercitii`
--
ALTER TABLE `istoricexercitii`
  ADD CONSTRAINT `istoricexercitii_ibfk_1` FOREIGN KEY (`pacient_id`) REFERENCES `pacient` (`id`);

--
-- Constrângeri pentru tabele `pacient`
--
ALTER TABLE `pacient`
  ADD CONSTRAINT `fk_pacient_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constrângeri pentru tabele `scor_adl`
--
ALTER TABLE `scor_adl`
  ADD CONSTRAINT `scor_adl_ibfk_1` FOREIGN KEY (`pacient_id`) REFERENCES `pacient` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
