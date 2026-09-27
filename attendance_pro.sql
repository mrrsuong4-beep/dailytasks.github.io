CREATE DATABASE IF NOT EXISTS attendance_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE attendance_pro;

CREATE TABLE users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 username VARCHAR(80) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','manager','staff') NOT NULL DEFAULT 'admin',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE employees (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 employee_code VARCHAR(50) NOT NULL UNIQUE,
 name VARCHAR(150) NOT NULL,
 gender ENUM('male','female','other') DEFAULT 'other',
 department VARCHAR(120) DEFAULT NULL,
 position VARCHAR(120) DEFAULT NULL,
 phone VARCHAR(40) DEFAULT NULL,
 photo VARCHAR(255) DEFAULT NULL,
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_employee_name(name),
 INDEX idx_department(department)
) ENGINE=InnoDB;

CREATE TABLE attendance (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 employee_id INT UNSIGNED NOT NULL,
 attendance_date DATE NOT NULL,
 status ENUM('present','late','absent','leave') NOT NULL DEFAULT 'present',
 check_in TIME DEFAULT NULL,
 check_out TIME DEFAULT NULL,
 note VARCHAR(500) DEFAULT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE KEY uq_employee_date(employee_id,attendance_date),
 CONSTRAINT fk_att_employee FOREIGN KEY(employee_id) REFERENCES employees(id) ON DELETE CASCADE,
 INDEX idx_att_date(attendance_date)
) ENGINE=InnoDB;

-- Demo login: admin / admin123
INSERT INTO users(name,username,password,role) VALUES
('Administrator','admin','$2y$12$R56SUjAlWhlTSZCG6pMD5ekj6Ze82sy1dMROpXEGOmXu3Lnnos3Ge','admin');

-- Demo employees
INSERT INTO employees(employee_code,name,gender,department,position,phone,status) VALUES
('EMP-001','Sok Dara','male','Administration','Manager','012345678','active'),
('EMP-002','Srey Leak','female','Finance','Accountant','098765432','active'),
('EMP-003','Chan Vuthy','male','IT','Developer','097111222','active'),
('EMP-004','Kanha','female','HR','HR Officer','096333444','active');
