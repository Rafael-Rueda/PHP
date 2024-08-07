export function createFieldError(input) {
    const errorDiv = document.createElement('div');
    errorDiv.classList.add('input-error');
    errorDiv.id = `input-error-${input.id}`
    input.parentNode.appendChild(errorDiv);
}

export function clearFieldErrors(input) {
    const inputError = input.parentNode.querySelector('.input-error');
    inputError.textContent = '';
    inputError.style.display = 'none';
}

export function checkFieldErrors() {
    const inputs = document.getElementsByClassName('input-error');
    let hasError = false;
    for (let input of inputs) {
        if (input.textContent !== '') {
            hasError = true;
        }
    }
    if (hasError) {
        return true;
    }
    return false;
}

export function showFieldError(message, input) {
    const inputError = input.parentNode.querySelector('.input-error')
    inputError.textContent = message;
    inputError.style.display = 'block';
    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
}