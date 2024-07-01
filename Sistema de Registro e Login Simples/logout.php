<?php
    session_start();

    $_SESSION['logged-in'] = [];
    
    header("Location: login.php");