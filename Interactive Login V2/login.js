let emailRef = document.querySelector(".email");
let passwordRef = document.querySelector(".password");
let eyeL = document.querySelector(".eyeball-l");
let eyeR = document.querySelector(".eyeball-r");
let handL = document.querySelector(".hand-l");
let handR = document.querySelector(".hand-r");

const showPwBtn = document.querySelector(".toggle-password");
const showPwIcon = showPwBtn.querySelector("i");
const submitBtn = document.querySelector(".submit-btn");


let normalEyeStyle = () => {
    eyeL.style.cssText = `
        left: 0.6em;
        top: 0.6em;
    `;
    eyeR.style.cssText = `
        right: 0.6em;
        top: 0.6em;
    `;
};

let normalHandStyle = () => {
    handL.style.cssText = `
        height: 2.81em;
        top: 8.4em;
        left: 7.5em;
        transform: rotate(0deg);
    `;
    handR.style.cssText = `
        height: 2.81em;
        top: 8.4em;
        right: 7.5em;
        transform: rotate(0deg);
    `;
};

//when clicked on email input
emailRef.addEventListener("focus", () => {
    eyeL.style.cssText = `
        left: 0.75em;
        top: 1.12em;
    `;
    eyeR.style.cssText = `
        right: 0.75em;
        top: 1.12em;
    `;
    normalHandStyle();
});

//when clicked on password input
passwordRef.addEventListener("focus", () => {
    handL.style.cssText = `
        height: 6.56em;
        top: 3.87em;
        left: 11.75em;
        transform: rotate(-155deg);
    `;
    handR.style.cssText = `
        height: 6.56em;
        top: 3.87em;
        right: 11.75em;
        transform: rotate(155deg);
    `;
    normalEyeStyle();
});

//when clicked outside email and password input
document.addEventListener("click", (e) => {
    let clickedElem = e.target;
    if((clickedElem != emailRef) && (clickedElem != passwordRef)) {
        normalEyeStyle();
        normalHandStyle();
    }
});

//show/hide password
showPwBtn.addEventListener("click", () => {
    passwordRef.type = passwordRef.type === 'password' ? 'text' : 'password'

    if(passwordRef.type === 'text') {
        showPwIcon.classList.remove("fa-solid"); showPwIcon.classList.remove("fa-eye-slash");
        showPwBtn.classList.add("fa-regular"); showPwBtn.classList.add("fa-eye");
    } else {
        showPwIcon.classList.add("fa-solid"); showPwIcon.classList.add("fa-eye-slash");
        showPwBtn.classList.remove("fa-regular"); showPwBtn.classList.remove("fa-eye");
    }
});

//submit button pressed
submitBtn.addEventListener("click", () => {
    passwordRef.type = 'password';
    showPwIcon.classList.add("fa-solid"); showPwIcon.classList.add("fa-eye-slash");
    showPwBtn.classList.remove("fa-regular"); showPwBtn.classList.remove("fa-eye");
});

//Moving images
var canvas = document.querySelector("canvas");
       

var drawingSurface = canvas.getContext("2d");
var spriteObject = {
    //this is a javascript object variable
    x: 0,
    y: 0,
    width: 125,
    height: 125
};
var spriteObject2 = {
    //this is a javascript object variable
    x: 0,
    y: 0,
    width: 125,
    height: 125
};

var turtle1 = Object.create(spriteObject);
turtle1.x = 175; turtle1.y = 25;

var turtle2 = Object.create(spriteObject2);
turtle2.x = 500; turtle2.y = 30;

var turtleImage1 = new Image();
turtleImage1.addEventListener("load", update, false);
turtleImage1.src = "images/turtle1.png";

var turtleImage2 = new Image();
turtleImage2.addEventListener("load", update, false);
turtleImage2.src = "images/turtle2.png";

var Yspeed1 = 0.2;
var Yspeed2 = -0.2;

function update() {
    window.requestAnimationFrame(update, canvas);

    turtle1.y += Yspeed1;
    turtle2.y += Yspeed2;

    if((turtle1.height + turtle1.y) > 175) {
        Yspeed1 = -0.2;
    } 
    else if((turtle1.height + turtle1.y) < 150) {
        Yspeed1 = 0.2;
    }

    if((turtle2.height + turtle2.y) > 175) {
        Yspeed2 = -0.2;
    } else if((turtle2.height + turtle2.y) < 130) {
        Yspeed2 = 0.2;
    }


    render();
}

function render() {
    drawingSurface.clearRect(0, 0, canvas.width, canvas.height);

    drawingSurface.drawImage (
        turtleImage1,
        Math.floor(turtle1.x), Math.floor(turtle1.y),
        turtle1.height, turtle1.width
    );
    drawingSurface.drawImage (
        turtleImage2,
        Math.floor(turtle2.x), Math.floor(turtle2.y),
        turtle2.height, turtle2.width
    );
}