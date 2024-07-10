<?php

include_once ('../utils/base_url.php');
include_once (BASE_PATH . 'backend/flash_messages.php');

session_start();

date_default_timezone_set('America/Sao_Paulo');

require BASE_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

function getIdFromName($questionName)
{
    $parts = explode('-', $questionName);
    return isset($parts[1]) && ctype_digit($parts[1]) ? (int) $parts[1] : null;
}

function getContentFromPost($postValue)
{
    if (!isset($postValue) || is_null($postValue)) {
        return ['content' => ''];
    }

    $returnValue = $postValue;
    if (is_array($postValue)) {
        $returnValue = '';
        foreach ($postValue as $valueOfArray) {
            $valueOfArray = str_replace(';', '\;', (string) $valueOfArray);
            if (!$returnValue) {
                $returnValue = $valueOfArray;
            } else {
                $returnValue = $returnValue . ';' . $valueOfArray;
            }
        }
        ;
    }
    return $returnValue;
}

$db_dsn = $_ENV['DB_DSN'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD'];

try {
    $conn = new PDO($db_dsn, $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start the transaction
    $conn->beginTransaction();

    // Prepare statements for inserting data
    $answerStmt = $conn->prepare('INSERT INTO answers (fk_questions_id, content, created_at) VALUES (:fk_questions_id, :content, :created_at)');
    $questionCheckStmt = $conn->prepare('SELECT 1 FROM questions WHERE id = :id');

    $questionStmt = $conn->prepare('SELECT * FROM questions WHERE fk_forms_id = :form_id');
    $questionStmt->execute([':form_id' => $_POST['form_id']]);
    $questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($questions as $question) {
        $questionId = $question['id'];
        $questionHash = $question['hash'];
        if ($questionId === null) {
            throw new Exception("Invalid question ID format.");
        }
    
        // Validate that the question ID exists in the database
        $questionCheckStmt->execute([':id' => $questionId]);
        if ($questionCheckStmt->rowCount() === 0) {
            throw new Exception("Question ID $questionId does not exist.");
        }
    
        $content = getContentFromPost(isset($_POST['question-' . $questionId]) ? $_POST['question-' . $questionId] : '');
        $now = new DateTime();
    
        // if...
    
        $periodicityStmt = $conn->prepare('SELECT * FROM periodicity WHERE fk_forms_id = :form_id');
        $periodicityStmt->execute([':form_id' => $_POST['form_id']]);
        $periodicity = $periodicityStmt->fetch(PDO::FETCH_ASSOC);
    
        $questionPeriodicityStmt = $conn->prepare('SELECT * FROM questions WHERE hash = :hash');
        $questionPeriodicityStmt->execute(['hash' => $periodicity['field']]);
        $questionPeriodicity = $questionPeriodicityStmt->fetch(PDO::FETCH_ASSOC);
    
        $answersFQStmt = $conn->prepare('SELECT * FROM answers WHERE fk_questions_id = :question_id');
        $answersFQStmt->execute([':question_id' => $questionPeriodicity['id']]);
        $answersForQuestion = $answersFQStmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($question['hash'] == $periodicity['field']) {
            foreach ($answersForQuestion as $answer) {
                echo ("CONTENT = " . $content);
                echo "<br/><br/>";
                echo ("ANSWER CONTENT = " . $answer['content']);
                echo "<br/><br/>";
                if ($content == $answer['content']) {
                    $createdAt = new DateTime($answer['created_at']);
                    $intervalSpec = sprintf('PT%dS', $periodicity['quantity']);
                    $add_time = new DateInterval($intervalSpec);
                    $futureTime = clone $createdAt;
                    $futureTime->add($add_time);
    
                    // $nowInSeconds = $now->getTimestamp();
                    // $createdAtInSeconds = $createdAt->getTimestamp();
                    // $futureTimeInSeconds = $futureTime->getTimestamp();
    
                    // echo "Now (seconds): " . $nowInSeconds . "<br>";
                    // echo "Created At (seconds): " . $createdAtInSeconds . "<br>";
                    // echo "Interval (seconds): " . $periodicity['quantity'] . "<br>";
                    // echo "Future Time (seconds): " . $futureTimeInSeconds . "<br>";
    
                    echo "Now (datetime): " . $now->format('Y-m-d H:i:s') . "<br>";
                    echo "Created At (datetime): " . $createdAt->format('Y-m-d H:i:s') . "<br>";
                    echo "Future Time (datetime): " . $futureTime->format('Y-m-d H:i:s') . "<br>";
    
                    if ($now <= $futureTime) {
                        // set_flash_message('Nao foi possivel responder esse formulario. Voce so podera responde-lo novamente a partir de ' . $futureTime, 'error');
                        test('aaa');
                        // header('Location: ' . BASE_URL . 'index.php');
                        exit;
                    }
                }
            }
        }
    
        $answerStmt->execute([':fk_questions_id' => $questionId, ':content' => $content, ':created_at' => $now->format('Y-m-d H:i:s')]);
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
