
CREATE TABLE `finder_interest_areas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_courses` int DEFAULT NULL,
  `id_profiles_finder` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_courses_idx` (`id_courses`),
  KEY `FK_finder_idx` (`id_profiles_finder`),
  CONSTRAINT `FK_courses_finder_areas` FOREIGN KEY (`id_courses`) REFERENCES `courses` (`id`),
  CONSTRAINT `FK_finder_areas` FOREIGN KEY (`id_profiles_finder`) REFERENCES `profiles_finder` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  CONSTRAINT `FK_courses` FOREIGN KEY (`id_courses`) REFERENCES `courses` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `education` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Institution_name` varchar(45) DEFAULT NULL,
  `id_courses` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_couses` (`id_courses`),
  CONSTRAINT `FK_Courses_Id_course` FOREIGN KEY (`id_courses`) REFERENCES `courses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
CREATE TABLE `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `courses_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
