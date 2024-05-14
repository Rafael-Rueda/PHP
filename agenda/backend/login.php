<?php 
function generateToken($length = 32)
{
    // Gera uma string aleatória
    $randomString = openssl_random_pseudo_bytes($length);
    
    // Converte a string aleatória para hexadecimal
    $token = bin2hex($randomString);
    
    return $token;
}

$db_dsn = 'mysql:host=localhost;dbname=pesq_db';
$db_username = 'root';
$db_password = 'password';

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

        $update_stmt = $conn->prepare('UPDATE users SET (access_token) VALUES (:token) WHERE id = :id');
        $update_stmt->bindParam(':token', $access_token);
        $update_stmt->bindParam(':id', $user['id']);
        $update_stmt->execute();

        header('Location: ../index.php');
    } else {
        echo "Senha incorreta.";
    }
} else {
    echo "Usuário não encontrado.";
}

$conn = null;