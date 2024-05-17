document.addEventListener("DOMContentLoaded", function () {
    const controls = document.querySelector('.controls');
    const fields = document.querySelectorAll('.create-field');
    const create_form = document.querySelector('.create-form');

    function fieldClicked(field) {

        // Set controls to the selected field
        let parentRect = create_form.getBoundingClientRect();
        let childRect = field.getBoundingClientRect();

        let scrollPos = childRect.top - parentRect.top + controls.scrollTop;
        controls.style.top = scrollPos + 'px';

        // Turns on the edit field mode
        
    }

    function checkTitleField(field, index) {
        const removeField = document.querySelector('.remove-field')
        if (index == 0) {
            removeField.style.display = 'none';
        } else {
            removeField.style.display = 'block';
        }
    }

    fieldClicked(fields[0]);

    Array.from(fields).forEach((field, index) => {
        field.addEventListener('click', () => {
            fieldClicked(field); 
            checkTitleField(field, index);
        });
    });
});
