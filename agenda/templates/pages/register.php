<?php
include_once ("../../utils/base_url.php");
?>
<?php
require BASE_PATH . 'vendor/autoload.php';

session_start();

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$db_dsn = $_ENV['DB_DSN'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD'];

$conn = new PDO($db_dsn, $db_username, $db_password);

$access_token = $_SESSION['access_token'];

$stmt = $conn->prepare('SELECT * FROM users WHERE access_token = :access_token');
$stmt->bindParam(':access_token', $access_token);
$stmt->execute();

if ($stmt->rowCount() > 0):

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar um novo usuario</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/first_register.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/styles.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/header.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/footer.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/flash_messages.css' ?>">
</head>

<body>
    <?php include_once (BASE_PATH . 'templates/partials/header.php'); ?>
    <?php include_once (BASE_PATH . 'templates/partials/flash_messages.php'); ?>

    <div class="bodyform">
        <form method="POST" action="<?= BASE_URL . 'backend/register.php' ?>">
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
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>

</html>

<?php else: ?>
    <?php header('Location: ' . BASE_URL . 'templates/pages/first_register.php'); ?>
<?php endif; ?>