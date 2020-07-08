function rand(max) {
  return Math.floor(Math.random() * Math.floor(max));
}
function div(val, by) {
  return (val - val % by) / by;
}
function cpr(arr1,arr2) {
  if (arr1.length == arr2.length){
    for (var i = 0; i < arr1.length; i++) {
      if (arr1[i] != arr2[i]) {
        return false;
      }
    }
  } else {
    return false;
  }
  return true;
}
function m(i,j) {
  console.log("s-",mstrs[i].speak,"w-",mstrs[i].with,"f-",mstrs[i].first,"wr-",mstrs[i].word)
  console.log("wt -",mstrs[i].wordTime); i = j;
  console.log("s-",mstrs[i].speak,"w-",mstrs[i].with,"f-",mstrs[i].first,"wr-",mstrs[i].word)
  console.log("wt -",mstrs[i].wordTime);
}
var kkk = [];
function sp() {
  kkk = [];
  for (var i = 0; i < mstrs.length; i++) {
    if (mstrs[i].speak) {
      kkk[kkk.length] = i;
    }
  }
  console.log(kkk);
}
function chek() {
  var num = 0;
  for (var i = 0; i < mstrs.length; i++) {
    if (mstrs[i].speak) num++;
  }
  return num;
}
function delWrong() {
  for (var i = 0; i < mstrs.length; i++) {
    if (mstrs[i].speak && mstrs[i].with != 100) {
      if (!mstrs[mstrs[i].with].speak){
        mstrs[i].speak = false;
        mstrs[i].word = [0,0];
        mstrs[i].with = -1;
      }
    }
  }
}
function end() {
  z = false;
  console.log("Win");
  alert("Win");
}

var alfbImg = new Image();
var playerImg = new Image();
var landImg = new Image();
var planteaImg = new Image();
var poopaImg = new Image();
var maplogo = new Image();
var mapImg = new Image();
var menusp = new Image();
alfbImg.src = "img/alfb.png";
playerImg.src = "img/player.png";
planteaImg.src = "img/plantea.png";
poopaImg.src = "img/poopa.png";
maplogo.src = "img/maplogo.png";
mapImg.src = "img/map.png";
menusp.src = "img/menuspeak.png";

{//sound
var soundPizd = new Audio();
soundPizd.src = "audio/BerlinistDescent.mp3";
soundPizd.addEventListener ("canplaythrough", event => {
  soundPizd.play ();
});
}

var MS = 2;
var numWord = 11;
var PS = 4;
var WT = 60 * 2;
var seeMap = false;

// alfb
var alfb = [
  [1,2,3],
  [4,5,6],
  [2,3,8],
  [5,6,8],
  [1,4,7],
  [3,5,8],
  [2,4,5],
  [5,6,7],
  [2,3,7],
  [3,5,7],
  [2,6,7]
]

// MAP
{
var map_biome = [];
for (var i = 0; i < 10; i++) {
    map_biome[i] = [];
}
for (var i = 0; i < 10; i++) {
  for (var j = 0; j < 10; j++) {
    map_biome[i][j] = rand(4)+1;
    if (j > 7 || i > 7) {map_biome[i][j] = 2};
  }
}

var map = []; var map_x = 0; var map_y = 0;
for (var i = 0; i < 70; i++) {
    map[i] = [];
}
function createMapPiz() {
  for (var i = 0; i < 64; i++) {
    for (var j = 0; j < 64; j++) {
      map[i][j] = rand(5);
      if (map[i][j] > 1) {
        map[i][j] = rand(3);
      }
    }
  }
  for (var i = 0; i < 64; i++) {
    for (var j = 0; j < 64; j++) {
      if (i <= 1 || i >= 62 || j <= 1 || j >= 62) {
        map[i][j] = 5;
      } else {
        if (map[i][j] != 1) {
          if (!(i-1 <= 1 || i-1 >= 62 || j-1 <= 1 || j-1 >= 62)) {map[i-1][j-1] = 0;}
          if (!(i-1 <= 1 || i-1 >= 62 || j   <= 1 || j   >= 62)) {map[i-1][j]   = 0;}
          if (!(i-1 <= 1 || i-1 >= 62 || j+1 <= 1 || j+1 >= 62)) {map[i-1][j+1] = 0;}
          if (!(i   <= 1 || i   >= 62 || j-1 <= 1 || j-1 >= 62)) {map[i][j-1]   = 0;}
          if (!(i   <= 1 || i   >= 62 || j+1 <= 1 || j+1 >= 62)) {map[i][j+1]   = 0;}
          if (!(i+1 <= 1 || i+1 >= 62 || j-1 <= 1 || j-1 >= 62)) {map[i+1][j-1] = 0;}
          if (!(i+1 <= 1 || i+1 >= 62 || j   <= 1 || j   >= 62)) {map[i+1][j]   = 0;}
          if (!(i+1 <= 1 || i+1 >= 62 || j+1 <= 1 || j+1 >= 62)) {map[i+1][j+1] = 0;}
        }
      }
    }
  }
  landImg.src = "img/land.png";

}
createMapPiz();
}

// WORLD
{

var plants = [];
for (var i = 1; i < 100; i++) {
  var xl = rand(60)+2;
  var yl = rand(60)+2;
  plants.push({
    x : xl * 48+24,
    y : yl * 48+24,
    type : rand(2),
    biome : map_biome[div(xl,8)][div(yl,8)]
  });
}
var source = [];
source.push({
  x : (rand(50)+7)*48+24,
  y : (rand(50)+7)*48+24,
  num : 100,
  biome : -6
});
source.push({
  x : (rand(50)+7)*48+24,
  y : (rand(50)+7)*48+24,
  num : 100,
  biome : -5
});

var time = 0;
for (var i = 1; i < 5; i++) {
  var xl = rand(60)+2;
  var yl = rand(60)+2;
  while (map_biome[div(xl,8)][div(yl,8)] != i) {
    xl = rand(60)+2;
    yl = rand(60)+2;
  }
  source.push({
    x : xl * 48+24,
    y : yl * 48+24,
    num : 100,
    biome : map_biome[div(xl,8)][div(yl,8)]
  });
}
function world() {
  time++;
  if (time % 3600 == 0) {
    for (var i = 0; i < mstrs.length; i++) {
      for (var j = 0; j < 4; j++) {
        if (mstrs[i].inven[j] > 0) mstrs[i].inven[j]--;
      }
    }
  }
  for (var i = 0; i < source.length; i++) {
    if (source[i].num <= 0) {
      for (var j = 0; j < mstrs.length; j++) {
        if (mstrs[j].memory[0].x == source[i].x && mstrs[j].memory[0].y == source[i].y) {
          mstrs[j].memory[0].x = 0;
          mstrs[j].memory[0].y = 0;
          mstrs[j].memory[0].biome = 5;
          mstrs[j].memory[0].exactly = false;
        }
        if (mstrs[j].memory[1].x == source[i].x && mstrs[j].memory[1].y == source[i].y) {
          mstrs[j].memory[1].x = 0;
          mstrs[j].memory[1].y = 0;
          mstrs[j].memory[1].biome = 5;
          mstrs[j].memory[1].exactly = false;
        }
        if (mstrs[j].memory[2].x == source[i].x && mstrs[j].memory[1].y == source[i].y) {
          mstrs[j].memory[2].x = 0;
          mstrs[j].memory[2].y = 0;
          mstrs[j].memory[2].biome = 5;
          mstrs[j].memory[2].exactly = false;
        }
      }
      var xl = rand(60)+2;
      var yl = rand(60)+2;
      while (map_biome[div(xl,8)][div(yl,8)] != source[i].biome) {
        xl = rand(60)+2;
        yl = rand(60)+2;
      }
      source.push({
        x : xl * 48+24,
        y : yl * 48+24,
        num : 100,
        biome : map_biome[div(xl,8)][div(yl,8)]
      });
      source.splice(i, 1);
    }
  }
  for (var i = 0; i < mstrs.length; i++) {
    if (mstrs[i].readySpeak > 0) mstrs[i].readySpeak--;
  }
}
}

// PLAYER
var player = {
  x : 48 * 8 - 24,
  y : 48 * 8 - 24,
  s : true,
  dir : 'd',
  move : false,
  im : 0,
  is : 0,
  purpose : {
    x : 48 * 8 - 24,
    y : 48 * 8 - 24,
    goto : -1
  },
  knowend: false,
  //speak
  speak : false,
  with : -1,
  word : [0,0],
  ltr : [],
  wordTime : WT,
  readySpeak : 1200
}

// MSTRS
var mstrs = [];
for (var i = 0; i < 25; i++) {
  mstrs.push({
    x : rand(2834)+120,
    y : rand(2834)+120,
    s : rand(3),
    r : true,
    ms : 0.25 * 20,
    purpose : {
      x : rand(2834)+120,
      y : rand(2834)+120,
      know : false
    },
    inven : [rand(5),rand(5),rand(5),rand(5)],
    need : 0,
    memory : [{ x : 0, y : 0, biome : 5, exactly : false },
              { x : 0, y : 0, biome : 5, exactly : false },
              { x : 0, y : 0, biome : 5, exactly : false }],
    // speak
    speak : false,
    first : false,
    with : -1,
    word : [0, 0],
    wordTime : WT,
    readySpeak : rand(1200)
  });
}

function move(e){
  switch (e) {
    case 65:
      if (player.x > 120) player.x-=PS;
      player.move = true;
      player.dir = 'l';
      break;
    case 68:
      if (player.x < 2952) player.x+=PS;
      player.move = true;
      player.dir = 'r';
      break;
    case 87:
      if (player.y > 120) player.y-=PS;
      player.move = true;
      player.dir = 'u';
      break;
    case 83:
      if (player.y < 2952) player.y+=PS;
      player.move = true;
      player.dir = 'd';
      break;
    }
  if (player.move) {
    player.im = 10;
  }
}
function s(cor, i) {
  switch (cor) {
    case 'x':
      if (player.move) {
        if (player.s) {
          return 0;
        } else {
          return 96;
        }
      } else {
        return 48;
      }
      break;
    case 'y':
      switch (player.dir) {
        case 'd':
          return 0;
          break;
        case 'l':
          return 48;
          break;
        case 'r':
          return 96;
          break;
        case 'u':
          return 144;
          break;
      }
      break;
    case "px":
      return 48 * mstrs[i].s;
      break;
    case "py":
      if (mstrs[i].r) {
        return 0;
      } else {
        return 48;
      }
      break;
  }
}
