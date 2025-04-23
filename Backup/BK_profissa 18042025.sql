-- MySQL dump 10.13  Distrib 8.0.40, for macos14 (arm64)
--
-- Host: localhost    Database: my_herd_app
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `advisor_availabilities`
--

DROP TABLE IF EXISTS `advisor_availabilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `advisor_availabilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_profiles_advisor` int NOT NULL,
  `available_date` date DEFAULT NULL,
  `weekday` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `advisor_availabilities_id_profiles_advisor_foreign` (`id_profiles_advisor`),
  CONSTRAINT `advisor_availabilities_id_profiles_advisor_foreign` FOREIGN KEY (`id_profiles_advisor`) REFERENCES `profiles_advisor` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `advisor_availabilities`
--

LOCK TABLES `advisor_availabilities` WRITE;
/*!40000 ALTER TABLE `advisor_availabilities` DISABLE KEYS */;
/*!40000 ALTER TABLE `advisor_availabilities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `advisor_skills`
--

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

--
-- Dumping data for table `advisor_skills`
--

LOCK TABLES `advisor_skills` WRITE;
/*!40000 ALTER TABLE `advisor_skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `advisor_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('pedroneves@gmail.com|127.0.0.1','i:2;',1744954187),('pedroneves@gmail.com|127.0.0.1:timer','i:1744954187;',1744954187);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `career_finders`
--

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

--
-- Dumping data for table `career_finders`
--

LOCK TABLES `career_finders` WRITE;
/*!40000 ALTER TABLE `career_finders` DISABLE KEYS */;
/*!40000 ALTER TABLE `career_finders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chirps`
--

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

--
-- Dumping data for table `chirps`
--

LOCK TABLES `chirps` WRITE;
/*!40000 ALTER TABLE `chirps` DISABLE KEYS */;
/*!40000 ALTER TABLE `chirps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `courses_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `education`
--

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

--
-- Dumping data for table `education`
--

LOCK TABLES `education` WRITE;
/*!40000 ALTER TABLE `education` DISABLE KEYS */;
/*!40000 ALTER TABLE `education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finder_interest_areas`
--

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

--
-- Dumping data for table `finder_interest_areas`
--

LOCK TABLES `finder_interest_areas` WRITE;
/*!40000 ALTER TABLE `finder_interest_areas` DISABLE KEYS */;
/*!40000 ALTER TABLE `finder_interest_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finder_skills`
--

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

--
-- Dumping data for table `finder_skills`
--

LOCK TABLES `finder_skills` WRITE;
/*!40000 ALTER TABLE `finder_skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `finder_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `finder_skills_interests`
--

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

--
-- Dumping data for table `finder_skills_interests`
--

LOCK TABLES `finder_skills_interests` WRITE;
/*!40000 ALTER TABLE `finder_skills_interests` DISABLE KEYS */;
/*!40000 ALTER TABLE `finder_skills_interests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

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

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_proposals`
--

DROP TABLE IF EXISTS `meeting_proposals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meeting_proposals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_meeting_request` int NOT NULL,
  `proposed_datetime` datetime NOT NULL,
  `finder_comment` text COLLATE utf8mb4_unicode_ci,
  `advisor_comment` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','accepted','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meeting_proposals_id_meeting_request_foreign` (`id_meeting_request`),
  CONSTRAINT `meeting_proposals_id_meeting_request_foreign` FOREIGN KEY (`id_meeting_request`) REFERENCES `meeting_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_proposals`
--

LOCK TABLES `meeting_proposals` WRITE;
/*!40000 ALTER TABLE `meeting_proposals` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting_proposals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_requests`
--

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

--
-- Dumping data for table `meeting_requests`
--

LOCK TABLES `meeting_requests` WRITE;
/*!40000 ALTER TABLE `meeting_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_01_21_183805_create_chirps_table',2),(5,'2025_01_23_163205_create_career_finders_table',2),(6,'2025_02_09_122633_create_cache_table',0),(7,'2025_02_09_122633_create_cache_locks_table',0),(8,'2025_02_09_122633_create_career_finders_table',0),(9,'2025_02_09_122633_create_chirps_table',0),(10,'2025_02_09_122633_create_courses_table',0),(11,'2025_02_09_122633_create_education_table',0),(12,'2025_02_09_122633_create_failed_jobs_table',0),(13,'2025_02_09_122633_create_finder_interest_areas_table',0),(14,'2025_02_09_122633_create_job_batches_table',0),(15,'2025_02_09_122633_create_jobs_table',0),(16,'2025_02_09_122633_create_logs_table',0),(17,'2025_02_09_122633_create_password_reset_tokens_table',0),(18,'2025_02_09_122633_create_profile_education_table',0),(19,'2025_02_09_122633_create_profiles_advisor_table',0),(20,'2025_02_09_122633_create_profiles_finder_table',0),(21,'2025_02_09_122633_create_requests_table',0),(22,'2025_02_09_122633_create_sessions_table',0),(23,'2025_02_09_122633_create_users_table',0),(24,'2025_02_09_122636_add_foreign_keys_to_education_table',0),(25,'2025_02_09_122636_add_foreign_keys_to_finder_interest_areas_table',0),(26,'2025_02_09_122636_add_foreign_keys_to_profile_education_table',0),(27,'2025_02_09_122636_add_foreign_keys_to_profiles_finder_table',0),(29,'2025_04_18_040051_create_advisor_availabilities_table',3),(30,'2025_04_18_040408_create_meeting_proposals_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_education`
--

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

--
-- Dumping data for table `profile_education`
--

LOCK TABLES `profile_education` WRITE;
/*!40000 ALTER TABLE `profile_education` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile_education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles_advisor`
--

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

--
-- Dumping data for table `profiles_advisor`
--

LOCK TABLES `profiles_advisor` WRITE;
/*!40000 ALTER TABLE `profiles_advisor` DISABLE KEYS */;
/*!40000 ALTER TABLE `profiles_advisor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles_finder`
--

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

--
-- Dumping data for table `profiles_finder`
--

LOCK TABLES `profiles_finder` WRITE;
/*!40000 ALTER TABLE `profiles_finder` DISABLE KEYS */;
/*!40000 ALTER TABLE `profiles_finder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests`
--

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

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('XVly4HQPHmjrzb6lwoReRsIsh9YCmOEr7200RW7f',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZXVtUVlDRzJzcTNOV0FpZzFFSVNrWHJEbkRsNW5HdUN6azRmZkJ1TyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=',1744954140);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skills`
--

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

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-18  8:09:07
