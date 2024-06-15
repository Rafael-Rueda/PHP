<?php

include_once ("../utils/base_url.php");
require BASE_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// error_reporting(0);
header('Content-Type: application/json');

$db_dsn = $_ENV['DB_DSN'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD'];

$conn = new PDO($db_dsn, $db_username, $db_password);

if (isset($_GET['question']) || isset($_GET['answer'])) {
    $questionStmt = $conn->prepare('SELECT * FROM questions WHERE content = :question_content AND fk_forms_id = :form_id');
    $questionStmt->bindParam(':question_content', $_GET['question']);
    $questionStmt->bindParam(':form_id', $_GET['form']);
    $questionStmt->execute();

    $question = $questionStmt->fetchAll(PDO::FETCH_ASSOC);
    $question_id = $question[0]['id'];

    $answerContent = '%' . $_GET['answer'] . '%';
    $answerStmt = $conn->prepare('SELECT * FROM answers WHERE fk_questions_id = :question_id AND content LIKE :answer_content');
    $answerStmt->bindParam(':question_id', $question_id);
    $answerStmt->bindParam(':answer_content', $answerContent);
    $answerStmt->execute();

    $answer = $answerStmt->fetchAll(PDO::FETCH_ASSOC);

    // Getting all questions from the form id
    $allQuestionsStmt = $conn->prepare('SELECT * FROM questions WHERE fk_forms_id = :form_id');
    $allQuestionsStmt->bindParam(':form_id', $_GET['form']);
    $allQuestionsStmt->execute();

    $allQuestions = $allQuestionsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Getting all answers from the questions
    $allAnswers = [];

    foreach($allQuestions as $quest) {
        $allAnswersStmt = $conn->prepare('SELECT * FROM answers WHERE fk_questions_id = :question_id');
        $allAnswersStmt->bindParam(':question_id', $quest['id']);
        $allAnswersStmt->execute();
        
        array_push($allAnswers, $allAnswersStmt->fetch(PDO::FETCH_ASSOC));
    }


    $currentAnswersAndQuestions = [];

    foreach ($answer as $answercontain) {
        $answerContainIndex = array_search($answercontain, $allAnswers);
        foreach ($allQuestions as $questIndex => $quest) {
            array_push($currentAnswersAndQuestions, [$quest, $allAnswers[$answerContainIndex]]);
        }
    }

    echo json_encode(['answers' => $allAnswers]);

} else {
    echo json_encode(['error' => 'Please provide the correct parameters']);
}
