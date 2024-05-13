<?php

session_start();

if (isset($_SESSION['logged-in'])) {
    $userLoggedIn = $_SESSION['logged-in'];
} else {
    $userLoggedIn = false;
}


?>

<?php
if ($userLoggedIn):
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <form action="./logout.php">
            <button type="submit">Logout</button>
        </form>
        <h1>Cadastre o seu carro</h1>
        <form action="./backend.php" method="POST">
            <div>
                <?php if (isset($_SESSION['validations']['marca'])): ?>
                    <ul>
                        <?php foreach ($_SESSION['validations']['marca'] as $validation): ?>
                            <li><?= $validation ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <input
                    value="<?php if (isset($_SESSION['post_data']['marca'])) {
                        echo $_SESSION['post_data']['marca'];
                    } ?>"
                    type="text" name="marca" placeholder="Digite a marca do carro">
            </div>

            <div>
                <label for="op1">Blindado</label>
                <input <?php if (isset($_SESSION['post_data']['opcionais']['blindado'])) {
                    echo 'checked';
                } ?> type="checkbox"
                    name="opcionais[]" id="op1" value="Blindado">
            </div>

            <div>
                <label for="op2">Caixas de som</label>
                <input <?php if (isset($_SESSION['post_data']['opcionais']['caixas'])) {
                    echo 'checked';
                } ?> type="checkbox"
                    name="opcionais[]" id="op2" value="Caixas de som">
            </div>

            <div>
                <label for="op3">Camera traseira</label>
                <input <?php if (isset($_SESSION['post_data']['opcionais']['camera'])) {
                    echo 'checked';
                } ?> type="checkbox"
                    name="opcionais[]" id="op3" value="Camera traseira">
            </div>

            <div>
                <input type="submit" value="Cadastrar">
            </div>
        </form>

        <div class="response">
            <?php if (!empty($_SESSION['response']) && isset($_SESSION['response'])): ?>
                <h2>Sua escolha: </h2>
                <h1><?= $_SESSION['response']['marca'] ?></h1>
                <ul>
                    <?php
                    if (isset($_SESSION['response']['opcionais'])):
                        foreach ($_SESSION['response']['opcionais'] as $opcao):
                            ?>
                            <li>
                                <?= $opcao ?>
                            </li>
                        <?php endforeach; endif; ?>
                </ul>
            <?php endif; ?>
        </div>

        <?php $_SESSION['response'] = ""; $_SESSION['post_data'] = "";?>
    </body>

    </html>

<?php else: ?>
<?php 
    header("Location: login.php");
?>
<? endif; ?>