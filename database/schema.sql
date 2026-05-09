-- BingoGLN Database Schema

CREATE DATABASE IF NOT EXISTS bingo_gln;
USE bingo_gln;

CREATE TABLE IF NOT EXISTS cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    numbers JSON NOT NULL,
    bg_path VARCHAR(255) DEFAULT NULL,
    title VARCHAR(100) DEFAULT 'Bingo Card',
    pages INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NULL
);

-- Add indexes for faster lookups
CREATE INDEX idx_code ON cards(code);
CREATE INDEX idx_created_at ON cards(created_at);
