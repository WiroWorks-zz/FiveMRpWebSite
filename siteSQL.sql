-- --------------------------------------------------------
-- Sunucu:                       127.0.0.1
-- Sunucu sürümü:                10.4.20-MariaDB - mariadb.org binary distribution
-- Sunucu İşletim Sistemi:       Win64
-- HeidiSQL Sürüm:               11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- wiro için veritabanı yapısı dökülüyor
CREATE DATABASE IF NOT EXISTS `wiro` /*!40100 DEFAULT CHARACTER SET utf16le */;
USE `wiro`;

-- tablo yapısı dökülüyor wiro.accounts
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `dgtarih` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `discord` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `permission` varchar(50) NOT NULL DEFAULT 'user',
  `tarih` varchar(50) NOT NULL,
  `Whitelist` tinyint(4) NOT NULL DEFAULT 0,
  `basvuruyapabilir` tinyint(4) NOT NULL DEFAULT 1,
  `basvurusonuc` tinyint(4) NOT NULL DEFAULT -1,
  `meslek` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf16le;

-- wiro.accounts: ~4 rows (yaklaşık) tablosu için veriler indiriliyor
DELETE FROM `accounts`;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` (`id`, `name`, `dgtarih`, `nickname`, `discord`, `pass`, `email`, `permission`, `tarih`, `Whitelist`, `basvuruyapabilir`, `basvurusonuc`, `meslek`) VALUES
	(1, 'Wiro', '00/00/2000', 'Wiro', 'Wiro', 'Wiro', 'wiroeposta', 'superadmin', '11/08/2021 18:39:21', 1, 1, -1, 'beklemede'),
	(12, 'aga', '00/00/2000', 'test', 'test', '321bitirişi', 'asdasd', 'admin', '17/08/2021 18:39:21', 0, 1, 0, ''),
	(13, 'asd', '123', 'asd', 'asd', 'asd', 'asd', 'user', '31/08/2021 13:53:42', 0, 1, 0, ''),
	(14, 'dsa', 'dsa', 'dsa', 'dsa', 'dsa', 'dsa', 'user', '31/08/2021 14:15:38', 1, 0, 2, ''),
	(15, 'berk', '00-00-0000', '_Wiro_', 'Wiro#5454', 'deneme123', 'xeposta', 'user', '08/10/2021 18:51:50', 0, 1, 1, '');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;

-- tablo yapısı dökülüyor wiro.sitee
CREATE TABLE IF NOT EXISTS `sitee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discordDavet` varchar(50) NOT NULL DEFAULT '',
  `tanıtımText` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf16le;

-- wiro.sitee: ~0 rows (yaklaşık) tablosu için veriler indiriliyor
DELETE FROM `sitee`;
/*!40000 ALTER TABLE `sitee` DISABLE KEYS */;
INSERT INTO `sitee` (`id`, `discordDavet`, `tanıtımText`) VALUES
	(1, 'aaaaaa', 'açıklama değiştir');
/*!40000 ALTER TABLE `sitee` ENABLE KEYS */;

-- tablo yapısı dökülüyor wiro.wlbasvurular
CREATE TABLE IF NOT EXISTS `wlbasvurular` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eposta` varchar(50) NOT NULL,
  `basvurujson` longtext NOT NULL,
  `tarih` varchar(50) NOT NULL DEFAULT '0',
  `durum` varchar(50) DEFAULT NULL,
  `kimTarafından` varchar(50) DEFAULT NULL,
  `tarihi` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf16le;

-- wiro.wlbasvurular: ~8 rows (yaklaşık) tablosu için veriler indiriliyor
DELETE FROM `wlbasvurular`;
/*!40000 ALTER TABLE `wlbasvurular` DISABLE KEYS */;
INSERT INTO `wlbasvurular` (`id`, `eposta`, `basvurujson`, `tarih`, `durum`, `kimTarafından`, `tarihi`) VALUES
	(1, 'wiroeposta', '{"kis":"asd","kdt":"asd","kaa":"sad","kba":"asd","kh":"asd","ksehir":"sda","kpolis":"asd","kbulun":"sad","ksehir2":"as"}', '14/08/2021 18:24:01', 'reddedildi', 'Wiro', '16/08/2021 20:20:59'),
	(2, 'wiroeposta', '{"kis":"asd","kdt":"sda","kaa":"sad","kba":"dasdas","kh":"sad","ksehir":"dsa","kpolis":"dsa","kbulun":"sdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasdasda","ksehir2":"ads"}', '14/08/2021 18:29:44', 'onaylandı', 'Wiro', '16/08/2021 20:35:44'),
	(3, 'wiroeposta', '{"kis":"sad","kdt":"asd","kaa":"dsasda\\"","kba":"dsadsa","kh":"aadss","ksehir":"as","kpolis":"asd","kbulun":"sad","ksehir2":"adsa"}', '14/08/2021 18:57:34', 'onaylandı', 'Wiro', '16/08/2021 21:53:08'),
	(4, 'wiroeposta', '{"kis":"asd","kdt":"sad","kaa":"sdadsa","kba":"das","kh":"ads","ksehir":"ads","kpolis":"asd","kbulun":"das","ksehir2":"ads"}', '14/08/2021 19:00:23', 'onaylandı', 'Wiro', '31/08/2021 13:39:14'),
	(5, 'wiroeposta', '{"kis":"asddsa\\"","kdt":"asddsa\'","kaa":"asd","kba":"sda","kh":"dsa","ksehir":"dasadsdsa","kpolis":"ads","kbulun":"das","ksehir2":"asd"}', '14/08/2021 19:00:51', 'onaylandı', 'Wiro', '16/08/2021 20:36:03'),
	(7, 'wiroeposta', '{"kis":"asd","kdt":"dsa","kaa":"ds","kba":"asd","kh":"asd","ksehir":"sad","kpolis":"dsa","kbulun":"dsa","ksehir2":"dsa"}', '17/08/2021 14:36:50', 'onaylandı', 'Wiro', '31/08/2021 13:36:31'),
	(8, 'dsa', '{"kis":"sadsa","kdt":"dsa","kaa":"dsa","kba":"adsdas","kh":"dsa","ksehir":"dasddasda","kpolis":"sad","kbulun":"asd","ksehir2":"ads"}', '31/08/2021 14:15:47', 'onaylandı', 'Wiro', '31/08/2021 14:17:40'),
	(9, 'xeposta', '{"kis":"test","kdt":"test","kaa":"test","kba":"test","kh":"testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest","ksehir":"testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest","kpolis":"testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest","kbulun":"testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest","ksehir2":"vtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest"}', '08/10/2021 18:52:23', 'reddedildi', 'Wiro', '08/10/2021 18:54:17');
/*!40000 ALTER TABLE `wlbasvurular` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
