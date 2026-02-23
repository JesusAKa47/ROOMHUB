-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para roomhub_db
CREATE DATABASE IF NOT EXISTS `roomhub_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `roomhub_db`;

-- Volcando estructura para tabla roomhub_db.apartments
CREATE TABLE IF NOT EXISTS `apartments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` bigint unsigned NOT NULL,
  `monthly_rent` decimal(10,2) NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Entidad federativa / Estado',
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `municipality` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locality` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nearby` json DEFAULT NULL COMMENT 'Cercanías: universidad, hospital, etc. [{ "tipo": "...", "nombre": "..." }]',
  `description` text COLLATE utf8mb4_unicode_ci,
  `lat` decimal(10,7) DEFAULT NULL,
  `lng` decimal(10,7) DEFAULT NULL,
  `available_from` date NOT NULL,
  `is_furnished` tinyint(1) NOT NULL,
  `has_ac` tinyint(1) NOT NULL DEFAULT '0',
  `has_tv` tinyint(1) NOT NULL DEFAULT '0',
  `has_wifi` tinyint(1) NOT NULL DEFAULT '0',
  `has_kitchen` tinyint(1) NOT NULL DEFAULT '0',
  `has_parking` tinyint(1) NOT NULL DEFAULT '0',
  `has_laundry` tinyint(1) NOT NULL DEFAULT '0',
  `has_heating` tinyint(1) NOT NULL DEFAULT '0',
  `has_balcony` tinyint(1) NOT NULL DEFAULT '0',
  `pets_allowed` tinyint(1) NOT NULL DEFAULT '0',
  `smoking_allowed` tinyint(1) NOT NULL DEFAULT '0',
  `max_people` smallint unsigned NOT NULL DEFAULT '1',
  `status` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `rules` json DEFAULT NULL,
  `photos` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `apartments_owner_id_foreign` (`owner_id`),
  CONSTRAINT `apartments_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.apartments: ~3 rows (aproximadamente)
INSERT INTO `apartments` (`id`, `title`, `owner_id`, `monthly_rent`, `address`, `postal_code`, `state`, `city`, `municipality`, `locality`, `nearby`, `description`, `lat`, `lng`, `available_from`, `is_furnished`, `has_ac`, `has_tv`, `has_wifi`, `has_kitchen`, `has_parking`, `has_laundry`, `has_heating`, `has_balcony`, `pets_allowed`, `smoking_allowed`, `max_people`, `status`, `rules`, `photos`, `created_at`, `updated_at`) VALUES
	(3, 'aaaaaaaaaaaaaaaaaa', 2, 1242.00, 'ZZzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz', NULL, NULL, NULL, NULL, NULL, '[]', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 16.9124841, -92.0984781, '2026-02-14', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 'activo', '["No fiestas ni eventos", "Horario de silencio 22:00-08:00", "No se permiten mascotas"]', '["apartments/h7abgeAd2ErbsOPBOxvEGciCtfez9gghHCzfSiEC.png", "apartments/jmQCI2fy7BhCFEMQSVhMxOyFubf42bFGn7qkzh9e.png", "apartments/jrJ4V5BQuxGME5X4Y3dlpZVOLT8U6Mfa8hu64DU8.png", "apartments/52NE8jhnHdd9wdcYzVvAj0kRkbl6zNFOxvHzc3Uq.png", "apartments/R9HGIMIN4jTfbrv8stt5fe5DJb9Eq1sXMLFAuYeW.png"]', '2025-10-18 06:09:50', '2026-02-14 07:14:42'),
	(4, 'departamento grande centrico', 3, 15000.00, 'boulevard norte', NULL, NULL, NULL, NULL, NULL, NULL, 'es grande y se puede hacer fiestas', 16.2390509, -92.1089915, '2026-02-09', 1, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 5, 'activo', '["se permite fiestas y de todo"]', '["apartments/aPOqO8piqj4zGAbM9SuZ6b9kTgfWy9St2CpNADCP.png"]', '2026-02-09 08:20:29', '2026-02-09 21:03:30'),
	(5, 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 3, 2000.00, 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', NULL, NULL, NULL, NULL, NULL, NULL, 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 20.9649011, -89.6166801, '2026-02-09', 0, 1, 0, 0, 0, 0, 1, 0, 0, 1, 1, 5, 'activo', '["AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"]', '["apartments/1AKYvtgfmlsnQpLaCCUnTFDViTGFztze3ZIymz0R.png", "apartments/BBKcxs8jMQJqAuoc9gHgPENPK1ZftaTnbzt4gwBP.png", "apartments/WaYWma8CcGts3E9jNDkLfx26EbTEbGg9JQ5k79Ql.png", "apartments/3WeSP0aRohGFtgYZzHVsiqRLguw03VJvPoGkMvgs.png"]', '2026-02-09 21:04:35', '2026-02-09 21:04:35'),
	(17, 'Casa de dos pisos', 3, 20000.00, 'bpulevard norte', NULL, 'Chiapas', 'Ocosingo', 'Ocosingo', 'Ocosingo', '[]', 'cuarto bonito y amplio para convivir en familia', 16.9123957, -92.0984096, '2026-02-14', 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 10, 'activo', '["No fiestas ni eventos", "Horario de silencio 22:00-08:00", "No fumar en el interior"]', '["apartments/E91GmfA2UpQ7r8dyIdltsoMlmrvyd1sqiTI3eand.jpg", "apartments/TxhCOzEsSSdW0IwDHsQR1JGhQ5btSAQS1uyvy919.jpg", "apartments/ZM9TGO71ZZYEJwkBuHMUYwpsYRgpH63D7MoV93ab.jpg"]', '2026-02-15 02:06:26', '2026-02-15 02:06:26');

-- Volcando estructura para tabla roomhub_db.apartment_comments
CREATE TABLE IF NOT EXISTS `apartment_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `apartment_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `apartment_comments_apartment_id_foreign` (`apartment_id`),
  KEY `apartment_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `apartment_comments_apartment_id_foreign` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `apartment_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.apartment_comments: ~2 rows (aproximadamente)
INSERT INTO `apartment_comments` (`id`, `apartment_id`, `user_id`, `body`, `rating`, `created_at`, `updated_at`) VALUES
	(1, 4, 5, 'hola', NULL, '2026-02-10 02:59:27', '2026-02-10 02:59:27'),
	(2, 4, 5, 'se mira bien el departamento', NULL, '2026-02-10 02:59:46', '2026-02-10 02:59:46'),
	(3, 4, 8, 'aaaa', 1, '2026-02-10 09:15:12', '2026-02-10 09:15:12');

-- Volcando estructura para tabla roomhub_db.api_tokens
CREATE TABLE IF NOT EXISTS `api_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'flutter-app',
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_tokens_token_unique` (`token`),
  KEY `api_tokens_user_id_foreign` (`user_id`),
  CONSTRAINT `api_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.api_tokens: ~0 rows (aproximadamente)
INSERT INTO `api_tokens` (`id`, `user_id`, `token`, `name`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(5, 10, 'a757e2dd25e6034d2ffb693c89ea15573a35f15bfa523ddc37a2eed79944de21', 'flutter-app', NULL, '2026-03-14 09:15:10', '2026-02-12 09:15:10', '2026-02-12 09:15:10');

-- Volcando estructura para tabla roomhub_db.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.cache: ~9 rows (aproximadamente)
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('laravel-cache-a@gmaill.com|127.0.0.1', 'i:2;', 1770879829),
	('laravel-cache-a@gmaill.com|127.0.0.1:timer', 'i:1770879829;', 1770879829),
	('laravel-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', 'i:2;', 1771145499),
	('laravel-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4:timer', 'i:1771145499;', 1771145499),
	('laravel-cache-cp_mx_29910', 'a:4:{s:5:"state";s:7:"Chiapas";s:4:"city";s:4:"Tila";s:12:"municipality";s:4:"Tila";s:10:"localities";a:6:{i:0;s:4:"Tila";i:1;s:13:"Misopa Chinal";i:2;s:8:"El Limar";i:3;s:7:"Shoctic";i:4;s:14:"Tiontepa Jocsa";i:5;s:13:"Chulum Juarez";}}', 1771656420),
	('laravel-cache-cp_mx_29950', 'a:4:{s:5:"state";s:7:"Chiapas";s:4:"city";s:11:"Loma Bonita";s:12:"municipality";s:11:"Loma Bonita";s:10:"localities";a:57:{i:0;s:11:"Loma Bonita";i:1;s:10:"Las Palmas";i:2;s:11:"San Juanito";i:3;s:5:"Nuevo";i:4;s:10:"Bellavista";i:5;s:6:"Chorro";i:6;s:7:"Mirador";i:7;s:12:"Nuevo Mexico";i:8;s:10:"Candelaria";i:9;s:9:"El Bosque";i:10;s:11:"Santa Lucia";i:11;s:9:"Manantial";i:12;s:13:"Santa Cecilia";i:13;s:22:"San Jose de las Flores";i:14;s:13:"Santo Domingo";i:15;s:15:"Ocosingo Centro";i:16;s:11:"San Antonio";i:17;s:12:"El Herradero";i:18;s:9:"Guadalupe";i:19;s:9:"Flanboyan";i:20;s:9:"El Carmen";i:21;s:12:"Patria Nueva";i:22;s:9:"El Sauzal";i:23;s:15:"Nuevo Guadalupe";i:24;s:8:"Ocosingo";i:25;s:9:"Siglo Xxi";i:26;s:10:"Aeropuerto";i:27;s:27:"Rancho Alegre Puerto Arturo";i:28;s:9:"El Cipres";i:29;s:24:"San Marcos Puerto Arturo";i:30;s:22:"San Jacinto Lindavista";i:31;s:6:"Tonina";i:32;s:27:"Los Tulipanes Puerto Arturo";i:33;s:9:"Jerusalen";i:34;s:5:"Norte";i:35;s:23:"La Lomita Puerto Arturo";i:36;s:8:"Lacantun";i:37;s:7:"Betania";i:38;s:11:"Magisterial";i:39;s:18:"Lomas Del Pedregal";i:40;s:25:"San Jacinto Puerto Arturo";i:41;s:11:"Pequeñeses";i:42;s:8:"Bonampak";i:43;s:21:"Tuy Tic Puerto Arturo";i:44;s:20:"Sibaca Puerto Arturo";i:45;s:21:"Ampliacion Aeropuerto";i:46;s:15:"Octavio Albores";i:47;s:13:"Los Girasoles";i:48;s:12:"Luis Donaldo";i:49;s:12:"San Calampio";i:50;s:15:"20 de Noviembre";i:51;s:11:"Los Pinos I";i:52;s:13:"San Sebastian";i:53;s:12:"Vistahermosa";i:54;s:8:"El Nuevo";i:55;s:9:"Lidavista";i:56;s:6:"Jordan";}}', 1771656394),
	('laravel-cache-cp_mx_29951', 'N;', 1771657058),
	('laravel-cache-emma@gmaill.com|127.0.0.1', 'i:3;', 1770975868),
	('laravel-cache-emma@gmaill.com|127.0.0.1:timer', 'i:1770975868;', 1770975868);

-- Volcando estructura para tabla roomhub_db.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.cache_locks: ~0 rows (aproximadamente)

-- Volcando estructura para tabla roomhub_db.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('hombre','mujer','otro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'otro',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `bio` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_scan_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.clients: ~9 rows (aproximadamente)
INSERT INTO `clients` (`id`, `name`, `email`, `password`, `phone`, `gender`, `is_verified`, `bio`, `id_scan_path`, `birthdate`, `created_at`, `updated_at`, `remember_token`) VALUES
	(2, 'angel', 'emma@gmail.com', NULL, '9611724435', 'hombre', 1, '', NULL, NULL, '2026-02-07 06:27:41', '2026-02-15 02:58:58', NULL),
	(3, 'acasio puto', 'acasio@gmail.com', NULL, '', 'otro', 0, '', NULL, NULL, '2026-02-10 03:20:07', '2026-02-10 03:20:07', NULL),
	(4, 'angel e', 't@gmail.com', NULL, '', 'otro', 0, '', NULL, NULL, '2026-02-10 08:09:32', '2026-02-10 08:09:32', NULL),
	(5, 'alaaa', 'xd@gmail.com', NULL, '', 'otro', 0, '', NULL, NULL, '2026-02-10 08:12:17', '2026-02-10 08:12:17', NULL),
	(6, 'angel', 'lopez@gmail.com', NULL, '', 'otro', 0, '', NULL, NULL, '2026-02-10 09:26:22', '2026-02-10 09:26:22', NULL),
	(7, 'roldan', 'r@gmail.com', NULL, '', 'otro', 0, '', NULL, NULL, '2026-02-10 21:06:49', '2026-02-10 21:06:49', NULL),
	(8, 'emmanuel', 'emm@gmail.com', NULL, '', 'otro', 0, '', NULL, NULL, '2026-02-12 09:15:09', '2026-02-12 09:15:09', NULL),
	(9, 'Arbey', 'arbe@gmail.com', NULL, '', 'otro', 0, '', NULL, NULL, '2026-02-13 22:36:42', '2026-02-13 22:36:42', NULL),
	(10, 'Angel Emmanuel Trujillo Lopez', 'angel@gmail.com', NULL, '9611724435', 'hombre', 0, '', NULL, '2004-11-24', '2026-02-15 04:31:40', '2026-02-15 04:31:40', NULL);

-- Volcando estructura para tabla roomhub_db.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla roomhub_db.favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `apartment_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favorites_user_id_apartment_id_unique` (`user_id`,`apartment_id`),
  KEY `favorites_apartment_id_foreign` (`apartment_id`),
  CONSTRAINT `favorites_apartment_id_foreign` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.favorites: ~1 rows (aproximadamente)
INSERT INTO `favorites` (`id`, `user_id`, `apartment_id`, `created_at`, `updated_at`) VALUES
	(1, 5, 3, '2026-02-15 07:16:57', '2026-02-15 07:16:57');

-- Volcando estructura para tabla roomhub_db.host_verifications
CREATE TABLE IF NOT EXISTS `host_verifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `ine_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `answers` json NOT NULL,
  `status` enum('pendiente','en_revision','aprobado','rechazado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `host_verifications_user_id_unique` (`user_id`),
  CONSTRAINT `host_verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.host_verifications: ~5 rows (aproximadamente)
INSERT INTO `host_verifications` (`id`, `user_id`, `ine_photo_path`, `answers`, `status`, `rejection_reason`, `reviewed_at`, `submitted_at`, `created_at`, `updated_at`) VALUES
	(1, 5, 'verifications/ine/kwXmP0cLKpetKUhNzvHXOyvHVt2Glp9nBu4Zpn2W.jpg', '{"q1": "si", "q2": "si", "q3": "si"}', 'aprobado', NULL, NULL, '2026-02-07 06:38:11', '2026-02-07 06:38:11', '2026-02-07 06:38:11'),
	(2, 7, 'verifications/ine/8ynFT55bzmPZ5udJ9Qe9MwqBAUTVvIEnnAcRM2EP.png', '{"q1": "si", "q2": "si", "q3": "si"}', 'aprobado', NULL, '2026-02-13 10:31:01', '2026-02-10 03:21:02', '2026-02-10 03:21:02', '2026-02-13 10:31:01'),
	(3, 8, 'verifications/ine/LFMOdrezFVunFnELWNip4HayF8yNdaAOgnsjP7Rt.jpg', '{"q1": "si", "q2": "si", "q3": "si"}', 'aprobado', NULL, '2026-02-10 08:45:05', '2026-02-10 08:37:09', '2026-02-10 08:24:42', '2026-02-10 08:45:05'),
	(4, 9, 'verifications/ine/GReGvM1fX3quFEdeXvQSYIQw6zuoFcalLZKRwJvo.png', '{"q1": "si", "q2": "si", "q3": "si"}', 'rechazado', 'de la vrg tus mamadas hijo', '2026-02-11 08:21:54', '2026-02-10 21:08:07', '2026-02-10 21:08:07', '2026-02-11 08:21:54'),
	(5, 11, 'verifications/ine/2YJxXPlcbzZbxVYYAWEQPIo1lK45dJiT8RKFMRHU.png', '{"q1": "si", "q2": "si", "q3": "si"}', 'aprobado', NULL, '2026-02-13 22:40:09', '2026-02-13 22:39:59', '2026-02-13 22:37:40', '2026-02-13 22:40:09');

-- Volcando estructura para tabla roomhub_db.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla roomhub_db.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.job_batches: ~0 rows (aproximadamente)

-- Volcando estructura para tabla roomhub_db.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `apartment_id` bigint unsigned DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_receiver_id_foreign` (`receiver_id`),
  KEY `messages_apartment_id_foreign` (`apartment_id`),
  CONSTRAINT `messages_apartment_id_foreign` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.messages: ~7 rows (aproximadamente)
INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `apartment_id`, `body`, `read_at`, `created_at`, `updated_at`) VALUES
	(1, 8, 5, 4, 'hola', '2026-02-10 09:23:55', '2026-02-10 09:23:19', '2026-02-10 09:23:55'),
	(2, 5, 8, NULL, 'digame que se le ofrece', '2026-02-10 09:24:28', '2026-02-10 09:24:07', '2026-02-10 09:24:28'),
	(3, 4, 5, 4, 'Hola, acabo de pagar la reserva para "departamento grande centrico" del 01/03/2026 al 01/04/2026. ¿Cuándo podríamos acordar el día y hora de entrada al alojamiento?', '2026-02-10 09:36:01', '2026-02-10 09:32:29', '2026-02-10 09:36:01'),
	(4, 4, 5, 4, 'Hola, acabo de pagar la reserva para "departamento grande centrico" del 01/03/2026 al 01/04/2026. ¿Cuándo podríamos acordar el día y hora de entrada al alojamiento?', '2026-02-10 09:36:01', '2026-02-10 09:35:35', '2026-02-10 09:36:01'),
	(5, 5, 6, NULL, 'hola', '2026-02-11 08:37:05', '2026-02-11 08:35:25', '2026-02-11 08:37:05'),
	(6, 6, 5, NULL, 'que deseas?', '2026-02-11 09:11:39', '2026-02-11 08:37:14', '2026-02-11 09:11:39'),
	(7, 6, 5, NULL, 'que pedo mijo', '2026-02-11 09:11:39', '2026-02-11 08:50:14', '2026-02-11 09:11:39'),
	(8, 5, 6, NULL, 'hola', NULL, '2026-02-13 22:28:51', '2026-02-13 22:28:51');

-- Volcando estructura para tabla roomhub_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.migrations: ~17 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_01_01_000100_create_owners_table', 1),
	(5, '2025_01_01_000150_create_clients_table', 1),
	(6, '2025_01_01_000200_create_apartments_table', 1),
	(7, '2025_10_17_235309_update_apartments_table_add_rules_and_max_people', 2),
	(8, '2025_10_17_235337_update_owners_table_add_verification_and_password', 2),
	(9, '2025_10_17_235351_update_clients_table_add_passwords', 2),
	(10, '2025_02_07_000000_add_role_and_links_to_users_table', 3),
	(11, '2025_02_07_100000_create_host_verifications_table', 4),
	(12, '2025_02_07_120000_add_apartment_details_and_amenities', 5),
	(13, '2025_02_07_150000_create_reservations_table', 6),
	(14, '2025_02_07_200000_create_apartment_comments_table', 7),
	(15, '2025_02_07_210000_add_review_fields_to_host_verifications', 8),
	(16, '2026_02_10_031119_add_rating_to_apartment_comments_table', 9),
	(17, '2026_02_10_031810_create_messages_table', 10),
	(18, '2026_02_11_000001_add_status_and_last_login_to_users_table', 11),
	(19, '2026_02_11_000002_create_user_activities_table', 11),
	(20, '2026_02_11_100000_add_payment_method_to_reservations_table', 12),
	(21, '2026_02_11_200000_create_notifications_table', 13),
	(22, '2026_02_11_300000_create_api_tokens_table', 14),
	(23, '2026_02_14_000001_add_location_and_nearby_to_apartments_table', 15),
	(24, '2026_02_14_100000_change_rules_to_json_array_in_apartments_table', 16),
	(25, '2026_02_14_200000_add_postal_code_to_apartments_table', 16),
	(26, '2026_02_14_300000_add_location_to_users_table', 17),
	(27, '2026_02_15_011411_create_apartment_user_favorites_table', 18),
	(28, '2026_02_14_400000_add_profile_features_to_users_table', 19),
	(29, '2026_02_14_500000_add_two_factor_to_users_table', 20);

-- Volcando estructura para tabla roomhub_db.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.notifications: ~14 rows (aproximadamente)
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
	('03b78d37-51e2-4a4f-8038-0885720b2237', 'App\\Notifications\\NewReservationNotification', 'App\\Models\\User', 6, '{"type":"new_reservation","title":"Nueva reserva","message":"Reserva #14 para \\u00abPenthouse exclusivo\\u00bb (entrada: 01\\/03\\/2026).","url":"http:\\/\\/127.0.0.1:8000\\/admin\\/finances","reservation_id":14}', '2026-02-15 09:02:27', '2026-02-13 22:34:04', '2026-02-15 09:02:27'),
	('06df4d43-50cd-4f6c-aa07-482b971d7ba3', 'App\\Notifications\\NewVerificationSubmittedNotification', 'App\\Models\\User', 6, '{"type":"verification_pending","title":"Solicitud de verificaci\\u00f3n","message":"Arbey envi\\u00f3 una solicitud para ser anfitri\\u00f3n. Revisa en verificaciones.","url":"http:\\/\\/127.0.0.1:8000\\/admin\\/verifications\\/5","host_verification_id":5}', '2026-02-13 22:38:59', '2026-02-13 22:37:40', '2026-02-13 22:38:59'),
	('15665154-e7a5-4fe3-9c04-fda030008557', 'App\\Notifications\\NewReservationNotification', 'App\\Models\\User', 6, '{"type":"new_reservation","title":"Nueva reserva","message":"Reserva #13 para \\u00abdepartamento grande centrico\\u00bb (entrada: 01\\/03\\/2026).","url":"http:\\/\\/127.0.0.1:8000\\/admin\\/finances","reservation_id":13}', '2026-02-13 10:29:59', '2026-02-13 10:03:15', '2026-02-13 10:29:59'),
	('163f77a1-db9c-42c6-90fe-4284994b45d7', 'App\\Notifications\\VerificationUnderReviewNotification', 'App\\Models\\User', 11, '{"type":"verification_under_review","title":"Solicitud en revisi\\u00f3n","message":"Tu solicitud para ser anfitri\\u00f3n est\\u00e1 siendo revisada por el equipo. Te avisaremos cuando tengamos una respuesta.","url":"http:\\/\\/127.0.0.1:8000\\/become-host","host_verification_id":5}', NULL, '2026-02-13 22:39:21', '2026-02-13 22:39:21'),
	('31e87066-2318-4544-a587-2e79b22a1da2', 'App\\Notifications\\ReservationPaidNotification', 'App\\Models\\User', 6, '{"type":"reservation_paid","title":"Reserva pagada","message":"Reserva #13 (\\u00abdepartamento grande centrico\\u00bb) fue pagada. Entrada: 01\\/03\\/2026.","url":"http:\\/\\/127.0.0.1:8000\\/admin\\/finances","reservation_id":13}', '2026-02-13 10:29:55', '2026-02-13 10:03:20', '2026-02-13 10:29:55'),
	('3f12e32f-b5f5-441a-b488-0343cc4be215', 'App\\Notifications\\VerificationApprovedNotification', 'App\\Models\\User', 7, '{"type":"verification_approved","title":"\\u00a1Felicidades, ya eres anfitri\\u00f3n!","message":"Tu solicitud fue aprobada. Completa tu perfil de anfitri\\u00f3n para publicar tu primer alojamiento.","url":"http:\\/\\/127.0.0.1:8000\\/become-host\\/complete","host_verification_id":2}', NULL, '2026-02-13 10:31:01', '2026-02-13 10:31:01'),
	('65994b7d-00ba-47d6-8e42-ef37368ac88a', 'App\\Notifications\\NewReservationNotification', 'App\\Models\\User', 6, '{"type":"new_reservation","title":"Nueva reserva","message":"Reserva #15 para \\u00abaaaaaaaaaaaaaaaaaa\\u00bb (entrada: 14\\/02\\/2026).","url":"http:\\/\\/127.0.0.1:8000\\/admin\\/finances","reservation_id":15}', '2026-02-15 09:02:27', '2026-02-15 02:50:51', '2026-02-15 09:02:27'),
	('697a18d1-f91c-4f02-ba3c-5244b4a52643', 'App\\Notifications\\VerificationApprovedNotification', 'App\\Models\\User', 11, '{"type":"verification_approved","title":"\\u00a1Felicidades, ya eres anfitri\\u00f3n!","message":"Tu solicitud fue aprobada. Completa tu perfil de anfitri\\u00f3n para publicar tu primer alojamiento.","url":"http:\\/\\/127.0.0.1:8000\\/become-host\\/complete","host_verification_id":5}', NULL, '2026-02-13 22:40:09', '2026-02-13 22:40:09'),
	('9999dc65-34b3-41a6-b33a-17152445bf03', 'App\\Notifications\\VerificationRejectedNotification', 'App\\Models\\User', 11, '{"type":"verification_rejected","title":"Solicitud no aprobada","message":"Tu solicitud para ser anfitri\\u00f3n no fue aprobada. Motivo: no come pilin","url":"http:\\/\\/127.0.0.1:8000\\/become-host","host_verification_id":5}', NULL, '2026-02-13 22:39:35', '2026-02-13 22:39:35'),
	('9af99b9d-522f-48c8-8e6c-7e38730f6582', 'App\\Notifications\\ReservationPaidNotification', 'App\\Models\\User', 6, '{"type":"reservation_paid","title":"Reserva pagada","message":"Reserva #14 (\\u00abPenthouse exclusivo\\u00bb) fue pagada. Entrada: 01\\/03\\/2026.","url":"http:\\/\\/127.0.0.1:8000\\/admin\\/finances","reservation_id":14}', '2026-02-15 09:02:27', '2026-02-13 22:34:12', '2026-02-15 09:02:27'),
	('a73a25af-8b16-4b6f-9e5c-9f63beb4cf6c', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 5, '{"type":"new_message","title":"Nuevo mensaje","message":"Administrador: que pedo mijo","url":"http:\\/\\/127.0.0.1:8000\\/mensajes?user=6","message_id":7,"sender_id":6}', '2026-02-11 09:11:38', '2026-02-11 08:50:15', '2026-02-11 09:11:38'),
	('b85dfaf6-22f0-4bda-8614-d74225556c64', 'App\\Notifications\\NewVerificationSubmittedNotification', 'App\\Models\\User', 6, '{"type":"verification_pending","title":"Solicitud de verificaci\\u00f3n","message":"Arbey envi\\u00f3 una solicitud para ser anfitri\\u00f3n. Revisa en verificaciones.","url":"http:\\/\\/127.0.0.1:8000\\/admin\\/verifications\\/5","host_verification_id":5}', '2026-02-13 22:40:04', '2026-02-13 22:39:59', '2026-02-13 22:40:04'),
	('c2b35f8e-30c9-4015-a198-e4c3d0386de3', 'App\\Notifications\\ReservationPaidNotification', 'App\\Models\\User', 6, '{"type":"reservation_paid","title":"Reserva pagada","message":"Reserva #15 (\\u00abaaaaaaaaaaaaaaaaaa\\u00bb) fue pagada. Entrada: 14\\/02\\/2026.","url":"http:\\/\\/127.0.0.1:8000\\/admin\\/finances","reservation_id":15}', '2026-02-15 09:02:27', '2026-02-15 02:50:57', '2026-02-15 09:02:27'),
	('c9cf50fb-1cbb-41d4-9232-549bed9249d2', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 6, '{"type":"new_message","title":"Nuevo mensaje","message":"angel: hola","url":"http:\\/\\/127.0.0.1:8000\\/mensajes?user=5","message_id":8,"sender_id":5}', '2026-02-13 22:30:50', '2026-02-13 22:28:51', '2026-02-13 22:30:50');

-- Volcando estructura para tabla roomhub_db.owners
CREATE TABLE IF NOT EXISTS `owners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('persona','empresa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'persona',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `verification_status` enum('en_revision','verificado','rechazado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_revision',
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `since` date NOT NULL DEFAULT '2025-10-16',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `owners_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.owners: ~1 rows (aproximadamente)
INSERT INTO `owners` (`id`, `name`, `email`, `password`, `phone`, `type`, `is_active`, `verification_status`, `avatar_path`, `notes`, `since`, `created_at`, `updated_at`, `remember_token`) VALUES
	(2, 'aaaaaaaa', 'ae@gmail.com', NULL, '12345678', 'persona', 1, 'en_revision', 'owners/UXmUEcU1HgkpMTWwVfjKG1aZtIbqkPkFthyYsl0i.png', 'aaaaaaaaaaaaaaa', '2025-10-17', '2025-10-18 05:59:23', '2025-10-18 05:59:23', NULL),
	(3, 'angel', 'emma@gmail.com', NULL, '9611724435', 'persona', 1, 'en_revision', NULL, '', '2025-10-16', '2026-02-07 06:38:32', '2026-02-07 06:38:32', NULL),
	(4, 'acasio puto', 'acasio@gmail.com', NULL, '3611724435', 'empresa', 1, 'en_revision', NULL, '', '2025-10-16', '2026-02-10 03:21:20', '2026-02-10 03:21:20', NULL),
	(5, 'alaaa', 'xd@gmail.com', NULL, '+52 1234567899', 'persona', 1, 'en_revision', NULL, '', '2025-10-16', '2026-02-10 08:57:40', '2026-02-10 08:57:40', NULL),
	(6, 'Arbey', 'arbe@gmail.com', NULL, '+52 9611724435', 'persona', 1, 'en_revision', NULL, '', '2025-10-16', '2026-02-13 22:40:29', '2026-02-13 22:40:29', NULL);

-- Volcando estructura para tabla roomhub_db.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.password_reset_tokens: ~0 rows (aproximadamente)
INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
	('lopeztrujilloxd@gmail.com', '$2y$12$EHpl66ncoAtvLLfQll.CF.V022MOLhZkMXNGUOIbO4.EVwgCsxCO.', '2026-02-10 03:02:30');

-- Volcando estructura para tabla roomhub_db.reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `apartment_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `rent_type` enum('day','month') COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount_cents` int unsigned NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mxn',
  `payment_method` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'stripe',
  `stripe_session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `guest_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reservations_stripe_session_id_unique` (`stripe_session_id`),
  KEY `reservations_user_id_foreign` (`user_id`),
  KEY `reservations_client_id_foreign` (`client_id`),
  KEY `reservations_apartment_id_check_in_check_out_index` (`apartment_id`,`check_in`,`check_out`),
  CONSTRAINT `reservations_apartment_id_foreign` FOREIGN KEY (`apartment_id`) REFERENCES `apartments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.reservations: ~14 rows (aproximadamente)
INSERT INTO `reservations` (`id`, `apartment_id`, `user_id`, `client_id`, `check_in`, `check_out`, `rent_type`, `total_amount_cents`, `currency`, `payment_method`, `stripe_session_id`, `stripe_payment_intent_id`, `status`, `guest_notes`, `created_at`, `updated_at`) VALUES
	(1, 4, 5, 2, '2026-02-09', '2026-03-09', 'month', 1500000, 'mxn', 'stripe', NULL, NULL, 'paid', NULL, '2026-02-09 19:35:21', '2026-02-09 19:35:21'),
	(2, 4, 5, 2, '2026-02-09', '2026-02-28', 'day', 950000, 'mxn', 'stripe', NULL, 'pi_3Sz5VP60ZTUKwQpK067cJCzy', 'pending', 'aaaa', '2026-02-10 01:12:41', '2026-02-10 01:12:42'),
	(3, 4, 5, 2, '2026-02-09', '2026-02-28', 'day', 950000, 'mxn', 'stripe', NULL, 'pi_3Sz5Wp60ZTUKwQpK0QGz3YTP', 'pending', 'aaaa', '2026-02-10 01:14:09', '2026-02-10 01:14:10'),
	(4, 4, 5, 2, '2026-02-09', '2026-02-28', 'day', 950000, 'mxn', 'stripe', NULL, NULL, 'pending', 'aaaa', '2026-02-10 01:15:27', '2026-02-10 01:15:27'),
	(5, 4, 5, 2, '2026-02-09', '2026-02-10', 'day', 50000, 'mxn', 'stripe', NULL, 'pi_3Sz72E60ZTUKwQpK09uWOyq5', 'paid', NULL, '2026-02-10 02:50:40', '2026-02-10 02:50:47'),
	(6, 4, 7, 3, '2026-03-01', '2026-04-01', 'month', 1500000, 'mxn', 'stripe', NULL, 'pi_3Sz7Xi60ZTUKwQpK1NtxKV98', 'pending', NULL, '2026-02-10 03:23:13', '2026-02-10 03:23:14'),
	(7, 4, 7, 3, '2026-03-01', '2026-04-01', 'month', 1500000, 'mxn', 'stripe', NULL, 'pi_3Sz7Xp60ZTUKwQpK1BkffJKg', 'paid', NULL, '2026-02-10 03:23:19', '2026-02-10 03:23:23'),
	(8, 4, 8, 5, '2026-03-01', '2026-04-01', 'month', 1500000, 'mxn', 'stripe', NULL, 'pi_3SzCwq60ZTUKwQpK0tGhlVXV', 'paid', NULL, '2026-02-10 09:09:30', '2026-02-10 09:09:33'),
	(9, 4, 4, 6, '2026-03-01', '2026-04-01', 'month', 1500000, 'mxn', 'stripe', NULL, 'pi_3SzDDz60ZTUKwQpK1bXIcybU', 'paid', NULL, '2026-02-10 09:27:13', '2026-02-10 09:27:18'),
	(10, 4, 4, 6, '2026-03-01', '2026-04-01', 'month', 1500000, 'mxn', 'stripe', NULL, 'pi_3SzDJ160ZTUKwQpK0BK15H1G', 'paid', NULL, '2026-02-10 09:32:25', '2026-02-10 09:32:29'),
	(11, 4, 4, 6, '2026-03-01', '2026-04-01', 'month', 1500000, 'mxn', 'stripe', NULL, 'pi_3SzDLv60ZTUKwQpK0N7URSN4', 'pending', NULL, '2026-02-10 09:35:25', '2026-02-10 09:35:27'),
	(12, 4, 4, 6, '2026-03-01', '2026-04-01', 'month', 1500000, 'mxn', 'stripe', NULL, 'pi_3SzDM160ZTUKwQpK1fqijV0O', 'paid', NULL, '2026-02-10 09:35:32', '2026-02-10 09:35:35'),
	(13, 4, 5, 2, '2026-03-01', '2026-04-01', 'month', 1500000, 'mxn', 'stripe', NULL, 'pi_3T0JDU60ZTUKwQpK0TFHpNcM', 'paid', NULL, '2026-02-13 10:03:14', '2026-02-13 10:03:20'),
	(15, 3, 5, 2, '2026-02-14', '2026-02-17', 'day', 12420, 'mxn', 'stripe', NULL, 'pi_3T0vQ960ZTUKwQpK0Th1b2iz', 'paid', NULL, '2026-02-15 02:50:50', '2026-02-15 02:50:57');

-- Volcando estructura para tabla roomhub_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.sessions: ~1 rows (aproximadamente)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('1DRSDKeNtBtRM9wvcIpHkOX7jYfRMmFWQ4reWzSF', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0NuU3I5WnRodXh0REc0Z2hMOEpnbWh1a0d6bHdtN1QwVDQ0RXR5VCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9maWxlcy9hcGFydG1lbnRzL1pNOVRHTzcxWlpZRUp3a0J1SE1VWXdwc1lSZ3BINjNEN01vVjkzYWIuanBnIjt9fQ==', 1771148577);

-- Volcando estructura para tabla roomhub_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `municipality` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locality` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `privacy_show_name_public` tinyint(1) NOT NULL DEFAULT '1',
  `privacy_show_location_public` tinyint(1) NOT NULL DEFAULT '0',
  `privacy_show_last_login` tinyint(1) NOT NULL DEFAULT '0',
  `locale` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `owner_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_owner_id_foreign` (`owner_id`),
  KEY `users_client_id_foreign` (`client_id`),
  CONSTRAINT `users_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `owners` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.users: ~11 rows (aproximadamente)
INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `role`, `status`, `email_verified_at`, `password`, `remember_token`, `last_login_at`, `postal_code`, `state`, `city`, `municipality`, `locality`, `privacy_show_name_public`, `privacy_show_location_public`, `privacy_show_last_login`, `locale`, `timezone`, `stripe_customer_id`, `two_factor_secret`, `two_factor_confirmed_at`, `owner_id`, `client_id`, `created_at`, `updated_at`) VALUES
	(1, 'Angel', 'a@gmail.com', NULL, 'client', 'active', NULL, '$2y$12$QhnoZKW4DFCXqG3X51twqe17cProhx.w5SEAdC9BmFfqRvtHK3w/K', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-17 04:23:48', '2025-10-17 04:23:48'),
	(2, 'angel e', 't@gmail.com', NULL, 'client', 'active', NULL, '$2y$12$Nx.C6dJ9dos0zMDK2AFEF.UeKXwUju9Qxm8M2sZWWg2NGKSLStNUu', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 4, '2025-10-17 06:01:10', '2026-02-10 08:09:32'),
	(4, 'angel', 'lopez@gmail.com', NULL, 'client', 'active', NULL, '$2y$12$0dE8Rw8jP6db2nOIDzu8uuin84SObzV4xKYD3qZUv/a2PwqRJl9Vy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 6, '2026-02-05 06:00:25', '2026-02-10 09:26:22'),
	(5, 'angel', 'lopeztrujilloxd@gmail.com', 'avatars/Vu9wwX8uvJVa8kyX9JgQtEq06xlYAqvIGoTQtgsI.png', 'owner', 'active', '2026-02-15 08:50:57', '$2y$12$fUyq8N9mMxfOQsoTheMxLeaSJe4CFp1d2.0zqVp/h5oBah1Mj25vy', 'Qm8Jf2wbUWGsahoQyfbk24o4sNrzPbQpcMFESoCGsJ6YhtgwVWvIa3onh2K8', '2026-02-15 07:59:14', '29950', 'Chiapas', 'Ocosingo', 'Ocosingo', 'Ocosingo', 1, 0, 1, 'es', NULL, 'cus_TyxoPHKuDlnaXr', 'eyJpdiI6IlBoMnpQV3JrVHhnWnlIL3cvMmlWR0E9PSIsInZhbHVlIjoieUN4ZGdNVDVicWpEUnMvOTE0c1BXSXRQdVp0azZNZGx3WWFaNkJJV0VEQWd2ZVFIMTdEMXo3L2hjMTdsL2tHWiIsIm1hYyI6IjgxYzc2ZWMzOGU1ZGU5ZjlkOWJiYWRlOTdmMTcxYTg5NzBjMzYyZjc0OGU4NjAzNDQ1MGY4YmFlNDliNjU0MDMiLCJ0YWciOiIifQ==', '2026-02-15 07:58:31', 3, 2, '2026-02-07 06:27:42', '2026-02-15 08:50:57'),
	(6, 'Administrador', 'admin@roomhub.local', NULL, 'admin', 'active', '2026-02-15 09:03:21', '$2y$12$pawAQaefMsZR3V1md2XdxePLDHJdMcG8qve.AFJXs6bKbYG3ucpvy', NULL, '2026-02-15 09:00:53', '29910', 'Chiapas', 'Tila', 'Tila', 'Tila', 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-09 07:58:53', '2026-02-15 09:00:53'),
	(7, 'acasio puto', 'acasio@gmail.com', NULL, 'owner', 'active', NULL, '$2y$12$pIafVwAyYDACoPy/c0UuTOwxUoQ8xOaKHbBn9FbGg2ERn0f6.1YOy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, 4, 3, '2026-02-10 03:20:08', '2026-02-10 03:21:20'),
	(8, 'alaaa', 'xd@gmail.com', NULL, 'owner', 'active', NULL, '$2y$12$XYGg6dEnNYhaSZiFlj0OsegUeeaCWFcgdVt.hQsXgrXo1my47YnYm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, 5, 5, '2026-02-10 08:12:17', '2026-02-10 08:57:40'),
	(9, 'roldan', 'r@gmail.com', NULL, 'client', 'active', NULL, '$2y$12$Gc8oW3nNeW91PkAKEjgt5e9KAhOmlgDt5P9wIhHCl7LSRXZ.JEbDS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2026-02-10 21:06:50', '2026-02-10 21:06:50'),
	(10, 'emmanuel', 'emm@gmail.com', NULL, 'client', 'active', NULL, '$2y$12$hA8UcfhUEhcNXLAN7iyq9u31.m/UhdlIftksJps/J/6i6i7koisGq', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 8, '2026-02-12 09:15:10', '2026-02-12 09:15:10'),
	(11, 'Arbey', 'arbe@gmail.com', NULL, 'owner', 'active', NULL, '$2y$12$K8ILV8RqsIrEc4rciqe8auLzSZup1UKaoYY40DvQHfcTSPVrbOA7K', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, 6, 9, '2026-02-13 22:36:43', '2026-02-13 22:40:29'),
	(12, 'Angel Emmanuel Trujillo Lopez', 'angel@gmail.com', NULL, 'client', 'active', NULL, '$2y$12$zv960XIAPcmx.AVOSrn0GeXpP5rgMl1pmCyQmctJh1WjR1K7sFvvG', NULL, NULL, '29910', 'Chiapas', 'Tila', 'Tila', 'Tila', 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2026-02-15 04:31:40', '2026-02-15 04:31:40');

-- Volcando estructura para tabla roomhub_db.user_activities
CREATE TABLE IF NOT EXISTS `user_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `action` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_activities_user_id_foreign` (`user_id`),
  CONSTRAINT `user_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roomhub_db.user_activities: ~63 rows (aproximadamente)
INSERT INTO `user_activities` (`id`, `user_id`, `action`, `metadata`, `ip_address`, `created_at`, `updated_at`) VALUES
	(1, 6, 'logout', NULL, '127.0.0.1', '2026-02-11 08:23:03', '2026-02-11 08:23:03'),
	(2, 5, 'login', NULL, '127.0.0.1', '2026-02-11 08:23:31', '2026-02-11 08:23:31'),
	(3, 5, 'logout', NULL, '127.0.0.1', '2026-02-11 08:35:31', '2026-02-11 08:35:31'),
	(4, 6, 'login', NULL, '127.0.0.1', '2026-02-11 08:35:45', '2026-02-11 08:35:45'),
	(5, 6, 'logout', NULL, '127.0.0.1', '2026-02-11 08:50:18', '2026-02-11 08:50:18'),
	(6, 5, 'login', NULL, '127.0.0.1', '2026-02-11 08:50:52', '2026-02-11 08:50:52'),
	(7, 5, 'logout', NULL, '127.0.0.1', '2026-02-11 09:45:00', '2026-02-11 09:45:00'),
	(8, 5, 'login', NULL, '127.0.0.1', '2026-02-11 09:45:28', '2026-02-11 09:45:28'),
	(9, 5, 'logout', NULL, '127.0.0.1', '2026-02-11 09:49:06', '2026-02-11 09:49:06'),
	(10, 5, 'login', NULL, '127.0.0.1', '2026-02-11 09:49:41', '2026-02-11 09:49:41'),
	(11, 5, 'login_api', '{"client": "flutter"}', '127.0.0.1', '2026-02-12 07:02:28', '2026-02-12 07:02:28'),
	(12, 5, 'logout_api', '{"client": "flutter"}', '127.0.0.1', '2026-02-12 07:02:37', '2026-02-12 07:02:37'),
	(15, 6, 'login_api', '{"client": "flutter"}', '127.0.0.1', '2026-02-12 07:09:47', '2026-02-12 07:09:47'),
	(16, 6, 'logout_api', '{"client": "flutter"}', '127.0.0.1', '2026-02-12 07:28:04', '2026-02-12 07:28:04'),
	(17, 5, 'login_api', '{"client": "flutter"}', '127.0.0.1', '2026-02-12 07:55:56', '2026-02-12 07:55:56'),
	(18, 5, 'logout_api', '{"client": "flutter"}', '127.0.0.1', '2026-02-12 09:11:51', '2026-02-12 09:11:51'),
	(19, 10, 'register_api', '{"client": "flutter"}', '127.0.0.1', '2026-02-12 09:15:10', '2026-02-12 09:15:10'),
	(20, 5, 'login', NULL, '127.0.0.1', '2026-02-13 09:44:18', '2026-02-13 09:44:18'),
	(21, 5, 'logout', NULL, '127.0.0.1', '2026-02-13 09:53:49', '2026-02-13 09:53:49'),
	(22, 5, 'login', NULL, '127.0.0.1', '2026-02-13 09:54:45', '2026-02-13 09:54:45'),
	(23, 5, 'logout', NULL, '127.0.0.1', '2026-02-13 10:09:56', '2026-02-13 10:09:56'),
	(24, 6, 'login', NULL, '127.0.0.1', '2026-02-13 10:10:13', '2026-02-13 10:10:13'),
	(25, 6, 'logout', NULL, '127.0.0.1', '2026-02-13 10:35:33', '2026-02-13 10:35:33'),
	(26, 5, 'login', NULL, '127.0.0.1', '2026-02-13 10:35:47', '2026-02-13 10:35:47'),
	(27, 5, 'login', NULL, '127.0.0.1', '2026-02-13 22:27:25', '2026-02-13 22:27:25'),
	(28, 5, 'logout', NULL, '127.0.0.1', '2026-02-13 22:29:21', '2026-02-13 22:29:21'),
	(29, 6, 'login', NULL, '127.0.0.1', '2026-02-13 22:30:00', '2026-02-13 22:30:00'),
	(30, 6, 'logout', NULL, '127.0.0.1', '2026-02-13 22:32:19', '2026-02-13 22:32:19'),
	(31, 5, 'login', NULL, '127.0.0.1', '2026-02-13 22:32:39', '2026-02-13 22:32:39'),
	(32, 5, 'logout', NULL, '127.0.0.1', '2026-02-13 22:36:20', '2026-02-13 22:36:20'),
	(33, 6, 'login', NULL, '127.0.0.1', '2026-02-13 22:38:25', '2026-02-13 22:38:25'),
	(34, 6, 'login', NULL, '127.0.0.1', '2026-02-14 06:41:28', '2026-02-14 06:41:28'),
	(35, 6, 'logout', NULL, '127.0.0.1', '2026-02-14 07:20:51', '2026-02-14 07:20:51'),
	(36, 5, 'login', NULL, '127.0.0.1', '2026-02-14 07:21:18', '2026-02-14 07:21:18'),
	(37, 5, 'logout', NULL, '127.0.0.1', '2026-02-14 08:02:37', '2026-02-14 08:02:37'),
	(38, 5, 'login', NULL, '127.0.0.1', '2026-02-14 08:18:07', '2026-02-14 08:18:07'),
	(39, 5, 'login', NULL, '127.0.0.1', '2026-02-15 01:08:26', '2026-02-15 01:08:26'),
	(40, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 01:19:48', '2026-02-15 01:19:48'),
	(41, 5, 'login', NULL, '127.0.0.1', '2026-02-15 01:23:09', '2026-02-15 01:23:09'),
	(42, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 01:23:57', '2026-02-15 01:23:57'),
	(43, 5, 'login', NULL, '127.0.0.1', '2026-02-15 01:38:25', '2026-02-15 01:38:25'),
	(44, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 01:47:44', '2026-02-15 01:47:44'),
	(45, 5, 'login', NULL, '127.0.0.1', '2026-02-15 01:53:10', '2026-02-15 01:53:10'),
	(46, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 02:31:21', '2026-02-15 02:31:21'),
	(47, 5, 'login', NULL, '127.0.0.1', '2026-02-15 02:33:39', '2026-02-15 02:33:39'),
	(48, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 02:39:09', '2026-02-15 02:39:09'),
	(49, 6, 'login', NULL, '127.0.0.1', '2026-02-15 02:39:37', '2026-02-15 02:39:37'),
	(50, 6, 'logout', NULL, '127.0.0.1', '2026-02-15 02:45:12', '2026-02-15 02:45:12'),
	(51, 5, 'login', NULL, '127.0.0.1', '2026-02-15 02:45:25', '2026-02-15 02:45:25'),
	(52, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 03:05:29', '2026-02-15 03:05:29'),
	(53, 5, 'login', NULL, '127.0.0.1', '2026-02-15 03:07:22', '2026-02-15 03:07:22'),
	(54, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 03:49:00', '2026-02-15 03:49:00'),
	(55, 5, 'login', NULL, '127.0.0.1', '2026-02-15 04:17:56', '2026-02-15 04:17:56'),
	(56, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 04:18:01', '2026-02-15 04:18:01'),
	(57, 5, 'login', NULL, '127.0.0.1', '2026-02-15 04:18:19', '2026-02-15 04:18:19'),
	(58, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 04:18:24', '2026-02-15 04:18:24'),
	(59, 12, 'logout', NULL, '127.0.0.1', '2026-02-15 05:14:31', '2026-02-15 05:14:31'),
	(60, 5, 'login', NULL, '127.0.0.1', '2026-02-15 06:52:31', '2026-02-15 06:52:31'),
	(61, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 07:58:48', '2026-02-15 07:58:48'),
	(62, 5, 'login', '{"2fa": true}', '127.0.0.1', '2026-02-15 07:59:14', '2026-02-15 07:59:14'),
	(63, 5, 'logout', NULL, '127.0.0.1', '2026-02-15 08:54:04', '2026-02-15 08:54:04'),
	(64, 6, 'login', NULL, '127.0.0.1', '2026-02-15 08:54:36', '2026-02-15 08:54:36'),
	(65, 6, 'logout', NULL, '127.0.0.1', '2026-02-15 09:00:43', '2026-02-15 09:00:43'),
	(66, 6, 'login', NULL, '127.0.0.1', '2026-02-15 09:00:53', '2026-02-15 09:00:53'),
	(67, 6, 'logout', NULL, '127.0.0.1', '2026-02-15 09:29:12', '2026-02-15 09:29:12');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
