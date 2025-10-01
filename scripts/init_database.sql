-- WebEstia Database Schema
-- This script initializes the database with the required tables

-- Drop existing tables if they exist (for clean reinstall)
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admin;

-- Create admin table
CREATE TABLE admin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    identifiant TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_by INTEGER,
    FOREIGN KEY (created_by) REFERENCES admin(id)
);

-- Insert default admin account
-- Username: Admin
-- Password: Est1a#System@25!
-- Note: The password will be hashed by the PHP application
INSERT INTO admin (username, password) VALUES ('Admin', '$2y$10$placeholder');
