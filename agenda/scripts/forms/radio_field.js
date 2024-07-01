export function radioField(question, label) {
    let questionInput = null;
    questionInput = document.createElement('div');
    questionInput.classList.add('select-question-fields');
    question.options.forEach(option => {
        const radioInput = document.createElement('input');
        radioInput.type = 'radio';
        radioInput.name = `question-${question.id}`;
        radioInput.id = `question-${question.id}-${option}`;
        radioInput.value = option;

        const radioLabel = document.createElement('label');
        radioLabel.setAttribute('for', `question-${question.id}-${option}`);
        radioLabel.textContent = option;
        radioLabel.classList.add('hide-after');

        if (parseInt(question.required)) {
            radioInput.required = true;
        } else {
            label.classList.add('hide-after');
        }

        const sqField = document.createElement('div');
        sqField.classList.add('sq-field');

        sqField.appendChild(radioInput);
        sqField.appendChild(radioLabel);
        questionInput.appendChild(sqField);
    });

    return questionInput;
}