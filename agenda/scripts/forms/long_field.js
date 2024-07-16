import { clearFieldErrors, showFieldError } from './field_error.js';

export function longField(question, label) {
    let questionInput = null;
    questionInput = document.createElement('textarea');
    questionInput.classList.add('long-field');
    questionInput.id = `question-${question.id}`;
    questionInput.name = `question-${question.id}`;
    questionInput.placeholder = 'Sua resposta';
    if (parseInt(question.required)) {
        questionInput.required = true;
    } else {
        label.classList.add('hide-after');
    }

    document.getElementById('answering-form').addEventListener('submit', (e) => {
        if (question.required && question.value == '') {
            e.preventDefault();
            showFieldError("Preencha este campo obrigatorio !", questionInput);
        } else if (question.required) {
            clearFieldErrors(questionInput);
        }
    });

    return questionInput;
}