import { onlyNumber, validateCpf } from '../../utils/utils.js';

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

    validateCpf(questionInput, document.getElementById('answering-form'));

    onlyNumber(questionInput);

    if (parseInt(question.required)) {
        questionInput.required = true;
    } else {
        label.classList.add('hide-after');
    }

    return questionInput;
}