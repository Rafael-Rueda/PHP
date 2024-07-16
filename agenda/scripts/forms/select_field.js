import { clearFieldErrors, showFieldError } from './field_error.js';

export function selectField(question, label) {
    let questionInput = null;
    questionInput = document.createElement('div');
    questionInput.classList.add('select-question-fields');

    question.options.forEach(option => {
        const checkboxInput = document.createElement('input');
        checkboxInput.type = 'checkbox';
        checkboxInput.name = `question-${question.id}[]`;
        checkboxInput.id = `question-${question.id}-${option}`;
        checkboxInput.value = option;

        const checkboxLabel = document.createElement('label');
        checkboxLabel.setAttribute('for', `question-${question.id}-${option}`);
        checkboxLabel.textContent = option;
        checkboxLabel.classList.add('hide-after');

        if (parseInt(question.required)) {
            checkboxInput.required = true;
        } else {
            label.classList.add('hide-after');
        }

        const sqField = document.createElement('div');
        sqField.classList.add('sq-field');

        sqField.appendChild(checkboxInput);
        sqField.appendChild(checkboxLabel);
        questionInput.appendChild(sqField);
    });

    // Validation

    document.getElementById('answering-form').addEventListener('submit', (e) => {
        if (question.required && !questionInput.value) {
            e.preventDefault();
            showFieldError("Preencha este campo obrigatorio !", questionInput);
        } else if (question.required) {
            clearFieldErrors(questionInput);
        }
    });

    return questionInput;
}