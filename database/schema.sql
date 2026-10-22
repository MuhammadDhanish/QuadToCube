-- =========================================================
-- Campus Recruitment Management System (CRMS)
-- Database Schema (MySQL / MariaDB / XAMPP Compatible)
-- =========================================================

CREATE DATABASE IF NOT EXISTS `crms_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `crms_db`;

-- Drop existing tables in reverse dependency order
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `selections`;
DROP TABLE IF EXISTS `applications`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `companies`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `admins`;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------
-- 1. Table: admins
-- Placement Coordinators and System Administrators
-- ---------------------------------------------------------
CREATE TABLE `admins` (
  `admin_id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('superadmin', 'coordinator') DEFAULT 'coordinator',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 2. Table: students
-- Registered students eligible for campus placements
-- ---------------------------------------------------------
CREATE TABLE `students` (
  `student_id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `course` VARCHAR(100) NOT NULL,          -- e.g. 'B.Tech CSE', 'B.Tech IT', 'MCA', 'B.Tech ECE'
  `semester` TINYINT UNSIGNED NOT NULL,     -- 1 to 8
  `cgpa` DECIMAL(3,2) NOT NULL DEFAULT 0.00,-- e.g. 8.45
  `skills` TEXT DEFAULT NULL,               -- Comma-separated or tag list e.g. 'Java, Python, SQL, React'
  `resume_link` VARCHAR(255) DEFAULT NULL,  -- External link or file reference
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_course` (`course`),
  INDEX `idx_cgpa` (`cgpa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 3. Table: companies
-- Partner recruiting companies / employers
-- ---------------------------------------------------------
CREATE TABLE `companies` (
  `company_id` INT AUTO_INCREMENT PRIMARY KEY,
  `company_name` VARCHAR(150) NOT NULL,
  `contact_person` VARCHAR(100) DEFAULT NULL,
  `contact_email` VARCHAR(150) DEFAULT NULL,
  `contact_phone` VARCHAR(20) DEFAULT NULL,
  `website` VARCHAR(255) DEFAULT NULL,
  `industry` VARCHAR(100) DEFAULT 'Information Technology',
  `location` VARCHAR(150) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 4. Table: jobs
-- Recruitment drives / opportunities posted by coordinator
-- ---------------------------------------------------------
CREATE TABLE `jobs` (
  `job_id` INT AUTO_INCREMENT PRIMARY KEY,
  `company_id` INT NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `job_type` ENUM('Full-time', 'Internship', 'FTE + Internship') DEFAULT 'Full-time',
  `salary_package` VARCHAR(100) DEFAULT 'Best in Industry', -- e.g. '12 LPA', '25,000 / month'
  `location` VARCHAR(150) DEFAULT 'On-site',
  -- Eligibility criteria
  `min_cgpa` DECIMAL(3,2) NOT NULL DEFAULT 6.00,
  `allowed_courses` TEXT NOT NULL,             -- Comma-delimited: e.g. 'B.Tech CSE,B.Tech IT,MCA' or 'All'
  `allowed_semesters` VARCHAR(100) NOT NULL,   -- e.g. '7,8' or 'All'
  `required_skills` TEXT DEFAULT NULL,         -- e.g. 'Python, SQL, DSA'
  `deadline` DATETIME NOT NULL,
  `status` ENUM('open', 'closed') DEFAULT 'open',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`company_id`) REFERENCES `companies`(`company_id`) ON DELETE CASCADE,
  INDEX `idx_deadline` (`deadline`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 5. Table: applications
-- Student job applications with workflow statuses
-- ---------------------------------------------------------
CREATE TABLE `applications` (
  `application_id` INT AUTO_INCREMENT PRIMARY KEY,
  `job_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  `applied_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('applied', 'under_review', 'shortlisted', 'selected', 'rejected') DEFAULT 'applied',
  `notes` TEXT DEFAULT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_job_student` (`job_id`, `student_id`),
  FOREIGN KEY (`job_id`) REFERENCES `jobs`(`job_id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `students`(`student_id`) ON DELETE CASCADE,
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 6. Table: selections
-- Final placement outcome & offer details
-- ---------------------------------------------------------
CREATE TABLE `selections` (
  `selection_id` INT AUTO_INCREMENT PRIMARY KEY,
  `application_id` INT NOT NULL UNIQUE,
  `result` ENUM('selected', 'rejected') NOT NULL DEFAULT 'selected',
  `offer_letter_ref` VARCHAR(100) DEFAULT NULL,
  `remarks` TEXT DEFAULT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`application_id`) REFERENCES `applications`(`application_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
