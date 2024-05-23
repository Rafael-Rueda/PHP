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

$user = $stmt1->fetch(PDO::FETCH_ASSOC);

if ($stmt1->rowCount() > 0):

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Area restrita</title>

        <!-- CSS -->
        <link rel="stylesheet" href="<?= BASE_URL . 'styles/header.css' ?>">
        <link rel="stylesheet" href="<?= BASE_URL . 'styles/styles.css' ?>">
        <link rel="stylesheet" href="<?= BASE_URL . 'styles/restrict.css' ?>">
    </head>

    <body>
        <?php include_once (BASE_PATH . 'templates/partials/header.php'); ?>
        <a class="create-form-link" href="<?= BASE_URL . 'templates/pages/create_form.php' ?>">Criar novo formulario</a>
        <div class="forms">
            <?php
            $stmt2 = $conn->prepare('SELECT * FROM forms WHERE owner = :user_id');
            $stmt2->bindParam(':user_id', $user['id']);
            $stmt2->execute();

            $forms_from_user = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            foreach ($forms_from_user as $form): ?>
                <div class="form-item">
                    <a href="">
                        <h2 class="form-title"><?= $form['name'] ?></h2>
                    </a>
                    <p class="form-description"><?= $form['description'] ?></p>
                    <p class="form-owner">
                        <?php
                        $userStmt = $conn->prepare('SELECT * FROM users WHERE id = :user_id');
                        $userStmt->bindParam(':user_id', $form['owner']);
                        $userStmt->execute();
                        $userfromform = $userStmt->fetch(PDO::FETCH_ASSOC);

                        echo $userfromform['username'];
                        ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </body>

    </html>

<?php else: ?>
    <?php header('Location: ' . BASE_URL . 'templates/pages/first_register.php'); ?>
<?php endif; ?>