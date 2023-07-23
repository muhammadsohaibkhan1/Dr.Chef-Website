let circularProgressBar = document.querySelector('.circular-progress'),
    progressValue = document.querySelector(".progress-value");

let progressStartValue = 0,
    progressEndValue = 20,
    speed = 100;

let progress = setInterval(()=>{
    progressStartValue++;
    progressValue.textContent = `${progressStartValue}/1420 Cal.`;
    circularProgressBar.style.background = `conic-gradient(#3D251E, ${progressStartValue * 3.6}deg, #dececa 0deg)`
    if(progressStartValue == progressEndValue){
        clearInterval(progress);
    }
}, speed);
