<?php

include_once ('../utils/base_url.php');
include_once (BASE_PATH . 'backend/flash_messages.php');

session_start();

require BASE_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();
function generateToken($length = 32)
{
    // Gera uma string aleatória
    $randomString = openssl_random_pseudo_bytes($length);

    // Converte a string aleatória para hexadecimal
    $token = bin2hex($randomString);

    return $token;
}

$db_dsn = $_ENV['DB_DSN'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD'];

$conn = new PDO($db_dsn, $db_username, $db_password);

$user_username = $_POST['username'];
$user_password = $_POST['password'];

$stmt = $conn->prepare('SELECT * from `users` WHERE username = :u');
$stmt->bindParam(':u', $user_username);
$stmt->execute();


if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $hashed_password = $user['password'];

    if (password_verify($user_password, $hashed_password)) {
        $access_token = generateToken();

        $update_stmt = $conn->prepare('UPDATE users SET access_token = :token WHERE id = :id');
        $update_stmt->bindParam(':token', $access_token);
        $update_stmt->bindParam(':id', $user['id']);
        $update_stmt->execute();

        $_SESSION['access_token'] = $access_token;

        // Flash message
        set_flash_message('Login efetuado com sucesso.', 'success');

        header('Location: ' . BASE_URL . 'index.php');
    } else {
        // Flash message
        set_flash_message('Senha incorreta.', 'error');

        header('Location: ' . BASE_URL . 'templates/pages/login.php');
    }
} else {
    // Flash message
    set_flash_message('Usuário não encontrado.', 'error');

    header('Location: ' . BASE_URL . 'templates/pages/login.php');
}

$conn = null;