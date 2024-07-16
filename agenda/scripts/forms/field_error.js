export function createFieldError(input) {
    const errorDiv = document.createElement('div');
    errorDiv.classList.add('input-error');
    errorDiv.id = `input-error-${input.id}`
    input.parentNode.appendChild(errorDiv);
}

export function clearFieldErrors(input) {
    const inputError = input.parentNode.querySelector('.input-error');
    inputError.textContent = '';
}

export function checkFieldErrors() {
    const inputs = document.getElementsByClassName('input-error');
    for (let input of inputs) {
        if (input.textContent !== '') {
            return true;
        }
    }
    return false;
}

export function showFieldError(message, input) {
    input.parentNode.querySelector('.input-error').textContent = message;
    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
}