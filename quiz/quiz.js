const progressBarFull = document.querySelector('.progress-bar-full');
const progressPercent = document.querySelector('.progress-percent');
const numCorrect = document.querySelector('.score');
const percentCorrect = document.getElementById('percentCorrect');
const sendAnswer = document.querySelector('.sendAnswer');
const question = document.getElementById('question');
const userAnswer = document.getElementById('answer');
const label = document.querySelector('span');
const finishedQuiz = document.querySelector('.finishedQuiz');
const restartQuiz = document.querySelector('.restartQuiz');
const hiddenMickey = document.querySelector('img');
const feedbackContainer = document.querySelector('.feedback-container');
const correctContainer = document.querySelector('.correct-container');
const incorrectContainer = document.querySelector('.incorrect-container');
var flashcardVector = [];
var usedIndexes = [];
let NUM_QUESTIONS;
let questionsIndex;
let mode;
let correctCounter = 0;
let questionsAnswered = 0;
let acceptingAnswers = false;

// lower font when h1 is at 200 characters
function playGame() {
    numCorrect.innerText = `${correctCounter}/${questionsAnswered}`;
    let percentageDone = `${(questionsAnswered/NUM_QUESTIONS) * 100}%`;
    progressBarFull.style.width = percentageDone;
    progressPercent.innerText = `${parseFloat(percentageDone).toFixed(2)}%`;

    //only start game if mode is quiz (not quiz_end);
    if(!mode) { 
        getNewQuestion(); 
    } else {
        percentCorrect.innerText = `${((correctCounter/questionsAnswered) * 100).toFixed(2)}%`;
    }
}

sendAnswer.addEventListener('click', checkAnswer = () => {
    //answers not case sensitive
    let answer = userAnswer.value.trim().toUpperCase();
    if(!acceptingAnswers || (answer === '')) { return; }
    acceptingAnswers = false;
    questionsAnswered++;
    flashcardVector[questionsIndex].qanswered = true;

    let fcardAnswer = flashcardVector[questionsIndex].answer.toUpperCase();

    let classToApply = (answer == fcardAnswer) ? 'correct' : 'incorrect';
    userAnswer.classList.add(classToApply);
    label.classList.add(classToApply + "Span");
    label.innerText = classToApply.toUpperCase();

    userAnswer.disabled = true; sendAnswer.disabled = true;

    if(answer === fcardAnswer) {
        correctCounter++;
        flashcardVector[questionsIndex].correct = true;
    }

    updateDb("quizAnswer", questionsIndex);

    //reset everything after timeout
    setTimeout(() => {
        userAnswer.disabled = false; sendAnswer.disabled = false;
        userAnswer.classList.remove(classToApply);
        userAnswer.value = "";
        label.classList.remove(classToApply + "Span");
        label.innerText = "ANSWER";
        playGame();
    }, 1000);
});

document.addEventListener('keydown', (e) => {
    //user presses enter when focused on the input field
    if((e.key === 'Enter') && (document.activeElement.tagName === 'INPUT')) {
        checkAnswer();
        console.log(userAnswer.value);
    }
});

function getNewQuestion() {

    questionsIndex = generateRandomIndex();
    if(questionsIndex === null) { finishedQuiz.click(); }

    question.innerText = flashcardVector[questionsIndex].question;
    if(question.innerText.length > 160) {
        question.style.fontSize = "1.6em";
    } else {
        question.style.fontSize = "2.6em";
    }
    acceptingAnswers = true;
    // console.log(questionsIndex);
}

//gets a random unique index
function generateRandomIndex() {
    let newIndex = Math.floor(Math.random() * NUM_QUESTIONS);
    while(usedIndexes.includes(newIndex)) {
        if(usedIndexes.length === NUM_QUESTIONS) { return null; }
        newIndex = Math.floor(Math.random() * NUM_QUESTIONS);
    }
    usedIndexes.push(newIndex);

    return newIndex;
}

restartQuiz.addEventListener('click', () => {
    updateDb("restartQuiz", 0);
})

const updateDb = (keyword, index) => {
    // update correct and qanswered or reset quiz
    $.ajax({
        type: "POST",
        url: "../server.php",
        data: { 
            [keyword]: {
                question : flashcardVector[index].question,
                answer : flashcardVector[index].answer,
                correct : flashcardVector[index].correct,
                qanswered : flashcardVector[index].qanswered
            }
        },
        success: function(response) {
            console.log(response);
        }
    });
}


window.onload = function() {
    $.ajax({
        type: "GET",
        url: "../server.php",
        data: { "requested" : "requested" },
        dataType: "json",
        success: function(response) {
            var flashcard = response;

            flashcard.forEach(function(item) {
                let card = {
                    question: item.question,
                    answer: item.answer,
                    correct: item.correct,
                    qanswered: item.qanswered
                };
                flashcardVector.push(card);
                if(card.correct == true) { correctCounter++; }
                if(card.qanswered == true) { 
                    questionsAnswered++;
                    usedIndexes.push(flashcardVector.indexOf(card));
                }
            });
            // console.log(flashcardVector);
            NUM_QUESTIONS = flashcardVector.length;

            flashcardVector.forEach(function(card) {
                //add cards to correct or incorrect container
                console.log("here");
                let div = document.createElement("div");
                div.classList.add("qcard");
                div.innerHTML += `<p class="question" style="font-weight: 600">Question: </p>`;
                div.innerHTML += `<p class="quiz-question">${card.question}</p>`;
                div.innerHTML += `<p class="answer" style="font-weight: 600">Answer: </p>`;
                div.innerHTML += `<p class="quiz-answer">${card.answer}</p><br><br>`;

                console.log(div);
                if(card.qanswered == 1) {
                    if(card.correct == 1) {
                        correctContainer.appendChild(div);
                        console.log(correctContainer);
                    } else {
                        incorrectContainer.appendChild(div);
                        console.log(correctContainer);
                    }
                }
            });

            mode = window.location.href.includes("mode=quiz_end");

            if(NUM_QUESTIONS === 0) { 
                hiddenMickey.classList.remove("hide");
                feedbackContainer.classList.add("hide");
                restartQuiz.disabled = true;
            } else {
                hiddenMickey.classList.add("hide");
                if(mode) { feedbackContainer.classList.remove("hide"); }
                restartQuiz.disabled = false;
            }

            //end game if there is no questions
            if((NUM_QUESTIONS === questionsAnswered) && !mode) { finishedQuiz.click(); }
            playGame();
        }
    }); 
};