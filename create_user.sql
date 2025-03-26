-- Pastikan database sudah dibuat
CREATE DATABASE IF NOT EXISTS soap_api;
USE soap_api;

-- Buat tabel users jika belum ada
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    name VARCHAR(100),
    token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert user pertama
INSERT INTO users (username, password, name) 
VALUES ('admin', 'admin123', 'Administrator'); 