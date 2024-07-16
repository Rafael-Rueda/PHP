import { onlyNumber, validator } from '../../utils/utils.js';
import { showFieldError, clearFieldErrors } from './field_error.js';

export function cpfField(question, label) {
    let questionInput = null;
    questionInput = document.createElement('input');
    questionInput.classList.add('cpf-field');
    questionInput.type = 'number';
    questionInput.id = `question-${question.id}`;
    questionInput.name = `question-${question.id}`;
    questionInput.placeholder = 'Sua resposta';

    questionInput.addEventListener('input', (e) => {
        if (e.target.value.length > 11) {
            e.target.value = e.target.value.slice(0, 11);
        }
    });

    document.getElementById('answering-form').addEventListener('submit', (e) => {
        if (!validator(String(questionInput.value))) {
            e.preventDefault();
            showFieldError("Digite um CPF valido !", questionInput);
        } else {
            clearFieldErrors(questionInput);
        }
    })

    onlyNumber(questionInput);

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