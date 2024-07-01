export function requiredFunc(id) {
    const questionId = id.split("-")[0];
    const questionRequiredCheckbox = document.getElementById(id);
    const hiddenInput = document.querySelector(`input[name="${questionId}"]`);
    const hiddenInputOldValue = hiddenInput.value;

    // Split the old value by ';' not preceded by '\' to get an array of parts
    const parts = hiddenInputOldValue.split(/(?<!\\);/);

    // Update the third part of the array
    parts[2] = questionRequiredCheckbox.checked;

    // Join the array back into a string using ';'
    const newValue = parts.join(';');

    // Update the value of the hidden input
    hiddenInput.value = newValue;
}