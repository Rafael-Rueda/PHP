<?php
function splitUnescapedSemicolons($string)
{
    $parts = preg_split('/(?<!\\\\);/', $string);
    return array_map(function ($part) {
        return str_replace('\;', ';', $part);
    }, $parts);
}

function splitUnescapedHyphens($string)
{
    $parts = preg_split('/(?<!\\\\)-/', $string);
    return array_map(function ($part) {
        return str_replace('\-', '-', $part);
    }, $parts);
}

include_once ('../utils/base_url.php');

require BASE_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

session_start();

$db_dsn = $_ENV['DB_DSN'];
$db_username = $_ENV['DB_USERNAME'];
$db_password = $_ENV['DB_PASSWORD'];

try {
    $conn = new PDO($db_dsn, $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $access_token = $_SESSION['access_token'];

    $stmt1 = $conn->prepare('SELECT * FROM users WHERE access_token = :access_token');
    $stmt1->bindParam(':access_token', $access_token);
    $stmt1->execute();

    $user = $stmt1->fetch(PDO::FETCH_ASSOC);

    if ($stmt1->rowCount() > 0) {
        $conn->beginTransaction();

        // Prepare statements for inserting data
        $formStmt = $conn->prepare('INSERT INTO forms (name, description, owner) VALUES (:name, :description, :owner)');
        $questionStmt = $conn->prepare('INSERT INTO questions (fk_forms_id, content, type, required, question_order, hash) VALUES (:fk_forms_id, :content, :type, :required, :order, :hash)');
        $optionsStmt = $conn->prepare('INSERT INTO questions_options (fk_questions_id, content) VALUES (:fk_questions_id, :content)');
        $periodicityStmt = $conn->prepare('INSERT INTO periodicity (fk_forms_id, quantity, field) VALUES (:fk_forms_id, :quantity, :field)');
        $formId = '';

        $count_order = 0;
        foreach ($_POST as $key => $value) {
            if ($key == 'new-form') {
                // Parse form data
                list($name, $description) = splitUnescapedSemicolons($value);
                $owner = $user['id'];

                // Debugging output
                // echo "Inserting form: Name = $name, Description = $description, Owner = $owner<br>";

                // Insert form data
                $formStmt->execute([':name' => $name, ':description' => $description, ':owner' => $owner]);
                $formId = $conn->lastInsertId();
                //echo "Inserted form ID: $formId<br>";
            } elseif ($key == 'period') {
                list($unit, $time, $field) = explode(';', $value);
                $quantity = 0;
                if ($unit == 'minute') {
                    $quantity = 60 * $time; // In seconds
                } elseif ($unit == 'hour') {
                    $quantity = 3600 * $time; // In seconds
                } elseif ($unit == 'day') {
                    $quantity = 86400 * $time; // In seconds
                }

                $periodicityStmt->execute([':fk_forms_id' => $formId, ':quantity' => $quantity, ':field' => $field]);

            } else {
                // Parse question data
                $parts = splitUnescapedSemicolons($value);

                $content = $parts[0];
                $type = $parts[1];
                $required = $parts[2] === 'true' ? 1 : 0;
                $options = isset($parts[3]) ? splitUnescapedHyphens($parts[3]) : '';
                $order = $count_order;
                $hash = $key;

                // Debugging output
                // echo "Inserting question: Form ID = $formId, Content = $content, Type = $type, Required = $required, Options = $options<br>";

                // Insert question data
                $questionStmt->execute([':fk_forms_id' => $formId, ':content' => $content, ':type' => $type, ':required' => $required, ':order' => $order, ':hash' => $hash]);
                $questionId = $conn->lastInsertId();
                // echo "Inserted question ID: $questionId<br>";

                if ($options) {
                    foreach($options as $option) {
                        $optionsStmt->execute([':fk_questions_id' => $questionId, 'content' => $option]);
                    }
                }

                $count_order++;
            }
        }

        // Commit the transaction
        $conn->commit();
        // echo "Transaction committed.<br>";

        // Redirect after a short delay for debugging purposes
        header('Location: ' . BASE_URL . 'index.php');
    } else {
        echo "User not found or session expired.<br>";
    }
} catch (PDOException $e) {
    // Roll back the transaction if something failed
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log("Error: " . $e->getMessage());
    echo "An error occurred: " . $e->getMessage() . "<br>";
}
