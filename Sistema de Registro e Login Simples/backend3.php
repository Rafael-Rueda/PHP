<?php

session_start();

$username = $_POST["username"];
$password = $_POST["password"];
$password2 = $_POST["password2"];

$messages = [];

$conn = new mysqli("localhost", "root", "password", "cursophp");

$query = $conn->query("SELECT * FROM users");
$users = $query->fetch_all();

$userExists = false;

foreach ($users as $user) {
    if ($user[1] == $username) {
        $userExists = true;
        $messages[] = "User already exists. Please choose another username.";
        $_SESSION["messages"] = $messages;
        break;
    }
}

$conn->close();

if ($userExists) {
    header("Location: register.php");
} else {
    if ($password == $password2) {
        $conn = new mysqli("localhost", "root", "password", "cursophp");
    
        $query = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $query->bind_param("ss", $username, $password);
        $query->execute();

        $messages[] = "User created with success. Please login.";
        $_SESSION["messages"] = $messages;

        $query->close();
        $conn->close();
    
        header("Location: login.php");
    } 
    else {
        $messages[] = "Passwords doesnt match.";
        $_SESSION["messages"] = $messages;
        header("Location: register.php");
    }
}
