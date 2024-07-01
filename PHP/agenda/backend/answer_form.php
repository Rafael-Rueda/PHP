<?php

function getIdFromName($questionName) {
    $parts = explode('-', $questionName);
    return isset($parts[1]) && ctype_digit($parts[1]) ? (int)$parts[1] : null;
}

function getContentFromPost($postValue) {
    if (!isset($postValue) || is_null($postValue)) {
        return ['content' => ''];
    }

    $returnValue = $postValue;
    if (is_array($postValue)) {
        $returnValue = '';
        foreach($postValue as $valueOfArray) {
            $valueOfArray = str_replace(';', '\;', (string) $valueOfArray);
            if (!$returnValue) {
                $returnValue = $valueOfArray;
            } else {
                $returnValue = $returnValue . ';' . $valueOfArray;
            }
        };
    }
    return $returnValue;
}

include_once ('../utils/base_url.php');

require BASE_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$db_dsn = $_ENV['DB_DSN'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD'];

try {
    $conn = new PDO($db_dsn, $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start the transaction
    $conn->beginTransaction();

    // Prepare statements for inserting data
    $answerStmt = $conn->prepare('INSERT INTO answers (fk_questions_id, content) VALUES (:fk_questions_id, :content)');
    $questionCheckStmt = $conn->prepare('SELECT 1 FROM questions WHERE id = :id');

    $questionStmt = $conn->prepare('SELECT * FROM questions WHERE fk_forms_id = :form_id');
    $questionStmt->execute([':form_id' => $_POST['form_id']]);
    $questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach($questions as $question) {
        $questionId = $question['id'];
        if ($questionId === null) {
            throw new Exception("Invalid question ID format.");
        }

        // Validate that the question ID exists in the database
        $questionCheckStmt->execute([':id' => $questionId]);
        if ($questionCheckStmt->rowCount() === 0) {
            throw new Exception("Question ID $questionId does not exist.");
        }

        $content = getContentFromPost(isset($_POST['question-' . $questionId]) ? $_POST['question-' . $questionId] : '');
        $answerStmt->execute([':fk_questions_id' => $questionId, ':content' => $content]);
    }

    // Commit the transaction
    $conn->commit();

    // Redirect after a short delay for debugging purposes
    header('Location: ' . BASE_URL . 'index.php');
    exit;

} catch (Exception $e) {
    // Roll back the transaction if something failed
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log("Error: " . $e->getMessage());
    echo "An error occurred: " . $e->getMessage() . "<br>";
}
