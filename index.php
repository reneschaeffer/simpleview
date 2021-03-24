<DOCTYPE html>
<head>
<meta charset ="utf-8"/>
<title>slideshow</title>

<style>
body {
  background-color: rgb(100,100,100);
}

img {
  margin-left: auto;
  margin-right: auto;
  height: 90%;
}

button {
  font-size: 1em;
}

#range_slider {
  width: 100%;
  -webkit-appearance: none;
  height: 1%;
  background: grey;
}

.control {
  display: table;
  height: 8%;
  margin: 5 auto;
  font-size: 10px;
}

.showduration {
  display: table;
  position: absolute;
  left: 50%;
}
</style>

<script>
// Globale Variablen
var bild_nr = 0;
var images;
var imax;
var intervalobject; 
var duration = 2;
var pause_p = true;
var slider;

// wird erst nach dem kompletten Laden des gesamten Dokuments ausgeführt
window.onload = function () {
  images = document.getElementsByTagName("img");
  imax = images.length - 1;                       
  slider = document.getElementById("range_slider");
  slider.oninput = function() {
    show_hide(this.value,bild_nr);
  }
}

// Keyboard-Steuerung
window.addEventListener("keydown", function(event) {
  if (!event.repeat) {
    if ((event.key == 'ArrowLeft') || (event.key == 'p')) {
      previous();
    } else if ((event.key == 'ArrowRight') || (event.key == 'n')) {
      next();
    } else if (event.key == '+') {
      modify_duration(1);
    } else if (event.key == '-') {
      modify_duration(-1);
    } else if (event.key == ' ') {
      toggle_pause();
    }
  }
}, true);

// Anpassen des Wertes für die Zeitdauer des Intervals
function modify_duration (modifier) {
  if (duration >= 2)
  duration += modifier; 
  clearInterval (intervalobject);
  intervalobject = window.setInterval("next()", duration*1000);
}

//     show_hide(this.value,bild_nr);
function show_hide(actual, old) {
   images[old].style.display = "none";
   images[actual].style.display = "block";
   bild_nr = actual;
}

// endet bei Bild 1
function previous() {
  if (bild_nr > 0) {
    images[bild_nr].style.display = "none";
    bild_nr -=1;
    images[bild_nr].style.display = "block";
    slider.value = bild_nr;
    if (pause_p) toggle_pause();
  }
}

//loopt am Ende auf Bild 1
function next() {
  images[bild_nr].style.display = "none";
  bild_nr += 1;
  if (bild_nr > imax) bild_nr = 0;
  images[bild_nr].style.display = "block";
  slider.value = bild_nr;
}

// wenn pause_p auf TRUE dann play und buttonwechsel ansonsten pause und buttonwechsel
function toggle_pause () {
  clearInterval(intervalobject);
  pause_p = !pause_p
  if (pause_p) {
    intervalobject = window.setInterval("next()", duration*1000);
    document.getElementById ("pausebutton").style.display = "inline";
    document.getElementById ("continuebutton").style.display = "none";
  } else {
    document.getElementById ("pausebutton").style.display = "none";
    document.getElementById ("continuebutton").style.display = "inline";
  }
}
</script>
</head>

<body>
<?php 
$images = scandir("images2");
$first = TRUE;
$imax = -1;
foreach($images as $img) {
  if (!substr_compare($img, ".jpg", -4)) {  
    $imax++;
    if ($first) {
?>
      <img src="images2/<?php echo $img ?>" style="display: block">
<?php 
  $first = FALSE;
    } else {
?>
      <img src="images2/<?php echo $img ?>" style="display: none">
<?php 
    }
  }
}
?>
<input type="range" 
  min="0" max="<?php echo $imax ?>" value="0" step="1" id="range_slider">
<div class="control">
  <button onclick="previous()">&#10094;</button>
  <button id="pausebutton" onclick="toggle_pause()" style="display:none">pause</button>
  <button id="continuebutton" onclick="toggle_pause()">play</button>
  <button onclick="next()">&#10095</button>
  
</div>
</body>
