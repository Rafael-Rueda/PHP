-- Create the database pesq_db
CREATE DATABASE IF NOT EXISTS pesq_db;

-- Use the database pesq_db
USE pesq_db;

-- Creates the table users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    access_token VARCHAR(255) DEFAULT NULL,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Forms, Questions and Answers

CREATE TABLE forms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    owner INT
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_forms_id INT,
    content TEXT,
    type VARCHAR(255),
    required BOOLEAN
);

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fk_questions_id INT,
    content TEXT
);

ALTER TABLE forms ADD CONSTRAINT FK_forms_owner
    FOREIGN KEY (owner)
    REFERENCES users (id)
    ON DELETE CASCADE;

ALTER TABLE questions ADD CONSTRAINT FK_questions_2
    FOREIGN KEY (fk_forms_id)
    REFERENCES forms (id)
    ON DELETE CASCADE;
 
ALTER TABLE answers ADD CONSTRAINT FK_answers_2
    FOREIGN KEY (fk_questions_id)
    REFERENCES questions (id)
    ON DELETE CASCADE;
