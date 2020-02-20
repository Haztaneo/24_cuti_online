ALTER TABLE `lokasi` ADD `potong_cuti_bersama` INT(2) NOT NULL DEFAULT '0' ;
ALTER TABLE `user_admin` ADD `user_role_id` INT(11) NOT NULL DEFAULT '0' ;
ALTER TABLE `pegawai` DROP `jumlah_cuti_bersama`;
ALTER TABLE `konfig` DROP `jumlah_cuti_bersama`;
ALTER TABLE `user_admin` ADD `show_report` TINYINT(1) NOT NULL DEFAULT '1' AFTER `show_admin_page`;
ALTER TABLE `user_admin` ADD `akses_report` TINYINT(1) NOT NULL DEFAULT '1' AFTER `show_report`;


CREATE TABLE IF NOT EXISTS `user_permission` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;

INSERT INTO `user_permission` (`id`, `name`) VALUES
(1, 'laporan_penggunaan_cuti'),
(3, 'laporan_kalender_pegawai'),
(22, 'laporan_sisa_cuti'),
(44, 'laporan_kalender_divisi');

CREATE TABLE IF NOT EXISTS `user_role` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

INSERT INTO `user_role` (`id`, `name`, `description`) VALUES
(1, 'Semua Laporan', 'Dapat mengakses semua halaman Laporan');

CREATE TABLE IF NOT EXISTS `user_role_permission` (
`id` int(11) NOT NULL,
  `user_role_id` int(11) NOT NULL,
  `user_permission_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

INSERT INTO `user_role_permission` (`id`, `user_role_id`, `user_permission_id`) VALUES
(25, 1, 1),
(26, 1, 3),
(27, 1, 22),
(28, 1, 44);

ALTER TABLE `user_permission`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `user_role`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `user_role_permission`
 ADD PRIMARY KEY (`id`), ADD KEY `user_role_id` (`user_role_id`), ADD KEY `user_permission_id` (`user_permission_id`);

ALTER TABLE `user_permission`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;

ALTER TABLE `user_role`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;

ALTER TABLE `user_role_permission`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;

ALTER TABLE `user_role_permission`
ADD CONSTRAINT `user_role_permission_ibfk_1` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `user_role_permission_ibfk_2` FOREIGN KEY (`user_permission_id`) REFERENCES `user_permission` (`id`) ON UPDATE CASCADE;
