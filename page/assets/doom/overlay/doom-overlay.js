(function () {
  const qs = (s, r = document) => r.querySelector(s);

  document.addEventListener('DOMContentLoaded', () => {
    const root  = qs('#doom-procrastinate');
    const btn   = qs('.doom-open', root);
    const wrap  = qs('#doom-frame-wrap', root);
    const frame = qs('#doom-frame', root);
    const btnFS = qs('.doom-fullscreen', root);
    const btnClose = qs('.doom-close', root);
    const usk = qs('.doom-usk', root);
    const uskBtn = qs('.doom-usk-accept', root);

    let lastFocus = null;

    function focusFrame() {
      const doc = frame.contentDocument;
      doc?.getElementById('doom')?.focus();
      frame.contentWindow?.focus();
    }

    function fixFrame() {
      focusFrame();
    }
    frame.addEventListener('load', fixFrame);

    function openGame() {
      lastFocus = document.activeElement;
      wrap.hidden = false;
      frame.src = DOOM_OVERLAY_CFG.engineUrl + '?game=doom1';
      frame.focus();
    }

    btn.addEventListener('click', () => {
      if (usk.hidden) {
        usk.hidden = false;
        uskBtn.focus();
      } else {
        openGame();
      }
    });

    uskBtn.addEventListener('click', () => {
      usk.hidden = true;
      openGame();
    });

    btnFS.addEventListener('click', () => {
      const mod = frame.contentWindow?.Module;
      if (typeof mod?.requestFullscreen === 'function') {
        mod.requestFullscreen(true, false);
      }
      focusFrame();
    });

    btnClose.addEventListener('click', () => {
      wrap.hidden = true;
      frame.src = 'about:blank';
      lastFocus?.focus();
    });
  });
})();
