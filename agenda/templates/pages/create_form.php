<?php
include_once ("../../utils/base_url.php");
?>
<?php

session_start();

$db_dsn = 'mysql:host=localhost;dbname=pesq_db';
$db_username = 'root';
$db_password = 'password';

$conn = new PDO($db_dsn, $db_username, $db_password);

$access_token = $_SESSION['access_token'];

$stmt1 = $conn->prepare('SELECT * FROM users WHERE access_token = :access_token');
$stmt1->bindParam(':access_token', $access_token);
$stmt1->execute();

$user = $stmt1->fetch();

if ($stmt1->rowCount() > 0):

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar formulario</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/styles.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/create_form.css' ?>">
</head>
<body>
    <div class="create-form">
        <div class="create-img">
            <img src="<?= BASE_URL . 'images/cabecalho.jpg' ?>" alt="header">
        </div>
        <form id="create-form" action="<?= BASE_URL . 'backend/create_form.php' ?>">
            <div class="create-title create-field">
                <input placeholder="Titulo do formulario" type="text" name="title" id="title">
                <textarea class="long-field" placeholder="Descricao do formulario" type="text" name="description" id="description"></textarea>
            </div>
        </form>
    </div>
    <script src="<?= BASE_URL . 'scripts/expand_field.js' ?>"></script>
</body>
</html>

<?php endif; ?>