<?php

function getIdFromName($questionName) {
    $parts = explode('-', $questionName);
    return isset($parts[1]) && ctype_digit($parts[1]) ? (int)$parts[1] : null;
}

function getContentFromPost($postValue) {
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

$db_dsn = 'mysql:host=localhost;dbname=pesq_db';
$db_username = 'root';
$db_password = 'password';

try {
    $conn = new PDO($db_dsn, $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start the transaction
    $conn->beginTransaction();

    // Prepare statements for inserting data
    $answerStmt = $conn->prepare('INSERT INTO answers (fk_questions_id, content) VALUES (:fk_questions_id, :content)');
    $questionCheckStmt = $conn->prepare('SELECT 1 FROM questions WHERE id = :id');

    foreach($_POST as $key => $value) {
        $questionId = getIdFromName($key);
        if ($questionId === null) {
            throw new Exception("Invalid question ID format.");
        }

        // Validate that the question ID exists in the database
        $questionCheckStmt->execute([':id' => $questionId]);
        if ($questionCheckStmt->rowCount() === 0) {
            throw new Exception("Question ID $questionId does not exist.");
        }

        $content = getContentFromPost($value);
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
