const container = document.querySelector(".container");
const addQuestionCard = document.getElementById("add-question-card");
const questionCardHeader= document.querySelector("#add-question-card h2");
const cardButton = document.getElementById("save-btn");
const question = document.getElementById("question");
const answer = document.getElementById("answer");
const errorMessage = document.getElementById("error");
const addQuestion = document.getElementById("add-flashcard");
const closeBtn = document.getElementById("close-btn");
const yesDel = document.getElementById("yes");
const noDel = document.getElementById("no");
const delQuestionCard = document.getElementById("delete-question-card");
const emptyDisplay = document.querySelector(".empty-display");
const menu = document.querySelector('#mobile-menu'); /* or can be .navbar_toggle */
const menuLinks = document.querySelector('.navbar-menu');
const editFlashcard = document.querySelector(".edit_btn");
const quiz = document.querySelector(".quiz");
const showHideAll = document.querySelector(".show-hide-all");
const draggables = document.querySelectorAll(".card");
const droppable = document.querySelector(".card-list-container");
const numCards = document.querySelector(".num-cards");
let questionbf = ""; let answerbf = "";
let pageLoaded = false;
let editBool = false;
let delBool = false;
let oneAnswerShowing = false;
let dragBool = false;
var del = false;
var index = 0;
var oldIndex = -1; var newIndex = -1;
var flashcardVector = [];

//Header/Navbar script/////////////////////
let menuToggle = false;
menu.addEventListener('click', function() {
    menu.classList.toggle('is-active');
    menuLinks.classList.toggle('active');
    menuToggle = !menuToggle;
    disableButtons(menuToggle);
});
//^Header/Navbar script/////////////////////

//Edit flashcard list button////////////////
editFlashcard.addEventListener('click', toggleEditBtn = () => {
    if(flashcardVector.length === 0) { return; }
    //hide slide-down menu if selected using that method
    if(menuToggle) {
        menuToggle = false;
        menu.classList.toggle('is-active');
        menuLinks.classList.toggle('active');
    }

    //hide all answers
    if(oneAnswerShowing) { showHide(); }

    dragBool = !dragBool;
    disableButtons(dragBool); disableNavbar(dragBool);

    flashcardVector.forEach(function(item) {
        item.style.opacity = 1;
        item.children[4].classList.toggle("hide");

        if(dragBool) {
            item.setAttribute("draggable", true);
            item.style.cursor = 'grab';
        } else {
            item.setAttribute("draggable", false);
            item.style.cursor = 'default';
        }

        item.addEventListener("dragstart", () => {
            item.classList.add("is-dragging");
        });
        item.addEventListener("dragend", () => {
            item.classList.remove("is-dragging");
            //change indexes of vector when user stops dragging
            if((oldIndex !== -1) && (newIndex !== -1)) { changeIndexes(oldIndex, newIndex); }
        });
    });
});

droppable.addEventListener("dragover", (e) => {
    e.preventDefault();

    const afterElement = getDragAfterElement(droppable, e.clientX, e.clientY);
    const currElement = document.querySelector(".is-dragging");
    oldIndex = flashcardVector.indexOf(currElement);
    let afterIndex = flashcardVector.indexOf(afterElement);

    if(!afterElement) {
        droppable.appendChild(currElement);
        newIndex = flashcardVector.length - 1;
    } else {
        droppable.insertBefore(currElement, afterElement);

        if(oldIndex < afterIndex){
            newIndex = flashcardVector.indexOf(afterElement) - 1;
        } else {
            newIndex = flashcardVector.indexOf(afterElement);
        }
    }
});

function getDragAfterElement(container, mouseX, mouseY) {
    const draggableElements = [
      ...container.querySelectorAll(".card:not(.is-dragging)")
    ];
    //reduce method finds the closest draggable element based on certain conditions
    return draggableElements.reduce(
      (closest, child, index) => {
        const box = child.getBoundingClientRect();
        const nextBox = draggableElements[index + 1] && draggableElements[index + 1].getBoundingClientRect();
        const inRow = mouseY - box.bottom <= 0 && mouseY - box.top >= 0; // check if this is in the same row
        //positive when below an element, negative when above
        const offset = mouseX - (box.left + box.width / 2);
        if (inRow) {
          if (offset < 0 && offset > closest.offset) {
            return {
              offset: offset,
              element: child
            };
          } else {
            if ( // handle row ends, 
              nextBox && // there is a box after this one. 
              mouseY - nextBox.top <= 0 && // the next is in a new row
              closest.offset === Number.NEGATIVE_INFINITY // we didn't find a fit in the current row.
            ) {
              return {
                offset: 0,
                element: draggableElements[index + 1]
              };
            }
            return closest;
          }
        } else {
          return closest;
        }
      }, {
        //initial value of offset for the reduce method
         offset: Number.NEGATIVE_INFINITY
      }
    ).element;
  }

//^Edit flashcard list button///////////////

//Quiz button///////////////////////////////
   
quiz.addEventListener('click', (e) => {
    if(flashcardVector.length === 0) { return; }
    //restart quiz
    updateDb("restartQuiz", 0);
});
//^Quiz button//////////////////////////////

//Show/Hide All button//////////////////////
showHideAll.addEventListener('click', (showHide = () => {
    if(flashcardVector.length === 0) { return; }
    //hide slide-down menu if selected using that method
    if(menuToggle) {
        menuToggle = false;
        menu.classList.toggle('is-active');
        menuLinks.classList.toggle('active');
    }
    //prioritize hiding all over showing all
    if(oneAnswerShowing) {
        oneAnswerShowing = false;
        flashcardVector.forEach(function(item) {
            item.children[2].classList.add("hide");
        });
    } else {
        oneAnswerShowing = true;
        flashcardVector.forEach(function(item) {
            item.children[2].classList.remove("hide");
        });
    }
    disableButtons(false);
}));

//^Show/Hide All button/////////////////////

//Add flashcard//////////////////
// Add question when user clicks 'Add flashcard' button
addQuestion.addEventListener('click', () => {
    //hide slide-down menu if selected using that method
    if(menuToggle) {
        menuToggle = false;
        menu.classList.toggle('is-active');
        menuLinks.classList.toggle('active');
        disableButtons(false);
    }
    emptyDisplay.classList.add("hide");
    container.classList.add("hide");
    questionCardHeader.innerHTML = "Add Flashcard";
    question.value = "";
    answer.value = "";
    addQuestionCard.classList.remove("hide");
});

// Hide Create/edit flashcard Card when pressing the 'x'
closeBtn.addEventListener('click', (hideQuestion = () => {
    container.classList.remove("hide");
    addQuestionCard.classList.add("hide");
    if(editBool) {
        editBool = false;
        question.value = questionbf; answer.value = answerbf;
        submitQuestion();
    }
    if(flashcardVector.length === 0) {
        emptyDisplay.classList.remove("hide");
    } 
}));

// Submit Question
// when user presses save button on create/edit flashcard card
cardButton.addEventListener('click', (submitQuestion = () => {
    editBool = false;
    tempQuestion = question.value.trim();
    tempAnswer = answer.value.trim();
    //show error message if question or answer textarea is blank
    if(!tempQuestion || !tempAnswer) {
        errorMessage.classList.remove("hide");
    } else {
        container.classList.remove("hide");
        errorMessage.classList.add("hide");
        viewlist();
        // console.log("clearing");
        question.value = "";
        answer.value = "";
        // console.log("cleared");
    }
}));

//^Add flashcard////////////////////

//Delete flashcard//////////////////
yesDel.addEventListener('click', yDel = () => {
    delQuestionCard.classList.add("hide");
    numCards.innerHTML = flashcardVector.length;
    if(delBool) {
        delBool = false;
        disableButtons(false); disableNavbar(false);
    }
    if(flashcardVector.length === 0) {
        emptyDisplay.classList.remove("hide");
    }
});

noDel.addEventListener('click', () => {
    delQuestionCard.classList.add("hide");
    if(delBool) {
        delBool = false;
        submitQuestion();
    }
});

//^Delete flashcard//////////////////

// Card Generate
function viewlist() {
    var listCard = document.getElementsByClassName("card-list-container");
    var div = document.createElement("div");
    div.classList.add("card");
    //Question
    //add question to div
    div.innerHTML += `<p class="question-div">${question.value.trim()}</p>`;
    //Answer
    var displayAnswer = document.createElement("p");
    displayAnswer.classList.add("answer-div", "hide");
    displayAnswer.innerText = answer.value.trim();
    
    //Link to show/hide answers
    var link = document.createElement("button");
    link.setAttribute("class", "show-hide-btn");
    link.innerHTML = "Show/Hide";

    link.addEventListener("click", () => {
        displayAnswer.classList.toggle("hide");
        oneAnswerShowing = false;
        flashcardVector.forEach(function(item) {
            if(!item.children[2].classList.contains("hide")) {
                oneAnswerShowing = true;
            }
        });
    });

    //add show/hide button and answer to div
    div.appendChild(link);
    div.appendChild(displayAnswer);

    //Edit button
    let buttonsCon = document.createElement("div");
    buttonsCon.classList.add("buttons-con");
    var editButton = document.createElement("button");
    editButton.setAttribute("class", "edit");
    editButton.innerHTML = `<i class="fa-solid fa-pen-to-square"></i>`;

    editButton.addEventListener("click", () => {
        // container.classList.add("hide");
        questionCardHeader.innerHTML = "Edit Flashcard";
        editBool = true;
        index = flashcardVector.indexOf(div);
        console.log(flashcardVector[index]);
        console.log("index: " + index);
        updateDb("modifyCard", flashcardVector.indexOf(div));
        flashcardVector.splice(index, 1);
        modifyElement(editButton, true);
        addQuestionCard.classList.remove("hide");
    });

    buttonsCon.appendChild(editButton);

    //^Edit button

    //Delete Button
    var deleteButton = document.createElement("button");
    deleteButton.setAttribute("class", "delete");
    deleteButton.innerHTML = `<i class="fa-solid fa-trash-can"></i>`;

    deleteButton.addEventListener("click", function(event) {
        // container.classList.add("hide");
        delBool = true;
        index = flashcardVector.indexOf(div);
        console.log(flashcardVector[index]);
        console.log("index: " + index);
        updateDb("modifyCard", flashcardVector.indexOf(div));
        flashcardVector.splice(index, 1);
        //allows us to delete cards without warning message while in edit mode
        if(!dragBool) { delQuestionCard.classList.remove("hide"); }
        modifyElement(deleteButton, true);
    });

    buttonsCon.appendChild(deleteButton);

    //^Delete button

    disableButtons(false); disableNavbar(false);
    div.appendChild(buttonsCon);

    //draggable icon
    let bar_container = document.createElement("div");
    bar_container.setAttribute("id", "bar-container");
    bar_container.classList.add("hide");
    for(let i = 0; i < 3; i++) {
        let bar = document.createElement("span");
        bar.classList.add("flashcard-bar");
        bar_container.append(bar);
    }
    div.appendChild(bar_container);
    console.log(div);


    //add div to the flashcard
    listCard[0].appendChild(div);

    flashcardVector.push(div);
    // console.log("length of vector: " + flashcardVector.length);
    numCards.innerHTML = flashcardVector.length;
    if(flashcardVector.length !== 0) { emptyDisplay.classList.add("hide"); }

    if(pageLoaded) { updateDb("newCard", flashcardVector.indexOf(div)); }

    hideQuestion();
}

//Modify Elements
const modifyElement = (element, edit=false) => {
    let parentDiv = element.parentElement.parentElement;
    console.log(parentDiv);
    let parentQuestion = parentDiv.querySelector(".question-div").innerText;
    if(edit) {
        let parentAns = parentDiv.querySelector(".answer-div").innerText;
        question.value = parentQuestion; answer.value = parentAns;
        questionbf = question.value; answerbf = answer.value;
        disableNavbar(true); disableButtons(true);
    }
    //triggered when last card is deleted while in edit mode
    if(flashcardVector.length === 0 && dragBool) { 
        dragBool = false; 
        yDel(); 
    }

    parentDiv.remove();
};

//Disable edit and delete buttons when editing or deleting
const disableButtons = (value) => {
    let editButtons = document.getElementsByClassName("edit");
    let deleteButtons = document.getElementsByClassName("delete");
    let showHideButtons = document.getElementsByClassName("show-hide-btn");


    Array.from(editButtons).forEach((element) => {
        element.disabled = value;
    });
    Array.from(showHideButtons).forEach((element) => {
        element.disabled = value;
    });

    //allows us to delete cards without warning message while in edit mode
    if(dragBool) {
        Array.from(deleteButtons).forEach((element) => {
            element.disabled = false;
        });
        return;
    } else {
        Array.from(deleteButtons).forEach((element) => {
            element.disabled = value;
        });
    }

    if(value === true) {
        flashcardVector.forEach(function(item) {
            item.style.opacity = 0.4;
        });
    } else {
        flashcardVector.forEach(function(item) {
            item.style.opacity = 1;
        });
    }
};

const disableNavbar = (value) => {
    editFlashcard.disabled = value;
    quiz.disabled = value;
    showHideAll.disabled = value;
    addQuestion.disabled = value;

    if(value === true) {
        editFlashcard.style.opacity = 0.4;
        quiz.style.opacity = 0.4;
        showHideAll.style.opacity = 0.4;
        addQuestion.style.opacity = 0.4;
    } else {
        editFlashcard.style.opacity = 1;
        quiz.style.opacity = 1;
        showHideAll.style.opacity = 1;
        addQuestion.style.opacity = 1;
    }

    //allows us to delete cards without warning message while in edit mode
    if(dragBool) { 
        editFlashcard.disabled = false; 
        editFlashcard.style.opacity = 1;
    }
}

const updateDb = (keyword, index) => {
    // edit or delete flashcard
    let userQuestion = flashcardVector[index].children[0].innerHTML;
    let userAnswer = flashcardVector[index].children[2].innerHTML;
    userQuestion = userQuestion.replace(/([\\'"])/g, '\\$1');
    userAnswer = userAnswer.replace(/([\\'"])/g, '\\$1');
    $.ajax({
        type: "POST",
        url: "server.php",
        data: { 
            [keyword]: {
                question : userQuestion,
                answer : userAnswer
            }
        },
        success: function(response) {
            console.log(response);
        }
    });
}

const changeIndexes = (oldIndex, newIndex) => {
    $.ajax({
        type: "POST",
        url: "server.php",
        data: {
            changeIndexes: {
                oldIndex : oldIndex,
                newIndex : newIndex,
            }
        },
        success: function(response) {
            console.log(response);
        }
    });

    //move card to new index
    let card = flashcardVector[oldIndex];
    flashcardVector.splice(oldIndex, 1);
    flashcardVector.splice(newIndex, 0, card);
    // flashcardVector.forEach(function(item) { console.log(item); });
}

//if user resizes window bigger while mobile menu is active
window.addEventListener('resize', () => {
    if(window.innerWidth >= 960 && menuToggle) {
        menu.click();
    }
});

window.onload = function() {
    console.log("here");
    $.ajax({
        type: "GET",
        url: "server.php",
        data: { "requested" : "requested" },
        dataType: "json",
        success: function(response) {
            // console.log(response);

            var flashcard = response;

            flashcard.forEach(function(item) {
                question.value = item.question; 
                answer.value = item.answer;
                submitQuestion();
            });
            pageLoaded = true;
        }
    });
}
