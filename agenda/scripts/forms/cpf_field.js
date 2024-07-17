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

    onlyNumber(questionInput);

    if (parseInt(question.required)) {
        questionInput.required = true;
    } else {
        label.classList.add('hide-after');
    }

    // Validation

    document.getElementById('answering-form').addEventListener('submit', (e) => {
        if (!questionInput.value) {
            if (Number(question.required)) {
                e.preventDefault();
                showFieldError("Preencha este campo obrigatório !", questionInput);
            } else {
                if (!validator(String(questionInput.value))) {
                    e.preventDefault();
                    showFieldError("Digite um CPF valido !", questionInput);
                } else {
                    clearFieldErrors(questionInput);
                }
            }
        } else {
            if (Number(question.required)) {
                clearFieldErrors(questionInput);
                if (!validator(String(questionInput.value))) {
                    e.preventDefault();
                    showFieldError("Digite um CPF valido !", questionInput);
                } else {
                    clearFieldErrors(questionInput);
                }
            } else {
                if (!validator(String(questionInput.value))) {
                    e.preventDefault();
                    showFieldError("Digite um CPF valido !", questionInput);
                } else {
                    clearFieldErrors(questionInput);
                }
            }
        }
    });

    return questionInput;
}