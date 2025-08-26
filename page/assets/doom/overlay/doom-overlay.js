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

    function open(game) {
      wrap.hidden = false;
      const url = setGameParam(DOOM_OVERLAY_CFG.engineUrl, game || null);
      if (frame.src !== url) {
        frame.src = url;
        frame.addEventListener('load', () => {
          frame.focus();
          frame.contentWindow?.focus();
        }, { once: true });
      } else {
        frame.focus();
        frame.contentWindow?.focus();
      }
    }

    btn.addEventListener('click', () => open(DOOM_OVERLAY_CFG.gameFreedoom));

    btnFreedoom.addEventListener('click', () => {
      frame.src = setGameParam(DOOM_OVERLAY_CFG.engineUrl, DOOM_OVERLAY_CFG.gameFreedoom);
      frame.addEventListener('load', () => {
        frame.focus();
        frame.contentWindow?.focus();
      }, { once: true });
    });

    btnShare?.addEventListener('click', () => {
      frame.src = setGameParam(DOOM_OVERLAY_CFG.engineUrl, DOOM_OVERLAY_CFG.gameShareware);
      frame.addEventListener('load', () => {
        frame.focus();
        frame.contentWindow?.focus();
      }, { once: true });
    });

    btnFS.addEventListener('click', () => {
      wrap.classList.toggle('full');
      try {
        frame.contentWindow.document.getElementById('fullscreen')?.click();
      } catch (e) {}
    });

    btnClose.addEventListener('click', () => {
      wrap.hidden = true;
      frame.src = 'about:blank';
      btn.focus();
    });
  });
})();
