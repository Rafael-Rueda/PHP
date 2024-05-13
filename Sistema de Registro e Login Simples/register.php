<?php 
    session_start();

    if (!isset($_SESSION["messages"])) {
        $_SESSION["messages"] = [];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form action="./backend3.php" method="POST">
        <ul>
            <?php 
                foreach($_SESSION["messages"] as $msg):
            ?>
            <li><?= $msg ?></li>
            <?endforeach;?>
        </ul>
        <div>
            <label for="username">Username: </label>
            <input type="text" name="username" id="username">
        </div>
        <div>
            <label for="password">Password: </label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="password2">Confirm Password: </label>
            <input type="password" name="password2" id="password2">
        </div>
        <input type="submit" value="Register">
    </form>
</body>
</html>

<?php 
$_SESSION = [];
?>