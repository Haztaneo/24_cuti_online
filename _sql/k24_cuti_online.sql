-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2016 at 04:03 AM
-- Server version: 5.6.14
-- PHP Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `k24_cuti_online`
--
CREATE DATABASE IF NOT EXISTS `k24_cuti_online` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `k24_cuti_online`;

-- --------------------------------------------------------

--
-- Table structure for table `agama`
--

CREATE TABLE IF NOT EXISTS `agama` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `agama`
--

INSERT INTO `agama` (`id`, `nama`) VALUES
(1, 'ISLAM'),
(2, 'KATHOLIK'),
(3, 'PROTESTAN'),
(4, 'HINDU'),
(5, 'BUDHA'),
(6, 'LAINNYA');

-- --------------------------------------------------------

--
-- Table structure for table `atasan`
--

CREATE TABLE IF NOT EXISTS `atasan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_divisi_id` int(11) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `keterangan` varchar(50) DEFAULT NULL,
  `is_manager` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `dept_divisi_id` (`dept_divisi_id`),
  KEY `pegawai_id` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cuti_bersama`
--

CREATE TABLE IF NOT EXISTS `cuti_bersama` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `periode` smallint(4) NOT NULL,
  `tgl` date NOT NULL,
  `hari` varchar(6) DEFAULT NULL,
  `keterangan` text,
  PRIMARY KEY (`id`),
  KEY `periode_id` (`periode`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cuti_bersama`
--

INSERT INTO `cuti_bersama` (`id`, `periode`, `tgl`, `hari`, `keterangan`) VALUES
(1, 2016, '2016-07-09', 'SABTU', 'Cuti Bersama Idul Fitri 7437 H'),
(2, 2016, '2016-07-08', 'JUMAT', 'Cuti Bersama Idul Fitri 7437 H'),
(3, 2016, '2016-12-26', 'SENIN', 'Cuti Bersama Natal 2016');

-- --------------------------------------------------------

--
-- Table structure for table `dept_divisi`
--

CREATE TABLE IF NOT EXISTS `dept_divisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `max_cuti_tahunan` tinyint(2) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Aktif,0=Tidak Aktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `dept_divisi`
--

INSERT INTO `dept_divisi` (`id`, `kode`, `nama`, `max_cuti_tahunan`, `status`) VALUES
(1, '001', 'IT', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `izin`
--

CREATE TABLE IF NOT EXISTS `izin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` smallint(4) NOT NULL,
  `bulan` tinyint(2) NOT NULL,
  `kode` varchar(25) NOT NULL,
  `tgl_pengajuan` date NOT NULL,
  `pegawai_id` int(11) NOT NULL,
  `uid` varchar(50) DEFAULT NULL,
  `pegawai_nama_lengkap` varchar(50) NOT NULL,
  `dept_divisi_nama` varchar(50) NOT NULL,
  `tipe_izin_id` int(11) NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_akhir` date NOT NULL,
  `jumlah_hari` tinyint(4) NOT NULL,
  `sisa_cuti` tinyint(4) NOT NULL DEFAULT '0',
  `jumlah_cuti_bersama` tinyint(4) NOT NULL DEFAULT '0',
  `alasan` text NOT NULL,
  `attach_dokumen` text,
  `approval_note` text,
  `disetujui_id` int(11) DEFAULT NULL,
  `disetujui_nama` varchar(50) DEFAULT NULL,
  `disetujui_tgl` datetime DEFAULT NULL,
  `diketahui_id` int(11) DEFAULT NULL,
  `diketahui_nama` varchar(50) DEFAULT NULL,
  `diketahui_tgl` datetime DEFAULT NULL,
  `status_proses` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=diajukan,1=disetujui,2=diketahui',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=New,1=Approve,2=Cancel',
  `cancel_by` varchar(50) DEFAULT NULL,
  `cancel_note` text,
  `cancel_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipe_izin_id` (`tipe_izin_id`),
  KEY `pegawai_id` (`pegawai_id`),
  KEY `disetujui_id` (`disetujui_id`),
  KEY `diketahui_id` (`diketahui_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `konfig`
--

CREATE TABLE IF NOT EXISTS `konfig` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `jumlah_cuti_setahun` tinyint(2) NOT NULL,
  `jumlah_cuti_bersama` tinyint(2) NOT NULL,
  `max_ambil_cuti` tinyint(2) NOT NULL,
  `tgl_min_doj` tinyint(2) NOT NULL,
  `periode` smallint(4) NOT NULL,
  `min_tgl_pengajuan` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `konfig`
--

INSERT INTO `konfig` (`id`, `jumlah_cuti_setahun`, `jumlah_cuti_bersama`, `max_ambil_cuti`, `tgl_min_doj`, `periode`, `min_tgl_pengajuan`) VALUES
(2, 12, 3, 3, 21, 2016, 10);

-- --------------------------------------------------------

--
-- Table structure for table `lokasi`
--

CREATE TABLE IF NOT EXISTS `lokasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `minggu` tinyint(1) DEFAULT '-1',
  `senin` tinyint(1) DEFAULT '-1',
  `selasa` tinyint(1) DEFAULT '-1',
  `rabu` tinyint(1) DEFAULT '-1',
  `kamis` tinyint(1) DEFAULT '-1',
  `jumat` tinyint(1) DEFAULT '-1',
  `sabtu` tinyint(1) DEFAULT '-1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `lokasi`
--

INSERT INTO `lokasi` (`id`, `nama`, `status`, `minggu`, `senin`, `selasa`, `rabu`, `kamis`, `jumat`, `sabtu`) VALUES
(1, 'Yogyakarta', 1, 0, -1, -1, -1, -1, -1, 6),
(2, 'Jakarta', 1, 0, -1, -1, -1, -1, -1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE IF NOT EXISTS `pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nik` varchar(30) DEFAULT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `doj` date NOT NULL,
  `dept_divisi_id` int(11) NOT NULL,
  `lokasi_id` int(11) NOT NULL,
  `nama_panggilan` varchar(15) DEFAULT NULL,
  `sex` enum('L','P') NOT NULL,
  `agama_id` int(11) NOT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `ktp` varchar(16) DEFAULT NULL,
  `alamat_ktp` text,
  `hp` varchar(15) DEFAULT NULL,
  `sisa_cuti` tinyint(2) NOT NULL DEFAULT '0',
  `pending_cuti` tinyint(2) NOT NULL DEFAULT '0',
  `is_doj_full` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=Full jatah cuti,0=Tidak Full',
  `uid_atasan` varchar(50) DEFAULT NULL,
  `nama_atasan` varchar(50) DEFAULT NULL,
  `max_cuti` tinyint(4) NOT NULL DEFAULT '0',
  `allow_izin` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ctl` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `dept_divisi_id` (`dept_divisi_id`),
  KEY `lokasi_id` (`lokasi_id`),
  KEY `agama_id` (`agama_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `tipe_izin`
--

CREATE TABLE IF NOT EXISTS `tipe_izin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `is_potong_cuti` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=Tidak Memotong Cuti,1=Memotong Cuti,2=Cuti Khusus',
  `jatah_cuti` tinyint(2) NOT NULL DEFAULT '0',
  `is_must_attach` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Aktif,0=Tidak Aktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tipe_izin`
--

INSERT INTO `tipe_izin` (`id`, `nama`, `is_potong_cuti`, `jatah_cuti`, `is_must_attach`, `status`) VALUES
(1, 'Cuti Tahunan', 1, 12, 0, 1),
(2, 'Sakit (surat dokter terlampir)', 0, 0, 1, 1),
(3, 'Ijin Khusus sesuai PP pasal 22', 0, 0, 0, 1),
(4, 'Terlambat', 0, 0, 0, 1),
(5, 'Cuti / izin di luar tanggungan', 1, 0, 0, 1),
(6, 'Pulang cepat', 0, 0, 0, 1),
(7, 'Cuti melahirkan / gugur kandungan', 0, 60, 0, 1),
(8, 'Lainnya ...........................', 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_admin`
--

CREATE TABLE IF NOT EXISTS `user_admin` (
  `uid` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `is_receive_email` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Ya,0=Tidak',
  `status` tinyint(1) DEFAULT '1' COMMENT '1=Aktif,0=Tidak Aktif',
  `show_admin_page` tinyint(1) NOT NULL DEFAULT '1',
  `level` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_admin`
--

INSERT INTO `user_admin` (`uid`, `email`, `nama`, `is_receive_email`, `status`, `show_admin_page`, `level`) VALUES
('hasta.bayu', 'hasta.bayu@k24.co.id', 'Hasta', 1, 1, 1, 3),
('yakobus.budiman', 'yakobus.budiman@k24.co.id', 'Yakobus Budiman', 1, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_ldap`
--

CREATE TABLE IF NOT EXISTS `user_ldap` (
  `uid` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `atasan`
--
ALTER TABLE `atasan`
  ADD CONSTRAINT `atasan_ibfk_1` FOREIGN KEY (`dept_divisi_id`) REFERENCES `dept_divisi` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `izin`
--
ALTER TABLE `izin`
  ADD CONSTRAINT `izin_ibfk_1` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `izin_ibfk_2` FOREIGN KEY (`tipe_izin_id`) REFERENCES `tipe_izin` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `izin_ibfk_4` FOREIGN KEY (`disetujui_id`) REFERENCES `pegawai` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `izin_ibfk_5` FOREIGN KEY (`diketahui_id`) REFERENCES `pegawai` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_2` FOREIGN KEY (`dept_divisi_id`) REFERENCES `dept_divisi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pegawai_ibfk_3` FOREIGN KEY (`lokasi_id`) REFERENCES `lokasi` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pegawai_ibfk_4` FOREIGN KEY (`agama_id`) REFERENCES `agama` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
