<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area restrita</title>
</head>
<body>
    <form method="POST" action="../../backend/login.php">
        <div>
            <label for="username">Usuario: </label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Senha: </label>
            <input type="password" name="password" id="password" required>
        </div>
        <input type="submit" value="Entrar">
    </form>
</body>
</html>