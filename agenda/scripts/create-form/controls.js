// IMPORTANT: analyze all the files to have a correct overview of the code
import { configType } from './form_config.js';
import { typeFunc } from './question_types.js';
import { nameFunc } from './question_name.js';
import { requiredFunc } from './question_required.js';
import { generateSecureRandomCode, onlyNumber, scrollToSmoothly } from '../../utils/utils.js'

document.addEventListener("DOMContentLoaded", function () {
    let atualField = 0;
    let atualHiddenInput = 0;
    const addButton = document.querySelector('.add-field');

    const controls = document.querySelector('.controls');
    let old_fields = document.querySelectorAll('.create-field');
    let oldHiddenInputs = [];
    const create_form = document.querySelector('.create-form');

    function fieldClicked(field, index) {
        let parentRect = create_form.getBoundingClientRect();
        let childRect = field.getBoundingClientRect();

        let scrollPos = childRect.top - parentRect.top + create_form.scrollTop;
        controls.style.top = scrollPos + 'px';
        controls.style.display = 'block';

        atualField = index;
        atualHiddenInput = index - 1;
    };

    function createFieldClickFunction(field, index) {
        return function () { fieldClicked(field, index); };
    };

    function removeFieldClickListeners() {
        const fields = document.querySelectorAll('.create-field');
        Array.from(fields).forEach((field, index) => {
            const listener = fieldClickListeners[index];
            if (listener) {
                field.removeEventListener('click', listener);
            }
        });
    };

    function createFieldClickListeners() {
        // Makes the fields clickable for the .controls add button move
        const fields = document.querySelectorAll('.create-field');
        Array.from(fields).forEach((field, index) => {
            const listener = createFieldClickFunction(field, index);
            fieldClickListeners[index] = listener;
            field.addEventListener('click', listener);
        });
        old_fields = fields;

        // For backend hidden inputs
        const hiddenInputFields = document.getElementById('create-form').querySelectorAll('input[type=hidden');

        oldHiddenInputs = hiddenInputFields;

        // Makes long fields expansible
        const longFields = document.querySelectorAll('.long-field');
        function expandLongField() {
            this.style.minHeight = '30px';
            this.style.minHeight = (this.scrollHeight) + 'px';
            this.parentNode.style.minHeight = 150 + this.scrollHeight + 'px';
        }
        Array.from(longFields).forEach(longField => {
            longField.removeEventListener('input', expandLongField);
            longField.addEventListener('input', expandLongField);
        });
    };

    function createFieldChangeListeners() {
        // Adds the event listener to watch the change of the type of the question
        const questions = document.querySelectorAll('.create-question');
        Array.from(questions).forEach((field, index) => {
            const selectElement = field.querySelector('select');
            const id = selectElement.id;

            // Remove existing event listener to prevent duplicate
            selectElement.removeEventListener('change', handleChangeListener);
            selectElement.addEventListener('change', handleChangeListener);

            function handleChangeListener() {
                typeFunc(id);
            }
        });

        // Adds the event listener to watch the change of the question text input
        Array.from(questions).forEach((field, index) => {
            const questionNameElement = field.querySelector('textarea');
            const id = questionNameElement.id;

            // Remove existing event listener to prevent duplicate
            questionNameElement.removeEventListener('input', handleNameChangeListener);
            questionNameElement.addEventListener('input', handleNameChangeListener);

            function handleNameChangeListener() {
                nameFunc(id);
            }
        });

        // Adds the event listener to watch the change of the required checkbox
        Array.from(questions).forEach((field, index) => {
            const questionCheckboxElement = field.querySelector('input[type=checkbox]');
            const id = questionCheckboxElement.id;

            // Remove existing event listener to prevent duplicate
            questionCheckboxElement.removeEventListener('change', handleCheckboxChangeListener);
            questionCheckboxElement.addEventListener('change', handleCheckboxChangeListener);

            function handleCheckboxChangeListener() {
                requiredFunc(id);
            }
        });
    }

    function createTitleChangeListeners() {
        const titleField = document.querySelector('.create-title');

        const createForm = document.getElementById('create-form');
        let hiddenInput = createForm.querySelector('input[name=new-form]');

        if (!hiddenInput) {
            const questionHiddenInput = document.createElement('input');
            questionHiddenInput.type = 'hidden';
            questionHiddenInput.name = 'new-form';
            questionHiddenInput.value = `${titleField.querySelector('#title').value};${titleField.querySelector('#description').value}`;

            if (document.querySelector('input[name=period')) {
                createForm.insertBefore(questionHiddenInput, document.querySelector('input=[name=period]'));
            } else {
                createForm.appendChild(questionHiddenInput);
            }
        }

        hiddenInput = createForm.querySelector('input[name=new-form]');

        function updateTitleHiddenInput(title, hiddenInput) {
            // For backend hidden inputs with values
            let appendTitle = title.trim().replace(/;/g, '\\;');
            hiddenInput.value = `${appendTitle};${titleField.querySelector('#description').value}`;
        }
        function updateDescriptionHiddenInput(description, hiddenInput) {
            // For backend hidden inputs with values
            let appendDescription = description.trim().replace(/;/g, '\\;');
            hiddenInput.value = `${titleField.querySelector('#title').value};${appendDescription}`;
        }

        titleField.querySelector('#title').addEventListener('input', (e) => {
            updateTitleHiddenInput(e.target.value, hiddenInput);
        });
        titleField.querySelector('#description').addEventListener('input', (e) => {
            updateDescriptionHiddenInput(e.target.value, hiddenInput);
        });
    }

    createTitleChangeListeners();

    const fieldClickListeners = [];
    createFieldClickListeners();

    addButton.addEventListener('click', () => {
        addQuestion();
    });

    function addQuestion() {
        // Visual output
        const randomCode = generateSecureRandomCode(32);
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
            { value: 'select-field', text: 'Campo de multipla escolha' },
            { value: 'cpf-field', text: 'Campo de CPF'}
        ];
        options.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option.value;
            optionElement.textContent = option.text;
            select.appendChild(optionElement);
        });

        const requiredDiv = document.createElement('div');
        requiredDiv.className = 'required';

        const checkboxLabel = document.createElement('label');
        checkboxLabel.setAttribute('for', 'required');
        checkboxLabel.textContent = 'Obrigatorio';
        requiredDiv.appendChild(checkboxLabel);

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'required';
        checkbox.id = `${randomCode}-required`;
        requiredDiv.appendChild(checkbox);

        const button = document.createElement('button');
        const buttonIcon = document.createElement('i');
        buttonIcon.className = 'fa-solid fa-trash';
        button.appendChild(buttonIcon);

        controlsDiv.appendChild(select);
        controlsDiv.appendChild(requiredDiv);
        controlsDiv.appendChild(button);
        questionDiv.appendChild(input);
        questionDiv.appendChild(controlsDiv);

        document.querySelector('.form').insertBefore(questionDiv, old_fields[atualField + 1]);

        // Configure the event listeners for the visual output
        removeFieldClickListeners();
        createFieldClickListeners();
        createFieldChangeListeners();

        document.getElementById(randomCode).querySelector('.question-controls button').addEventListener('click', () => {
            removeQuestion(randomCode);
        });

        fieldClicked(document.getElementById(randomCode), atualField + 1);

        const newQuestionTop = questionDiv.getBoundingClientRect().top + window.scrollY;
        scrollToSmoothly(newQuestionTop, 1000);

        // For backend hidden inputs with values
        const questionHiddenInput = document.createElement('input');
        questionHiddenInput.type = 'hidden';
        questionHiddenInput.name = randomCode;
        questionHiddenInput.value = `${input.value};${select.value};${checkbox.checked}`;

        const createForm = document.getElementById('create-form');
        // createForm.appendChild(questionHiddenInput);
        createForm.insertBefore(questionHiddenInput, oldHiddenInputs[atualHiddenInput + 1]);

        // For form-configs
        initializeOptions(questionHiddenInput);
    }

    function removeQuestion(id) {
        const questionElement = document.getElementById(id);
        if (questionElement) {
            // Removes the hidden input for the backend
            const hiddenInput = document.querySelector(`input[name="${id}"]`);
            if (hiddenInput) {
                hiddenInput.remove();
            }
            // Removes the question output element
            removeFieldClickListeners();

            questionElement.remove();
            old_fields = document.querySelectorAll('.create-field');

            createFieldClickListeners();

            if (old_fields.length > 0) {
                atualField = Math.max(atualField - 1, 0);
                fieldClicked(old_fields[atualField], atualField);
            } else {
                console.log('Hiding controls');
                controls.style.display = 'none';
            }

            // For form-configs
            initializeOptions(hiddenInput);

            // Remove the corresponding option in the period-verifier
            const periodVerifier = document.getElementById('period-verifier');
            const optionToRemove = periodVerifier.querySelector(`option[data-id="${id}"]`);
            if (optionToRemove) {
                optionToRemove.remove();
            }
        }
    }

    // Initial selection of the first field
    if (old_fields.length > 0) {
        fieldClicked(old_fields[0], 0);
    }

    // Form Config

    // Function to initialize options of form config based on existing hidden inputs
    const initializeOptions = (hiddenInput) => {
        const createForm = document.getElementById('create-form');
        const hiddenInputs = createForm.querySelectorAll('input[type="hidden"]:not([name="new-form"])');

        addOrUpdateOption(hiddenInput);
        observeHiddenInput(hiddenInput);
    }

    // Function to add or update an option in the select of form config
    const addOrUpdateOption = (hiddenInput) => {
        const periodVerifier = document.getElementById('period-verifier');
        const existingOption = periodVerifier.querySelector(`option[data-id="${hiddenInput.name}"]`);
        const value = hiddenInput.value.split(/(?<!\\);/)[0];

        if (existingOption) {
            existingOption.value = hiddenInput.name;
            existingOption.text = value;
        } else {
            const option = document.createElement('option');
            option.value = hiddenInput.name;
            option.text = value;
            option.dataset.id = hiddenInput.name;
            periodVerifier.add(option);
        }

        document.getElementById('period-verifier').setAttribute('value', value);
    }

    // Function to observe hidden input changes using MutationObserver
    const observeHiddenInput = (hiddenInput) => {
        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                    addOrUpdateOption(hiddenInput);
                }
            });
        });

        observer.observe(hiddenInput, { attributes: true, attributeFilter: ['value'] });
    }

    function createConfigChangeListeners() {
        const createForm = document.getElementById('create-form');
        const configInputs = document.querySelectorAll('.config-input');

        // Create this for every new config input >
        const periodConfigInputs = document.querySelectorAll('.config-period');
        const configHiddeninput = document.createElement('input');
        configHiddeninput.type = 'hidden';
        configHiddeninput.name = 'period';
        Array.from(periodConfigInputs).forEach((input) => {
            configHiddeninput.value += `${input.value};`;
        });
        createForm.appendChild(configHiddeninput);
        // < Create this for every new config input

        const configObserver = new MutationObserver((mutations) => {
            mutations.forEach(mutation => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                    configType(mutation.target);
                }
            });
        });

        Array.from(configInputs).forEach((input) => {
            // Ensure input has a value attribute to observe
            if (!input.hasAttribute('value')) {
                input.setAttribute('value', input.value);
            }

            configObserver.observe(input, { attributes: true, attributeFilter: ['value'] });

            // Update value attribute on input change to trigger MutationObserver
            input.addEventListener('input', (event) => {
                event.target.setAttribute('value', event.target.value);
            });
        });
    }

    createConfigChangeListeners();

    // Allows user to type only number values in the form options period-value input
    onlyNumber(document.getElementById('period-value'));
});
