(function () {
  const qs = (s, r = document) => r.querySelector(s);

  function setGameParam(url, game) {
    try {
      const u = new URL(url, window.location.origin);
      if (game) u.searchParams.set('game', game);
      return u.toString();
    } catch { return url; }
  }

  document.addEventListener('DOMContentLoaded', () => {
    const root  = qs('#doom-procrastinate');
    const btn   = qs('.doom-open', root);
    const wrap  = qs('#doom-frame-wrap', root);
    const frame = qs('#doom-frame', root);
    const btnFS = qs('.doom-fullscreen', root);
    const btnClose = qs('.doom-close', root);
    const btnFreedoom = qs('.doom-iwad-freedoom', root);
    const btnShare = qs('.doom-iwad-shareware', root);

    let lastFocus = null;

    function focusFrame() {
      const doc = frame.contentDocument;
      doc?.getElementById('doom')?.focus();
      frame.contentWindow?.focus();
    }

    function fixFrame() {
      const doc = frame.contentDocument;
      if (!doc) return;
      doc.getElementById('fullscreen')?.style.setProperty('display', 'none');
      doc.getElementById('preview')?.style.setProperty('display', 'none');
      const canvas = doc.getElementById('doom');
      if (canvas) {
        canvas.style.left = '0';
        canvas.style.right = '0';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
      }
      doc.addEventListener('fullscreenchange', focusFrame);
      focusFrame();
    }
    frame.addEventListener('load', fixFrame);

    function open(game) {
      lastFocus = document.activeElement;
      wrap.hidden = false;
      const url = setGameParam(DOOM_OVERLAY_CFG.engineUrl, game || null);
      if (frame.src !== url) frame.src = url;
      frame.focus();
    }

    btn.addEventListener('click', () => open(DOOM_OVERLAY_CFG.gameFreedoom));

    btnFreedoom.addEventListener('click', () => {
      frame.src = setGameParam(DOOM_OVERLAY_CFG.engineUrl, DOOM_OVERLAY_CFG.gameFreedoom);
      frame.focus();
    });

    btnShare?.addEventListener('click', () => {
      frame.src = setGameParam(DOOM_OVERLAY_CFG.engineUrl, DOOM_OVERLAY_CFG.gameShareware);
      frame.focus();
    });

    btnFS.addEventListener('click', () => {
      const fs = frame.contentDocument?.getElementById('fullscreen');
      fs?.click();
      focusFrame();
    });

    btnClose.addEventListener('click', () => {
      wrap.hidden = true;
      frame.src = 'about:blank';
      lastFocus?.focus();
    });
  });
})();
