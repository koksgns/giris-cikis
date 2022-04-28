-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 22 Nis 2022, 01:30:14
-- Sunucu sürümü: 8.0.21
-- PHP Sürümü: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `giris_cikis`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `log_user_id` int NOT NULL,
  `log_month` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `log_year` varchar(4) NOT NULL,
  `log_day` varchar(50) NOT NULL,
  `log_enter` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `log_exit` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `log_total` int DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `logs`
--

INSERT INTO `logs` (`log_id`, `log_user_id`, `log_month`, `log_year`, `log_day`, `log_enter`, `log_exit`, `log_total`) VALUES
(1, 1, '03', '2022', '00-00-0000', '00:00:00', '00:00:00', 0),
(2, 1, '03', '2022', '03-03-2022', '15:34:00', '18:43:00', 11340),
(3, 1, '03', '2022', '04-03-2022', '09:58:00', '19:17:00', 33540),
(4, 1, '03', '2022', '07-03-2022', '09:30:00', '19:00:00', 34200),
(5, 1, '03', '2022', '08-03-2022', '10:05:00', '20:20:00', 36900),
(6, 1, '03', '2022', '09-03-2022', '10:06:00', '19:38:00', 34320),
(7, 1, '03', '2022', '10-03-2022', '10:08:37', '19:56:35', 35278),
(8, 1, '03', '2022', '11-03-2022', '10:20:52', '20:05:59', 35107),
(9, 1, '03', '2022', '14-03-2022', '10:23:48', '19:11:13', 31645),
(10, 1, '03', '2022', '15-03-2022', '10:55:56', '20:26:09', 34213),
(11, 1, '03', '2022', '16-03-2022', '11:58:56', '20:52:58', 32042),
(12, 1, '03', '2022', '17-03-2022', '09:59:49', '20:07:22', 36453),
(13, 1, '03', '2022', '18-03-2022', '10:47:42', '20:17:28', 34186),
(14, 1, '03', '2022', '21-03-2022', '10:22:42', '20:10:22', 35260),
(15, 1, '03', '2022', '22-03-2022', '09:53:14', '19:02:50', 32976),
(16, 1, '03', '2022', '23-03-2022', '10:29:40', '19:35:00', 32720),
(17, 1, '03', '2022', '24-03-2022', '10:20:10', '19:07:25', 31635),
(18, 1, '03', '2022', '28-03-2022', '10:15:05', '18:43:46', 30521),
(19, 1, '03', '2022', '29-03-2022', '10:38:25', '14:51:24', 15179),
(20, 1, '03', '2022', '30-03-2022', '11:59:10', '16:44:14', 17104),
(21, 1, '03', '2022', '31-03-2022', '12:55:54', '18:57:24', 21690),
(22, 1, '04', '2022', '01-04-2022', '09:37:42', '20:45:44', 40082),
(23, 1, '04', '2022', '09-04-2022', '13:06:39', '21:23:05', 29786),
(24, 1, '04', '2022', '11-04-2022', '09:17:52', '20:42:01', 41049),
(25, 1, '04', '2022', '12-04-2022', '10:16:18', '21:21:17', 39899),
(26, 1, '04', '2022', '13-04-2022', '09:36:02', '20:49:06', 40384),
(27, 1, '04', '2022', '14-04-2022', '10:53:04', '22:39:30', 42386),
(28, 1, '04', '2022', '15-04-2022', '11:35:04', '22:05:04', 37800),
(29, 1, '04', '2022', '17-04-2022', '11:35:07', '22:00:58', 37551),
(30, 1, '04', '2022', '18-04-2022', '10:22:39', '20:35:09', 36750),
(31, 1, '04', '2022', '19-04-2022', '09:51:35', '17:41:25', 28190),
(32, 1, '04', '2022', '20-04-2022', '09:48:17', '18:50:08', 32511),
(33, 1, '04', '2022', '21-04-2022', '11:31:39', '23:23:59', 42740),
(34, 1, '04', '2022', '22-04-2022', '00:34:50', NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_name` varchar(75) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`user_id`, `user_email`, `user_password`, `user_name`) VALUES
(1, 'koksal.gunes@olleco.com', '4297f44b13955235245b2497399d7a93', 'Köksal Güneş');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
