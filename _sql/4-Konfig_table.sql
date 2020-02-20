DROP TABLE konfig;

-- --------------------------------------------------------

--
-- Table structure for table `konfig`
--

CREATE TABLE IF NOT EXISTS `konfig` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `jumlah_cuti_setahun` tinyint(2) NOT NULL,
  `max_ambil_cuti` tinyint(2) NOT NULL,
  `tgl_min_doj` tinyint(2) NOT NULL,
  `pending_cuti` tinyint(2) NOT NULL,
  `periode` smallint(4) NOT NULL,
  `min_tgl_pengajuan` tinyint(4) NOT NULL DEFAULT '0',
  `app` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `konfig`
--

INSERT INTO `konfig` (`id`, `jumlah_cuti_setahun`, `max_ambil_cuti`, `tgl_min_doj`, `pending_cuti`, `periode`, `min_tgl_pengajuan`, `app`) VALUES
(2, 12, 3, 21, 0, 2017, 10, 0);
