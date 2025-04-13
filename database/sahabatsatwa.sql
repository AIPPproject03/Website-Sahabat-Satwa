-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Apr 2025 pada 12.18
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sahabatsatwa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `animal`
--

CREATE TABLE `animal` (
  `animal_id` int(11) NOT NULL,
  `animal_name` varchar(40) NOT NULL,
  `animal_born` date NOT NULL,
  `owner_id` int(11) NOT NULL,
  `at_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `animal`
--

INSERT INTO `animal` (`animal_id`, `animal_name`, `animal_born`, `owner_id`, `at_id`) VALUES
(1, 'Kitty', '2020-01-01', 2, 1),
(2, 'Rex', '2020-02-02', 1, 7),
(3, 'Bobi', '2020-03-03', 4, 2),
(4, 'Hamtaro', '2020-04-04', 5, 3),
(5, 'Kiki', '2020-05-05', 6, 5),
(6, 'Nemo', '2020-06-06', 3, 6),
(7, 'Bobo', '2020-07-07', 2, 4),
(8, 'Grey', '2020-08-08', 6, 1),
(9, 'Hamtara', '2020-09-09', 5, 3),
(10, 'Cooky', '2020-10-10', 3, 7);

--
-- Trigger `animal`
--
DELIMITER $$
CREATE TRIGGER `log_delete_animal` AFTER DELETE ON `animal` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted animal: ', OLD.animal_name));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_animal` AFTER INSERT ON `animal` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new animal: ', NEW.animal_name));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_animal` AFTER UPDATE ON `animal` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated animal: ', NEW.animal_name));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `animal_type`
--

CREATE TABLE `animal_type` (
  `at_id` int(11) NOT NULL,
  `at_description` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `animal_type`
--

INSERT INTO `animal_type` (`at_id`, `at_description`) VALUES
(1, 'Kucing'),
(2, 'Anjing'),
(3, 'Kelinci'),
(4, 'Hamster'),
(5, 'Burung'),
(6, 'Ikan'),
(7, 'Reptil');

--
-- Trigger `animal_type`
--
DELIMITER $$
CREATE TRIGGER `log_delete_animal_type` AFTER DELETE ON `animal_type` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted animal type: ', OLD.at_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_animal_type` AFTER INSERT ON `animal_type` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new animal type: ', NEW.at_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_animal_type` AFTER UPDATE ON `animal_type` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated animal type: ', NEW.at_id));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `user` varchar(50) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `user`, `action`, `timestamp`) VALUES
(1, 'root@localhost', 'Updated owner: 2', '2025-03-07 12:47:48'),
(2, 'root@localhost', 'Inserted new clinic: 4', '2025-03-07 12:52:37'),
(4, 'superadmin@localhost', 'Updated clinic: 4', '2025-03-07 13:05:41'),
(6, 'ria@localhost', 'Updated visit: 10', '2025-03-07 13:11:18'),
(7, 'root@localhost', 'Inserted new visit: 11', '2025-03-25 19:39:36'),
(8, 'root@localhost', 'Updated animal: Kitty', '2025-04-13 15:40:27'),
(9, 'root@localhost', 'Updated animal: Kity', '2025-04-13 16:25:06'),
(21, 'root@localhost', 'Inserted new animal: Uma lah', '2025-04-13 16:56:44'),
(22, 'root@localhost', 'Deleted animal: Uma lah', '2025-04-13 16:56:58'),
(23, 'root@localhost', 'Updated animal: Kity', '2025-04-13 17:03:41'),
(24, 'root@localhost', 'Updated animal: Kitty', '2025-04-13 17:03:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `clinic`
--

CREATE TABLE `clinic` (
  `clinic_id` int(11) NOT NULL,
  `clinic_name` varchar(50) NOT NULL,
  `clinic_address` varchar(80) NOT NULL,
  `clinic_phone` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `clinic`
--

INSERT INTO `clinic` (`clinic_id`, `clinic_name`, `clinic_address`, `clinic_phone`) VALUES
(1, 'Klinik Hewan Puri', 'Jl. Banteng No. 24', '021-1234567'),
(2, 'Klinik Hewan Sejahtera', 'Jl. Raya Depok No. 2', '021-2345678'),
(3, 'Klinik Hewan Bahagia', 'Jl. Raya Indah No. 18', '021-3456789');

--
-- Trigger `clinic`
--
DELIMITER $$
CREATE TRIGGER `log_delete_clinic` AFTER DELETE ON `clinic` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted clinic: ', OLD.clinic_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_clinic` AFTER INSERT ON `clinic` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new clinic: ', NEW.clinic_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_clinic` AFTER UPDATE ON `clinic` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated clinic: ', NEW.clinic_id));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `drug`
--

CREATE TABLE `drug` (
  `drug_id` int(11) NOT NULL,
  `drug_name` varchar(50) NOT NULL,
  `drug_usage` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `drug`
--

INSERT INTO `drug` (`drug_id`, `drug_name`, `drug_usage`) VALUES
(1, 'Paracetamol', 'Obat penurun panas'),
(2, 'Amoxicillin', 'Antibiotik'),
(3, 'Ibuprofen', 'Obat pereda nyeri'),
(4, 'Dexamethasone', 'Obat antiinflamasi'),
(5, 'Ranitidine', 'Obat antiasam lambung'),
(6, 'Omeprazole', 'Obat antasid'),
(7, 'Loratadine', 'Obat antihistamin'),
(8, 'Diphenhydramine', 'Obat antialergi'),
(9, 'Cetirizine', 'Obat antialergi'),
(10, 'Fexofenadine', 'Obat antialergi'),
(11, 'Loperamide', 'Obat antidiare'),
(12, 'Simethicone', 'Obat antikembung'),
(13, 'Lactulose', 'Obat pencahar'),
(14, 'Bisacodyl', 'Obat pencahar'),
(15, 'Psyllium', 'Obat pencahar');

--
-- Trigger `drug`
--
DELIMITER $$
CREATE TRIGGER `log_delete_drug` AFTER DELETE ON `drug` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted drug: ', OLD.drug_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_drug` AFTER INSERT ON `drug` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new drug: ', NEW.drug_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_drug` AFTER UPDATE ON `drug` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated drug: ', NEW.drug_id));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `owners`
--

CREATE TABLE `owners` (
  `owner_id` int(11) NOT NULL,
  `owner_givenname` varchar(30) DEFAULT NULL,
  `owner_familyname` varchar(30) DEFAULT NULL,
  `owner_address` varchar(100) NOT NULL,
  `owner_phone` varchar(14) DEFAULT NULL,
  `owner_password` varbinary(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `owners`
--

INSERT INTO `owners` (`owner_id`, `owner_givenname`, `owner_familyname`, `owner_address`, `owner_phone`, `owner_password`) VALUES
(1, 'Budi', 'Santoso', 'Jl. Hiu No. 18', '081234567890', ''),
(2, 'Ani', 'Rahayu', 'Jl. Rajawali No. 23', '081234567891', 0xa63e6b6ef938a640e1f26a6a7a33cfb0),
(3, 'Cindy', 'Wijaya', 'Jl. Samudera No. 03', '081234567892', ''),
(4, 'Dodi', 'Saputra', 'Jl. RTA No. 08', '081234567893', ''),
(5, 'Eva', 'Sari', 'Jl. Borneo No. 19', '081234567894', ''),
(6, 'Fandi', 'Wijaya', 'Jl. Kamboja No. 25', '081234567895', '');

--
-- Trigger `owners`
--
DELIMITER $$
CREATE TRIGGER `log_delete_owner` AFTER DELETE ON `owners` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted owner: ', OLD.owner_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_owner` AFTER INSERT ON `owners` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new owner: ', NEW.owner_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_owner` AFTER UPDATE ON `owners` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated owner: ', NEW.owner_id));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `specialisation`
--

CREATE TABLE `specialisation` (
  `spec_id` int(11) NOT NULL,
  `spec_description` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `specialisation`
--

INSERT INTO `specialisation` (`spec_id`, `spec_description`) VALUES
(1, 'Onkologi'),
(2, 'Kardiologi'),
(3, 'Dermatologi');

--
-- Trigger `specialisation`
--
DELIMITER $$
CREATE TRIGGER `log_delete_specialisation` AFTER DELETE ON `specialisation` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted specialisation: ', OLD.spec_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_specialisation` AFTER INSERT ON `specialisation` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new specialisation: ', NEW.spec_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_specialisation` AFTER UPDATE ON `specialisation` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated specialisation: ', NEW.spec_id));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `spec_visit`
--

CREATE TABLE `spec_visit` (
  `clinic_id` int(11) NOT NULL,
  `vet_id` int(11) NOT NULL,
  `sv_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `spec_visit`
--
DELIMITER $$
CREATE TRIGGER `log_delete_spec_visit` AFTER DELETE ON `spec_visit` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted spec visit: ', OLD.clinic_id, ' - ', OLD.vet_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_spec_visit` AFTER INSERT ON `spec_visit` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new spec visit: ', NEW.clinic_id, ' - ', NEW.vet_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_spec_visit` AFTER UPDATE ON `spec_visit` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated spec visit: ', NEW.clinic_id, ' - ', NEW.vet_id));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `vet`
--

CREATE TABLE `vet` (
  `vet_id` int(11) NOT NULL,
  `vet_title` char(4) NOT NULL,
  `vet_givenname` varchar(30) DEFAULT NULL,
  `vet_familyname` varchar(30) DEFAULT NULL,
  `vet_phone` varchar(14) NOT NULL,
  `vet_employed` date NOT NULL,
  `spec_id` int(11) DEFAULT NULL,
  `clinic_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `vet`
--

INSERT INTO `vet` (`vet_id`, `vet_title`, `vet_givenname`, `vet_familyname`, `vet_phone`, `vet_employed`, `spec_id`, `clinic_id`) VALUES
(1, 'Dr.', 'Ria', 'Agustin', '081234567890', '2021-01-01', 3, 1),
(2, 'Prof', 'Irwin', 'Pangesti', '081234567891', '2021-01-02', 1, 1),
(3, 'Dr.', 'Martha', 'Lena', '081234567892', '2021-01-03', 2, 2),
(4, 'Prof', 'Salsabila', 'Aprilia', '081234567893', '2021-01-04', 3, 2),
(5, 'Dr.', 'Bima', 'Saputra', '081234567894', '2021-01-05', 1, 3),
(6, 'Dr.', 'Abil', 'Abzari', '081234567895', '2021-01-06', 1, 3);

--
-- Trigger `vet`
--
DELIMITER $$
CREATE TRIGGER `log_delete_vet` AFTER DELETE ON `vet` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted vet: ', OLD.vet_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_vet` AFTER INSERT ON `vet` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new vet: ', NEW.vet_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_vet` AFTER UPDATE ON `vet` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated vet: ', NEW.vet_id));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `visit`
--

CREATE TABLE `visit` (
  `visit_id` int(11) NOT NULL,
  `visit_date_time` date NOT NULL,
  `visit_notes` varchar(200) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `vet_id` int(11) NOT NULL,
  `from_visit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `visit`
--

INSERT INTO `visit` (`visit_id`, `visit_date_time`, `visit_notes`, `animal_id`, `vet_id`, `from_visit_id`) VALUES
(1, '2024-08-01', 'Kucing Kity sakit mata', 1, 1, NULL),
(2, '2024-09-22', 'Komodo Rex sakit kulit', 2, 2, NULL),
(3, '2024-10-11', 'Anjing Bobi sakit gigi', 3, 3, NULL),
(4, '2024-11-21', 'Burung Kiki sakit paruh', 4, 4, NULL),
(5, '2024-12-05', 'Ikan Nemo sakit sirip', 5, 5, NULL),
(6, '2025-01-02', 'Hamster Bobo sakit mata', 6, 6, NULL),
(7, '2025-01-08', 'Kucing Grey sakit telinga', 7, 1, NULL),
(8, '2025-01-15', 'Kelinci Hamtara periksa kutu', 8, 2, NULL),
(9, '2025-01-22', 'Burung Kiki kontrol kesehatan', 4, 4, 4),
(10, '2025-01-25', 'Kucing Grey Lanjut Tes Kesehatan', 7, 1, 7),
(11, '2025-03-01', 'Kucing Kity sakit mata', 1, 1, NULL);

--
-- Trigger `visit`
--
DELIMITER $$
CREATE TRIGGER `log_delete_visit` AFTER DELETE ON `visit` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted visit: ', OLD.visit_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_visit` AFTER INSERT ON `visit` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new visit: ', NEW.visit_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_visit` AFTER UPDATE ON `visit` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated visit: ', NEW.visit_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `validate_visit_date` BEFORE INSERT ON `visit` FOR EACH ROW BEGIN
    IF NEW.visit_date_time > NOW() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Tanggal kunjungan tidak boleh di masa depan!';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `visit_drug`
--

CREATE TABLE `visit_drug` (
  `visit_id` int(11) NOT NULL,
  `drug_id` int(11) NOT NULL,
  `visit_drug_dose` varchar(20) NOT NULL,
  `visit_drug_frequency` varchar(20) DEFAULT NULL,
  `visit_drug_qtysupplied` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `visit_drug`
--

INSERT INTO `visit_drug` (`visit_id`, `drug_id`, `visit_drug_dose`, `visit_drug_frequency`, `visit_drug_qtysupplied`) VALUES
(1, 1, '1/2 tablet', '3x sehari', 10),
(1, 2, '1 tablet', '2x sehari', 5),
(2, 3, '1 tablet', '2x sehari', 5),
(2, 4, '1/2 tablet', '3x sehari', 10),
(3, 5, '1 tablet', '2x sehari', 5),
(3, 6, '1 tablet', '2x sehari', 5),
(4, 7, '1 tablet', '2x sehari', 5),
(4, 8, '1 tablet', '2x sehari', 5),
(5, 9, '1 tablet', '2x sehari', 5),
(5, 10, '1 tablet', '2x sehari', 5),
(6, 11, '1 tablet', '2x sehari', 5),
(6, 12, '1 tablet', '2x sehari', 5),
(7, 13, '1 tablet', '2x sehari', 5),
(7, 14, '1 tablet', '2x sehari', 5),
(8, 15, '1 tablet', '2x sehari', 5);

--
-- Trigger `visit_drug`
--
DELIMITER $$
CREATE TRIGGER `log_delete_visit_drug` AFTER DELETE ON `visit_drug` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Deleted visit drug: ', OLD.visit_id, ' - ', OLD.drug_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_insert_visit_drug` AFTER INSERT ON `visit_drug` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Inserted new visit drug: ', NEW.visit_id, ' - ', NEW.drug_id));
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `log_update_visit_drug` AFTER UPDATE ON `visit_drug` FOR EACH ROW BEGIN
    INSERT INTO audit_log (user, action)
    VALUES (USER(), CONCAT('Updated visit drug: ', NEW.visit_id, ' - ', NEW.drug_id));
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`animal_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `at_id` (`at_id`);

--
-- Indeks untuk tabel `animal_type`
--
ALTER TABLE `animal_type`
  ADD PRIMARY KEY (`at_id`);

--
-- Indeks untuk tabel `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indeks untuk tabel `clinic`
--
ALTER TABLE `clinic`
  ADD PRIMARY KEY (`clinic_id`);

--
-- Indeks untuk tabel `drug`
--
ALTER TABLE `drug`
  ADD PRIMARY KEY (`drug_id`);

--
-- Indeks untuk tabel `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`owner_id`);

--
-- Indeks untuk tabel `specialisation`
--
ALTER TABLE `specialisation`
  ADD PRIMARY KEY (`spec_id`);

--
-- Indeks untuk tabel `spec_visit`
--
ALTER TABLE `spec_visit`
  ADD PRIMARY KEY (`clinic_id`,`vet_id`),
  ADD KEY `vet_id` (`vet_id`);

--
-- Indeks untuk tabel `vet`
--
ALTER TABLE `vet`
  ADD PRIMARY KEY (`vet_id`),
  ADD KEY `spec_id` (`spec_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indeks untuk tabel `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`visit_id`),
  ADD KEY `animal_id` (`animal_id`),
  ADD KEY `vet_id` (`vet_id`),
  ADD KEY `from_visit_id` (`from_visit_id`);

--
-- Indeks untuk tabel `visit_drug`
--
ALTER TABLE `visit_drug`
  ADD PRIMARY KEY (`visit_id`,`drug_id`),
  ADD KEY `drug_id` (`drug_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `animal`
--
ALTER TABLE `animal`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `animal_type`
--
ALTER TABLE `animal_type`
  MODIFY `at_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `clinic`
--
ALTER TABLE `clinic`
  MODIFY `clinic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `drug`
--
ALTER TABLE `drug`
  MODIFY `drug_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `owners`
--
ALTER TABLE `owners`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `specialisation`
--
ALTER TABLE `specialisation`
  MODIFY `spec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `vet`
--
ALTER TABLE `vet`
  MODIFY `vet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `visit`
--
ALTER TABLE `visit`
  MODIFY `visit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `animal_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`owner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `animal_ibfk_2` FOREIGN KEY (`at_id`) REFERENCES `animal_type` (`at_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `spec_visit`
--
ALTER TABLE `spec_visit`
  ADD CONSTRAINT `spec_visit_ibfk_1` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `spec_visit_ibfk_2` FOREIGN KEY (`vet_id`) REFERENCES `vet` (`vet_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `vet`
--
ALTER TABLE `vet`
  ADD CONSTRAINT `vet_ibfk_1` FOREIGN KEY (`spec_id`) REFERENCES `specialisation` (`spec_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vet_ibfk_2` FOREIGN KEY (`clinic_id`) REFERENCES `clinic` (`clinic_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`animal_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visit_ibfk_2` FOREIGN KEY (`vet_id`) REFERENCES `vet` (`vet_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visit_ibfk_3` FOREIGN KEY (`from_visit_id`) REFERENCES `visit` (`visit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `visit_drug`
--
ALTER TABLE `visit_drug`
  ADD CONSTRAINT `visit_drug_ibfk_1` FOREIGN KEY (`visit_id`) REFERENCES `visit` (`visit_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visit_drug_ibfk_2` FOREIGN KEY (`drug_id`) REFERENCES `drug` (`drug_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
