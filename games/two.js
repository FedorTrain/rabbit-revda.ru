var cvs = document.getElementById("canvas");
var ctx = cvs.getContext("2d");
ctx.fillStyle = "#FF0000";

var deg = { x : 0.0, y : 0, z : 0 }
var ctr = { x : 30, y : 30, z : 30 }

var xt = 70;
var yt = 50;

var p = [];
p.push({ x : 10, y : 10, z : 10 }); //1
p.push({ x : 10, y : 10, z : 50 }); //2
p.push({ x : 50, y : 10, z : 50 }); //3
p.push({ x : 50, y : 10, z : 10 }); //4
p.push({ x : 10, y : 50, z : 10 }); //5
p.push({ x : 10, y : 50, z : 50 }); //6
p.push({ x : 50, y : 50, z : 50 }); //7
p.push({ x : 50, y : 50, z : 10 }); //8

function cos(t) { return Math.cos(t); }
function sin(t) { return Math.sin(t); }

function x(i) {
  var _x = p[i].x - ctr.x;
  var _y = p[i].y - ctr.y;
  _x = _x*Math.cos(deg.z) - _y*Math.sin(deg.z);
  return (_x + ctr.x) + ctr.z / p[i].z;
}
function y(i) {
  var _x = p[i].x - ctr.x;
  var _y = p[i].y - ctr.y;
  _y = _x*Math.sin(deg.z) + _y*Math.cos(deg.z);
  return (_y + ctr.y) + ctr.z / p[i].z;
}

function draw() {
  deg.x+=0.01;
  deg.z+=0.01;
  ctx.beginPath();
  ctx.moveTo(x(0)+0.5, y(0)+0.5);
  ctx.lineTo(x(1)+0.5, y(1)+0.5);
  ctx.lineTo(x(2)+0.5, y(2)+0.5);
  ctx.lineTo(x(3)+0.5, y(3)+0.5);
  ctx.lineTo(x(0)+0.5, y(0)+0.5);
  ctx.lineTo(x(4)+0.5, y(4)+0.5);
  ctx.lineTo(x(5)+0.5, y(5)+0.5);
  ctx.lineTo(x(1)+0.5, y(1)+0.5);
  ctx.moveTo(x(2)+0.5, y(2)+0.5);
  ctx.lineTo(x(6)+0.5, y(6)+0.5);
  ctx.lineTo(x(7)+0.5, y(7)+0.5);
  ctx.lineTo(x(3)+0.5, y(3)+0.5);
  ctx.moveTo(x(7)+0.5, y(7)+0.5);
  ctx.lineTo(x(4)+0.5, y(4)+0.5);
  ctx.moveTo(x(5)+0.5, y(5)+0.5);
  ctx.lineTo(x(6)+0.5, y(6)+0.5);
  ctx.clearRect(0, 0, cvs.width, cvs.height);
  ctx.stroke();
}

function go() {
  draw();
  requestAnimationFrame(go);
}

go();
