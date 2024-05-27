export function longField(question, label) {
    let questionInput = null;
    questionInput = document.createElement('textarea');
    questionInput.classList.add('long-field');
    questionInput.id = `question-${question.id}`;
    questionInput.name = `question-${question.id}`;
    questionInput.placeholder = 'Sua resposta';
    if (question.required) {
        questionInput.required = true;
    } else {
        label.classList.add('hide-after');
    }

    return questionInput;
}