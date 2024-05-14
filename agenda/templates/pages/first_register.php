<?php

$db_dsn = 'mysql:host=localhost;dbname=pesq_db';
$db_username = 'root';
$db_password = 'password';

$conn = new PDO($db_dsn, $db_username, $db_password);

$stmt = $conn->prepare('SELECT * FROM users');
$stmt->execute();

if ($stmt->rowCount() > 0):
    ?>
    <?= 'Voce nao tem acesso a essa pagina.' ?>
<?php else: ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registrar primeiro usuario</title>

        <!-- CSS -->
        <link rel="stylesheet" href="../../styles/first_register.css">
    </head>

    <body>
        <form method="POST" action="../../backend/register.php">
            <div>
                <label for="username">Usuario: </label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="email">Email: </label>
                <input type="email" name="email" id="email">
            </div>
            <div>
                <label for="password">Senha: </label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <label for="password2">Confirmar senha: </label>
                <input type="password" name="password2" id="password2" required>
            </div>
            <input type="submit" value="Registrar Usuario">
        </form>
    </body>

    </html>
<?php endif; ?>