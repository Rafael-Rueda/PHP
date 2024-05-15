document.addEventListener("DOMContentLoaded", function() {
    const longFields = document.querySelectorAll('.long-field');
    
    Array.from(longFields).forEach(longField => {
        
        longField.addEventListener('input', function() {
            this.style.height = '30px'; // Redefines the height to default
            this.style.height = (this.scrollHeight) + 'px'; // Defines the height based on the content
            this.parentNode.style.height = 150 + this.scrollHeight + 'px';
        });
    })
});