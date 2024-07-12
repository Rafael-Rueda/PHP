<?php

include_once ("../utils/base_url.php");
require BASE_PATH . 'vendor/autoload.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// error_reporting(0);
header('Content-Type: application/json');

$db_dsn = $_ENV['DB_DSN'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD'];

$conn = new PDO($db_dsn, $db_username, $db_password);

if (isset($_GET['question']) && isset($_GET['form']) && $_GET['question'] != '') {
    $questionStmt = $conn->prepare('SELECT * FROM questions WHERE content = :question_content AND fk_forms_id = :form_id');
    $questionStmt->bindParam(':question_content', $_GET['question']);
    $questionStmt->bindParam(':form_id', $_GET['form']);
    $questionStmt->execute();

    $question = $questionStmt->fetch(PDO::FETCH_ASSOC);
    if ($questionStmt->rowCount() > 0) {
        $question_id = $question['id'];
    } else {
        echo json_encode(['error' => 'Question not found']);
        exit();
    }

    $answerContent = '%' . strval($_GET['answer']) . '%';
    // $operator = is_numeric($_GET['answer']) ? '=' : 'LIKE';

    $answerStmt = $conn->prepare("SELECT * FROM answers WHERE fk_questions_id = :question_id AND content LIKE :answer_content");
    $answerStmt->bindParam(':question_id', $question_id);
    $answerStmt->bindParam(':answer_content', $answerContent);
    $answerStmt->execute();

    // print_r($answerStmt->errorInfo());

    $answer = $answerStmt->fetchAll(PDO::FETCH_ASSOC);

    // Getting all questions from the form id
    $allQuestionsStmt = $conn->prepare('SELECT * FROM questions WHERE fk_forms_id = :form_id');
    $allQuestionsStmt->bindParam(':form_id', $_GET['form']);
    $allQuestionsStmt->execute();

    $allQuestions = $allQuestionsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Getting the total of questions of the form
    $totalQuestionsStmt = $conn->prepare('SELECT COUNT(*) as total FROM questions WHERE fk_forms_id = :form_id');
    $totalQuestionsStmt->bindParam(':form_id', $_GET['form']);
    $totalQuestionsStmt->execute();
    $totalQuestions = $totalQuestionsStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Getting all answers from the questions
    $allAnswers = [];

    foreach ($answer as $unique_answer) {
        // Descending loop
        $appendAnswers = [];
        $appendOrderedAnswers = [];
        $orderOfQuestion = intval($question['question_order']);
        $counter = 0;
        while ($orderOfQuestion >= 0) {
            $idAnswer = intval($unique_answer['id']) - $counter;
            $appendAnswersStmt = $conn->prepare('SELECT * FROM answers WHERE id = :id');
            $appendAnswersStmt->bindParam(':id', $idAnswer);
            $appendAnswersStmt->execute();

            $appendAnswer = $appendAnswersStmt->fetch(PDO::FETCH_ASSOC);

            array_push($appendOrderedAnswers, $appendAnswer);
            $orderOfQuestion--;
            $counter++;
        }

        foreach (array_reverse($appendOrderedAnswers) as $orderedAnswer) {
            array_push($appendAnswers, $orderedAnswer);
        }

        // Increasing loop
        $orderOfQuestion = intval($question['question_order']) + 1;
        $counter = 1;
        while ($orderOfQuestion < $totalQuestions) {
            $idAnswer = intval($unique_answer['id']) + $counter;
            $appendAnswersStmt = $conn->prepare('SELECT * FROM answers WHERE id = :id');
            $appendAnswersStmt->bindParam(':id', $idAnswer);
            $appendAnswersStmt->execute();

            $appendAnswer = $appendAnswersStmt->fetch(PDO::FETCH_ASSOC);

            if (!$appendAnswer) {
                break;
            }

            array_push($appendAnswers, $appendAnswer);
            $orderOfQuestion++;
            $counter++;
        }

        array_push($allAnswers, $appendAnswers);
    }

    echo json_encode($allAnswers);

} else {
    echo json_encode(['error' => 'Please provide the correct parameters']);
}
