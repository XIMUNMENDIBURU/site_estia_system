const konamiCode = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];
let pressedKeys = [];

function checkKonamiCode() {
  if (pressedKeys.join('') === konamiCode.join('')) {
    window.location.href = 'https://www.youtube.com/watch?v=GBIIQ0kP15E';
  }
}

document.addEventListener('keydown', (event) => {
  pressedKeys.push(event.key);
  pressedKeys = pressedKeys.slice(-konamiCode.length);
  checkKonamiCode();
});
