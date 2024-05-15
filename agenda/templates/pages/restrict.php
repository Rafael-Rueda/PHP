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
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/styles.css' ?>">
    <title>Area restrita</title>
</head>
<body>
    
</body>
</html>

<?php else: ?>
    <?php header('Location: ' . BASE_URL . 'templates/pages/first_register.php'); ?>
<?php endif; ?>