<?php

session_start();

$_SESSION['post_data'] = $_POST;

if (isset($_POST['marca'])) {

    $validations = [];

    switch ($_POST['marca']) {
        case '':
            $validations['marca'][] = 'Por favor, digite uma marca';
            break;
    }

    if (strlen($_POST['marca']) < 3) {
        $validations['marca'][] = 'Por favor, digite uma marca valida (3 caracteres minimo)';
    }

    $_SESSION['post_data']['opcionais'] = [];
    
    if (isset($_POST['opcionais'])) {
        foreach ($_POST['opcionais'] as $opcao) {
            switch ($opcao) {
                case 'Blindado':
                    $_SESSION['post_data']['opcionais']['blindado'] = $opcao;
                    break;
                case 'Caixas de som':
                    $_SESSION['post_data']['opcionais']['caixas'] = $opcao;
                    break;
                case 'Camera traseira':
                    $_SESSION['post_data']['opcionais']['camera'] = $opcao;
                    break;
            }
        }
    }

    if (!count($validations)) {
        $_SESSION['response'] = $_POST;
    }

    $_SESSION['validations'] = $validations;

    header('Location: index.php');
    exit();
}