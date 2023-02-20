-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2023 at 04:31 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pwpb_aplikasi_pembayaran_spp`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `reset` ()   BEGIN
	DELETE FROM pembayaran;
	ALTER TABLE pembayaran AUTO_INCREMENT = 1;
    
    DELETE FROM aktivitas;
	ALTER TABLE aktivitas AUTO_INCREMENT = 1;
    
    DELETE FROM petugas;
	ALTER TABLE petugas AUTO_INCREMENT = 1;
    
    DELETE FROM spp_detail;
	ALTER TABLE spp_detail AUTO_INCREMENT = 1;
    
    DELETE FROM spp;
	ALTER TABLE spp AUTO_INCREMENT = 1;
    
    DELETE FROM siswa;
	ALTER TABLE siswa AUTO_INCREMENT = 1;
    
    DELETE FROM rombel;
	ALTER TABLE rombel AUTO_INCREMENT = 1;
    
    DELETE FROM kompetensi_keahlian;
	ALTER TABLE kompetensi_keahlian AUTO_INCREMENT = 1;
    
    DELETE FROM jurusan;
	ALTER TABLE jurusan AUTO_INCREMENT = 1;
    
    DELETE FROM tingkat;
	ALTER TABLE tingkat AUTO_INCREMENT = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `siswa` ()   SELECT 
	siswa.id, siswa.nisn, siswa.nis, siswa.nama, rombel.rombel, kompetensi_keahlian.kompetensi_keahlian, jurusan.jurusan, tingkat.tingkat, siswa.alamat, siswa.telepon, siswa.password, siswa.dibuat, siswa.diubah, spp.nominal AS `total_nominal`, SUM(pembayaran.jumlah_pembayaran) AS `total_jumlah_pembayaran`, siswaStatus(spp.nominal, SUM(pembayaran.jumlah_pembayaran))

FROM siswa
	INNER JOIN rombel ON siswa.id_rombel=rombel.id
    INNER JOIN kompetensi_keahlian ON rombel.id_kompetensi_keahlian=kompetensi_keahlian.id
    INNER JOIN jurusan ON rombel.id_jurusan=jurusan.id
    INNER JOIN tingkat ON rombel.id_tingkat=tingkat.id
    INNER JOIN spp_detail ON siswa.id=spp_detail.id_siswa
    INNER JOIN spp ON spp_detail.id_spp=spp.id
    LEFT JOIN pembayaran ON spp_detail.id=pembayaran.id_spp_detail
    
GROUP BY siswa.id, spp.nominal, siswa.id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `siswaById` (IN `P_id` INT(11))   SELECT 
	siswa.id, siswa.nisn, siswa.nis, siswa.nama, rombel.rombel, kompetensi_keahlian.kompetensi_keahlian, jurusan.jurusan, tingkat.tingkat, siswa.alamat, siswa.telepon, siswa.password, siswa.dibuat, siswa.diubah, spp.nominal AS `total_nominal`, SUM(pembayaran.jumlah_pembayaran) AS `total_jumlah_pembayaran`, siswaStatus(spp.nominal, SUM(pembayaran.jumlah_pembayaran))

FROM siswa
	INNER JOIN rombel ON siswa.id_rombel=rombel.id
    INNER JOIN kompetensi_keahlian ON rombel.id_kompetensi_keahlian=kompetensi_keahlian.id
    INNER JOIN jurusan ON rombel.id_jurusan=jurusan.id
    INNER JOIN tingkat ON rombel.id_tingkat=tingkat.id
    INNER JOIN spp_detail ON siswa.id=spp_detail.id_siswa
    INNER JOIN spp ON spp_detail.id_spp=spp.id
    LEFT JOIN pembayaran ON spp_detail.id=pembayaran.id_spp_detail
 
WHERE siswa.id=P_id
 
GROUP BY siswa.id, spp.nominal, siswa.id$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `siswaStatus` (`P_nominal` INT(11), `P_jumlah_pembayaran` INT(11)) RETURNS VARCHAR(255) CHARSET utf8mb4  RETURN IF(P_nominal=P_jumlah_pembayaran, "Sudah Lunas", "Belum Lunas")$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `aktivitas`
--

CREATE TABLE `aktivitas` (
  `id` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `aktivitas` varchar(255) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL,
  `jurusan` varchar(255) NOT NULL,
  `singkatan` varchar(255) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `diubah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kompetensi_keahlian`
--

CREATE TABLE `kompetensi_keahlian` (
  `id` int(11) NOT NULL,
  `kompetensi_keahlian` varchar(255) NOT NULL,
  `singkatan` varchar(255) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `diubah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `id_petugas` int(11) NOT NULL,
  `id_spp_detail` int(11) NOT NULL,
  `bukti_pembayaran` varchar(255) NOT NULL,
  `tanggal_pembayaran` date NOT NULL,
  `bulan_pembayaran` int(11) NOT NULL,
  `jumlah_pembayaran` int(11) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `level` enum('petugas','admin','superadmin') NOT NULL,
  `status` enum('tidak aktif','aktif') NOT NULL DEFAULT 'tidak aktif',
  `password` varchar(255) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `diubah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id`, `nama`, `username`, `telepon`, `level`, `status`, `password`, `dibuat`, `diubah`) VALUES
(1, 'Superadmin 1', 'superadmin1', '1', 'superadmin', 'aktif', '2c7b0576873ffcbb4ca61c5a225b94e7', '2023-02-20 03:18:59', '2023-02-20 03:25:04'),
(2, 'Admin 1', 'admin1', '1', 'admin', 'aktif', 'e00cf25ad42683b3df678c61f42c6bda', '2023-02-20 03:24:50', '2023-02-20 03:24:50'),
(3, 'Petugas 1', 'petugas1', '1', 'petugas', 'aktif', 'b53fe7751b37e40ff34d012c7774d65f', '2023-02-20 03:26:26', '2023-02-20 03:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `rombel`
--

CREATE TABLE `rombel` (
  `id` int(11) NOT NULL,
  `id_kompetensi_keahlian` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `id_tingkat` int(11) NOT NULL,
  `rombel` varchar(255) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `diubah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id` int(11) NOT NULL,
  `nisn` varchar(255) NOT NULL,
  `nis` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `id_rombel` int(11) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `diubah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `spp`
--

CREATE TABLE `spp` (
  `id` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nominal` int(11) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `diubah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Triggers `spp`
--
DELIMITER $$
CREATE TRIGGER `createSPPNominal` BEFORE INSERT ON `spp` FOR EACH ROW BEGIN
    	IF (NEW.nominal < 0) THEN
        	SET NEW.nominal = 0;
        END IF;
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `updateSPPNominal` BEFORE UPDATE ON `spp` FOR EACH ROW BEGIN
	IF (NEW.nominal < 0) THEN
    	SET NEW.nominal = 0;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `spp_detail`
--

CREATE TABLE `spp_detail` (
  `id` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_spp` int(11) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tingkat`
--

CREATE TABLE `tingkat` (
  `id` int(11) NOT NULL,
  `tingkat` varchar(255) NOT NULL,
  `dibuat` timestamp NOT NULL DEFAULT current_timestamp(),
  `diubah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aktivitas`
--
ALTER TABLE `aktivitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_id_petugas_aktivitas` (`id_petugas`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kompetensi_keahlian`
--
ALTER TABLE `kompetensi_keahlian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_spp_detail` (`id_spp_detail`,`bulan_pembayaran`),
  ADD KEY `FK_id_petugas` (`id_petugas`),
  ADD KEY `FK_id_spp_detail` (`id_spp_detail`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `rombel`
--
ALTER TABLE `rombel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_id_kompetensi_keahlian` (`id_kompetensi_keahlian`),
  ADD KEY `FK_id_jurusan` (`id_jurusan`),
  ADD KEY `FK_id_tingkat` (`id_tingkat`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nisn` (`nisn`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `FK_id_rombel` (`id_rombel`);

--
-- Indexes for table `spp`
--
ALTER TABLE `spp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spp_detail`
--
ALTER TABLE `spp_detail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_siswa` (`id_siswa`,`id_spp`),
  ADD KEY `FK_id_spp` (`id_spp`);

--
-- Indexes for table `tingkat`
--
ALTER TABLE `tingkat`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivitas`
--
ALTER TABLE `aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kompetensi_keahlian`
--
ALTER TABLE `kompetensi_keahlian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rombel`
--
ALTER TABLE `rombel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spp`
--
ALTER TABLE `spp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spp_detail`
--
ALTER TABLE `spp_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tingkat`
--
ALTER TABLE `tingkat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aktivitas`
--
ALTER TABLE `aktivitas`
  ADD CONSTRAINT `FK_id_petugas_aktivitas` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `FK_id_petugas` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id`),
  ADD CONSTRAINT `FK_id_spp_detail` FOREIGN KEY (`id_spp_detail`) REFERENCES `spp_detail` (`id`);

--
-- Constraints for table `rombel`
--
ALTER TABLE `rombel`
  ADD CONSTRAINT `FK_id_jurusan` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`),
  ADD CONSTRAINT `FK_id_kompetensi_keahlian` FOREIGN KEY (`id_kompetensi_keahlian`) REFERENCES `kompetensi_keahlian` (`id`),
  ADD CONSTRAINT `FK_id_tingkat` FOREIGN KEY (`id_tingkat`) REFERENCES `tingkat` (`id`);

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `FK_id_rombel` FOREIGN KEY (`id_rombel`) REFERENCES `rombel` (`id`);

--
-- Constraints for table `spp_detail`
--
ALTER TABLE `spp_detail`
  ADD CONSTRAINT `FK_id_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id`),
  ADD CONSTRAINT `FK_id_spp` FOREIGN KEY (`id_spp`) REFERENCES `spp` (`id`),
  ADD CONSTRAINT `spp_detail_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
