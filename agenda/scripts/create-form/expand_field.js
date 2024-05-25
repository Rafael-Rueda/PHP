document.addEventListener("DOMContentLoaded", function () {
    const longFields = document.querySelectorAll('.long-field');
    function expandLongField() {
        this.style.height = '30px'; // Redefines the height to default
        this.style.height = (this.scrollHeight) + 'px'; // Defines the height based on the content
        this.parentNode.style.height = 150 + this.scrollHeight + 'px';
    }
    Array.from(longFields).forEach(longField => {
        longField.removeEventListener('input', expandLongField);
        longField.addEventListener('input', expandLongField);
    })
});

export function expandLongField() {
    const longFields = document.querySelectorAll('.long-field');
    function expandLongField() {
        this.style.height = '30px'; // Redefines the height to default
        this.style.height = (this.scrollHeight) + 'px'; // Defines the height based on the content
        this.parentNode.style.height = 150 + this.scrollHeight + 'px';
    }
    Array.from(longFields).forEach(longField => {
        longField.removeEventListener('input', expandLongField);
        longField.addEventListener('input', expandLongField);
    })
}