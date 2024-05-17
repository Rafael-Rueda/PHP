document.addEventListener("DOMContentLoaded", function () {
    function generateRandomCode() {
        return Math.random().toString(36).slice(2, 10).toUpperCase();
    }

    let atualField = 0;
    const addButton = document.querySelector('.add-field');

    const controls = document.querySelector('.controls');
    let old_fields = document.querySelectorAll('.create-field');
    const create_form = document.querySelector('.create-form');

    // Functions that create/remove event listeners for fields
    function fieldClicked(field, index) {

        // Set controls to the selected field
        let parentRect = create_form.getBoundingClientRect();
        let childRect = field.getBoundingClientRect();

        let scrollPos = childRect.top - parentRect.top + controls.scrollTop;
        controls.style.top = scrollPos + 'px';

        atualField = index;

    }

    fieldClicked(old_fields[0], 0);

    const fieldClickListeners = [];

    function createFieldClickFunction(field, index) {
        return function () { fieldClicked(field, index) };
    };

    function removeFieldClickListeners() {
        const fields = document.querySelectorAll('.create-field');
        Array.from(fields).forEach((field, index) => {
            const listener = fieldClickListeners[index];
            if (listener) {
                field.removeEventListener('click', listener);
            }
        });
    }

    function createFieldClickListeners() {
        const fields = document.querySelectorAll('.create-field');
        Array.from(fields).forEach((field, index) => {
            const listener = createFieldClickFunction(field, index);
            fieldClickListeners[index] = listener;
            field.addEventListener('click', listener);
        });
        old_fields = fields;
    }

    createFieldClickListeners();

    // Controls event listeners
    addButton.addEventListener('click', () => {
        addQuestion();
    });

    function addQuestion() {
        const randomCode = generateRandomCode();
        const questionDiv = document.createElement('div');
        questionDiv.id = randomCode;
        questionDiv.className = 'create-question create-field';

        const input = document.createElement('textarea');
        input.placeholder = 'Sua pergunta';
        input.id = `${randomCode}-input`;
        input.className = 'long-field';

        const controlsDiv = document.createElement('div');
        controlsDiv.className = 'question-controls';

        const select = document.createElement('select');
        select.id = `${randomCode}-select`;
        const options = [
            { value: 'short-field', text: 'Campo de texto curto' },
            { value: 'long-field', text: 'Campo de texto longo' },
            { value: 'radio-field', text: 'Campo de escolha' },
            { value: 'select-field', text: 'Campo de multipla escolha' }
        ];
        options.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option.value;
            optionElement.textContent = option.text;
            select.appendChild(optionElement);
        });

        const button = document.createElement('button');
        button.textContent = '(U)';

        // Mount the structure
        controlsDiv.appendChild(select);
        controlsDiv.appendChild(button);
        questionDiv.appendChild(input);
        questionDiv.appendChild(controlsDiv);

        document.querySelector('.form').insertBefore(questionDiv, old_fields[atualField + 1]);

        removeFieldClickListeners();
        createFieldClickListeners();
    }
});
