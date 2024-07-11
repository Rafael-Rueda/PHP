<?php
include_once ("../../utils/base_url.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area restrita</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/login.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/header.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/footer.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/styles.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/flash_messages.css' ?>">
</head>

<body>
    <?php include_once (BASE_PATH . 'templates/partials/header.php'); ?>
    <?php include_once (BASE_PATH . 'templates/partials/flash_messages.php'); ?>
    
    <div class="bodyform">
        <form method="POST" action="<?= BASE_URL . "backend/login.php" ?>">
            <h1>Bem-vindo(a) de volta !</h1>
            <div>
                <label for="username">Usuario: </label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="password">Senha: </label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>

</html>