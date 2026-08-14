-- Leyla Safari Tours — full MySQL schema (idempotent)
-- Generated: 2026-08-14 14:16:38
-- Source: database/migrations (via php artisan schema:export-sql)
--
-- Safe to re-run on production:
--   • Creates tables that do not exist
--   • Adds missing columns, indexes, and foreign keys
--   • Does NOT drop columns or data
--
-- Import in phpMyAdmin or: mysql -u USER -p DATABASE < database/schema/full-schema.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP PROCEDURE IF EXISTS sync_column;
DROP PROCEDURE IF EXISTS sync_index;
DROP PROCEDURE IF EXISTS sync_foreign_key;

DELIMITER $$

CREATE PROCEDURE sync_column(
    IN p_table VARCHAR(64),
    IN p_column VARCHAR(64),
    IN p_definition TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_table
          AND COLUMN_NAME = p_column
    ) THEN
        SET @sql = CONCAT(
            'ALTER TABLE `', REPLACE(p_table, '`', '``'),
            '` ADD COLUMN `', REPLACE(p_column, '`', '``'),
            '` ', p_definition
        );
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

CREATE PROCEDURE sync_index(
    IN p_table VARCHAR(64),
    IN p_index VARCHAR(64),
    IN p_definition TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_table
          AND INDEX_NAME = p_index
    ) THEN
        SET @sql = CONCAT(
            'ALTER TABLE `', REPLACE(p_table, '`', '``'),
            '` ADD ', p_definition
        );
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

CREATE PROCEDURE sync_foreign_key(
    IN p_table VARCHAR(64),
    IN p_constraint VARCHAR(64),
    IN p_column VARCHAR(64),
    IN p_ref_table VARCHAR(64),
    IN p_ref_column VARCHAR(64),
    IN p_on_delete VARCHAR(32)
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.TABLE_CONSTRAINTS tc
        INNER JOIN information_schema.KEY_COLUMN_USAGE kcu
            ON tc.CONSTRAINT_SCHEMA = kcu.CONSTRAINT_SCHEMA
           AND tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
           AND tc.TABLE_NAME = kcu.TABLE_NAME
        WHERE tc.CONSTRAINT_TYPE = 'FOREIGN KEY'
          AND tc.TABLE_SCHEMA = DATABASE()
          AND tc.TABLE_NAME = p_table
          AND kcu.COLUMN_NAME = p_column
          AND kcu.REFERENCED_TABLE_NAME = p_ref_table
          AND kcu.REFERENCED_COLUMN_NAME = p_ref_column
    ) THEN
        SET @sql = CONCAT(
            'ALTER TABLE `', REPLACE(p_table, '`', '``'),
            '` ADD CONSTRAINT `', REPLACE(p_constraint, '`', '``'),
            '` FOREIGN KEY (`', REPLACE(p_column, '`', '``'),
            '`) REFERENCES `', REPLACE(p_ref_table, '`', '``'),
            '` (`', REPLACE(p_ref_column, '`', '``'),
            '`) ON DELETE ', p_on_delete
        );
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

-- ============================================================
-- CREATE TABLES (skipped when table already exists)
-- ============================================================

CREATE TABLE IF NOT EXISTS `annual_events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` BIGINT UNSIGNED NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `excerpt` TEXT NULL,
  `description` LONGTEXT NULL,
  `event_date` DATE NOT NULL,
  `early_bird_deadline` DATE NULL,
  `early_bird_price` DECIMAL(10,2) NULL,
  `regular_price` DECIMAL(10,2) NULL,
  `currency` VARCHAR(255) NOT NULL DEFAULT 'USD',
  `hero_image` VARCHAR(255) NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `annual_events_slug_unique` (`slug`),
  PRIMARY KEY (`id`),
  CONSTRAINT `annual_events_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `author_id` BIGINT UNSIGNED NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `excerpt` TEXT NULL,
  `content` LONGTEXT NULL,
  `featured_image` VARCHAR(255) NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'draft',
  `published_at` TIMESTAMP NULL,
  `seo_title` VARCHAR(255) NULL,
  `seo_description` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  PRIMARY KEY (`id`),
  CONSTRAINT `blog_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NOT NULL,
  `expiration` INT UNSIGNED NOT NULL,
  KEY `cache_expiration_index` (`expiration`),
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT UNSIGNED NOT NULL,
  KEY `cache_locks_expiration_index` (`expiration`),
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `destinations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `country` VARCHAR(255) NOT NULL DEFAULT 'Kenya',
  `region` VARCHAR(255) NULL,
  `excerpt` TEXT NULL,
  `description` LONGTEXT NULL,
  `best_time` VARCHAR(255) NULL,
  `signature_wildlife` TEXT NULL,
  `hero_image` VARCHAR(255) NULL,
  `gallery` TEXT NULL,
  `facts` TEXT NULL,
  `latitude` DECIMAL(10,7) NULL,
  `longitude` DECIMAL(10,7) NULL,
  `seo_title` VARCHAR(255) NULL,
  `seo_description` TEXT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `destinations_slug_unique` (`slug`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NULL,
  `package_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NULL,
  `whatsapp` VARCHAR(255) NULL,
  `preferred_destinations` VARCHAR(255) NULL,
  `travel_dates` VARCHAR(255) NULL,
  `group_size` SMALLINT UNSIGNED NULL,
  `budget_range` VARCHAR(255) NULL,
  `special_interests` TEXT NULL,
  `message` TEXT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'new',
  `assigned_to` INT NULL,
  `admin_notes` TEXT NULL,
  `source` VARCHAR(255) NOT NULL DEFAULT 'website',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `enquiries_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `enquiries_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `enquiries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `experience_package` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `experience_id` BIGINT UNSIGNED NOT NULL,
  `package_id` BIGINT UNSIGNED NOT NULL,
  UNIQUE KEY `experience_package_experience_id_package_id_unique` (`experience_id`, `package_id`),
  PRIMARY KEY (`id`),
  CONSTRAINT `experience_package_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `experience_package_experience_id_foreign` FOREIGN KEY (`experience_id`) REFERENCES `experiences` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `experiences` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `type` VARCHAR(255) NOT NULL DEFAULT 'wildlife',
  `excerpt` TEXT NULL,
  `description` LONGTEXT NULL,
  `image` VARCHAR(255) NULL,
  `duration_hours` INT UNSIGNED NULL,
  `starting_price` DECIMAL(10,2) NULL,
  `currency` VARCHAR(255) NOT NULL DEFAULT 'USD',
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `experiences_slug_unique` (`slug`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` VARCHAR(255) NOT NULL DEFAULT 'general',
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `footer_links` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `group` VARCHAR(255) NOT NULL DEFAULT 'explore',
  `label` VARCHAR(255) NOT NULL,
  `route_name` VARCHAR(255) NULL,
  `url` VARCHAR(255) NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `image` VARCHAR(255) NOT NULL,
  `eyebrow` VARCHAR(255) NULL,
  `title` VARCHAR(255) NOT NULL,
  `subtitle` TEXT NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` TEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` INT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT NOT NULL,
  KEY `jobs_queue_index` (`queue`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `nav_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(255) NOT NULL,
  `route_name` VARCHAR(255) NULL,
  `url` VARCHAR(255) NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `is_highlight` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `email` VARCHAR(255) NOT NULL,
  `subscribed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `newsletter_subscribers_email_unique` (`email`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `package_days` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` BIGINT UNSIGNED NOT NULL,
  `day_number` SMALLINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `location` VARCHAR(255) NULL,
  `morning` TEXT NULL,
  `afternoon` TEXT NULL,
  `evening` TEXT NULL,
  `narrative` TEXT NULL,
  `meals` TEXT NULL,
  `accommodation` VARCHAR(255) NULL,
  `accommodation_note` TEXT NULL,
  `activities` TEXT NULL,
  `travel_notes` TEXT NULL,
  `wildlife_highlights` TEXT NULL,
  `image` VARCHAR(255) NULL,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `package_days_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `package_destination` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` BIGINT UNSIGNED NOT NULL,
  `destination_id` BIGINT UNSIGNED NOT NULL,
  UNIQUE KEY `package_destination_package_id_destination_id_unique` (`package_id`, `destination_id`),
  PRIMARY KEY (`id`),
  CONSTRAINT `package_destination_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `package_destination_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `packages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `tagline` VARCHAR(255) NULL,
  `short_description` TEXT NULL,
  `long_description` LONGTEXT NULL,
  `duration_days` INT NOT NULL,
  `starting_price` DECIMAL(10,2) NULL,
  `currency` VARCHAR(255) NOT NULL DEFAULT 'USD',
  `price_note` VARCHAR(255) NULL,
  `experience_types` TEXT NULL,
  `traveler_types` TEXT NULL,
  `departure_style` VARCHAR(255) NOT NULL DEFAULT 'private',
  `highlights` TEXT NULL,
  `inclusions` TEXT NULL,
  `exclusions` TEXT NULL,
  `gallery` TEXT NULL,
  `hero_image` VARCHAR(255) NULL,
  `pricing_notes` TEXT NULL,
  `practical_info` TEXT NULL,
  `route_map_image` VARCHAR(255) NULL,
  `seo_title` VARCHAR(255) NULL,
  `seo_description` TEXT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_template` TINYINT(1) NOT NULL DEFAULT 0,
  `status` VARCHAR(255) NOT NULL DEFAULT 'draft',
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `view_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `packages_slug_unique` (`slug`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `quotes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enquiry_id` BIGINT UNSIGNED NOT NULL,
  `package_id` BIGINT UNSIGNED NULL,
  `reference` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `currency` VARCHAR(255) NOT NULL DEFAULT 'USD',
  `line_items` TEXT NULL,
  `notes` TEXT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'draft',
  `valid_until` DATE NULL,
  `sent_at` TIMESTAMP NULL,
  `accepted_at` TIMESTAMP NULL,
  `pdf_path` VARCHAR(255) NULL,
  `created_by` INT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotes_reference_unique` (`reference`),
  CONSTRAINT `quotes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quotes_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quotes_enquiry_id_foreign` FOREIGN KEY (`enquiry_id`) REFERENCES `enquiries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(255) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT UNSIGNED NOT NULL,
  KEY `sessions_last_activity_index` (`last_activity`),
  KEY `sessions_user_id_index` (`user_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NULL,
  `group` VARCHAR(255) NOT NULL DEFAULT 'general',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` BIGINT UNSIGNED NULL,
  `author_name` VARCHAR(255) NOT NULL,
  `author_location` VARCHAR(255) NULL,
  `content` LONGTEXT NOT NULL,
  `rating` INT NOT NULL DEFAULT 5,
  `source` VARCHAR(255) NOT NULL DEFAULT 'manual',
  `source_url` VARCHAR(255) NULL,
  `is_approved` TINYINT(1) NOT NULL DEFAULT 0,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `reviewed_at` DATE NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `testimonials_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `phone` VARCHAR(255) NULL,
  `role` VARCHAR(255) NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `package_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_package_id_unique` (`user_id`, `package_id`),
  CONSTRAINT `wishlists_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SYNC MISSING COLUMNS ON EXISTING TABLES
-- ============================================================

CALL sync_column('annual_events', 'package_id', '`package_id` BIGINT UNSIGNED NULL');
CALL sync_column('annual_events', 'title', '`title` VARCHAR(255) NOT NULL');
CALL sync_column('annual_events', 'slug', '`slug` VARCHAR(255) NOT NULL');
CALL sync_column('annual_events', 'excerpt', '`excerpt` TEXT NULL');
CALL sync_column('annual_events', 'description', '`description` LONGTEXT NULL');
CALL sync_column('annual_events', 'event_date', '`event_date` DATE NOT NULL');
CALL sync_column('annual_events', 'early_bird_deadline', '`early_bird_deadline` DATE NULL');
CALL sync_column('annual_events', 'early_bird_price', '`early_bird_price` DECIMAL(10,2) NULL');
CALL sync_column('annual_events', 'regular_price', '`regular_price` DECIMAL(10,2) NULL');
CALL sync_column('annual_events', 'currency', '`currency` VARCHAR(255) NOT NULL DEFAULT ''USD''');
CALL sync_column('annual_events', 'hero_image', '`hero_image` VARCHAR(255) NULL');
CALL sync_column('annual_events', 'is_published', '`is_published` TINYINT(1) NOT NULL DEFAULT 1');
CALL sync_column('annual_events', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('annual_events', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('blog_posts', 'author_id', '`author_id` BIGINT UNSIGNED NULL');
CALL sync_column('blog_posts', 'title', '`title` VARCHAR(255) NOT NULL');
CALL sync_column('blog_posts', 'slug', '`slug` VARCHAR(255) NOT NULL');
CALL sync_column('blog_posts', 'excerpt', '`excerpt` TEXT NULL');
CALL sync_column('blog_posts', 'content', '`content` LONGTEXT NULL');
CALL sync_column('blog_posts', 'featured_image', '`featured_image` VARCHAR(255) NULL');
CALL sync_column('blog_posts', 'status', '`status` VARCHAR(255) NOT NULL DEFAULT ''draft''');
CALL sync_column('blog_posts', 'published_at', '`published_at` TIMESTAMP NULL');
CALL sync_column('blog_posts', 'seo_title', '`seo_title` VARCHAR(255) NULL');
CALL sync_column('blog_posts', 'seo_description', '`seo_description` TEXT NULL');
CALL sync_column('blog_posts', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('blog_posts', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('cache', 'key', '`key` VARCHAR(255) NOT NULL');
CALL sync_column('cache', 'value', '`value` TEXT NOT NULL');
CALL sync_column('cache', 'expiration', '`expiration` INT UNSIGNED NOT NULL');
CALL sync_column('cache_locks', 'key', '`key` VARCHAR(255) NOT NULL');
CALL sync_column('cache_locks', 'owner', '`owner` VARCHAR(255) NOT NULL');
CALL sync_column('cache_locks', 'expiration', '`expiration` INT UNSIGNED NOT NULL');
CALL sync_column('destinations', 'name', '`name` VARCHAR(255) NOT NULL');
CALL sync_column('destinations', 'slug', '`slug` VARCHAR(255) NOT NULL');
CALL sync_column('destinations', 'country', '`country` VARCHAR(255) NOT NULL DEFAULT ''Kenya''');
CALL sync_column('destinations', 'region', '`region` VARCHAR(255) NULL');
CALL sync_column('destinations', 'excerpt', '`excerpt` TEXT NULL');
CALL sync_column('destinations', 'description', '`description` LONGTEXT NULL');
CALL sync_column('destinations', 'best_time', '`best_time` VARCHAR(255) NULL');
CALL sync_column('destinations', 'signature_wildlife', '`signature_wildlife` TEXT NULL');
CALL sync_column('destinations', 'hero_image', '`hero_image` VARCHAR(255) NULL');
CALL sync_column('destinations', 'gallery', '`gallery` TEXT NULL');
CALL sync_column('destinations', 'facts', '`facts` TEXT NULL');
CALL sync_column('destinations', 'latitude', '`latitude` DECIMAL(10,7) NULL');
CALL sync_column('destinations', 'longitude', '`longitude` DECIMAL(10,7) NULL');
CALL sync_column('destinations', 'seo_title', '`seo_title` VARCHAR(255) NULL');
CALL sync_column('destinations', 'seo_description', '`seo_description` TEXT NULL');
CALL sync_column('destinations', 'is_featured', '`is_featured` TINYINT(1) NOT NULL DEFAULT 0');
CALL sync_column('destinations', 'is_published', '`is_published` TINYINT(1) NOT NULL DEFAULT 1');
CALL sync_column('destinations', 'sort_order', '`sort_order` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('destinations', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('destinations', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('enquiries', 'user_id', '`user_id` BIGINT UNSIGNED NULL');
CALL sync_column('enquiries', 'package_id', '`package_id` BIGINT UNSIGNED NULL');
CALL sync_column('enquiries', 'name', '`name` VARCHAR(255) NOT NULL');
CALL sync_column('enquiries', 'email', '`email` VARCHAR(255) NOT NULL');
CALL sync_column('enquiries', 'phone', '`phone` VARCHAR(255) NULL');
CALL sync_column('enquiries', 'whatsapp', '`whatsapp` VARCHAR(255) NULL');
CALL sync_column('enquiries', 'preferred_destinations', '`preferred_destinations` VARCHAR(255) NULL');
CALL sync_column('enquiries', 'travel_dates', '`travel_dates` VARCHAR(255) NULL');
CALL sync_column('enquiries', 'group_size', '`group_size` SMALLINT UNSIGNED NULL');
CALL sync_column('enquiries', 'budget_range', '`budget_range` VARCHAR(255) NULL');
CALL sync_column('enquiries', 'special_interests', '`special_interests` TEXT NULL');
CALL sync_column('enquiries', 'message', '`message` TEXT NULL');
CALL sync_column('enquiries', 'status', '`status` VARCHAR(255) NOT NULL DEFAULT ''new''');
CALL sync_column('enquiries', 'assigned_to', '`assigned_to` INT NULL');
CALL sync_column('enquiries', 'admin_notes', '`admin_notes` TEXT NULL');
CALL sync_column('enquiries', 'source', '`source` VARCHAR(255) NOT NULL DEFAULT ''website''');
CALL sync_column('enquiries', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('enquiries', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('experience_package', 'experience_id', '`experience_id` BIGINT UNSIGNED NOT NULL');
CALL sync_column('experience_package', 'package_id', '`package_id` BIGINT UNSIGNED NOT NULL');
CALL sync_column('experiences', 'name', '`name` VARCHAR(255) NOT NULL');
CALL sync_column('experiences', 'slug', '`slug` VARCHAR(255) NOT NULL');
CALL sync_column('experiences', 'type', '`type` VARCHAR(255) NOT NULL DEFAULT ''wildlife''');
CALL sync_column('experiences', 'excerpt', '`excerpt` TEXT NULL');
CALL sync_column('experiences', 'description', '`description` LONGTEXT NULL');
CALL sync_column('experiences', 'image', '`image` VARCHAR(255) NULL');
CALL sync_column('experiences', 'duration_hours', '`duration_hours` INT UNSIGNED NULL');
CALL sync_column('experiences', 'starting_price', '`starting_price` DECIMAL(10,2) NULL');
CALL sync_column('experiences', 'currency', '`currency` VARCHAR(255) NOT NULL DEFAULT ''USD''');
CALL sync_column('experiences', 'is_published', '`is_published` TINYINT(1) NOT NULL DEFAULT 1');
CALL sync_column('experiences', 'sort_order', '`sort_order` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('experiences', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('experiences', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('failed_jobs', 'uuid', '`uuid` VARCHAR(255) NOT NULL');
CALL sync_column('failed_jobs', 'connection', '`connection` TEXT NOT NULL');
CALL sync_column('failed_jobs', 'queue', '`queue` TEXT NOT NULL');
CALL sync_column('failed_jobs', 'payload', '`payload` LONGTEXT NOT NULL');
CALL sync_column('failed_jobs', 'exception', '`exception` LONGTEXT NOT NULL');
CALL sync_column('failed_jobs', 'failed_at', '`failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
CALL sync_column('faqs', 'category', '`category` VARCHAR(255) NOT NULL DEFAULT ''general''');
CALL sync_column('faqs', 'question', '`question` VARCHAR(255) NOT NULL');
CALL sync_column('faqs', 'answer', '`answer` TEXT NOT NULL');
CALL sync_column('faqs', 'sort_order', '`sort_order` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('faqs', 'is_published', '`is_published` TINYINT(1) NOT NULL DEFAULT 1');
CALL sync_column('faqs', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('faqs', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('footer_links', 'group', '`group` VARCHAR(255) NOT NULL DEFAULT ''explore''');
CALL sync_column('footer_links', 'label', '`label` VARCHAR(255) NOT NULL');
CALL sync_column('footer_links', 'route_name', '`route_name` VARCHAR(255) NULL');
CALL sync_column('footer_links', 'url', '`url` VARCHAR(255) NULL');
CALL sync_column('footer_links', 'sort_order', '`sort_order` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('footer_links', 'is_active', '`is_active` TINYINT(1) NOT NULL DEFAULT 1');
CALL sync_column('footer_links', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('footer_links', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('hero_slides', 'image', '`image` VARCHAR(255) NOT NULL');
CALL sync_column('hero_slides', 'eyebrow', '`eyebrow` VARCHAR(255) NULL');
CALL sync_column('hero_slides', 'title', '`title` VARCHAR(255) NOT NULL');
CALL sync_column('hero_slides', 'subtitle', '`subtitle` TEXT NULL');
CALL sync_column('hero_slides', 'sort_order', '`sort_order` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('hero_slides', 'is_active', '`is_active` TINYINT(1) NOT NULL DEFAULT 1');
CALL sync_column('hero_slides', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('hero_slides', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('job_batches', 'id', '`id` VARCHAR(255) NOT NULL');
CALL sync_column('job_batches', 'name', '`name` VARCHAR(255) NOT NULL');
CALL sync_column('job_batches', 'total_jobs', '`total_jobs` INT NOT NULL');
CALL sync_column('job_batches', 'pending_jobs', '`pending_jobs` INT NOT NULL');
CALL sync_column('job_batches', 'failed_jobs', '`failed_jobs` INT NOT NULL');
CALL sync_column('job_batches', 'failed_job_ids', '`failed_job_ids` LONGTEXT NOT NULL');
CALL sync_column('job_batches', 'options', '`options` TEXT NULL');
CALL sync_column('job_batches', 'cancelled_at', '`cancelled_at` INT NULL');
CALL sync_column('job_batches', 'created_at', '`created_at` INT NOT NULL');
CALL sync_column('job_batches', 'finished_at', '`finished_at` INT NULL');
CALL sync_column('jobs', 'queue', '`queue` VARCHAR(255) NOT NULL');
CALL sync_column('jobs', 'payload', '`payload` LONGTEXT NOT NULL');
CALL sync_column('jobs', 'attempts', '`attempts` INT UNSIGNED NOT NULL');
CALL sync_column('jobs', 'reserved_at', '`reserved_at` INT UNSIGNED NULL');
CALL sync_column('jobs', 'available_at', '`available_at` INT UNSIGNED NOT NULL');
CALL sync_column('jobs', 'created_at', '`created_at` INT NOT NULL');
CALL sync_column('nav_items', 'label', '`label` VARCHAR(255) NOT NULL');
CALL sync_column('nav_items', 'route_name', '`route_name` VARCHAR(255) NULL');
CALL sync_column('nav_items', 'url', '`url` VARCHAR(255) NULL');
CALL sync_column('nav_items', 'sort_order', '`sort_order` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('nav_items', 'is_active', '`is_active` TINYINT(1) NOT NULL DEFAULT 1');
CALL sync_column('nav_items', 'is_highlight', '`is_highlight` TINYINT(1) NOT NULL DEFAULT 0');
CALL sync_column('nav_items', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('nav_items', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('newsletter_subscribers', 'name', '`name` VARCHAR(255) NULL');
CALL sync_column('newsletter_subscribers', 'email', '`email` VARCHAR(255) NOT NULL');
CALL sync_column('newsletter_subscribers', 'subscribed_at', '`subscribed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
CALL sync_column('newsletter_subscribers', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('newsletter_subscribers', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('package_days', 'package_id', '`package_id` BIGINT UNSIGNED NOT NULL');
CALL sync_column('package_days', 'day_number', '`day_number` SMALLINT UNSIGNED NOT NULL');
CALL sync_column('package_days', 'title', '`title` VARCHAR(255) NOT NULL');
CALL sync_column('package_days', 'location', '`location` VARCHAR(255) NULL');
CALL sync_column('package_days', 'morning', '`morning` TEXT NULL');
CALL sync_column('package_days', 'afternoon', '`afternoon` TEXT NULL');
CALL sync_column('package_days', 'evening', '`evening` TEXT NULL');
CALL sync_column('package_days', 'narrative', '`narrative` TEXT NULL');
CALL sync_column('package_days', 'meals', '`meals` TEXT NULL');
CALL sync_column('package_days', 'accommodation', '`accommodation` VARCHAR(255) NULL');
CALL sync_column('package_days', 'accommodation_note', '`accommodation_note` TEXT NULL');
CALL sync_column('package_days', 'activities', '`activities` TEXT NULL');
CALL sync_column('package_days', 'travel_notes', '`travel_notes` TEXT NULL');
CALL sync_column('package_days', 'wildlife_highlights', '`wildlife_highlights` TEXT NULL');
CALL sync_column('package_days', 'image', '`image` VARCHAR(255) NULL');
CALL sync_column('package_days', 'sort_order', '`sort_order` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('package_days', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('package_days', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('package_destination', 'package_id', '`package_id` BIGINT UNSIGNED NOT NULL');
CALL sync_column('package_destination', 'destination_id', '`destination_id` BIGINT UNSIGNED NOT NULL');
CALL sync_column('packages', 'title', '`title` VARCHAR(255) NOT NULL');
CALL sync_column('packages', 'slug', '`slug` VARCHAR(255) NOT NULL');
CALL sync_column('packages', 'tagline', '`tagline` VARCHAR(255) NULL');
CALL sync_column('packages', 'short_description', '`short_description` TEXT NULL');
CALL sync_column('packages', 'long_description', '`long_description` LONGTEXT NULL');
CALL sync_column('packages', 'duration_days', '`duration_days` INT NOT NULL');
CALL sync_column('packages', 'starting_price', '`starting_price` DECIMAL(10,2) NULL');
CALL sync_column('packages', 'currency', '`currency` VARCHAR(255) NOT NULL DEFAULT ''USD''');
CALL sync_column('packages', 'price_note', '`price_note` VARCHAR(255) NULL');
CALL sync_column('packages', 'experience_types', '`experience_types` TEXT NULL');
CALL sync_column('packages', 'traveler_types', '`traveler_types` TEXT NULL');
CALL sync_column('packages', 'departure_style', '`departure_style` VARCHAR(255) NOT NULL DEFAULT ''private''');
CALL sync_column('packages', 'highlights', '`highlights` TEXT NULL');
CALL sync_column('packages', 'inclusions', '`inclusions` TEXT NULL');
CALL sync_column('packages', 'exclusions', '`exclusions` TEXT NULL');
CALL sync_column('packages', 'gallery', '`gallery` TEXT NULL');
CALL sync_column('packages', 'hero_image', '`hero_image` VARCHAR(255) NULL');
CALL sync_column('packages', 'pricing_notes', '`pricing_notes` TEXT NULL');
CALL sync_column('packages', 'practical_info', '`practical_info` TEXT NULL');
CALL sync_column('packages', 'route_map_image', '`route_map_image` VARCHAR(255) NULL');
CALL sync_column('packages', 'seo_title', '`seo_title` VARCHAR(255) NULL');
CALL sync_column('packages', 'seo_description', '`seo_description` TEXT NULL');
CALL sync_column('packages', 'is_featured', '`is_featured` TINYINT(1) NOT NULL DEFAULT 0');
CALL sync_column('packages', 'is_template', '`is_template` TINYINT(1) NOT NULL DEFAULT 0');
CALL sync_column('packages', 'status', '`status` VARCHAR(255) NOT NULL DEFAULT ''draft''');
CALL sync_column('packages', 'sort_order', '`sort_order` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('packages', 'view_count', '`view_count` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('packages', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('packages', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('password_reset_tokens', 'email', '`email` VARCHAR(255) NOT NULL');
CALL sync_column('password_reset_tokens', 'token', '`token` VARCHAR(255) NOT NULL');
CALL sync_column('password_reset_tokens', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('quotes', 'enquiry_id', '`enquiry_id` BIGINT UNSIGNED NOT NULL');
CALL sync_column('quotes', 'package_id', '`package_id` BIGINT UNSIGNED NULL');
CALL sync_column('quotes', 'reference', '`reference` VARCHAR(255) NOT NULL');
CALL sync_column('quotes', 'title', '`title` VARCHAR(255) NOT NULL');
CALL sync_column('quotes', 'total_amount', '`total_amount` DECIMAL(12,2) NOT NULL');
CALL sync_column('quotes', 'currency', '`currency` VARCHAR(255) NOT NULL DEFAULT ''USD''');
CALL sync_column('quotes', 'line_items', '`line_items` TEXT NULL');
CALL sync_column('quotes', 'notes', '`notes` TEXT NULL');
CALL sync_column('quotes', 'status', '`status` VARCHAR(255) NOT NULL DEFAULT ''draft''');
CALL sync_column('quotes', 'valid_until', '`valid_until` DATE NULL');
CALL sync_column('quotes', 'sent_at', '`sent_at` TIMESTAMP NULL');
CALL sync_column('quotes', 'accepted_at', '`accepted_at` TIMESTAMP NULL');
CALL sync_column('quotes', 'pdf_path', '`pdf_path` VARCHAR(255) NULL');
CALL sync_column('quotes', 'created_by', '`created_by` INT NULL');
CALL sync_column('quotes', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('quotes', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('sessions', 'id', '`id` VARCHAR(255) NOT NULL');
CALL sync_column('sessions', 'user_id', '`user_id` BIGINT UNSIGNED NULL');
CALL sync_column('sessions', 'ip_address', '`ip_address` VARCHAR(255) NULL');
CALL sync_column('sessions', 'user_agent', '`user_agent` TEXT NULL');
CALL sync_column('sessions', 'payload', '`payload` LONGTEXT NOT NULL');
CALL sync_column('sessions', 'last_activity', '`last_activity` INT UNSIGNED NOT NULL');
CALL sync_column('settings', 'key', '`key` VARCHAR(255) NOT NULL');
CALL sync_column('settings', 'value', '`value` TEXT NULL');
CALL sync_column('settings', 'group', '`group` VARCHAR(255) NOT NULL DEFAULT ''general''');
CALL sync_column('settings', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('settings', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('testimonials', 'package_id', '`package_id` BIGINT UNSIGNED NULL');
CALL sync_column('testimonials', 'author_name', '`author_name` VARCHAR(255) NOT NULL');
CALL sync_column('testimonials', 'author_location', '`author_location` VARCHAR(255) NULL');
CALL sync_column('testimonials', 'content', '`content` LONGTEXT NOT NULL');
CALL sync_column('testimonials', 'rating', '`rating` INT NOT NULL DEFAULT 5');
CALL sync_column('testimonials', 'source', '`source` VARCHAR(255) NOT NULL DEFAULT ''manual''');
CALL sync_column('testimonials', 'source_url', '`source_url` VARCHAR(255) NULL');
CALL sync_column('testimonials', 'is_approved', '`is_approved` TINYINT(1) NOT NULL DEFAULT 0');
CALL sync_column('testimonials', 'is_featured', '`is_featured` TINYINT(1) NOT NULL DEFAULT 0');
CALL sync_column('testimonials', 'sort_order', '`sort_order` INT UNSIGNED NOT NULL DEFAULT 0');
CALL sync_column('testimonials', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('testimonials', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('testimonials', 'reviewed_at', '`reviewed_at` DATE NULL');
CALL sync_column('users', 'name', '`name` VARCHAR(255) NOT NULL');
CALL sync_column('users', 'email', '`email` VARCHAR(255) NOT NULL');
CALL sync_column('users', 'email_verified_at', '`email_verified_at` TIMESTAMP NULL');
CALL sync_column('users', 'password', '`password` VARCHAR(255) NOT NULL');
CALL sync_column('users', 'remember_token', '`remember_token` VARCHAR(255) NULL');
CALL sync_column('users', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('users', 'updated_at', '`updated_at` TIMESTAMP NULL');
CALL sync_column('users', 'phone', '`phone` VARCHAR(255) NULL');
CALL sync_column('users', 'role', '`role` VARCHAR(255) NOT NULL DEFAULT ''customer''');
CALL sync_column('wishlists', 'user_id', '`user_id` BIGINT UNSIGNED NOT NULL');
CALL sync_column('wishlists', 'package_id', '`package_id` BIGINT UNSIGNED NOT NULL');
CALL sync_column('wishlists', 'created_at', '`created_at` TIMESTAMP NULL');
CALL sync_column('wishlists', 'updated_at', '`updated_at` TIMESTAMP NULL');

-- ============================================================
-- SYNC MISSING INDEXES
-- ============================================================

CALL sync_index('annual_events', 'annual_events_slug_unique', 'UNIQUE INDEX `annual_events_slug_unique` (`slug`)');
CALL sync_index('blog_posts', 'blog_posts_slug_unique', 'UNIQUE INDEX `blog_posts_slug_unique` (`slug`)');
CALL sync_index('cache', 'cache_expiration_index', 'INDEX `cache_expiration_index` (`expiration`)');
CALL sync_index('cache_locks', 'cache_locks_expiration_index', 'INDEX `cache_locks_expiration_index` (`expiration`)');
CALL sync_index('destinations', 'destinations_slug_unique', 'UNIQUE INDEX `destinations_slug_unique` (`slug`)');
CALL sync_index('experience_package', 'experience_package_experience_id_package_id_unique', 'UNIQUE INDEX `experience_package_experience_id_package_id_unique` (`experience_id`, `package_id`)');
CALL sync_index('experiences', 'experiences_slug_unique', 'UNIQUE INDEX `experiences_slug_unique` (`slug`)');
CALL sync_index('failed_jobs', 'failed_jobs_uuid_unique', 'UNIQUE INDEX `failed_jobs_uuid_unique` (`uuid`)');
CALL sync_index('jobs', 'jobs_queue_index', 'INDEX `jobs_queue_index` (`queue`)');
CALL sync_index('newsletter_subscribers', 'newsletter_subscribers_email_unique', 'UNIQUE INDEX `newsletter_subscribers_email_unique` (`email`)');
CALL sync_index('package_destination', 'package_destination_package_id_destination_id_unique', 'UNIQUE INDEX `package_destination_package_id_destination_id_unique` (`package_id`, `destination_id`)');
CALL sync_index('packages', 'packages_slug_unique', 'UNIQUE INDEX `packages_slug_unique` (`slug`)');
CALL sync_index('quotes', 'quotes_reference_unique', 'UNIQUE INDEX `quotes_reference_unique` (`reference`)');
CALL sync_index('sessions', 'sessions_last_activity_index', 'INDEX `sessions_last_activity_index` (`last_activity`)');
CALL sync_index('sessions', 'sessions_user_id_index', 'INDEX `sessions_user_id_index` (`user_id`)');
CALL sync_index('settings', 'settings_key_unique', 'UNIQUE INDEX `settings_key_unique` (`key`)');
CALL sync_index('users', 'users_email_unique', 'UNIQUE INDEX `users_email_unique` (`email`)');
CALL sync_index('wishlists', 'wishlists_user_id_package_id_unique', 'UNIQUE INDEX `wishlists_user_id_package_id_unique` (`user_id`, `package_id`)');

-- ============================================================
-- SYNC MISSING FOREIGN KEYS
-- ============================================================

CALL sync_foreign_key('annual_events', 'annual_events_package_id_foreign', 'package_id', 'packages', 'id', 'SET NULL');
CALL sync_foreign_key('blog_posts', 'blog_posts_author_id_foreign', 'author_id', 'users', 'id', 'SET NULL');
CALL sync_foreign_key('enquiries', 'enquiries_assigned_to_foreign', 'assigned_to', 'users', 'id', 'SET NULL');
CALL sync_foreign_key('enquiries', 'enquiries_package_id_foreign', 'package_id', 'packages', 'id', 'SET NULL');
CALL sync_foreign_key('enquiries', 'enquiries_user_id_foreign', 'user_id', 'users', 'id', 'SET NULL');
CALL sync_foreign_key('experience_package', 'experience_package_package_id_foreign', 'package_id', 'packages', 'id', 'CASCADE');
CALL sync_foreign_key('experience_package', 'experience_package_experience_id_foreign', 'experience_id', 'experiences', 'id', 'CASCADE');
CALL sync_foreign_key('package_days', 'package_days_package_id_foreign', 'package_id', 'packages', 'id', 'CASCADE');
CALL sync_foreign_key('package_destination', 'package_destination_destination_id_foreign', 'destination_id', 'destinations', 'id', 'CASCADE');
CALL sync_foreign_key('package_destination', 'package_destination_package_id_foreign', 'package_id', 'packages', 'id', 'CASCADE');
CALL sync_foreign_key('quotes', 'quotes_created_by_foreign', 'created_by', 'users', 'id', 'SET NULL');
CALL sync_foreign_key('quotes', 'quotes_package_id_foreign', 'package_id', 'packages', 'id', 'SET NULL');
CALL sync_foreign_key('quotes', 'quotes_enquiry_id_foreign', 'enquiry_id', 'enquiries', 'id', 'CASCADE');
CALL sync_foreign_key('testimonials', 'testimonials_package_id_foreign', 'package_id', 'packages', 'id', 'SET NULL');
CALL sync_foreign_key('wishlists', 'wishlists_package_id_foreign', 'package_id', 'packages', 'id', 'CASCADE');
CALL sync_foreign_key('wishlists', 'wishlists_user_id_foreign', 'user_id', 'users', 'id', 'CASCADE');

DROP PROCEDURE IF EXISTS sync_column;
DROP PROCEDURE IF EXISTS sync_index;
DROP PROCEDURE IF EXISTS sync_foreign_key;

SET FOREIGN_KEY_CHECKS = 1;
