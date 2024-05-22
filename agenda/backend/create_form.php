<?php
function splitUnescapedSemicolons($string)
{
    $parts = preg_split('/(?<!\\\\);/', $string);
    return array_map(function ($part) {
        return str_replace('\;', ';', $part);
    }, $parts);
}

include_once ('../utils/base_url.php');

session_start();

$db_dsn = 'mysql:host=localhost;dbname=pesq_db';
$db_username = 'root';
$db_password = 'password';

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
        $questionStmt = $conn->prepare('INSERT INTO questions (fk_forms_id, content, type, required) VALUES (:fk_forms_id, :content, :type, :required)');

        $formId = '';
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
            } else {
                // Parse question data
                $parts = splitUnescapedSemicolons($value);
                
                $content = $parts[0];
                $type = $parts[1];
                $required = $parts[2] === 'true' ? 1 : 0;
                $options = isset($parts[3]) ? $parts[3] : '';

                // Debugging output
                // echo "Inserting question: Form ID = $formId, Content = $content, Type = $type, Required = $required, Options = $options<br>";

                // Insert question data
                $questionStmt->execute([':fk_forms_id' => $formId, ':content' => $content, ':type' => $type, ':required' => $required]);
                $questionId = $conn->lastInsertId();
                // echo "Inserted question ID: $questionId<br>";
            }
        }

        // Commit the transaction
        $conn->commit();
        echo "Transaction committed.<br>";

        // Redirect after a short delay for debugging purposes
        header('refresh:5; url=' . BASE_URL . 'index.php');
        echo "Redirecting in 5 seconds...<br>";
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
