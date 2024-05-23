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
        <link rel="stylesheet" href="<?= BASE_URL . 'styles/header.css' ?>">
        <link rel="stylesheet" href="<?= BASE_URL . 'styles/create_form.css' ?>">

        <!-- script -->
        <script src="https://kit.fontawesome.com/b559fbf78d.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <?php include_once (BASE_PATH . 'templates/partials/header.php'); ?>
        <div class="create-form">
            <div class="create-img">
                <img src="<?= BASE_URL . 'images/cabecalho.jpg' ?>" alt="header">
            </div>
            <div class="form">
                <div class="create-title create-field">
                    <input placeholder="Titulo do formulario" type="text" name="title" id="title">
                    <textarea class="long-field" placeholder="Descricao do formulario" type="text" name="description"
                        id="description"></textarea>
                </div>
                <!-- <div id="HD8393RD" class="create-question create-field">
                    <textarea class="long-field" placeholder="Sua pergunta" id="HD8393RD-input"></textarea>
                    <div class="question-controls">
                        <select id="HD8393RD-select">
                            <option value="short-field">Campo de texto curto</option>
                            <option value="long-field">Campo de texto longo</option>
                            <option value="radio-field">Campo de escolha</option>
                            <option value="select-field">Campo de multipla escolha</option>
                        </select>
                        <div class="required">
                            <label for="required">Obrigatorio</label>
                            <input type="checkbox" name="required" id="HD8393RD-required">
                        </div>
                        <button><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div> -->
            </div>
            <form id="create-form" method="POST" action="<?= BASE_URL . 'backend/create_form.php' ?>">
                <!-- <input type="hidden" name="HD8393RD" value="Pergunta 1;long-field;true"> -->
                <button type="submit">Criar formulario</button>
            </form>
            <?php include_once (BASE_PATH . 'templates/partials/controls.php'); ?>
        </div>
        <script type="module" src="<?= BASE_URL . 'scripts/create-form/controls.js' ?>"></script>
    </body>

    </html>
<?php else: ?>
    <?php header('Location: ' . BASE_URL . 'templates/pages/first_register.php'); ?>
<?php endif; ?>