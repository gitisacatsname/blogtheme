document.addEventListener('DOMContentLoaded', function () {
  var btn = document.getElementById('procrastinate-btn');
  if (!btn) return;

  lottie.loadAnimation({
    container: btn,
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: themeUrl + '/js/lotties/procrastination.json'
  });

  btn.addEventListener('click', function () {
    var overlay = document.getElementById('doom-overlay');
    overlay.style.display = 'flex';
    if (!window._doomLoaded) {
      window._doomLoaded = true;
      Dos(document.getElementById('doom-container'), {
        wdosboxUrl: 'https://js-dos.com/v7/build/wdosbox.js'
      }).run('https://js-dos.com/games/doom.jsdos');
    }
  });

  document.getElementById('close-doom').addEventListener('click', function () {
    document.getElementById('doom-overlay').style.display = 'none';
  });
});
