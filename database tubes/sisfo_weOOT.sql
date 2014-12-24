-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Inang: 127.0.0.1
-- Waktu pembuatan: 12 Des 2014 pada 16.39
-- Versi Server: 5.5.27
-- Versi PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Basis data: `sisfo_we`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `fixed_asset`
--

CREATE TABLE IF NOT EXISTS `fixed_asset` (
  `fasset_id` varchar(10) NOT NULL DEFAULT '',
  `fk_fasset` varchar(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date_acquired` varchar(20) DEFAULT NULL,
  `last_service` varchar(20) DEFAULT NULL,
  `next_service` varchar(20) DEFAULT NULL,
  `prod_capacity` int(11) DEFAULT NULL,
  `uom` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`fasset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `fixed_asset`
--

INSERT INTO `fixed_asset` (`fasset_id`, `fk_fasset`, `name`, `date_acquired`, `last_service`, `next_service`, `prod_capacity`, `uom`) VALUES
('fa1', '1000', 'pencil labeller #1', '12 juni 2000', '12 juni 2000', '12 juni 2001', 1000000, 'pens/day');

-- --------------------------------------------------------

--
-- Struktur dari tabel `fixed_asset_assign`
--

CREATE TABLE IF NOT EXISTS `fixed_asset_assign` (
  `faa_id` varchar(10) NOT NULL DEFAULT '',
  `fk_faa` varchar(10) DEFAULT NULL,
  `fk_faa2` varchar(10) DEFAULT NULL,
  `from_date` varchar(10) DEFAULT NULL,
  `thru_date` varchar(10) DEFAULT NULL,
  `comm` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`faa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `fixed_asset_type`
--

CREATE TABLE IF NOT EXISTS `fixed_asset_type` (
  `fat_id` varchar(10) NOT NULL DEFAULT '',
  `des` varchar(10) DEFAULT NULL,
  `parent_asset` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`fat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `fixed_asset_type`
--

INSERT INTO `fixed_asset_type` (`fat_id`, `des`, `parent_asset`) VALUES
('1000', 'pm machine', 'equipment'),
('1390', 'pm machine', 'equipment'),
('2266', 'fork lift', 'vehicle');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_item`
--

CREATE TABLE IF NOT EXISTS `order_item` (
  `oi_seq_id` varchar(10) NOT NULL DEFAULT '',
  `est_deliv_date` varchar(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` int(11) DEFAULT NULL,
  PRIMARY KEY (`oi_seq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `order_item`
--

INSERT INTO `order_item` (`oi_seq_id`, `est_deliv_date`, `quantity`, `unit_price`) VALUES
('oi01', '2 oktober 2000', 2000, 100000),
('oi02', '2 oktober 2000', 2500, 100000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `party`
--

CREATE TABLE IF NOT EXISTS `party` (
  `party_id` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`party_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `party`
--

INSERT INTO `party` (`party_id`, `name`) VALUES
('p02', 'John Smith'),
('p03', 'Sam Bossman'),
('p04', 'Dick Jones'),
('p05', 'Bob Jenkins'),
('p06', 'Jane Smith');

-- --------------------------------------------------------

--
-- Struktur dari tabel `party_faa`
--

CREATE TABLE IF NOT EXISTS `party_faa` (
  `pfaa_id` varchar(10) NOT NULL DEFAULT '',
  `fk_pfaa` varchar(10) DEFAULT NULL,
  `fk_pfaa2` varchar(10) DEFAULT NULL,
  `start_date` varchar(10) DEFAULT NULL,
  `end_date` varchar(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`pfaa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `party_skill_data`
--

CREATE TABLE IF NOT EXISTS `party_skill_data` (
  `psd_id` varchar(10) NOT NULL DEFAULT '',
  `fk_psd` varchar(10) DEFAULT NULL,
  `skill_type` varchar(50) DEFAULT NULL,
  `years_of_exp` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  PRIMARY KEY (`psd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `party_skill_data`
--

INSERT INTO `party_skill_data` (`psd_id`, `fk_psd`, `skill_type`, `years_of_exp`, `rating`) VALUES
('001', 'p01', 'market research', 30, 9),
('002', 'p02', 'project management', 20, 10),
('003', 'p02', 'marketing', 5, 6),
('004', 'p04', 'project management', 12, 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `party_work_req_role`
--

CREATE TABLE IF NOT EXISTS `party_work_req_role` (
  `pwrr_id` varchar(10) NOT NULL DEFAULT '',
  `fk_pwrr` varchar(10) DEFAULT NULL,
  `fk_pwrr2` varchar(10) DEFAULT NULL,
  `fk_pwrr3` varchar(10) DEFAULT NULL,
  `from_date` varchar(20) DEFAULT NULL,
  `thru_date` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`pwrr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `party_work_req_role`
--

INSERT INTO `party_work_req_role` (`pwrr_id`, `fk_pwrr`, `fk_pwrr2`, `fk_pwrr3`, `from_date`, `thru_date`) VALUES
('pwrr01', '50985', 'rrt01', 'p01', '5 juli 2000', ' '),
('pwrr02', '50985', 'rrt02', 'p02', '5 juli 2000', ' '),
('pwrr03', '50985', 'rrt03', 'p02', '5 juli 2000', '15 desember 2000'),
('pwrr04', '50985', 'rrt04', 'p03', '8 juli 2000', ' '),
('pwrr05', '50985', 'rrt03', 'p04', '16 desember 2000', '20 februari 2001'),
('pwrr06', '50985', 'rrt03', 'p02', '21 februari 2001', ' '),
('pwrr07', '60102', 'rrt01', 'p03', '10 juni 2000', ' '),
('pwrr08', '60102', 'rrt03', 'p04', '15 juni 2000', '1 januari 2001');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rare_type`
--

CREATE TABLE IF NOT EXISTS `rare_type` (
  `raretype_id` varchar(10) NOT NULL DEFAULT '',
  `des` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`raretype_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `rare_type`
--

INSERT INTO `rare_type` (`raretype_id`, `des`) VALUES
('r1', 'regular pay'),
('r2', 'overtime billing'),
('r3', 'regular billing'),
('r4', 'overtime pay');

-- --------------------------------------------------------

--
-- Struktur dari tabel `req_role_type`
--

CREATE TABLE IF NOT EXISTS `req_role_type` (
  `rrt_id` varchar(10) NOT NULL DEFAULT '',
  `des` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`rrt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `req_role_type`
--

INSERT INTO `req_role_type` (`rrt_id`, `des`) VALUES
('rrt01', 'created for'),
('rrt02', 'created by'),
('rrt03', 'responsible for'),
('rrt04', 'authorized by');

-- --------------------------------------------------------

--
-- Struktur dari tabel `req_type`
--

CREATE TABLE IF NOT EXISTS `req_type` (
  `req_type_id` varchar(10) NOT NULL DEFAULT '',
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`req_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `req_type`
--

INSERT INTO `req_type` (`req_type_id`, `description`) VALUES
('5', 'production run'),
('6', 'internal project'),
('7', 'maintenance');

-- --------------------------------------------------------

--
-- Struktur dari tabel `time_sheet_entry`
--

CREATE TABLE IF NOT EXISTS `time_sheet_entry` (
  `tse_id` varchar(10) NOT NULL DEFAULT '',
  `ts_from` varchar(20) DEFAULT NULL,
  `ts_thru` varchar(20) DEFAULT NULL,
  `fk_tse` varchar(10) DEFAULT NULL,
  `we_id` varchar(10) DEFAULT NULL,
  `te_from` varchar(20) DEFAULT NULL,
  `te_thru` varchar(20) DEFAULT NULL,
  `hours` int(11) DEFAULT NULL,
  PRIMARY KEY (`tse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `time_sheet_entry`
--

INSERT INTO `time_sheet_entry` (`tse_id`, `ts_from`, `ts_thru`, `fk_tse`, `we_id`, `te_from`, `te_thru`, `hours`) VALUES
('1390', '1 jan 2001', '15 jan 2001', 'p02', '29000', '2 jan 2001', '4 jan 2001', 13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(20) NOT NULL,
  `userlevel` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `userlevel`) VALUES
(2, 'admin', 'admin', -1),
(3, 'user', 'user', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `we_breakdown`
--

CREATE TABLE IF NOT EXISTS `we_breakdown` (
  `id` varchar(10) NOT NULL DEFAULT '',
  `fk_we_bd` varchar(10) DEFAULT NULL,
  `we_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `we_breakdown`
--

INSERT INTO `we_breakdown` (`id`, `fk_we_bd`, `we_id`) VALUES
('1', '120001', '12000'),
('2', '120001', '28045'),
('4', '120002', '28045');

-- --------------------------------------------------------

--
-- Struktur dari tabel `we_fa_req`
--

CREATE TABLE IF NOT EXISTS `we_fa_req` (
  `wefr_id` varchar(10) NOT NULL DEFAULT '',
  `fk_wefr` varchar(10) DEFAULT NULL,
  `fk_wefr2` varchar(10) DEFAULT NULL,
  `est_quantity` int(11) DEFAULT NULL,
  `est_duration` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`wefr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `we_from_order_item`
--

CREATE TABLE IF NOT EXISTS `we_from_order_item` (
  `wfoi_id` varchar(10) NOT NULL DEFAULT '',
  `fk_wfoi2` varchar(10) DEFAULT NULL,
  `fk_wfoi` varchar(10) DEFAULT NULL,
  `req_item` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`wfoi_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `we_from_order_item`
--

INSERT INTO `we_from_order_item` (`wfoi_id`, `fk_wfoi2`, `fk_wfoi`, `req_item`) VALUES
('wfoi01', '29534', 'oi02', 'customized'),
('wfoi02', '29874', 'oi02', 'customized');

-- --------------------------------------------------------

--
-- Struktur dari tabel `we_from_work_req`
--

CREATE TABLE IF NOT EXISTS `we_from_work_req` (
  `wfwr_id` varchar(10) NOT NULL DEFAULT '',
  `fk_wfwr` varchar(10) DEFAULT NULL,
  `fk_wfwr2` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`wfwr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `we_from_work_req`
--

INSERT INTO `we_from_work_req` (`wfwr_id`, `fk_wfwr`, `fk_wfwr2`) VALUES
('wfwr01', '28045', '50985'),
('wfwr02', '28045', '51245'),
('wfwr03', '51285', '51285'),
('wfwr04', '51298', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `we_good_standard`
--

CREATE TABLE IF NOT EXISTS `we_good_standard` (
  `wegs_id` varchar(10) NOT NULL DEFAULT '',
  `fk_wegs` varchar(10) DEFAULT NULL,
  `item` varchar(50) DEFAULT NULL,
  `est_quantity` int(11) DEFAULT NULL,
  `est_cost` int(11) DEFAULT NULL,
  PRIMARY KEY (`wegs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `we_party_assignment_data`
--

CREATE TABLE IF NOT EXISTS `we_party_assignment_data` (
  `wepad_id` varchar(10) NOT NULL DEFAULT '',
  `fk_wepad` varchar(10) DEFAULT NULL,
  `fk_wepad2` varchar(10) DEFAULT NULL,
  `we_role_type` varchar(50) DEFAULT NULL,
  `from_date` varchar(20) DEFAULT NULL,
  `thru_date` varchar(20) DEFAULT NULL,
  `com` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`wepad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `we_party_assignment_data`
--

INSERT INTO `we_party_assignment_data` (`wepad_id`, `fk_wepad`, `fk_wepad2`, `we_role_type`, `from_date`, `thru_date`, `com`) VALUES
('wepad01', '39409', 'p04', 'project manager', '2 januari 2001', '15 september 2001', ''),
('wepad02', '39409', 'p05', 'project administrator', '', '', ''),
('wepad03', '39409', 'p02', 'team member', '5 maret 2001', '6 agustus 2001', 'leaving vacation on 7 agustus, 2001'),
('wepad04', '39409', 'p02', 'team member', '1 september 2001', '2 desember 2001', ''),
('wepad05', '39409', 'p06', 'team member', '6 agustus 2001', '15 september 2001', 'very excited about assignment');

-- --------------------------------------------------------

--
-- Struktur dari tabel `we_rate`
--

CREATE TABLE IF NOT EXISTS `we_rate` (
  `werate_id` varchar(10) NOT NULL DEFAULT '',
  `work_task` varchar(50) DEFAULT NULL,
  `fk_werate` varchar(10) DEFAULT NULL,
  `fk_werate2` varchar(10) DEFAULT NULL,
  `from_date` varchar(10) DEFAULT NULL,
  `thru_date` varchar(10) DEFAULT NULL,
  `rate` int(11) DEFAULT NULL,
  PRIMARY KEY (`werate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `we_rate`
--

INSERT INTO `we_rate` (`werate_id`, `work_task`, `fk_werate`, `fk_werate2`, `from_date`, `thru_date`, `rate`) VALUES
('werate1', 'develop accounting program', 'p07', 'r3', '15may2000', '14may2001', 65);

-- --------------------------------------------------------

--
-- Struktur dari tabel `we_status`
--

CREATE TABLE IF NOT EXISTS `we_status` (
  `status_id` varchar(10) NOT NULL DEFAULT '',
  `cfk_we_status` varchar(10) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `we_status`
--

INSERT INTO `we_status` (`status_id`, `cfk_we_status`, `status`) VALUES
('s1', '1', 'started 2jun2000 1pm, complete 2jun2000 2pm'),
('s2', '4', 'started 3jun2000 1pm, complete 3jun2000 4pm');

-- --------------------------------------------------------

--
-- Struktur dari tabel `we_type`
--

CREATE TABLE IF NOT EXISTS `we_type` (
  `wetype_id` varchar(10) NOT NULL DEFAULT '',
  `des` varchar(50) DEFAULT NULL,
  `standard_work_hours` int(11) DEFAULT NULL,
  PRIMARY KEY (`wetype_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `we_type`
--

INSERT INTO `we_type` (`wetype_id`, `des`, `standard_work_hours`) VALUES
('type1', 'job', 0),
('type2', 'activity', 20),
('type3', 'activity', 10),
('type4', 'activity', 5),
('type5', 'task', 5),
('type6', 'task', 8),
('type7', 'task', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_effort`
--

CREATE TABLE IF NOT EXISTS `work_effort` (
  `we_id` varchar(10) NOT NULL DEFAULT '',
  `fk_we` varchar(10) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `des` varchar(50) DEFAULT NULL,
  `start_date` varchar(20) DEFAULT NULL,
  `completion_date` varchar(20) DEFAULT NULL,
  `estimated_hours` int(11) DEFAULT NULL,
  PRIMARY KEY (`we_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `work_effort`
--

INSERT INTO `work_effort` (`we_id`, `fk_we`, `name`, `des`, `start_date`, `completion_date`, `estimated_hours`) VALUES
('12000', 'type2', 'set up production line', '', '1 juni 2001', '4 juni 2001', 20),
('28045', 'type1', 'production run', 'prod run of 3500 pencils', '1 juni 2000', '4 juni 2000', 0),
('29000', 'type3', 'develop project plan', '', '', '', 6),
('29534', 'type1', 'production run #1', 'prod run of 1500 pencils', '23 februari 2001', '4 juni 2001', 0),
('29874', 'type1', 'production run #2', 'prod run of 1000 pencils', '23 maret 2001', '4 juni 2001', 0),
('34545', 'type6', 'move pen manufactur in place', '', '1 juni 2000', '1 juni 2001', 7),
('39409', 'type4', 'develop a sales and marketing', '', '2 januari 2001', '15 september 2001', 0),
('51285', 'type1', 'production run', 'prod run of 1500 pencils', '5 desember 2000', '4 januari 2000', 0),
('51298', 'type1', 'production run', 'prod run of 1500 pencils', '6 desember 2000', '1 februari 2001', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_req`
--

CREATE TABLE IF NOT EXISTS `work_req` (
  `work_req_id` varchar(10) NOT NULL DEFAULT '',
  `fk_work_req` varchar(10) DEFAULT NULL,
  `req_creation_date` varchar(20) DEFAULT NULL,
  `req_by_date` varchar(20) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`work_req_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `work_req`
--

INSERT INTO `work_req` (`work_req_id`, `fk_work_req`, `req_creation_date`, `req_by_date`, `description`) VALUES
('50985', '5', '5 juli 2000', '5 agustus 2000', 'anticipated demand of 2000 custom-engraved'),
('51245', '5', '5 september 2000', '5 november 2000', 'anticipated demand of 1500 custom-engraved'),
('51285', '5', '8 november 2000', '5 desember 2000', 'anticipated demand of 3000 custom-engraved'),
('60102', '6', '15 oktober 2000', '15 desember 2000', 'develop sales and marketing plan 2002'),
('70485', '7', '16 juni 2000', '18 juni 2000', 'fix engraving machine');

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_req_type`
--

CREATE TABLE IF NOT EXISTS `work_req_type` (
  `wrt_id` varchar(10) NOT NULL DEFAULT '',
  `fk_work_req_type` varchar(10) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `product` varchar(50) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `deliverable` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`wrt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `work_req_type`
--

INSERT INTO `work_req_type` (`wrt_id`, `fk_work_req_type`, `description`, `product`, `quantity`, `deliverable`) VALUES
('01', '50985', 'Anticipated demand Engraved 2000', 'engraved black-pen with gold trim', 2000, '-'),
('02', '51245', 'Anticipated demand Engraved 1500', 'engraved black-pen with gold trim', 1500, '-'),
('03', '51285', 'Anticipated demand Engraved 3000', 'engraved black-pen with gold trim', 3000, '-'),
('04', '60102', 'Develop sales and marketing plan for 2001', '-', 0, '2001 Sales/Marketing Plan'),
('05', '70485', 'fix engraving machine', '-', 0, '-');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
