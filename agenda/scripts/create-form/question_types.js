import { createElement } from '../../utils/utils.js';

function updateHiddenInput(questionId, partIndex, newValue) {
    const hiddenInput = document.querySelector(`input[name="${questionId}"]`);
    const hiddenInputOldValue = hiddenInput.value;

    // Split the old value by ';' not preceded by '\' to get an array of parts
    const parts = hiddenInputOldValue.split(/(?<!\\);/);

    // Update the specified part of the array
    if (!(partIndex in parts)) {
        parts.push(newValue);
    } else {
        parts[partIndex] = newValue;
    }

    if (!newValue) {
        parts.pop();
    }

    // Join the array back into a string using ';'
    const updatedValue = parts.join(';');

    // Update the value of the hidden input
    hiddenInput.value = updatedValue;
}

function updateAllOptions(id, contentDiv) {
    const allOptions = Array.from(contentDiv.querySelectorAll(`.optionInput-${id}`))
        .map(opt => opt.value.replace(/;/g, '\\;').replace(/-/g, '\\-'))
        .join('-');
    updateHiddenInput(id, 3, allOptions);
}

function clearQuestionContent(questionId) {
    const questionDiv = document.getElementById(questionId);
    let contentDiv = questionDiv.querySelector('.question-content');
    if (contentDiv) {
        contentDiv.remove();
    }
    contentDiv = createElement('div', { class: 'question-content' });
    questionDiv.appendChild(contentDiv);
    return contentDiv;
}

function showRequiredOption(questionId) {
    const questionDiv = document.getElementById(questionId);
    const requiredDiv = questionDiv.querySelector('.required');
    if (requiredDiv) {
        requiredDiv.style.display = 'flex';
    }
}

function shortFieldFunc(id) {
    // Output
    clearQuestionContent(id);
    updateHiddenInput(id, 3, '');
}

function longFieldFunc(id) {
    // Output
    clearQuestionContent(id);
    updateHiddenInput(id, 3, '');
}

function radioFieldFunc(id) {
    // Output
    clearQuestionContent(id);
    updateHiddenInput(id, 3, '');
    const questionDiv = document.getElementById(id);
    const contentDiv = questionDiv.querySelector(`.question-content`);
    const addButton = createElement('button', {}, document.createTextNode('Adicionar opção'));
    addButton.addEventListener('click', () => {
        const optionInput = createElement('input', { type: 'text', placeholder: 'Opção', class: `optionInput-${id}` });
        const button = createElement('button', {}, createElement('i', { class: 'fa-solid fa-trash' }));
        const optionDiv = createElement('div', {}, optionInput, button);
        contentDiv.insertBefore(optionDiv, addButton);

        optionInput.addEventListener('input', () => {
            updateAllOptions(id, questionDiv);
        });

        button.addEventListener('click', () => {
            optionDiv.remove();
            updateAllOptions(id, questionDiv);
        });
        updateAllOptions(id, questionDiv);
    });
    contentDiv.appendChild(addButton);
}

function selectFieldFunc(id) {
    // Hide the required option
    const questionDiv = document.getElementById(id);
    const requiredDiv = questionDiv.querySelector('.required');
    if (requiredDiv) {
        requiredDiv.style.display = 'none';
        updateHiddenInput(id, 2, 'false');
        const checkbox = requiredDiv.querySelector('input[type=checkbox]');
        if (checkbox) {
            checkbox.checked = false; // Ensure the checkbox is unchecked
        }
    }
    
    // Output
    clearQuestionContent(id);
    updateHiddenInput(id, 3, '');
    const contentDiv = questionDiv.querySelector(`.question-content`);
    const addButton = createElement('button', {}, document.createTextNode('Adicionar opção'));
    addButton.addEventListener('click', () => {
        const optionInput = createElement('input', { type: 'text', placeholder: 'Opção', class: `optionInput-${id}` });
        const button = createElement('button', {}, createElement('i', { class: 'fa-solid fa-trash' }));
        const optionDiv = createElement('div', {}, optionInput, button);
        contentDiv.insertBefore(optionDiv, addButton);

        optionInput.addEventListener('input', () => {
            updateAllOptions(id, questionDiv);
        });

        button.addEventListener('click', () => {
            optionDiv.remove();
            updateAllOptions(id, questionDiv);
        });
        updateAllOptions(id, questionDiv);
    });
    contentDiv.appendChild(addButton);
}

export function typeFunc(outputId) {
    const questionId = outputId.split("-")[0];
    const questionSelectType = document.getElementById(outputId);
    const hiddenInput = document.querySelector(`input[name="${questionId}"]`);
    const hiddenInputOldValue = hiddenInput.value;

    // Split the old value by ';' not preceded by '\' to get an array of parts
    const parts = hiddenInputOldValue.split(/(?<!\\);/);

    // Update the second part of the array
    parts[1] = questionSelectType.value;

    // Join the array back into a string using ';'
    const newValue = parts.join(';');

    // Update the value of the hidden input
    hiddenInput.value = newValue;

    // Show the required option by default
    showRequiredOption(questionId);

    switch (questionSelectType.value) {
        case 'short-field':
            shortFieldFunc(questionId);
            break;
        case 'long-field':
            longFieldFunc(questionId);
            break;
        case 'radio-field':
            radioFieldFunc(questionId);
            break;
        case 'select-field':
            selectFieldFunc(questionId);
            break;
        default:
            break;
    }
}