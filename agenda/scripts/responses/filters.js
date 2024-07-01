export function loadFilteredData(data, formID) {
    // For debug purpouses
    console.log(data);

    // Clear all the answers
    document.querySelectorAll('.body').forEach((form_response) => {
        form_response.remove();
    })

    const answerContainer = document.querySelector('.answer');

    data.forEach((answer) => {
        answer.forEach((answer) => {
            const answerBody = document.createElement('div');
            answerBody.classList.add('body');

            const questionDiv = document.createElement('div');
            questionDiv.classList.add('question');
            const questionP = document.createElement('p');

            fetch('')

            questionP.textContent = answer.fk_questions_id;
            questionDiv.appendChild(questionP);

            const answerDiv = document.createElement('div');
            answerDiv.classList.add('answer');
            const answerP = document.createElement('p');
            answerP.textContent = answer.content;
            answerDiv.appendChild(answerP);

            answerBody.appendChild(questionDiv);
            answerBody.appendChild(answerDiv);

            // Adiciona o container principal ao container
            answerContainer.appendChild(answerBody);
        });
    });
}