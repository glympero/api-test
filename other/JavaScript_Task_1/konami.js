// a key map of allowed keys
var allowedKeys = {
  37: 'left',
  38: 'up',
  39: 'right',
  40: 'down',
  65: 'a',
  66: 'b'
};

// the 'official' Konami Code sequence
var konamiCode = [
  'up',
  'up',
  'down',
  'down',
  'left',
  'right',
  'left',
  'right',
  'b',
  'a'
];

// a variable to store the time of previous keystroke.
var previousKeyPressed = null;

// a variable to remember the 'position' the user has reached so far.
var konamiCodePosition = 0;

// add keydown event listener
document.addEventListener('keydown', function(e) {
  // get the value of the key code from the key map
  var key = allowedKeys[e.keyCode];
  // get the value of the required key from the konami code
  var requiredKey = konamiCode[konamiCodePosition];

  // compare the key with the required key
  if (key == requiredKey) {
    var currentKeyPressed = new Date().getTime();
    // move to the next key in the konami code sequence
    if (
      previousKeyPressed === null ||
      getTimeElapsed(previousKeyPressed, currentKeyPressed) <= 500
    ) {
      konamiCodePosition++;
      previousKeyPressed = currentKeyPressed;
    } else {
      restart();
    }

    // if the last key is reached, activate cheats
    if (konamiCodePosition == konamiCode.length) {
      activateCheats();
      restart();
    }
  } else {
    konamiCodePosition = konamiCode.length + 1;
    setTimeout(function() {
      restart();
    }, 1000);
  }
});

function activateCheats() {
  alert('cheats activated');
}

function getTimeElapsed(oldkey, currentKey) {
  return currentKey - oldkey;
}

function restart() {
  konamiCodePosition = 0;
  previousKeyPressed = null;
}
