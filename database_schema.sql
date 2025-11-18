-- ========================================
-- RiverVibe Database Schema
-- ========================================
-- Execute this file in phpMyAdmin or MySQL CLI
-- ========================================

 Create database
CREATE DATABASE IF NOT EXISTS rivervibe_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rivervibe_db;

-- ========================================
-- 1. USERS TABLE
-- ========================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
-- Password: admin123 (hashed with PASSWORD_DEFAULT)
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@rivervibe.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- ========================================
-- 2. RIVER_REPORTS TABLE (EXACTLY AS REQUIRED)
-- ========================================
CREATE TABLE IF NOT EXISTS river_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    river_name VARCHAR(150) NOT NULL,
    location VARCHAR(200),
    image VARCHAR(255),
    status ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
    pollution_type VARCHAR(150),
    date_submitted DATE,
    reported_by VARCHAR(150),
    description TEXT,
    location_coordinates VARCHAR(200),
    authority_response TEXT,
    qr_code VARCHAR(255),
    user_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_river_name (river_name),
    INDEX idx_user_id (user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO river_reports (river_name, location, status, pollution_type, date_submitted, reported_by, description, location_coordinates) VALUES
('Ganga', 'Varanasi Ghat Area, Uttar Pradesh', 'Pending', 'Chemical', '2025-11-10', 'Citizens Group', 'Multiple instances of chemical discharge observed near the industrial area. Water shows visible discoloration and strong chemical odor. Local wildlife affected. Immediate attention required.', '25.3176° N, 82.9739° E'),
('Yamuna', 'Okhla Barrage, Delhi', 'In Progress', 'Sewage', '2025-11-11', 'Environmental NGO', 'Heavy sewage discharge continues to pollute the river. Foam formation and black water observed. Urgent intervention needed to address untreated sewage disposal.', '28.5244° N, 77.3109° E'),
('Mithi', 'Kurla-BKC Area, Mumbai', 'Resolved', 'Plastic', '2025-11-08', 'Local Resident', 'Significant accumulation of plastic waste and debris observed. Local clean-up initiatives ongoing but requiring more support.', '19.0728° N, 72.8826° E'),
('Sabarmati', 'Vasna Barrage Area, Ahmedabad', 'Pending', 'Industrial', '2025-11-09', 'Fishermen Association', 'Industrial discharge causing water quality deterioration. Fish mortality reported in several areas. Immediate action required.', '23.0225° N, 72.5714° E'),
('Brahmaputra', 'Guwahati Port Area, Assam', 'Pending', 'Other', '2025-11-12', 'Port Workers', 'Oil slicks spotted near port area. Urban waste accumulation increasing. Local authorities notified for cleanup.', '26.1445° N, 91.7362° E'),
('Cauvery', 'Srirangapatna Region, Karnataka', 'In Progress', 'Sewage', '2025-11-09', 'Local Community', 'Excessive agricultural runoff causing algal blooms. Sewage discharge from urban areas affecting water quality. Comprehensive action plan needed.', '12.4214° N, 76.6947° E');

-- ========================================
-- 3. FEEDBACK TABLE (MATCHES YOUR FORM EXACTLY)
-- ========================================
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    gender ENUM('Male', 'Female', 'Other'),
    river_name VARCHAR(150),
    location VARCHAR(150),
    pollution_type VARCHAR(100),
    issue_description TEXT,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_river_name (river_name),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample feedback
INSERT INTO feedback (name, email, gender, river_name, location, pollution_type, issue_description) VALUES
('Rahul Sharma', 'rahul@example.com', 'Male', 'Ganga', 'Varanasi', 'Industrial', 'Noticed chemical foam near the ghats. Very concerning for pilgrims and local fishermen.'),
('Priya Patel', 'priya@example.com', 'Female', 'Yamuna', 'Delhi', 'Sewage', 'The river has turned completely black near our area. Unbearable smell during summers.'),
('Alex Johnson', 'alex@example.com', 'Other', 'Mithi', 'Mumbai', 'Plastic', 'Plastic bottles and bags everywhere. We need more awareness campaigns.');

-- ========================================
-- 4. SUCCESS STORIES TABLE (OPTIONAL)
-- ========================================
CREATE TABLE IF NOT EXISTS success_stories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    river_name VARCHAR(150),
    location VARCHAR(200),
    description TEXT,
    image VARCHAR(255),
    date_achieved DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_river_name (river_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- VERIFY TABLES
-- ========================================
SHOW TABLES;
SELECT 'Database schema created successfully!' AS status;
