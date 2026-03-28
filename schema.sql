-- ============================================================
-- Portfolio Library - Database Schema
-- Run this in phpMyAdmin or MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- Users table (both students and teachers)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(60) NOT NULL,
    lastName VARCHAR(60) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    userName VARCHAR(60) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,         -- bcrypt hashed
    role ENUM('student', 'teacher') NOT NULL DEFAULT 'student',
    avatar VARCHAR(255) DEFAULT NULL,       -- profile photo filename
    bio TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Portfolio profiles (students fill this out)
CREATE TABLE IF NOT EXISTS portfolios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    headline VARCHAR(150) DEFAULT NULL,     -- e.g. "Full Stack Developer"
    bio TEXT DEFAULT NULL,
    github VARCHAR(255) DEFAULT NULL,
    linkedin VARCHAR(255) DEFAULT NULL,
    website VARCHAR(255) DEFAULT NULL,
    resume_file VARCHAR(255) DEFAULT NULL,  -- uploaded PDF filename
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Skills
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_name VARCHAR(80) NOT NULL,
    level ENUM('Beginner', 'Intermediate', 'Advanced', 'Expert') DEFAULT 'Intermediate',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Projects
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    tech_stack VARCHAR(255) DEFAULT NULL,   -- e.g. "PHP, MySQL, JavaScript"
    project_url VARCHAR(255) DEFAULT NULL,
    file_name VARCHAR(255) DEFAULT NULL,    -- uploaded file
    thumbnail VARCHAR(255) DEFAULT NULL,    -- optional image
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Teacher comments on student projects
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    teacher_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- Sample teacher account (password: Teacher@123)
-- ============================================================
INSERT INTO users (firstName, lastName, email, userName, password, role)
VALUES (
    'Admin', 'Teacher',
    'teacher@school.com',
    'teacher',
    '$2y$10$HIb3o/APxYULq.65519HKuh6qjkhKQ4F.P/vGcLCLAuaibfGWIsPK',
    'teacher'
);
-- NOTE: Generate the real hash in PHP:
-- echo password_hash('Teacher@123', PASSWORD_BCRYPT);
-- Then UPDATE users SET password = '<hash>' WHERE userName = 'teacher';
