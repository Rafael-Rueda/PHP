<?php
include_once ("./utils/base_url.php");
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financiamento para pesquisa</title>

    <!-- css -->
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/styles.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/header.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/footer.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/forms.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/form-list.css' ?>">
</head>

<body>
    <?php include_once (BASE_PATH . 'templates/partials/header.php'); ?>
    <?php
    $db_dsn = 'mysql:host=localhost;dbname=pesq_db';
    $db_username = 'root';
    $db_password = 'password';

    $conn = new PDO($db_dsn, $db_username, $db_password);

    $stmt = $conn->prepare('SELECT * FROM forms');
    $stmt->execute();

    $forms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="form-list" id="form-list">
        <?php
        foreach ($forms as $form):
            ?>
            <div class="form-item" data-form-id="<?= $form['id'] ?>">
                <div class="form-title">
                    <h2><?= $form['name'] ?></h2>
                </div>
                <div class="form-description">
                    <p><?= $form['description'] ?></p>
                </div>
                <div class="form-number-questions">
                    <p><?php
                    $questionsFormStmt = $conn->prepare('SELECT * FROM questions WHERE fk_forms_id = :id_form');

                    $questionsFormStmt->bindParam(':id_form', $form['id']);
                    $questionsFormStmt->execute();

                    echo $questionsFormStmt->rowCount() . ' Perguntas';
                    ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script type="module">
        import { expandLongField } from "<?= BASE_URL . 'scripts/create-form/expand_field.js' ?>";
        document.addEventListener('DOMContentLoaded', function () {
            const formList = document.getElementById('form-list');
            const formItems = document.querySelectorAll('.form-item');

            formItems.forEach(item => {
                item.addEventListener('click', function () {
                    const formId = this.getAttribute('data-form-id');
                    const requestUrl = `<?= BASE_URL . 'backend/fetch-questions.php' ?>?form_id=${formId}`;
                    console.log(`Request URL: ${requestUrl}`);

                    // Remove the form list
                    formList.innerHTML = '';

                    // Fetch questions for the selected form
                    fetch(requestUrl)
                        .then(response => response.text()) // Use text() to debug raw response
                        .then(data => {
                            console.log('Raw response:', data); // Log raw response
                            const questions = JSON.parse(data); // Parse JSON

                            if (questions.error) {
                                console.error(questions.error);
                                return;
                            }

                            const formContainer = document.createElement('div');
                            formContainer.classList.add('request-form');

                            const formElement = document.createElement('form');
                            formElement.method = 'POST';
                            formElement.action = '';

                            questions.forEach(question => {
                                const questionDiv = document.createElement('div');
                                questionDiv.classList.add('question');

                                const label = document.createElement('label');
                                label.for = `question_${question.id}`;
                                label.textContent = question.content;

                                let questionInput = ''

                                switch (question.type) {
                                    case 'long-field':
                                        questionInput = document.createElement('textarea');
                                        questionInput.classList.add('long-field');
                                        questionInput.id = `question_${question.id}`;
                                        questionInput.placeholder = 'Sua resposta';
                                        if (question.required) {
                                            questionInput.required = true;
                                        } else {
                                            label.classList.add('hide-after');
                                        }
                                        break;
                                    case 'short-field':
                                        questionInput = document.createElement('input');
                                        questionInput.classList.add('short-field');
                                        questionInput.type = 'text';
                                        questionInput.id = `question_${question.id}`;
                                        questionInput.placeholder = 'Sua resposta';
                                        if (question.required) {
                                            questionInput.required = true;
                                        } else {
                                            label.classList.add('hide-after');
                                        }
                                        break;
                                    case 'radio-field':
                                        questionInput = document.createElement('div');
                                        question.options.forEach(option => {
                                            const radioInput = document.createElement('input');
                                            radioInput.type = 'radio';
                                            radioInput.name = `question_${question.id}`;
                                            radioInput.id = `question-${question.id}-${option}`;
                                            radioInput.value = option;

                                            const radioLabel = document.createElement('label');
                                            radioLabel.for = `question-${question.id}-${option}`;
                                            radioLabel.textContent = option;
                                            radioLabel.classList.add('hide-after');

                                            if (question.required) {
                                                radioInput.required = true;
                                            }

                                            questionInput.appendChild(radioInput);
                                            questionInput.appendChild(radioLabel);
                                        });
                                        break;
                                    case 'select-field':
                                        questionInput = document.createElement('div');
                                        question.options.forEach(option => {
                                            const checkboxInput = document.createElement('input');
                                            checkboxInput.type = 'checkbox';
                                            checkboxInput.name = `question_${question.id}[]`;
                                            checkboxInput.id = `question-${question.id}-${option}`;
                                            checkboxInput.value = option;

                                            const checkboxLabel = document.createElement('label');
                                            checkboxLabel.for = `question-${question.id}-${option}`;
                                            checkboxLabel.textContent = option;
                                            checkboxLabel.classList.add('hide-after');

                                            if (question.required) {
                                                checkboxInput.required = true;
                                            }

                                            questionInput.appendChild(checkboxInput);
                                            questionInput.appendChild(checkboxLabel);
                                        });
                                        break;
                                    default:
                                        break;
                                }

                                questionDiv.appendChild(label);
                                questionDiv.appendChild(questionInput);
                                formElement.appendChild(questionDiv);
                            });

                            const submitDiv = document.createElement('div');
                            submitDiv.classList.add('submit');

                            const submitButton = document.createElement('button');
                            submitButton.type = 'submit';
                            submitButton.textContent = 'Enviar';

                            submitDiv.appendChild(submitButton);
                            formElement.appendChild(submitDiv);

                            formContainer.appendChild(formElement);
                            document.body.appendChild(formContainer);

                            expandLongField();
                        })
                        .catch(error => console.error('Error fetching questions:', error));
                });
            });
        });
    </script>
    <!-- <div class="request-form">
        <form method="POST" action="">
            <div class="question">
                <label for="nome">Seu nome completo</label>
                <input required placeholder="Sua resposta" type="text" name="nome" id="nome">
            </div>
            <div class="question">
                <label for="teste">Teste</label>
                <textarea required placeholder="Sua resposta" class="long-field" name="teste" id="teste"></textarea>
            </div>
            <div class="question">
                <label for="teste2">Teste de pergunta 2</label>
                <textarea required placeholder="Sua resposta" class="long-field" name="teste2" id="teste2"></textarea>
            </div>
            <div class="question">
                <label for="teste3">Teste de pergunta 3</label>
                <textarea required placeholder="Sua resposta" class="long-field" name="teste3" id="teste3"></textarea>
            </div>
            <div class="submit">
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div> -->
    <script src="<?= BASE_URL . 'scripts/expand_field.js' ?>"></script>
</body>

</html>