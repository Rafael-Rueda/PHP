-- Cria o banco de dados pesq_db
CREATE DATABASE IF NOT EXISTS pesq_db;

-- Usa o banco de dados pesq_db
USE pesq_db;

-- Cria a tabela users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    access_token VARCHAR(255) DEFAULT NULL,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);