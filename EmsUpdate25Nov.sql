-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.17-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for armada_ems
CREATE DATABASE IF NOT EXISTS `armada_ems` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `armada_ems`;

-- Dumping structure for table armada_ems.auth_groups_users
CREATE TABLE IF NOT EXISTS `auth_groups_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_groups_users_user_id_foreign` (`user_id`),
  CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.auth_groups_users: ~4 rows (approximately)
REPLACE INTO `auth_groups_users` (`id`, `user_id`, `group`, `created_at`) VALUES
	(2, 3, 'admin', '2025-11-11 08:15:05'),
	(3, 4, 'user', '2025-11-11 08:15:05'),
	(5, 2, 'admin', '2025-11-14 13:39:15'),
	(6, 5, 'user', '2025-11-25 07:16:21');

-- Dumping structure for table armada_ems.auth_identities
CREATE TABLE IF NOT EXISTS `auth_identities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `force_reset` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_secret` (`type`,`secret`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.auth_identities: ~4 rows (approximately)
REPLACE INTO `auth_identities` (`id`, `user_id`, `type`, `name`, `secret`, `secret2`, `expires`, `extra`, `force_reset`, `last_used_at`, `created_at`, `updated_at`) VALUES
	(1, 2, 'email_password', NULL, 'admin@example.com', '$2y$12$Acg6uqzs1cPRArwCO1f.Puw4jwgglt7M1YgsUmd6ExSDCJYgYDRaO', NULL, NULL, 0, '2025-11-14 16:29:10', '2025-11-11 04:44:01', '2025-11-14 16:29:10'),
	(2, 3, 'email_password', NULL, 'manager@example.com', '$2y$12$JuVYGYB2QjXz78P9SSo9g.rZMnmBF6slCqKz45xpR8WWLxN451nlO', NULL, NULL, 0, '2025-11-25 07:15:51', '2025-11-11 08:15:05', '2025-11-25 07:15:51'),
	(3, 4, 'email_password', NULL, 'user@example.com', '$2y$12$VPqB4k8LrbqVGjrM6igjgeUphLiSuAjAea3pBnHdyAOYTRbnx.t7a', NULL, NULL, 0, '2025-11-14 11:19:28', '2025-11-11 08:15:05', '2025-11-14 11:19:28'),
	(4, 5, 'email_password', NULL, 'dzaki@gmail.com', '$2y$12$eKT.g/SFwt8GLZYIILOJ/.veSk8OAhTupgoHIbH.mv7OL3HQospk6', NULL, NULL, 0, '2025-11-25 07:16:39', '2025-11-14 13:33:57', '2025-11-25 07:16:39');

-- Dumping structure for table armada_ems.auth_logins
CREATE TABLE IF NOT EXISTS `auth_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_type_identifier` (`id_type`,`identifier`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.auth_logins: ~30 rows (approximately)
REPLACE INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
	(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'administrator', NULL, '2025-11-11 06:33:52', 0),
	(2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'administrator', NULL, '2025-11-11 06:34:24', 0),
	(3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'administrator', NULL, '2025-11-11 06:35:07', 0),
	(4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'admin@example.com', 2, '2025-11-11 06:35:23', 1),
	(5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'administrator', NULL, '2025-11-11 08:00:13', 0),
	(6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'admin@example.com', 2, '2025-11-11 08:00:26', 1),
	(7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-11 08:16:20', 1),
	(8, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'admin@example.com', 2, '2025-11-11 08:18:06', 1),
	(9, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-11 08:19:20', 1),
	(10, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-11 08:21:29', 1),
	(11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'admin@example.com', 2, '2025-11-11 15:41:48', 1),
	(12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-11 15:42:11', 1),
	(13, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-11 19:30:17', 1),
	(14, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'administrator', NULL, '2025-11-14 08:30:01', 0),
	(15, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'admin@example.com', 2, '2025-11-14 08:30:12', 1),
	(16, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-14 08:30:57', 1),
	(17, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-14 11:19:03', 1),
	(18, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'user@example.com', 4, '2025-11-14 11:19:28', 1),
	(19, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'administrator', NULL, '2025-11-14 13:30:02', 0),
	(20, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'dzaki@gmail.com', 5, '2025-11-14 13:34:40', 1),
	(21, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-14 13:34:59', 1),
	(22, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'dzaki@gmail.com', 5, '2025-11-14 15:56:14', 1),
	(23, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'dzaki@gmail.com', 5, '2025-11-14 16:09:05', 1),
	(24, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'dzaki@gmail.com', 5, '2025-11-14 16:09:39', 1),
	(25, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'admin@example.com', 2, '2025-11-14 16:29:00', 1),
	(26, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'admin@example.com', 2, '2025-11-14 16:29:10', 1),
	(27, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-14 16:29:18', 1),
	(28, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'dzaki@example.com', NULL, '2025-11-14 16:30:46', 0),
	(29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-14 16:31:35', 1),
	(30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'dzaki@example.com', NULL, '2025-11-25 07:00:26', 0),
	(31, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'manager@example.com', 3, '2025-11-25 07:15:51', 1),
	(32, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'email_password', 'dzaki@gmail.com', 5, '2025-11-25 07:16:39', 1);

-- Dumping structure for table armada_ems.auth_permissions_users
CREATE TABLE IF NOT EXISTS `auth_permissions_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `auth_permissions_users_user_id_foreign` (`user_id`),
  CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.auth_permissions_users: ~23 rows (approximately)
REPLACE INTO `auth_permissions_users` (`id`, `user_id`, `permission`, `created_at`) VALUES
	(1, 3, 'users.view', '2025-11-11 08:15:05'),
	(2, 3, 'users.create', '2025-11-11 08:15:05'),
	(3, 3, 'users.edit', '2025-11-11 08:15:05'),
	(4, 3, 'users.ban', '2025-11-11 08:15:05'),
	(5, 3, 'files.upload', '2025-11-11 08:15:05'),
	(6, 3, 'files.download', '2025-11-11 08:15:05'),
	(7, 3, 'files.delete', '2025-11-11 08:15:05'),
	(8, 3, 'files.manage-all', '2025-11-11 08:15:05'),
	(9, 3, 'folders.create', '2025-11-11 08:15:05'),
	(10, 3, 'folders.delete', '2025-11-11 08:15:05'),
	(11, 3, 'folders.manage-all', '2025-11-11 08:15:05'),
	(12, 3, 'trash.view', '2025-11-11 08:15:05'),
	(13, 3, 'trash.restore', '2025-11-11 08:15:05'),
	(14, 3, 'trash.empty', '2025-11-11 08:15:05'),
	(15, 3, 'admin.access', '2025-11-11 08:15:05'),
	(16, 3, 'search.advanced', '2025-11-11 08:15:05'),
	(17, 4, 'files.upload', '2025-11-11 08:15:05'),
	(18, 4, 'files.download', '2025-11-11 08:15:05'),
	(19, 4, 'files.delete', '2025-11-11 08:15:05'),
	(20, 4, 'folders.create', '2025-11-11 08:15:05'),
	(21, 4, 'folders.delete', '2025-11-11 08:15:05'),
	(22, 4, 'trash.view', '2025-11-11 08:15:05'),
	(23, 4, 'trash.restore', '2025-11-11 08:15:05');

-- Dumping structure for table armada_ems.auth_remember_tokens
CREATE TABLE IF NOT EXISTS `auth_remember_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`selector`),
  KEY `auth_remember_tokens_user_id_foreign` (`user_id`),
  CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.auth_remember_tokens: ~0 rows (approximately)

-- Dumping structure for table armada_ems.auth_token_logins
CREATE TABLE IF NOT EXISTS `auth_token_logins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_type_identifier` (`id_type`,`identifier`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.auth_token_logins: ~0 rows (approximately)

-- Dumping structure for table armada_ems.files
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `size` int(11) NOT NULL,
  `status` int(2) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `folder_id` (`folder_id`),
  CONSTRAINT `files_ibfk_2` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.files: ~12 rows (approximately)
REPLACE INTO `files` (`id`, `user_id`, `folder_id`, `name`, `file_path`, `file_type`, `size`, `status`, `created_at`, `created_by`, `deleted_at`) VALUES
	(6, 4, 2, 'Materi_Training_BBS_STOP7_Silabus_Simple.pdf', 'uploads/drive/1/2/1760324117_77fb40d5ce943c736d15.pdf', 'pdf', 1694, 1, '2025-10-12 19:55:17', '', NULL),
	(10, 4, 2, 'Display Room R&D.jpg', 'uploads/drive/1/2/Display Room R&D.jpg', 'image', 1762748, 1, '2025-10-12 20:29:51', '', NULL),
	(11, 4, 14, 'bus.jpg', 'uploads/drive/1/14/1760339465_a50f674a90c73e78b282.jpg', 'image', 114818, 1, '2025-10-13 00:11:05', '', NULL),
	(18, 4, 14, 'Quizizz_PreTest_CyberAwareness.xlsx', 'uploads/drive/1/14/1760413289_3c20bafc41ff4658375c.xlsx', 'excel', 11231, 1, '2025-10-13 20:41:29', '', NULL),
	(19, 4, 22, 'FM-SOP-GA-01-01.xlsx', 'uploads/drive/1/22/1762239373_a06a02775d1571838fcf.xlsx', 'excel', 66073, 1, '2025-11-03 23:56:13', '', NULL),
	(20, 4, 22, 'SOP-GA-01 Prosedur IADL.pdf', 'uploads/drive/1/22/1762239574_a8199f29c05309aa15c6.pdf', 'pdf', 486409, 1, '2025-11-03 23:59:34', '', NULL),
	(21, 3, 29, 'mifi router.doc', 'uploads/drive/3/29/1763114321_2223e9782e0c6e88d443.docx', 'doc', 475284, 1, '2025-11-14 09:58:41', '', '2025-11-14 16:58:51'),
	(22, 3, 29, 'mifi router.doc', 'uploads/drive/3/29/1763114350_42e7f710cb940ea7bbe8.docx', 'doc', 475284, 1, '2025-11-14 09:59:10', '', NULL),
	(23, 3, 29, 'mifi router.doc', 'uploads/drive/3/29/1763114366_b0c6dce137cfc43e9831.docx', 'doc', 475284, 1, '2025-11-14 09:59:26', '', NULL),
	(24, 3, 29, 'mifi router.doc', 'uploads/drive/3/29/1763114413_746333c4c780f563593b.docx', 'doc', 475284, 1, '2025-11-14 10:00:13', '', NULL),
	(25, 3, 29, 'Gate.zip', 'uploads/drive/3/29/1763114437_d08782a3034041f769d5.zip', 'zip', 164303, 1, '2025-11-14 10:00:37', '', NULL),
	(26, 3, 30, 'mifi router.doc', 'uploads/drive/3/30/1763114495_56a9dde039bc362ded2a.docx', 'doc', 475284, 1, '2025-11-14 10:01:35', '', NULL);

-- Dumping structure for table armada_ems.file_types
CREATE TABLE IF NOT EXISTS `file_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.file_types: ~0 rows (approximately)

-- Dumping structure for table armada_ems.folders
CREATE TABLE IF NOT EXISTS `folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `folders_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `folders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.folders: ~17 rows (approximately)
REPLACE INTO `folders` (`id`, `user_id`, `parent_id`, `name`, `created_at`, `deleted_at`) VALUES
	(2, 4, NULL, 'yreewasjdbkajshdkajsanjsbhdjkashdjk', '2025-08-27 00:48:41', NULL),
	(14, 4, NULL, 'IATF', '2025-10-13 00:10:44', NULL),
	(16, 4, NULL, 'Master Data ISO14001 2015', '2025-11-03 23:51:24', NULL),
	(17, 4, 16, 'Prosedur', '2025-11-03 23:52:03', NULL),
	(18, 4, 17, '01 Analisa SWOT', '2025-11-03 23:52:28', NULL),
	(19, 4, 17, '02 Kebijakan dan Manual MAJ', '2025-11-03 23:52:53', NULL),
	(20, 4, 17, '03 Prosedur GA', '2025-11-03 23:53:10', NULL),
	(21, 4, 17, '04 Prosedur MR', '2025-11-03 23:53:30', NULL),
	(22, 4, 20, '01 Prosedur IADL', '2025-11-03 23:54:26', NULL),
	(23, 4, 20, '02 Prosedur Identifikasi dan Evaluasi Legal', '2025-11-03 23:54:35', NULL),
	(24, 4, 20, '03 Prosedur Pengendalian Operasional', '2025-11-03 23:54:55', NULL),
	(25, 4, 20, '04 Prosedur Penanganan B3', '2025-11-03 23:55:20', NULL),
	(26, 4, 20, '05 Prosedur Pemantauan dan pengukuran', '2025-11-03 23:55:30', NULL),
	(27, 4, 16, 'Audit ISO 1400012015  EKSTERNAL', '2025-11-04 00:47:18', NULL),
	(28, 4, NULL, 'tesad', '2025-11-09 19:48:33', NULL),
	(29, 3, NULL, 'Root', '2025-11-14 09:57:43', '2025-11-14 16:58:51'),
	(30, 3, NULL, 'tes', '2025-11-14 10:01:03', NULL);

-- Dumping structure for table armada_ems.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.migrations: ~3 rows (approximately)
REPLACE INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
	(1, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1762833860, 1),
	(2, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1762833860, 1),
	(3, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1762833860, 1);

-- Dumping structure for table armada_ems.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.settings: ~0 rows (approximately)

-- Dumping structure for table armada_ems.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table armada_ems.users: ~4 rows (approximately)
REPLACE INTO `users` (`id`, `username`, `status`, `status_message`, `active`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(2, 'administrator', NULL, NULL, 1, '2025-11-14 08:30:34', '2025-11-11 04:44:01', '2025-11-14 13:40:39', NULL),
	(3, 'manager', NULL, NULL, 1, '2025-11-14 17:02:12', '2025-11-11 08:15:04', '2025-11-14 13:42:39', NULL),
	(4, 'user', NULL, NULL, 1, '2025-11-14 13:20:15', '2025-11-11 08:15:05', '2025-11-14 13:42:43', NULL),
	(5, 'dzaki', NULL, NULL, 1, '2025-11-25 07:19:35', '2025-11-14 13:33:57', '2025-11-25 07:16:02', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;