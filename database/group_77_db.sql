CREATE DATABASE IF NOT EXISTS group_77_db;

USE group_77_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(50) NOT NULL PRIMARY KEY,
    password VARCHAR(255) NOT NULL,        -- hashed password
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20),
    email_address VARCHAR(100),
    address TEXT,
    gender VARCHAR(10),
    age INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Donations table
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    amount DECIMAL(10,2),
    attachment VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default accounts
INSERT INTO users (name, email, username, password, role) 
VALUES 
('John Doe', 'admin@email.com', 'admin', 'admin', 'admin'),
('Jane Doe', 'jane.doe@email.com', 'uoc', 'uoc', 'user');

-- Communities table
CREATE TABLE IF NOT EXISTS communities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_username VARCHAR(50) NOT NULL,
    name VARCHAR(100),
    vision TEXT,
    mission TEXT,
    FOREIGN KEY (admin_username) REFERENCES users(username)
);

-- Community principles table
CREATE TABLE IF NOT EXISTS community_principles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    community_id INT NOT NULL,
    principle VARCHAR(255),
    description TEXT,
    FOREIGN KEY (community_id) REFERENCES communities(id) ON DELETE CASCADE
);

-- Contacts table
CREATE TABLE IF NOT EXISTS contacts (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    contact_number VARCHAR(50) NOT NULL,
    email_address VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content TEXT,
    attachment VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_posts_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    community_id INT NOT NULL,
    community_name VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    type VARCHAR(100) DEFAULT NULL,
    description TEXT NOT NULL,
    content TEXT NOT NULL,
    attachment VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (community_id) REFERENCES communities(id)
);

CREATE TABLE IF NOT EXISTS event_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    contact_number VARCHAR(50) NOT NULL,
    email_address VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE event_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT NULL,
    username VARCHAR(100) NOT NULL,
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE post_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NULL,
    username VARCHAR(100) NOT NULL,
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id)
);




