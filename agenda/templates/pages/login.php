<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area restrita</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../../styles/login.css">
    <link rel="stylesheet" href="../../styles/header.css">
    <link rel="stylesheet" href="../../styles/styles.css">
</head>
<body>
    <?php include_once("../partials/header.php"); ?>
    <div class="bodyform">
        <form method="POST" action="../../backend/login.php">
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