document.addEventListener("DOMContentLoaded", function() { 
    const controls = document.querySelector('.controls');
    const fields = document.querySelectorAll('.create-field');
    const create_form = document.querySelector('.create-form');

    function fieldClicked(field) {
        let parentRect = create_form.getBoundingClientRect();
        let childRect = field.getBoundingClientRect();

        let scrollPos = childRect.top - parentRect.top + controls.scrollTop;
        controls.style.top = scrollPos + 'px';

        console.log(scrollPos);
    }

    fieldClicked(fields[0]);

    Array.from(fields).forEach(field => {
        field.addEventListener('click', () => fieldClicked(field));
    });
});
