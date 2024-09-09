DROP DATABASE IF EXISTS my_tasks;

CREATE DATABASE my_tasks;

USE my_tasks;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(60) NOT NULL,
    description TEXT NULL,
    status ENUM('pending', 'completed') NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Seed
INSERT INTO users (username, email, password, created_at, updated_at) VALUES
        ('Kavin', 'kavin@kavin.com', 'kavin123', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
        ('Test', 'testl@test.com', 'test', '2024-08-20 12:30:0', '2024-08-22 14:00:00');
