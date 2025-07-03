USE wmdemo;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- USERS TABLE
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `role` enum('customer','admin','manager') NOT NULL DEFAULT 'customer',
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `user_code` varchar(20) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'active',
  `suspension_reason` varchar(191) DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(191) DEFAULT NULL,
  `login_attempts` int UNSIGNED NOT NULL DEFAULT '0',
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_user_code_unique` (`user_code`),
  KEY `users_status_index` (`status`),
  KEY `users_role_index` (`role`),
  KEY `users_first_name_last_name_index` (`first_name`,`last_name`),
  KEY `users_phone_index` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- HALLS
DROP TABLE IF EXISTS `halls`;
CREATE TABLE `halls` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `capacity` int NOT NULL,
  `image` varchar(255),
  `features` json,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PACKAGES
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `highlight` boolean DEFAULT 0,
  `image` varchar(255),
  `features` json,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- WEDDING TYPES
DROP TABLE IF EXISTS `wedding_types`;
CREATE TABLE `wedding_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DECORATIONS
DROP TABLE IF EXISTS `decorations`;
CREATE TABLE `decorations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT 0.00,
  `image` varchar(255),
  `wedding_type_id` bigint UNSIGNED,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wedding_type_id` (`wedding_type_id`),
  CONSTRAINT `decorations_wedding_type_id_foreign` FOREIGN KEY (`wedding_type_id`) REFERENCES `wedding_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CATERING MENUS
DROP TABLE IF EXISTS `catering_menus`;
CREATE TABLE `catering_menus` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `details` json,
  `package_id` bigint UNSIGNED,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `catering_menus_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CATERING ITEMS
DROP TABLE IF EXISTS `catering_items`;
CREATE TABLE `catering_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(50),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ADDITIONAL SERVICES
DROP TABLE IF EXISTS `additional_services`;
CREATE TABLE `additional_services` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT 0.00,
  `image` varchar(255),
  `type` enum('compulsory','optional','paid') DEFAULT 'optional',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- BOOKINGS
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `hall_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `wedding_type_id` bigint UNSIGNED,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `hall_name` varchar(255) NOT NULL,
  `hall_booking_date` date NOT NULL,
  `package_name` varchar(255),
  `package_price` decimal(10,2) NOT NULL,
  `guest_count` int,
  `wedding_type_time_slot` varchar(50),
  `catholic_day1_date` date,
  `catholic_day2_date` date,
  `selected_menu_id` bigint UNSIGNED,
  `contact_name` varchar(255),
  `contact_email` varchar(255),
  `contact_phone` varchar(255),
  `visit_purpose` varchar(255),
  `visit_purpose_other` varchar(255),
  `visit_date` date,
  `visit_time` time,
  `special_requests` text,
  `wedding_groom_name` varchar(255),
  `wedding_bride_name` varchar(255),
  `wedding_groom_email` varchar(255),
  `wedding_bride_email` varchar(255),
  `wedding_groom_phone` varchar(255),
  `wedding_bride_phone` varchar(255),
  `wedding_date` date,
  `wedding_ceremony_time` varchar(255),
  `wedding_reception_time` varchar(255),
  `wedding_additional_notes` text,
  `wedding_terms_agreed` boolean,
  `wedding_privacy_agreed` boolean,
  `total_amount` decimal(12,2),
  `terms_agreed` boolean DEFAULT 0,
  `privacy_agreed` boolean DEFAULT 0,
  `event_date` date,
  `start_time` varchar(255),
  `end_time` varchar(255),
  `customization_guest_count` int,
  `customization_wedding_type` varchar(255),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `hall_id` (`hall_id`),
  KEY `package_id` (`package_id`),
  KEY `wedding_type_id` (`wedding_type_id`),
  KEY `selected_menu_id` (`selected_menu_id`),
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_hall_id_foreign` FOREIGN KEY (`hall_id`) REFERENCES `halls` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `bookings_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `bookings_wedding_type_id_foreign` FOREIGN KEY (`wedding_type_id`) REFERENCES `wedding_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_selected_menu_id_foreign` FOREIGN KEY (`selected_menu_id`) REFERENCES `catering_menus` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- BOOKING DECORATIONS
DROP TABLE IF EXISTS `booking_decorations`;
CREATE TABLE `booking_decorations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `decoration_id` bigint UNSIGNED NOT NULL,
  `quantity` int DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `decoration_id` (`decoration_id`),
  CONSTRAINT `booking_decorations_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_decorations_decoration_id_foreign` FOREIGN KEY (`decoration_id`) REFERENCES `decorations` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- BOOKING SERVICES
DROP TABLE IF EXISTS `booking_services`;
CREATE TABLE `booking_services` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `booking_services_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `additional_services` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- BOOKING CATERING ITEMS
DROP TABLE IF EXISTS `booking_catering_items`;
CREATE TABLE `booking_catering_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `item_id` bigint UNSIGNED,
  `custom_name` varchar(255),
  `category` varchar(255),
  `price` decimal(10,2),
  `quantity` int DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `booking_catering_items_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_catering_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `catering_items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- BOOKING PAYMENTS
DROP TABLE IF EXISTS `booking_payments`;
CREATE TABLE `booking_payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transaction_id` varchar(255),
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_date` datetime,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `booking_payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- GALLERY ITEMS
DROP TABLE IF EXISTS `gallery_items`;
CREATE TABLE IF NOT EXISTS `gallery_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- MESSAGES
DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PASSWORD RESET TOKENS
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_reset_tokens_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SESSIONS
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` text NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- JOBS
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- JOB BATCHES
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- FAILED JOBS
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
