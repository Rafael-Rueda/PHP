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
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/form_list.css' ?>">
    <link rel="stylesheet" href="<?= BASE_URL . 'styles/flash_messages.css' ?>">
</head>

<body>
    <?php include_once (BASE_PATH . 'templates/partials/header.php'); ?>
    <?php include_once (BASE_PATH . 'templates/partials/flash_messages.php'); ?>
    <?php

    require BASE_PATH . 'vendor/autoload.php';

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(BASE_PATH);
    $dotenv->load();

    $db_dsn = $_ENV['DB_DSN'];
    $db_username = $_ENV['DB_USERNAME'];
    $db_password = $_ENV['DB_PASSWORD'];

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
        import { selectField } from "<?= BASE_URL . 'scripts/forms/select_field.js' ?>";
        import { radioField } from "<?= BASE_URL . 'scripts/forms/radio_field.js' ?>";
        import { longField } from "<?= BASE_URL . 'scripts/forms/long_field.js' ?>";
        import { shortField } from "<?= BASE_URL . 'scripts/forms/short_field.js' ?>";
        document.addEventListener('DOMContentLoaded', function () {
            const formList = document.getElementById('form-list');
            const formItems = document.querySelectorAll('.form-item');

            formItems.forEach(item => {
                item.addEventListener('click', function () {
                    const formId = this.getAttribute('data-form-id');
                    const requestUrl = `<?= BASE_URL . 'backend/fetch_questions.php' ?>?form_id=${formId}`;
                    console.log(`Request URL: ${requestUrl}`);

                    // Remove the form list
                    formList.innerHTML = '';

                    // Add the image of the form
                    var imageContainerDiv = document.createElement('div');
                    imageContainerDiv.className = 'create-img';

                    var headerImage = document.createElement('img');
                    headerImage.src = '<?= BASE_URL . "images/cabecalho.jpg" ?>';
                    headerImage.alt = 'header';

                    imageContainerDiv.appendChild(headerImage);
                    formList.appendChild(imageContainerDiv);

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
                            formElement.action = '<?= BASE_URL . 'backend/answer_form.php' ?>';

                            questions.forEach(question => {
                                const questionDiv = document.createElement('div');
                                questionDiv.classList.add('question');

                                const formIdInput = document.createElement('input');
                                formIdInput.type = 'hidden';
                                formIdInput.name = 'form_id';
                                formIdInput.value = formId;

                                questionDiv.appendChild(formIdInput);

                                const label = document.createElement('label');
                                label.setAttribute('for', `question-${question.id}`);
                                label.textContent = question.content;

                                let questionInput = '';

                                switch (question.type) {
                                    case 'long-field':
                                        questionInput = longField(question, label);
                                        break;
                                    case 'short-field':
                                        questionInput = shortField(question, label);
                                        break;
                                    case 'radio-field':
                                        questionInput = radioField(question, label);
                                        break;
                                    case 'select-field':
                                        questionInput = selectField(question, label);
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