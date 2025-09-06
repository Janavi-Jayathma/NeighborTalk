CREATE DATABASE IF NOT EXISTS group_77_db;

USE group_77_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL
);

-- Default accounts
INSERT INTO users (name, email, username, password, role) 
VALUES 
('John Doe', 'admin@email.com', 'admin', 'admin', 'admin'),
('Jane Doe', 'jane.doe@email.com', 'uoc', 'uoc', 'user');
