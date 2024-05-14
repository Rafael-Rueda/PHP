<?php

$username = $_POST['username'];
$password = $_POST['password'];
$password2 = $_POST['password2'];

if ($password == $password2) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $db_dsn = 'mysql:host=localhost;dbname=pesq_db';
    $db_username = 'root';
    $db_password = 'password';

    $conn = new PDO($db_dsn, $db_username, $db_password);

    // Insert into database
    $sql = "INSERT INTO users (username, password) VALUES (:u, :p)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':u', $username);
    $stmt->bindParam(':p', $hashedPassword);
    $stmt->execute();
}
