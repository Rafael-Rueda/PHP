<?php 
    include_once("./utils/base_url.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financiamento para pesquisa</title>

    <!-- css -->
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/styles.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/header.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/footer.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/forms.css' ?>">
</head>
<body>
    <?php include_once(__DIR__ . '/templates/partials/header.php');?>
    <div class="request-form">
        <form method="POST" action="">
            <div class="question">
                <label for="nome">Seu nome completo</label>
                <input required placeholder="Sua resposta" type="text" name="nome" id="nome">
            </div>
            <div class="question">
                <label for="teste">Teste</label>
                <textarea required placeholder="Sua resposta" class="long-field" name="teste" id="teste"></textarea>
            </div>
            <div class="question">
                <label for="teste2">Teste de pergunta 2</label>
                <textarea required placeholder="Sua resposta" class="long-field" name="teste2" id="teste2"></textarea>
            </div>
            <div class="question">
                <label for="teste3">Teste de pergunta 3</label>
                <textarea required placeholder="Sua resposta" class="long-field" name="teste3" id="teste3"></textarea>
            </div>
            <div class="submit">
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div>
    <script src="<?= BASE_URL . 'scripts/expand_field.js' ?>"></script>
</body>
</html>