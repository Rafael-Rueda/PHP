function updateHiddenInput(formConfig, partIndex, newValue) {
    const hiddenInput = document.querySelector(`input[name="${formConfig}"]`);
    const hiddenInputOldValue = hiddenInput.value;

    // Split the old value by ';' not preceded by '\' to get an array of parts
    const parts = hiddenInputOldValue.split(/(?<!\\);/);

    // Update the specified part of the array
    if (!(partIndex in parts)) {
        parts.push(newValue);
    } else {
        parts[partIndex] = newValue;
    }

    // Join the array back into a string using ';'
    const updatedValue = parts.join(';');

    // Update the value of the hidden input
    hiddenInput.value = updatedValue;
}

function periodUnitType(input) {
    updateHiddenInput('period', 0, input.value);
}

function periodValueType(input) {
    updateHiddenInput('period', 1, input.value);
}

function periodVerifierType(input) {
    updateHiddenInput('period', 2, input.value);
}

export function configType(input) {
    switch (input.name) {
        case 'period-unit':
            periodUnitType(input);
            break;
        case 'period-value':
            periodValueType(input);
            break;
        case 'period-verifier':
            periodVerifierType(input);
            break;
    }
}