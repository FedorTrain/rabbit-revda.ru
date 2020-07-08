var cvs = document.getElementById("canvas");
var ctx = cvs.getContext("2d");
ctx.canvas.width  = 800;
ctx.canvas.height = 600;

function draw(){
  ctx.clearRect(0, 0, cvs.width, cvs.height);

  // draw maps
  for (var i = 0; i < 20; i++) {
    for (var j = 0; j < 14; j++) {
      var x = div(map_x, 48) * 48 - map_x + 48 * i;
      var y = div(map_y, 48) * 48 - map_y + 48 * j;
      ctx.drawImage(landImg, x, y);
      if (map[div(map_x + x, 48)][div(map_y + y, 48)] == 5) {
        ctx.drawImage(planteaImg,0,48*2,48,48, x, y, 48, 48);
      }
    }
  }

  // draw plants
  for (var i = 0; i < plants.length; i++) {
    ctx.drawImage(planteaImg,48 * (plants[i].biome - 1),48 * plants[i].type,48,48, plants[i].x - map_x - 24, plants[i].y - map_y - 24, 48, 48);
  }

  //draw source
  for (var i = 0; i < source.length; i++) {
    if (source[i].biome == -5 && player.knowend) {
      for (var j = 3; j < 6; j++) {
        ctx.drawImage(menusp,0,40*(j),40,40, source[i].x - map_x - 48, source[i].y - map_y - 48, 96, 96);
      }
    } else
    if (source[i].biome == -6) {
      for (var j = 0; j < 3; j++) {
        ctx.drawImage(menusp,0,40*(j),40,40, source[i].x - map_x - 48, source[i].y - map_y - 48, 96, 96);
      }
    } else {
      ctx.drawImage(planteaImg,48 * (source[i].biome - 1),48 * 3,48,48, source[i].x - map_x - 24, source[i].y - map_y - 24, 48, 48);
    }
  }

  //draw monstrs
  for (var i = 0; i < mstrs.length; i++) {
    ctx.drawImage(poopaImg,s("px",i),s("py",i),48,48, mstrs[i].x - map_x - 24, mstrs[i].y - map_y - 24, 48, 48);
  }

  // draw player
  ctx.drawImage(playerImg,s('x',0),s('y',0),48,48, player.x - map_x - 24, player.y - map_y - 24, 48, 48);

  // draw words
  for (var i = 0; i < mstrs.length; i++) {
    if (mstrs[i].speak && mstrs[i].wordTime != -1){
      if (mstrs[i].word[0] <= numWord) {
        if (mstrs[i].word[1] != 0) {
          var w = alfb[mstrs[i].word[0]-1];
          for (var j = 0; j < w.length; j++) {
            ctx.drawImage(alfbImg,20*(w[j]-1),0,20,20, mstrs[i].x - map_x - 24, mstrs[i].y - map_y - 72, 20, 20);
          }
        }
        if (mstrs[i].word[1] != 0) {
          var w = alfb[mstrs[i].word[1]-1];
          for (var j = 0; j < w.length; j++) {
            ctx.drawImage(alfbImg,20*(w[j]-1),0,20,20, mstrs[i].x - map_x, mstrs[i].y - map_y - 72, 20, 20);
          }
        }
        if (mstrs[i].word[0] == 7) {
          ctx.drawImage(alfbImg,20,0,20,20, mstrs[i].x - map_x, mstrs[i].y - map_y - 72, 20, 20);
          ctx.drawImage(alfbImg,20*4,0,20,20, mstrs[i].x - map_x, mstrs[i].y - map_y - 72, 20, 20);
          ctx.drawImage(alfbImg,20*3,0,20,20, mstrs[i].x - map_x, mstrs[i].y - map_y - 72, 20, 20);
        }
      } else {
        var w = mstrs[i].word[0]-12;
        ctx.drawImage(alfbImg,20*w,20,20,20, mstrs[i].x - map_x - 24, mstrs[i].y - map_y - 72, 20, 20);
        var w = mstrs[i].word[1]-20;
        ctx.drawImage(alfbImg,20*w,40,20,20, mstrs[i].x - map_x - 24, mstrs[i].y - map_y - 72, 20, 20);

      }
    }
  }

  // draw interface
  ctx.drawImage(maplogo,0,0,48,48, 800 - 48, 600 - 48, 48, 48);
  if (seeMap) {
    ctx.drawImage(mapImg,0,0,180,180, 220, 120, 360, 360);
    var lx = player.x * 0.104;
    var ly = player.y * 0.104;
    ctx.drawImage(playerImg,48,0,48,48,240+lx,140+ly, 30, 30);

  }
  if (player.speak) {
    ctx.drawImage(menusp,0,0,80,362, 100, 100, 80, 362);
    if (!cpr(player.ltr, [])) {
      for (var i = 0; i < player.ltr.length; i++) {
        player.ltr[i];
        ctx.drawImage(menusp,0,40*(player.ltr[i]-1),40,40, 100+(40*div(i,3)), 422, 40, 40);
      }
    }
  }
}

function update(){
  // 1 PLAYER
  {
  if (player.x - map_x < 300) {map_x = player.x - 300}
  if (player.x - map_x > 500) {map_x = player.x - 500}
  if (player.y - map_y < 260) {map_y = player.y - 260}
  if (player.y - map_y > 340) {map_y = player.y - 340}
  if (map_x < 0) map_x = 0;
  if (map_y < 0) map_y = 0;
  if (map_x > 2272) map_x = 2272;
  if (map_y > 2472) map_y = 2472;
  if (player.im == 0) {player.move = false;}
  if (player.im > 0) {player.im--;}
  if (player.is == 0) {
    player.s = !player.s;
    player.is = 15;
    for (var i = 0; i < mstrs.length; i++) {
      mstrs[i].s++;
      if (mstrs[i].s >= 3) mstrs[i].s = 0;
    }
  }
  if (player.is > 0) {player.is--;}
  var dx = Math.abs(player.x - player.purpose.x);
  var dy = Math.abs(player.y - player.purpose.y);
  if (player.purpose.goto != -1) {
    player.purpose.x = mstrs[player.purpose.goto].x;
    player.purpose.y = mstrs[player.purpose.goto].y;
    var mstr = player.purpose.goto;

    var lx = mstrs[mstr].x - player.x;
    var ly = mstrs[mstr].y - player.y;
    if (lx*lx+ly*ly <= 2304 && !player.speak && !mstrs[mstr].speak) {
      player.speak = true;
      player.with = mstr;
      player.word = [0,0];
      player.ltr = [];
      player.wordTime = WT;
      player.readySpeak = 1200;

      mstrs[mstr].speak = true;
      mstrs[mstr].first = false;
      mstrs[mstr].with = 100;
      mstrs[mstr].word = [0,0];
      mstrs[mstr].wordTime = 0;
      mstrs[mstr].readySpeak = 1200;
    }
  }
  if (!player.speak) {
    if (player.x > player.purpose.x && dx > 6) {
      if (player.x > 120) player.x-=PS;
      player.move = true;
      player.dir = 'l';
    }
    if (player.x < player.purpose.x && dx > 6) {
      if (player.x < 2952) player.x+=PS;
      player.move = true;
      player.dir = 'r';
    }
    if (player.y > player.purpose.y && dy > 6) {
      if (player.y > 120) player.y-=PS;
      player.move = true;
      player.dir = 'u';
    }
    if (player.y < player.purpose.y && dy > 6) {
      if (player.y < 2952) player.y+=PS;
      player.move = true;
      player.dir = 'd';
    }
  }
  var dx = Math.abs(player.x - source[1].x);
  var dy = Math.abs(player.y - source[1].y);
  if (dx*dx + dy*dy <= 2304 && player.knowend) {
      end();
  }

  }

  // 2 MSTRS
  for (var i = 0; i < mstrs.length; i++) {

    // 2.1 NEED
    for (var j = 0; j < 4; j++) {
      if (mstrs[i].inven[j] < mstrs[i].inven[mstrs[i].need]) {
        mstrs[i].need = j;
      }
    }

    // 2.2 SEE
    for (var j = 0; j < source.length; j++) {
      var dx = mstrs[i].x - source[j].x;
      var dy = mstrs[i].y - source[j].y;
      if (dx*dx + dy*dy <= 147456) {
        if ((source[j].biome != mstrs[i].memory[0].biome) && (source[j].biome != mstrs[i].memory[1].biome) && (source[j].biome != mstrs[i].memory[2].biome)) {
          mstrs[i].memory[2].biome = mstrs[i].memory[1].biome;
          mstrs[i].memory[2].x = mstrs[i].memory[1].x;
          mstrs[i].memory[2].y = mstrs[i].memory[1].y;
          mstrs[i].memory[2].exactly = mstrs[i].memory[1].exactly;
          mstrs[i].memory[1].biome = mstrs[i].memory[0].biome;
          mstrs[i].memory[1].x = mstrs[i].memory[0].x;
          mstrs[i].memory[1].y = mstrs[i].memory[0].y;
          mstrs[i].memory[1].exactly = mstrs[i].memory[0].exactly;
          mstrs[i].memory[0].biome = source[j].biome;
          mstrs[i].memory[0].x = source[j].x;
          mstrs[i].memory[0].y = source[j].y;
          mstrs[i].memory[0].exactly = true;
        }
        if (source[j].biome == mstrs[i].memory[0].biome && !mstrs[i].memory[0].exactly) {
          mstrs[i].memory[0].biome = source[j].biome;
          mstrs[i].memory[0].x = source[j].x;
          mstrs[i].memory[0].y = source[j].y;
          mstrs[i].memory[0].exactly = true;
        }
        if (source[j].biome == mstrs[i].memory[1].biome && !mstrs[i].memory[1].exactly) {
          mstrs[i].memory[1].biome = source[j].biome;
          mstrs[i].memory[1].x = source[j].x;
          mstrs[i].memory[1].y = source[j].y;
          mstrs[i].memory[1].exactly = true;
        }
        if (source[j].biome == mstrs[i].memory[2].biome && !mstrs[i].memory[2].exactly) {
          mstrs[i].memory[2].biome = source[j].biome;
          mstrs[i].memory[2].x = source[j].x;
          mstrs[i].memory[2].y = source[j].y;
          mstrs[i].memory[2].exactly = true;
        }
      }
    }

    // 2.3 PURPOSE
    if (mstrs[i].need+1 == mstrs[i].memory[0].biome) {
      mstrs[i].purpose.x = mstrs[i].memory[0].x;
      mstrs[i].purpose.y = mstrs[i].memory[0].y;
      mstrs[i].purpose.know = true;
    }
    if (mstrs[i].need+1 == mstrs[i].memory[1].biome) {
      mstrs[i].purpose.x = mstrs[i].memory[1].x;
      mstrs[i].purpose.y = mstrs[i].memory[1].y;
      mstrs[i].purpose.know = true;
    }
    if (mstrs[i].need+1 == mstrs[i].memory[2].biome) {
      mstrs[i].purpose.x = mstrs[i].memory[2].x;
      mstrs[i].purpose.y = mstrs[i].memory[2].y;
      mstrs[i].purpose.know = true;
    }

    // 2.4 SPEAK
    if (!mstrs[i].purpose.know && !mstrs[i].speak && mstrs[i].readySpeak == 0) {
      for (var j = 0; j < mstrs.length; j++) {
        if (!mstrs[j].speak && mstrs[j].readySpeak == 0) {
          if (i == j) j++;
          if (j == mstrs.length) break;
          var dx = mstrs[i].x - mstrs[j].x;
          var dy = mstrs[i].y - mstrs[j].y;
          if (dx*dx + dy*dy <= 147456) {
            mstrs[i].ms = 4;
            mstrs[i].purpose.x = mstrs[j].x;
            mstrs[i].purpose.y = mstrs[j].y;
          }
          if (dx*dx + dy*dy <= 2304 && !mstrs[j].speak && mstrs[j].readySpeak == 0) {
            // first
            mstrs[i].speak = true;
            mstrs[i].first = true;
            mstrs[i].with = j;
            mstrs[i].wordTime = WT;
            mstrs[i].readySpeak = 1200;
            // not first
            mstrs[j].speak = true;
            mstrs[j].first = false;
            mstrs[j].with = i;
            mstrs[j].wordTime = 0;
            mstrs[j].readySpeak = 1200;
          }
        }
      }
    }
    if (mstrs[i].speak && mstrs[i].first) { //Спрашивает
      // z = false;
      if (mstrs[i].wordTime > 0) {
        mstrs[i].wordTime--;
        if (cpr(mstrs[mstrs[i].with].word, [0, 0])) {
          mstrs[i].word = Array(5, 3);
        } else
        if (cpr(mstrs[mstrs[i].with].word, [5, 3])) {
          mstrs[i].word = Array(6, mstrs[i].need + 8);
        } else
        if (cpr(mstrs[mstrs[i].with].word, [7, 0])) {
          mstrs[mstrs[i].with].speak = false;
          mstrs[mstrs[i].with].word = [0, 0];
          mstrs[mstrs[i].with].with = -1;
          mstrs[i].speak = false;
          mstrs[i].with = -1;
          mstrs[i].word = [0, 0];
          mstrs[i].first = false;
          // console.log("dont know");
        } else
        if (mstrs[mstrs[i].with].word[0] > numWord) {
          // z = false;
          var xl = mstrs[mstrs[i].with].word[0] - numWord - 1;
          var yl = mstrs[mstrs[i].with].word[1] - numWord - 9;
          xl = (xl * 8 + 4) * 48;
          yl = (yl * 8 + 4) * 48;
          mstrs[i].memory[2].x = mstrs[i].memory[1].x;
          mstrs[i].memory[2].y = mstrs[i].memory[1].y;
          mstrs[i].memory[2].biome = mstrs[i].memory[1].biome;
          mstrs[i].memory[2].exactly = mstrs[i].memory[1].exactly;
          mstrs[i].memory[1].x = mstrs[i].memory[0].x;
          mstrs[i].memory[1].y = mstrs[i].memory[0].y;
          mstrs[i].memory[1].biome = mstrs[i].memory[0].biome;
          mstrs[i].memory[1].exactly = mstrs[i].memory[0].exactly;
          mstrs[i].memory[0].x = xl;
          mstrs[i].memory[0].y = yl;
          mstrs[i].memory[0].biome = mstrs[i].need + 1;
          mstrs[i].memory[0].exactly = false;
          mstrs[mstrs[i].with].speak = false;
          mstrs[mstrs[i].with].word = [0, 0];
          mstrs[mstrs[i].with].with = -1;
          mstrs[i].speak = false;
          mstrs[i].with = -1;
          mstrs[i].word = [0, 0];
          mstrs[i].first = false;
          // console.log("know");
        }
      } else {
        if (mstrs[i].wordTime != -1) {
          mstrs[mstrs[i].with].wordTime = WT;
          mstrs[i].wordTime = -1;
        }
      }
    }
    if (mstrs[i].speak && !mstrs[i].first) {// Отвечает
      if (mstrs[i].with == 100) {
        if (player.wordTime == 0) {
          if (cpr(player.word, [5,3])){
            mstrs[i].word = [5,4];
            mstrs[i].wordTime = 2 * WT;
            player.word = [0,0];
            player.wordTime = WT;
          }
          else
          for (var j = 1; j < 12; j++) {
            if (j == 3) j = 8;
            if (cpr(player.word, [6, j])) {
              if (mstrs[i].memory[0].biome + 7 == j) {
                var xl = div(div(mstrs[i].memory[0].x, 48), 8);
                var yl = div(div(mstrs[i].memory[0].y, 48), 8);
                mstrs[i].word = Array(numWord + xl + 1, numWord + yl + 9);
                if (j == 2) player.knowend = true;
              } else if (mstrs[i].memory[1].biome == j) {
                var xl = div(div(mstrs[i].memory[1].x, 48), 8);
                var yl = div(div(mstrs[i].memory[1].y, 48), 8);
                mstrs[i].word = Array(numWord + xl + 1, numWord + yl + 9);
                if (j == 2) player.knowend = true;
              } else if (mstrs[i].memory[2].biome + 7 == j) {
                var xl = div(div(mstrs[i].memory[2].x, 48), 8);
                var yl = div(div(mstrs[i].memory[2].y, 48), 8);
                mstrs[i].word = Array(numWord + xl + 1, numWord + yl + 9);
                if (j == 2) player.knowend = true;
              } else {
                mstrs[i].word = Array(7, 0);
              }
            }
          }
        }
      } else

      if (mstrs[i].wordTime > 0) {
        mstrs[i].wordTime--;
        if (cpr(mstrs[mstrs[i].with].word, [5, 3])) {
          mstrs[i].word = Array(5, 3);
        } else
        for (var j = 8; j < 12; j++) {
          if (cpr(mstrs[mstrs[i].with].word, [6, j])) {
            if (mstrs[i].memory[0].biome + 7 == j) {
              var xl = div(div(mstrs[i].memory[0].x, 48), 8);
              var yl = div(div(mstrs[i].memory[0].y, 48), 8);
              mstrs[i].word = Array(numWord + xl + 1, numWord + yl + 9);
            } else if (mstrs[i].memory[1].biome == j) {
              var xl = div(div(mstrs[i].memory[1].x, 48), 8);
              var yl = div(div(mstrs[i].memory[1].y, 48), 8);
              mstrs[i].word = Array(numWord + xl + 1, numWord + yl + 9);
            } else if (mstrs[i].memory[2].biome == j) {
              var xl = div(div(mstrs[i].memory[2].x, 48), 8);
              var yl = div(div(mstrs[i].memory[2].y, 48), 8);
              mstrs[i].word = Array(numWord + xl + 1, numWord + yl + 9);
            } else {
              mstrs[i].word = Array(7, 0);
            }
          }
        }
      } else {
        if (mstrs[i].wordTime != -1) {
          mstrs[mstrs[i].with].wordTime = WT;
          mstrs[i].wordTime = -1;
        }
      }
    }

    // 2.5 WALK
    if (!mstrs[i].speak) {
      for (var j= 0; j < source.length; j++) {
        var dx = mstrs[i].x - source[j].x;
        var dy = mstrs[i].y - source[j].y;
        if (dx*dx + dy*dy < 100 && source[j].biome == mstrs[i].need+1) {
          source[j].num -= 5 - mstrs[i].inven[mstrs[i].need];
          mstrs[i].inven[mstrs[i].need] = 5;
        }
      }
      var dx = Math.abs(mstrs[i].x - mstrs[i].purpose.x);
      var dy = Math.abs(mstrs[i].y - mstrs[i].purpose.y);
      if (mstrs[i].x < mstrs[i].purpose.x && dx > 6) { mstrs[i].x += mstrs[i].ms; mstrs[i].r = true;  }
      if (mstrs[i].x > mstrs[i].purpose.x && dx > 6) { mstrs[i].x -= mstrs[i].ms; mstrs[i].r = false; }
      if (mstrs[i].y < mstrs[i].purpose.y && dy > 6) mstrs[i].y += mstrs[i].ms;
      if (mstrs[i].y > mstrs[i].purpose.y && dy > 6) mstrs[i].y -= mstrs[i].ms;
      mstrs[i].ms = MS;
      if (dx < 8 && dy < 8) {
        mstrs[i].purpose.x = rand(2834)+120;
        mstrs[i].purpose.y = rand(2834)+120;
        mstrs[i].purpose.know = false;
      }
    }
  }
}

$(document.body).on('keydown', function(e) {move(e.which);});
function windowToCanvas(canvas, x, y) {
    var bbox = canvas.getBoundingClientRect();
    return { x: x - bbox.left * (canvas.width / bbox.width),
        y: y - bbox.top * (canvas.height / bbox.height)
    };
}
canvas.onmousedown = function (e) {
  var loc = windowToCanvas(cvs, e.clientX, e.clientY);
  if (seeMap) seeMap = false;
  else
  if (loc.x > 800 - 48 && loc.y > 600 - 48) {
    seeMap = true;
  } else
  if (player.speak) {
    if (loc.y > 100 && loc.y < 420) {
      if (loc.x > 140 && loc.x < 180) {
        if (loc.y > 340 && loc.y < 380) {// Отправить
          if (player.ltr.length == 6) {
            var ok = 0
            var w1 = [player.ltr[0],player.ltr[1],player.ltr[2]];
            var w2 = [player.ltr[3],player.ltr[4],player.ltr[5]];
            w1.sort(function(a, b){return a - b});
            w2.sort(function(a, b){return a - b});
            for (var i = 0; i < alfb.length; i++) {
              if (cpr(alfb[i], w1)) {
                player.word[0] = i+1;
                ok++;
              }
              if (cpr(alfb[i], w2)) {
                player.word[1] = i+1;
                ok++;
              }
            }
            if (ok == 2) {
              player.ltr = [];
              player.wordTime = 0;

            } else {
              player.word = [0,0];
            }
          }
        }
        if (loc.y > 380 && loc.y < 420) {
          if (player.ltr.length > 0) player.ltr.splice(player.ltr.length-1,1);
          else {
            stopspeak();
          }
        }
      }
      if (loc.x > 100 && loc.x < 140 && player.ltr.length < 6) {
        var ps = player.ltr.length;
        var is = false;
        var newltr = div(loc.y-100,40)+1;
        if (ps == 1) if (player.ltr[0] == newltr) is = true;
        if (ps == 2) if (player.ltr[0] == newltr || player.ltr[1] == newltr) is = true;
        if (ps == 4) if (player.ltr[3] == newltr) is = true;
        if (ps == 5) if (player.ltr[3] == newltr || player.ltr[4] == newltr) is = true;
        if (!is) player.ltr.push(newltr);
      }
    }
  }
  else {
    player.purpose.x = loc.x + map_x;
    player.purpose.y = loc.y + map_y;
    player.purpose.goto = -1;
    for (var i = 0; i < mstrs.length; i++) {
      var lx = mstrs[i].x - player.purpose.x;
      var ly = mstrs[i].y - player.purpose.y ;
      if (lx*lx + ly*ly <= 625) {
        player.purpose.goto = i;
      }
    }
  }
};
function stopspeak() {
  var mstr = player.with;
  player.speak = false;
  player.with = -1;
  player.word = [0,0];
  player.ltr = [];
  player.wordTime = WT;
  player.readySpeak = 1200;
  player.purpose.x = player.x;
  player.purpose.y = player.y;
  player.purpose.goto = -1;
  mstrs[mstr].speak = false;
  mstrs[mstr].first = false;
  mstrs[mstr].with = -1;
  mstrs[mstr].word = [0,0];
  mstrs[mstr].wordTime = WT;
  mstrs[mstr].readySpeak = 1200;
}

console.log("Кто прочитал, тот сдохнет!!!");
var z = true;
function u() {
  z = false;
}
function g() {
  z = true;
  loop();
}
function goL(num) {
  for (var i = 0; i < num; i++) {
    loop();
  }
}
function loop() {
  update();
  draw();
  world();
  delWrong();
  if (z) requestAnimationFrame(loop);
}
function midgard() {
  requestAnimationFrame(midgard);
}
// midgard();
loop();
