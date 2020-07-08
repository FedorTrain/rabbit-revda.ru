var cvs = document.getElementById("canvas");
var ctx = cvs.getContext("2d");

var red = [];

var green = [];

var tts = 0;

function rand(max) {
  return Math.floor(Math.random() * Math.floor(max));
}

function start() {
  for (var i = 0; i < 60; i++) {
    green.push( {
      x : rand(cvs.width),
      y : rand(cvs.height)
    } );
  }
  red.push( {
    x : rand(cvs.width),
    y : rand(cvs.height),
    size : 0
  } );
}

var clrgreen = "#00FF00";

function draw() {
  ctx.clearRect(0, 0, cvs.width, cvs.height);
  for (var i = 0; i < green.length; i++) {
    ctx.fillStyle = clrgreen;
    ctx.beginPath();
    ctx.arc(green[i].x, green[i].y, 4, 0, Math.PI*2, true);
    ctx.fill();
  }
  for (var i = 0; i < red.length; i++) {
    ctx.fillStyle = "#FF0000";
    ctx.beginPath();
    ctx.arc(red[i].x, red[i].y, 5 + red[i].size * 0.2, 0, Math.PI*2, true);
    ctx.fill();
  }
}

function update(){
  tts++;
  if (tts >= 60 - red.length * 2) {
    tts = 0;
    green.push( {
      x : rand(cvs.width),
      y : rand(cvs.height)
    } );
  }


  for (var i = 0; i < red.length; i++) {
    var loP = (cvs.width + cvs.height) * (cvs.width + cvs.height);
    var id = 0;
    for (var j = 0; j < green.length; j++) {
      var dx = red[i].x - green[j].x;
      var dy = red[i].y - green[j].y;
      if (dx * dx + dy * dy < loP) {
        loP = dx * dx + dy * dy;
        id = j;
      }
    }
    if (red[i].x < green[id].x) { red[i].x++; }
    if (red[i].x > green[id].x) { red[i].x--; }
    if (red[i].y < green[id].y) { red[i].y++; }
    if (red[i].y > green[id].y) { red[i].y--; }
    if (loP < 5 + red[i].size * 0.2) {
      green.splice(id, 1);
      red[i].size++;
    }
    if (red[i].size >= 10) {
      red[i].size = 0;
      red.push( {
        x : rand(cvs.width),
        y : rand(cvs.height),
        size : 0
      } );
    }
  }
  for (var i = 0; i < red.length && red.length > 1; i++) {
    var loP = (cvs.width + cvs.height) * (cvs.width + cvs.height);
    var id = 0;
    for (var j = 0; j < red.length; j++) {
      var dx = red[i].x - red[j].x;
      var dy = red[i].y - red[j].y;
      if (dx * dx + dy * dy < loP && i != j) {
        loP = dx * dx + dy * dy;
        id = j;
      }
    }
    if (loP < 64) {
      if (red[i].x < red[id].x) { red[i].x-=2; }
      if (red[i].x > red[id].x) { red[i].x+=2; }
      if (red[i].y < red[id].y) { red[i].y-=2; }
      if (red[i].y > red[id].y) { red[i].y+=2; }
    }
  }

  for (var i = 0; i < green.length; i++) {
    var loP = (cvs.width + cvs.height) * (cvs.width + cvs.height);
    var id = 0;
    for (var j = 0; j < red.length; j++) {
      var dx = red[j].x - green[i].x;
      var dy = red[j].y - green[i].y;
      if (dx * dx + dy * dy < loP) {
        loP = dx * dx + dy * dy;
        id = j;
      }
    }
    if (loP < 1600) {
      if (red[id].x < green[i].x && green[i].x < cvs.width)  { green[i].x+=0.8; }
      if (red[id].x > green[i].x && green[i].x > 0)          { green[i].x-=0.8; }
      if (red[id].y < green[i].y && green[i].y < cvs.height) { green[i].y+=0.8; }
      if (red[id].y > green[i].y && green[i].y > 0)          { green[i].y-=0.8; }
    }
  }
}

function loop() {
  update();
  draw();
  requestAnimationFrame(loop);
}

start();
loop();
