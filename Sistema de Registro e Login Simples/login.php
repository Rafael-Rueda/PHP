<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <?php if (isset($_SESSION["messages"])): ?>
        <?php foreach ($_SESSION["messages"] as $message): ?>
            <p>
                <?= $message ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>
    <form action="./backend2.php" method="POST">
        <div>
            <label for="username">Username: </label>
            <input value="<?php if (isset($_SESSION["username"])) {
                echo $_SESSION["username"];
            } ?>" type="text" name="username" id="username">
        </div>
        <?php if (isset($_SESSION["inputMessages"])): ?>
            <ul>
                <?php
                foreach ($_SESSION["inputMessages"]["username"] as $message):
                    ?>
                    <li><?= $message ?></li>
                <?php endforeach; ?>

            </ul>
        <?php endif; ?>
        <div>
            <label for="password">Password: </label>
            <input type="password" name="password" id="password">
        </div>
        <input type="submit" value="Login">
    </form>
    <a href="./register.php">Register</a>
    <?php
    $_SESSION = [];
    ?>
</body>

</html>