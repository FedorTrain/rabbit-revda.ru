var cvs = document.getElementById("canvas");
var ctx = cvs.getContext("2d");

var bird = new Image();
var bg = new Image();
var fg = new Image();
var pipeUp = new Image();
var pipeBottom = new Image();

bird.src = "img/bird.png";
bg.src = "img/bg.png";
fg.src = "img/fg.png";
pipeUp.src = "img/pipeUp.png";
pipeBottom.src = "img/pipeBottom.png";

// Звуковые файлы
var fly = new Audio();
var score_audio = new Audio();

fly.src = "audio/fly.mp3";
score_audio.src = "audio/score.mp3";

var gap = 90;

// При нажатии на какую-либо кнопку
document.addEventListener("keydown", moveUp);
document.addEventListener("touchstart", moveUp);

function moveUp() {
   if (yPos == cvs.height - fg.height) {
     v = 100;
     fly.play();
   }
}

// Создание блоков
var pipe = [];

pipe[0] = {

  x : cvs.width,
  y : 0
}

var score = 0;
// Позиция птички
var xPos = 10;
var yPos = 150;
var g = 40;
var t = 0.07;
var v = 0;
var game = true;
var speed = 2;

function draw() {
 ctx.drawImage(bg, 0, 0);

 for(var i = 0; i < pipe.length; i++) {
   ctx.drawImage(pipeBottom, pipe[i].x, pipe[i].y + 50);

   if (game) {
     pipe[i].x -= 1;
   }
   if(pipe[i].x == 125) {

     pipe.push({
       x : cvs.width,
       y : 0
     });
   }

   // Отслеживание прикосновений
   if(xPos + bird.width >= pipe[i].x && xPos <= pipe[i].x + pipeUp.width) {
    // game = false; // Перезагрузка страницы
   }

   if(pipe[i].x == 5) {
     score++;
     score_audio.play();
   }
 }

 ctx.drawImage(fg, 0, cvs.height - fg.height + bird.height);
 ctx.drawImage(bird, xPos, yPos);

 v = v - g * t;
 s = v * t - g * t*t / 2;
 yPos -= s;
 if (yPos > cvs.height - fg.height){
  yPos = cvs.height - fg.height;
 }
 ctx.fillStyle = "#000";
 ctx.font = "24px Verdana";
 ctx.fillText("Счет: " + score, 10, cvs.height - 20);

 requestAnimationFrame(draw);
}

pipeBottom.onload = draw;
