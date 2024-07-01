<?php

session_start();

if (isset($_POST)) {
    $messages = [];
    $inputMessages = [];

    if (empty($_POST['username'])) {
        $inputMessages["username"][] = "Por favor, digite um usuario valido !";
        $_SESSION['inputMessages'] = $inputMessages;
        header("Location: login.php");
    }

    $conn = new mysqli("localhost", "root", "password", "cursophp");
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT username, password FROM users WHERE username = '$username'");

    $userAndPassword = $result->fetch_assoc();
    $dbUser = $userAndPassword["username"];
    $dbPass = $userAndPassword["password"];

    $_SESSION["username"] = $_POST['username'];

    if ($dbUser && $dbUser == $username && $dbPass == $password) {
        $_SESSION['logged-in'] = true;
        $conn->close();
        header("Location: index.php");
    } else {
        $messages[] = "Credenciais invalidas ! Tente novamente.";
        $_SESSION["messages"] = $messages;
        print_r($dbPass);
        header("Location: login.php");
    }
}
