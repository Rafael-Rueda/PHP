<?php
include_once ("../utils/base_url.php");

require BASE_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Disable error reporting to avoid non-JSON output
error_reporting(0);

header('Content-Type: application/json');

if (isset($_GET['form_id'])) {
    $form_id = $_GET['form_id'];

    $db_dsn = $_ENV['DB_DSN'];
    $db_username = $_ENV['DB_USERNAME'];
    $db_password = $_ENV['DB_PASSWORD'];

    try {
        $conn = new PDO($db_dsn, $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare('SELECT * FROM questions WHERE fk_forms_id = :id_form');
        $stmt->bindParam(':id_form', $form_id, PDO::PARAM_INT);
        $stmt->execute();

        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as &$question) {
            $stmtOptions = $conn->prepare('SELECT * FROM questions_options WHERE fk_questions_id = :id_question');
            $stmtOptions->bindParam(':id_question', $question['id'], PDO::PARAM_INT);
            $stmtOptions->execute();

            $options = $stmtOptions->fetchAll(PDO::FETCH_COLUMN, 2);
            $question['options'] = $options;
        }

        echo json_encode($questions);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Form ID not provided']);
}