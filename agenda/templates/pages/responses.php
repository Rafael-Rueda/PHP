<?php
include_once ("../../utils/base_url.php");
?>
<?php

session_start();

function userOwnsForm($formsOwned, $formId)
{
    foreach ($formsOwned as $form) {
        if ($form['id'] == $formId) {
            return true;
        }
    }
    return false;
}

require BASE_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$db_dsn = $_ENV['DB_DSN'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD'];

$conn = new PDO($db_dsn, $db_username, $db_password);

// Check user logged-in

$access_token = $_SESSION['access_token'];

$stmt1 = $conn->prepare('SELECT * FROM users WHERE access_token = :access_token');
$stmt1->bindParam(':access_token', $access_token);
$stmt1->execute();

$user = $stmt1->fetch(PDO::FETCH_ASSOC);

$formsStmt = $conn->prepare('SELECT * FROM forms WHERE owner = :owner');
$formsStmt->execute([':owner' => $user['id']]);
$formsOwned = $formsStmt->fetchAll(PDO::FETCH_ASSOC);

$form_id = $_GET['form'];
?>
<?php
if ($stmt1->rowCount() > 0 && isset($_GET) && userOwnsForm($formsOwned, $form_id)):
    ?>
    <?php
    $formStmt = $conn->prepare('SELECT * FROM forms WHERE id = :id');
    $formStmt->execute([':id' => $form_id]);
    $form = $formStmt->fetch(PDO::FETCH_ASSOC);

    $questionsStmt = $conn->prepare('SELECT * FROM questions WHERE fk_forms_id = :id');
    $questionsStmt->execute([':id' => $form_id]);
    $questions = $questionsStmt->fetchAll(PDO::FETCH_ASSOC);

    $answers = [];
    foreach ($questions as $question) {
        $answersStmt = $conn->prepare('SELECT * FROM answers WHERE fk_questions_id = :id');
        $answersStmt->execute([':id' => $question['id']]);
        array_push($answers, $answersStmt->fetchAll(PDO::FETCH_ASSOC));
    }
    ?>
    <?php
    // Determine the current page
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $answersPerPage = 1; // Number of answers per page

    $totalPages = 0;

    foreach ($answers as $answer) {
        $pagesAnalyzed = count($answer);
        if ($pagesAnalyzed >= $totalPages) {
            $totalPages = $pagesAnalyzed;
        }
    }

    // Calculate the starting index for the current page
    $startIndex = ($page - 1) * $answersPerPage;

    $currentAnswersAndQuestions = []; // [['question', 'answer'], ['question', 'answer']]

    // For debug propouses
    // echo '<pre>';
    // print_r($answers);
    // echo '</pre>';

    // Get the max fields (registers) of the question's answer
    $maxFields = 0;
    
    foreach ($answers as $orderedQuestionID => $answer) {
        $i = 0;
        foreach($answer as $keyField => $field ) {
            $i++;
        }
        if ($maxFields < $i) {
            $maxFields = $i;
        }
    }

    // Fetch the answers for the current page
    foreach($answers as $orderedQuestionID => $answer) {
        array_push($currentAnswersAndQuestions, [$questions[$orderedQuestionID], isset($answer[0]) ? array_slice($answer, $startIndex, $answersPerPage)[0] : ['content' => '']]);
    }
    
    
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Respostas do formulario <?= $form_id ?></title>

        <!-- CSS -->
        <link rel="stylesheet" href="<?= BASE_URL . 'styles/header.css' ?>">
        <link rel="stylesheet" href="<?= BASE_URL . 'styles/styles.css' ?>">
        <link rel="stylesheet" href="<?= BASE_URL . 'styles/responses.css' ?>">
    </head>

    <body>
        <?php include_once (BASE_PATH . 'templates/partials/header.php') ?>
        <div class="answer">
            <div class="header">
                <div class="title">
                    <h1><?= $form['name'] ?></h1>
                </div>
                <div class="description">
                    <p><?= $form['description'] ?></p>
                </div>
            </div>
            <?php
            foreach ($currentAnswersAndQuestions as $answersForQuestion):
                ?>

                <div class="body">
                    <div class="question">
                        <p><?= $answersForQuestion[0]['content']; ?></p>
                    </div>
                    <div class="answer">
                        <?php if (isset($answersForQuestion[1])): ?>
                            <p><?= $answersForQuestion[1]['content']; ?></p>
                        <?php else: ?>
                            <p></p>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            for ($c = 1; $c <= $totalPages; $c++):
                ?>
                <span><a href="?form=<?= $form_id ?>&page=<?= $c ?>"><?= $c ?></a></span>
            <?php endfor; ?>
        </div>
    </body>

    </html>
    <?php
else:
    header('Location: ' . BASE_URL . 'templates/pages/restrict.php');
    ?>

<?php endif; ?>