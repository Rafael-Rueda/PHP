function shortFieldFunc(id) {

}

function longFieldFunc(id) {

}

function radioFieldFunc(id) {
    const parts = hiddenInputOldValue.split(/(?<!\\);/);
}

function selectFieldFunc(id) {

    
    const questionSelectType = document.getElementById(`${id}-select`);
    const hiddenInput = document.querySelector(`input[name="${id}"]`);
    const hiddenInputOldValue = hiddenInput.value;
    
    // Split the old value by ';' not preceded by '\' to get an array of parts
    const parts = hiddenInputOldValue.split(/(?<!\\);/);

    function pushFourthValue() {
        // Adds a fourth value to the hidden input csv values
        parts.push(``);
    };


    // Join the array back into a string using ';'
    const newValue = parts.join(';');

    // Update the value of the hidden input
    hiddenInput.value = newValue;
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