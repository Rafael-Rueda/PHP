<?php

include_once ('../utils/base_url.php');

require BASE_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$username = $_POST['username'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$email = $_POST['email'];

if ($password == $password2) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $db_dsn = $_ENV['DB_DSN'];
    $db_username = $_ENV['DB_USERNAME'];
    $db_password = $_ENV['DB_PASSWORD'];

    $conn = new PDO($db_dsn, $db_username, $db_password);

    // Insert into database
    $sql = "INSERT INTO users (username, password, email) VALUES (:u, :p, :e)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':u', $username);
    $stmt->bindParam(':p', $hashedPassword);
    $stmt->bindParam(':e', $email);
    $stmt->execute();

    header('Location: ' . BASE_URL . 'templates/pages/login.php');
} else {
    header('Location: ' . BASE_URL . 'templates/pages/register.php');
}
