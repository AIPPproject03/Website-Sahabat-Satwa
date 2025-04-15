-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Apr 2025 pada 18.15
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
(16, 'Kitty', '2025-04-07', 16, 1),
(17, 'Blob', '2025-03-03', 14, 5),
(18, 'Yuzi', '2024-06-18', 15, 2),
(19, 'Goofy', '2023-02-14', 18, 6),
(20, 'Hawk', '2023-02-07', 17, 5),
(21, 'Rex', '2019-02-19', 17, 8);

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
(7, 'Reptil'),
(8, 'Dinosaurus');

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
(30, 'root@localhost', 'Updated visit: 1', '2025-04-14 19:24:17'),
(31, 'superadmin@localhost', 'Updated animal: Kity', '2025-04-14 19:40:46'),
(32, 'ria@localhost', 'Updated visit drug: 1 - 1', '2025-04-14 19:45:24'),
(74, 'root@localhost', 'Deleted visit: 14', '2025-04-15 21:56:44'),
(83, 'root@localhost', 'Updated vet: 2', '2025-04-15 22:00:53'),
(84, 'root@localhost', 'Updated vet: 3', '2025-04-15 22:01:49'),
(85, 'root@localhost', 'Updated vet: 4', '2025-04-15 22:02:18'),
(86, 'root@localhost', 'Updated vet: 5', '2025-04-15 22:02:27'),
(87, 'root@localhost', 'Inserted new owner: 14', '2025-04-15 22:12:13'),
(88, 'root@localhost', 'Inserted new owner: 15', '2025-04-15 22:14:12'),
(89, 'root@localhost', 'Inserted new owner: 16', '2025-04-15 22:15:23'),
(90, 'root@localhost', 'Inserted new owner: 17', '2025-04-15 22:17:01'),
(91, 'root@localhost', 'Inserted new owner: 18', '2025-04-15 22:21:07'),
(92, 'superadmin@localhost', 'Inserted new animal: Kitty', '2025-04-15 22:24:44'),
(93, 'superadmin@localhost', 'Inserted new animal: Blob', '2025-04-15 22:25:19'),
(94, 'superadmin@localhost', 'Inserted new animal: Yuzi', '2025-04-15 22:30:55'),
(95, 'superadmin@localhost', 'Inserted new animal: Goofy', '2025-04-15 22:31:26'),
(96, 'superadmin@localhost', 'Inserted new animal: Hawk', '2025-04-15 22:31:52'),
(97, 'superadmin@localhost', 'Inserted new animal: Rex', '2025-04-15 22:32:08'),
(98, 'superadmin@localhost', 'Inserted new visit: 15', '2025-04-15 22:33:09'),
(99, 'superadmin@localhost', 'Inserted new spec visit: 1 - 1', '2025-04-15 22:33:09'),
(100, 'superadmin@localhost', 'Inserted new visit: 16', '2025-04-15 22:33:40'),
(101, 'superadmin@localhost', 'Updated spec visit: 1 - 1', '2025-04-15 22:33:40'),
(102, 'superadmin@localhost', 'Inserted new visit: 17', '2025-04-15 22:34:40'),
(103, 'superadmin@localhost', 'Inserted new spec visit: 1 - 2', '2025-04-15 22:34:40'),
(104, 'superadmin@localhost', 'Inserted new visit: 18', '2025-04-15 22:40:04'),
(105, 'superadmin@localhost', 'Inserted new spec visit: 3 - 5', '2025-04-15 22:40:04'),
(106, 'superadmin@localhost', 'Inserted new visit: 19', '2025-04-15 22:41:29'),
(107, 'superadmin@localhost', 'Inserted new spec visit: 2 - 3', '2025-04-15 22:41:29'),
(108, 'superadmin@localhost', 'Inserted new visit: 20', '2025-04-15 22:42:01'),
(109, 'superadmin@localhost', 'Inserted new spec visit: 3 - 6', '2025-04-15 22:42:01'),
(110, 'ria@localhost', 'Inserted new visit drug: 15 - 8', '2025-04-15 22:53:43'),
(111, 'root@localhost', 'Inserted new owner: 19', '2025-04-15 22:57:08'),
(112, 'root@localhost', 'Deleted owner: 19', '2025-04-15 23:00:20'),
(113, 'ria@localhost', 'Updated visit: 16', '2025-04-15 23:08:48'),
(114, 'martha@localhost', 'Inserted new visit drug: 19 - 14', '2025-04-15 23:14:17');

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
  `owner_password` varbinary(255) NOT NULL,
  `owner_username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `owners`
--

INSERT INTO `owners` (`owner_id`, `owner_givenname`, `owner_familyname`, `owner_address`, `owner_phone`, `owner_password`, `owner_username`) VALUES
(14, 'ahmad', 'iqbal', 'JL. SETH AJI', '083257348123', 0x243279243130246b66785177742e527042796d474e436c7848425a574f39724a595446574c546f7772774f4b386434563439454b4f315549354d546d, 'iqcarry'),
(15, 'eka', 'jaya', 'JL. KOBAR', '084312832374', 0x243279243130244a466f384d3637784f75346137322e334d644b504a4f45366a45363855794b4e6e594e626d4a2e527332456e427079663737777243, 'eka'),
(16, 'Febrianson', 'Christian', 'JL. SAMUDIN AMAN IV', '0821743952395', 0x243279243130244a484c2e6c4556356a51684876574862477333696a75534a744f7a6b2f617a4f4f45334e3061337035313447504b424b64776d4d36, 'ebi'),
(17, 'Luniko', 'Jamal', 'JL. BROMO III', '082374375045', 0x24327924313024373155382f2f7678656b526b41714872726551382e6545376642365765674361422e424944682e5054414b7a747655576859373769, 'niko'),
(18, 'Andika', 'Biasalah', 'JL.RAJAWALI', '082343728537', 0x243279243130244b6c51714e324e62455a6e74743976332e33695978656c626f3446756e387667366b3666764465523247752e616e7647476d544f79, 'dika');

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
-- Dumping data untuk tabel `spec_visit`
--

INSERT INTO `spec_visit` (`clinic_id`, `vet_id`, `sv_count`) VALUES
(1, 1, 2),
(1, 2, 1),
(2, 3, 1),
(3, 5, 1),
(3, 6, 1);

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
  `clinic_id` int(11) NOT NULL,
  `vet_username` varchar(50) NOT NULL,
  `vet_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `vet`
--

INSERT INTO `vet` (`vet_id`, `vet_title`, `vet_givenname`, `vet_familyname`, `vet_phone`, `vet_employed`, `spec_id`, `clinic_id`, `vet_username`, `vet_password`) VALUES
(1, 'Dr.', 'Ria', 'Agustin', '081234567890', '2021-01-01', 3, 1, 'ria', 'ksbdjbfkjsdkfhsdlkfhlksdflksdlfkdlskfj'),
(2, 'Prof', 'Irwin', 'Pangesti', '081234567891', '2021-01-02', 1, 1, 'irwin', 'huieq87f89284f94nft93q97rgf89qg349793423q4f98q3fq34f'),
(3, 'Dr.', 'Martha', 'Lena', '081234567892', '2021-01-03', 2, 2, 'martha', '8nqqg132d93g6n4f9q8n89g37nd79348nf9gen9rf'),
(4, 'Prof', 'Salsabila', 'Aprilia', '081234567893', '2021-01-04', 3, 2, 'caca', 'qi8dyn3894ynd33qdhn97q3endnq38'),
(5, 'Dr.', 'Bima', 'Saputra', '081234567894', '2021-01-05', 1, 3, 'bima', 'd3qdy7nd93w3d834gnd9gq3gd49g3q9gq3d4dq34'),
(6, 'Dr.', 'Abil', 'Abzari', '081234567895', '2021-01-06', 1, 3, 'abil', 'sdojfpodsfopsdpfpadfpawjepdfjpejf');

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
(15, '2025-04-03', 'Periksa Kesehatan Bulu', 16, 1, NULL),
(16, '2025-04-10', 'Perisa lanjutan', 16, 1, 15),
(17, '2025-03-17', 'Operasi karena luka saat berkelahi dengan pemiliknya', 20, 2, NULL),
(18, '2025-02-20', 'Perisa kesehatan gigi', 18, 5, NULL),
(19, '2025-04-15', 'Suntik Vaksin', 21, 3, NULL),
(20, '2025-04-15', 'Kontrol gizi', 17, 6, NULL);

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
CREATE TRIGGER `update_spec_visit_after_delete` AFTER DELETE ON `visit` FOR EACH ROW BEGIN
    DECLARE vet_spec_id INT;
    DECLARE vet_clinic_id INT;

    -- Dapatkan spesialisasi dan klinik dokter hewan
    SELECT spec_id, clinic_id INTO vet_spec_id, vet_clinic_id
    FROM vet WHERE vet_id = OLD.vet_id;

    -- Kurangi count kunjungan
    UPDATE spec_visit
    SET sv_count = sv_count - 1
    WHERE clinic_id = vet_clinic_id AND vet_id = OLD.vet_id;

    -- Hapus record jika count menjadi 0
    DELETE FROM spec_visit
    WHERE clinic_id = vet_clinic_id AND vet_id = OLD.vet_id AND sv_count <= 0;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_spec_visit_after_insert` AFTER INSERT ON `visit` FOR EACH ROW BEGIN
    DECLARE vet_spec_id INT;
    DECLARE vet_clinic_id INT;

    -- Dapatkan spesialisasi dan klinik dokter hewan
    SELECT spec_id, clinic_id INTO vet_spec_id, vet_clinic_id
    FROM vet WHERE vet_id = NEW.vet_id;

    -- Periksa apakah sudah ada record di spec_visit
    IF EXISTS (SELECT 1 FROM spec_visit WHERE clinic_id = vet_clinic_id AND vet_id = NEW.vet_id) THEN
        -- Update jumlah kunjungan
        UPDATE spec_visit
        SET sv_count = sv_count + 1
        WHERE clinic_id = vet_clinic_id AND vet_id = NEW.vet_id;
    ELSE
        -- Buat record baru
        INSERT INTO spec_visit (clinic_id, vet_id, sv_count)
        VALUES (vet_clinic_id, NEW.vet_id, 1);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_spec_visit_after_update` AFTER UPDATE ON `visit` FOR EACH ROW BEGIN
    DECLARE old_vet_spec_id INT;
    DECLARE old_vet_clinic_id INT;
    DECLARE new_vet_spec_id INT;
    DECLARE new_vet_clinic_id INT;

    -- Hanya proses jika vet_id berubah
    IF NEW.vet_id != OLD.vet_id THEN
        -- Dapatkan data vet lama
        SELECT spec_id, clinic_id INTO old_vet_spec_id, old_vet_clinic_id
        FROM vet WHERE vet_id = OLD.vet_id;

        -- Kurangi count di vet lama
        UPDATE spec_visit
        SET sv_count = sv_count - 1
        WHERE clinic_id = old_vet_clinic_id AND vet_id = OLD.vet_id;

        -- Dapatkan data vet baru
        SELECT spec_id, clinic_id INTO new_vet_spec_id, new_vet_clinic_id
        FROM vet WHERE vet_id = NEW.vet_id;

        -- Tambah count di vet baru
        IF EXISTS (SELECT 1 FROM spec_visit WHERE clinic_id = new_vet_clinic_id AND vet_id = NEW.vet_id) THEN
            UPDATE spec_visit
            SET sv_count = sv_count + 1
            WHERE clinic_id = new_vet_clinic_id AND vet_id = NEW.vet_id;
        ELSE
            INSERT INTO spec_visit (clinic_id, vet_id, sv_count)
            VALUES (new_vet_clinic_id, NEW.vet_id, 1);
        END IF;
    END IF;
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
(15, 8, '1/2 tablet', '3x sehari', 10),
(19, 14, '1 ml', '1x', 1);

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
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `animal_type`
--
ALTER TABLE `animal_type`
  MODIFY `at_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT untuk tabel `clinic`
--
ALTER TABLE `clinic`
  MODIFY `clinic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `drug`
--
ALTER TABLE `drug`
  MODIFY `drug_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `owners`
--
ALTER TABLE `owners`
  MODIFY `owner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `visit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
