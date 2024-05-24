<?php
include_once ("./utils/base_url.php");

// Disable error reporting to avoid non-JSON output
error_reporting(0);

header('Content-Type: application/json');

if (isset($_GET['form_id'])) {
    $form_id = $_GET['form_id'];
    
    $db_dsn = 'mysql:host=localhost;dbname=pesq_db';
    $db_username = 'root';
    $db_password = 'password';

    try {
        $conn = new PDO($db_dsn, $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare('SELECT * FROM questions WHERE fk_forms_id = :id_form');
        $stmt->bindParam(':id_form', $form_id, PDO::PARAM_INT);
        $stmt->execute();

        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($questions);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Form ID not provided']);
}