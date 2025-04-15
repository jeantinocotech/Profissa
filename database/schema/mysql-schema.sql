/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `advisor_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `advisor_skills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_profiles_advisor` int NOT NULL,
  `id_skills` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_advisor_skills_profile` (`id_profiles_advisor`),
  KEY `FK_advisor_skills_skills` (`id_skills`),
  CONSTRAINT `FK_advisor_skills_profile` FOREIGN KEY (`id_profiles_advisor`) REFERENCES `profiles_advisor` (`id`),
  CONSTRAINT `FK_advisor_skills_skills` FOREIGN KEY (`id_skills`) REFERENCES `skills` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `career_finders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `career_finders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `chirps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chirps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `courses_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `education` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Institution_name` varchar(45) DEFAULT NULL,
  `id_courses` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_couses` (`id_courses`),
  CONSTRAINT `FK_Courses_Id_course` FOREIGN KEY (`id_courses`) REFERENCES `courses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `finder_interest_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `finder_interest_areas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_courses` int DEFAULT NULL,
  `id_profiles_finder` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_courses_idx` (`id_courses`),
  KEY `FK_finder_idx` (`id_profiles_finder`),
  CONSTRAINT `FK_courses_finder_areas` FOREIGN KEY (`id_courses`) REFERENCES `courses` (`id`),
  CONSTRAINT `FK_finder_areas` FOREIGN KEY (`id_profiles_finder`) REFERENCES `profiles_finder` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `finder_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `finder_skills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_profiles_finder` bigint unsigned NOT NULL,
  `id_skills` bigint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `finder_skill_unique` (`id_profiles_finder`,`id_skills`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `finder_skills_interests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `finder_skills_interests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_profiles_finder` int NOT NULL,
  `id_skills` int NOT NULL,
  `importance_level` int NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`),
  KEY `FK_finder_skills_profile` (`id_profiles_finder`),
  KEY `FK_finder_skills_skills` (`id_skills`),
  CONSTRAINT `FK_finder_skills_profile` FOREIGN KEY (`id_profiles_finder`) REFERENCES `profiles_finder` (`id`),
  CONSTRAINT `FK_finder_skills_skills` FOREIGN KEY (`id_skills`) REFERENCES `skills` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `meeting_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meeting_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_profiles_finder` int NOT NULL,
  `id_profiles_advisor` int NOT NULL,
  `status` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending',
  `finder_message` text,
  `advisor_response` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_meeting_finder` (`id_profiles_finder`),
  KEY `FK_meeting_advisor` (`id_profiles_advisor`),
  CONSTRAINT `FK_meeting_advisor` FOREIGN KEY (`id_profiles_advisor`) REFERENCES `profiles_advisor` (`id`),
  CONSTRAINT `FK_meeting_finder` FOREIGN KEY (`id_profiles_finder`) REFERENCES `profiles_finder` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profile_education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile_education` (
  `id_profiles_education` int NOT NULL AUTO_INCREMENT,
  `id_profiles_advisor` int DEFAULT NULL,
  `id_profiles_finder` int DEFAULT NULL,
  `id_courses` int DEFAULT NULL,
  `institution_name` varchar(45) DEFAULT NULL,
  `certification` varchar(45) DEFAULT NULL,
  `dt_start` date DEFAULT NULL,
  `dt_end` date DEFAULT NULL,
  `comments` text,
  `created_at` datetime DEFAULT NULL COMMENT '		',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_profiles_education`),
  KEY `FK_profile_advisor_idx` (`id_profiles_advisor`),
  KEY `FK_profiles_finder_idx` (`id_profiles_finder`),
  KEY `FK_courses` (`id_courses`),
  CONSTRAINT `FK_courses` FOREIGN KEY (`id_courses`) REFERENCES `courses` (`id`),
  CONSTRAINT `FK_profiles_advisor_edu` FOREIGN KEY (`id_profiles_advisor`) REFERENCES `profiles_advisor` (`id`),
  CONSTRAINT `FK_profiles_finder_edu` FOREIGN KEY (`id_profiles_finder`) REFERENCES `profiles_finder` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profiles_advisor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles_advisor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `full_name` varchar(45) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(45) DEFAULT NULL,
  `instagram_url` varchar(45) DEFAULT NULL,
  `overview` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_completed` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `Idx_FK_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `profiles_finder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles_finder` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `full_name` varchar(45) NOT NULL,
  `profile_picture` varchar(45) DEFAULT NULL,
  `linkedin_url` varchar(45) DEFAULT NULL,
  `instagram_url` varchar(45) DEFAULT NULL,
  `overview` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_completed` tinyint(1) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_profile_user_idx` (`user_id`),
  CONSTRAINT `FK_profile_user2` FOREIGN KEY (`user_id`) REFERENCES `profisa`.`users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_profiles_advisor` int DEFAULT NULL,
  `id_profiles_finder` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_requests_advisor_idx` (`id_profiles_advisor`),
  KEY `FK_requests_finder_idx` (`id_profiles_finder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
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
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `skills` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_01_21_183805_create_chirps_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_01_23_163205_create_career_finders_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_02_09_122633_create_cache_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_02_09_122633_create_cache_locks_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_02_09_122633_create_career_finders_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_02_09_122633_create_chirps_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_02_09_122633_create_courses_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_02_09_122633_create_education_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_02_09_122633_create_failed_jobs_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_02_09_122633_create_finder_interest_areas_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_02_09_122633_create_job_batches_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_02_09_122633_create_jobs_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_02_09_122633_create_logs_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_02_09_122633_create_password_reset_tokens_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_02_09_122633_create_profile_education_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_02_09_122633_create_profiles_advisor_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_02_09_122633_create_profiles_finder_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_02_09_122633_create_requests_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_02_09_122633_create_sessions_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_02_09_122633_create_users_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_02_09_122636_add_foreign_keys_to_education_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_02_09_122636_add_foreign_keys_to_finder_interest_areas_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_02_09_122636_add_foreign_keys_to_profile_education_table',0);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_02_09_122636_add_foreign_keys_to_profiles_finder_table',0);
