document.addEventListener('DOMContentLoaded', function () {
  var btn = document.getElementById('procrastinate-btn');
  if (!btn) return;

  lottie.loadAnimation({
    container: btn,
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: PROCRASTINATE_CFG.lottieUrl
  });

  btn.addEventListener('click', function () {
    var overlay = document.getElementById('doom-overlay');
    var container = document.getElementById('doom-container');
    if (!overlay || !container) return;
    overlay.style.display = 'flex';
    if (!window._doomLoaded) {
      window._doomLoaded = true;
      Dos(container, {
        wdosboxUrl: 'https://js-dos.com/6.22/current/wdosbox.js'
      }).run('https://js-dos.com/6.22/current/doom.jsdos');
    }
  });

  var closeBtn = document.getElementById('close-doom');
  if (closeBtn) {
    closeBtn.addEventListener('click', function () {
      var overlay = document.getElementById('doom-overlay');
      if (overlay) overlay.style.display = 'none';
    });
  }
});
