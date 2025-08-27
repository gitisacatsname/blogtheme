(function () {
  const qs = (s, r = document) => r.querySelector(s);

  document.addEventListener('DOMContentLoaded', () => {
    const root  = qs('#doom-procrastinate');
    const btn   = qs('.doom-open', root);
    const wrap  = qs('#doom-frame-wrap', root);
    const frame = qs('#doom-frame', root);
    const btnFS = qs('.doom-fullscreen', root);
    const btnClose = qs('.doom-close', root);
    const selIwad = qs('.doom-iwad', root);

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

    function open() {
      lastFocus = document.activeElement;
      wrap.hidden = false;
      const game = encodeURIComponent(selIwad.value);
      const url = DOOM_OVERLAY_CFG.engineUrl + '?game=' + game;
      frame.src = url;
      frame.focus();
    }

    btn.addEventListener('click', open);

    selIwad.addEventListener('change', () => {
      const game = encodeURIComponent(selIwad.value);
      frame.src = DOOM_OVERLAY_CFG.engineUrl + '?game=' + game;
      focusFrame();
    });

    btnFS.addEventListener('click', () => {
      const mod = frame.contentWindow?.Module;
      if (typeof mod?.requestFullscreen === 'function') {
        mod.requestFullscreen(true, false);
      } else {
        const fs = frame.contentDocument?.getElementById('fullscreen');
        fs?.click();
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
