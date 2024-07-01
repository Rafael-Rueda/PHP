export function shortField(question, label) {
    let questionInput = null;
    questionInput = document.createElement('input');
    questionInput.classList.add('short-field');
    questionInput.type = 'text';
    questionInput.id = `question-${question.id}`;
    questionInput.name = `question-${question.id}`;
    questionInput.placeholder = 'Sua resposta';
    if (parseInt(question.required)) {
        questionInput.required = true;
    } else {
        label.classList.add('hide-after');
    }
    
    return questionInput;
}